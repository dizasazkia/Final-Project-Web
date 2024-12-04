<?php
namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;
use App\Models\Loan;
use App\Models\Book;
use App\Models\Review;
use Illuminate\Http\Request;

class BorrowController extends Controller
{
    public function __construct()
    {
        $user = Auth::user();
        View::share('user', $user);
    }

    public function borrowedBooks()
    {
        $loans = Loan::where('user_id', Auth::id())
                    ->whereIn('status', ['dikembalikan','dipinjam', 'menunggu konfirmasi']) 
                    ->with(['book'])
                    ->get();

        return view('mahasiswa.pinjam.borrowedBooks', compact('loans'));
    }

    public function extendLoan($loanId)
    {
        $loan = Loan::findOrFail($loanId);

        if ($loan->user_id != Auth::id()) {
            return redirect()->route('mahasiswa.borrowedBooks')->with('error', 'Unauthorized action.');
        }

        $tanggalKembali = \Carbon\Carbon::parse($loan->tanggal_kembali);

        if ($tanggalKembali->diffInDays(now()) > 3) {
            return redirect()->route('mahasiswa.borrowedBooks')->with('error', 'Masa peminjaman sudah melewati batas perpanjangan.');
        }

        $loan->tanggal_kembali = $tanggalKembali->addDays(3);
        $loan->save();

        return redirect()->route('mahasiswa.borrowedBooks')->with('success', 'Masa peminjaman berhasil diperpanjang selama 3 hari!');
    }

    public function borrowBook(Book $book)
    {
        $userId = Auth::id();

        // Hitung jumlah buku yang sedang dipinjam oleh mahasiswa
        $activeLoansCount = Loan::where('user_id', $userId)
                                ->whereIn('status', ['dipinjam'])
                                ->count();

        // Cek apakah sudah meminjam 3 buku
        if ($activeLoansCount >= 3) {
            return redirect()->route('mahasiswa.borrowedBooks')
                            ->with('error', 'Anda hanya dapat meminjam maksimal 3 buku dalam satu waktu.');
        }

        // Cek apakah buku yang sama sudah dipinjam oleh mahasiswa
        $alreadyBorrowed = Loan::where('user_id', $userId)
                                ->where('book_id', $book->id)
                                ->whereIn('status', ['dipinjam'])
                                ->exists();

        if ($alreadyBorrowed) {
            return redirect()->route('mahasiswa.borrowedBooks')
                            ->with('error', 'Anda tidak dapat meminjam buku yang sama dalam satu waktu.');
        }

        // Lakukan peminjaman jika semua validasi lolos
        if ($book->stok > 0) {
            Loan::create([
                'user_id' => $userId,
                'book_id' => $book->id,
                'tanggal_pinjam' => now(),
                'tanggal_kembali' => now()->addMinute(),
                'status' => 'dipinjam',
            ]);

            $book->decrement('stok'); // Kurangi stok buku

            return redirect()->route('mahasiswa.borrowedBooks')
                            ->with('success', 'Buku berhasil dipinjam!');
        }

        return redirect()->route('mahasiswa.borrowedBooks')
                        ->with('error', 'Buku tidak tersedia, stok habis!');
    }

    public function returnBook($loanId)
    {
        $loan = Loan::findOrFail($loanId);

        if ($loan->user_id != Auth::id()) {
            return redirect()->route('mahasiswa.borrowedBooks')->with('error', 'Unauthorized action.');
        }

        $loan->status = 'menunggu konfirmasi';
        $loan->tanggal_pengembalian = now(); 
        $loan->save();

        return redirect()->route('mahasiswa.borrowedBooks')->with('success', 'Pengembalian berhasil! Menunggu konfirmasi dari pegawai.');
    }

    public function requestReturn($loanId)
    {
        $loan = Loan::findOrFail($loanId);

        if ($loan->user_id != Auth::id()) {
            return redirect()->route('mahasiswa.borrowedBooks')->with('error', 'Unauthorized action.');
        }

        $loan->status = 'menunggu konfirmasi';
        $loan->tanggal_pengembalian = now(); 
        $loan->save();

        return redirect()->route('mahasiswa.borrowedBooks')->with('success', 'Pengembalian telah diajukan, menunggu konfirmasi admin.');
    }

    public function showLoans()
    {
        $returnedLoans = Loan::where('user_id', Auth::id())
                            ->where('status', 'dikembalikan')
                            ->with(['book', 'review']) 
                            ->get();

        return view('mahasiswa.riwayat.returnBooks', compact('returnedLoans'));
    }

    public function addReview(Request $request, $loanId)
    {
        $loan = Loan::findOrFail($loanId);

        if ($loan->user_id != Auth::id()) {
            return redirect()->route('mahasiswa.riwayat.returnBooks')->with('error', 'Unauthorized action.');
        }

        $request->validate([
            'rating' => 'required|integer|between:1,5',
            'komentar' => 'required|string|max:255',
        ]);

        $review = Review::updateOrCreate(
            [
                'user_id' => Auth::id(),
                'book_id' => $loan->book_id,
            ],
            [
                'rating' => $request->rating,
                'komentar' => $request->komentar,
            ]
        );

        if ($review->wasRecentlyCreated || $review->wasChanged()) {
            return redirect()->route('mahasiswa.riwayat')->with('success', 'Review berhasil diberikan!');
        } else {
            return redirect()->route('mahasiswa.riwayat')->with('info', 'Review sudah ada dan tidak ada perubahan.');
        }
    }

    public function search(Request $request)
    {
        $userId = Auth::id();
        $query = $request->input('query');
    
        $loans = Loan::where('user_id', $userId)
            ->whereHas('book', function ($q) use ($query) {
                $q->where('judul', 'like', '%' . $query . '%');
            })
            ->with('book')
            ->orderBy('tanggal_pinjam', 'desc') 
            ->get();
    
        return view('mahasiswa.pinjam.borrowedBooks', compact('loans'));
    }    
    
    public function filter(Request $request)
    {
        $status = $request->query('status'); 
    
        $loans = Loan::where('user_id', Auth::id())
            ->when($status, function ($query) use ($status) {
                return $query->where('status', $status);
            })
            ->with('book') 
            ->get();
    
        return view('mahasiswa.pinjam.borrowedBooks', compact('loans'));
    }

    public function searchReturnedBooks(Request $request)
    {
        $query = $request->input('query');
        $userId = Auth::id();
    
        // Pencarian untuk buku yang sudah dikembalikan
        $returnedLoans = Loan::where('user_id', $userId)
            ->where('status', 'dikembalikan')
            ->whereHas('book', function ($q) use ($query) {
                $q->where('judul', 'like', '%' . $query . '%');
            })
            ->with('book')
            ->get();
    
        return view('mahasiswa.riwayat.returnBooks', compact('returnedLoans'));
    }
    
    
}

