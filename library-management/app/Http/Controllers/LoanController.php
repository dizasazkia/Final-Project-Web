<?php

namespace App\Http\Controllers;

use App\Models\Loan;
use App\Models\Book;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoanController extends Controller
{
    
    public function index(Request $request)
    {
        $loans = Loan::all();
        $status = $request->query('status');
        $sort = $request->query('sort');
    
        $statusOptions = ['dipinjam', 'dikembalikan', 'terlambat'];
    
        $loans = Loan::with('user', 'book')
            ->when($status, function ($queryBuilder) use ($status) {
                return $queryBuilder->where('status', $status);
            })
            ->when($sort, function ($queryBuilder) use ($sort) {
                return $queryBuilder->orderBy('tanggal_kembali', $sort);
            })
            ->get();

        return view('pegawai.loan.index', compact('loans', 'statusOptions'));
    }

    public function search(Request $request)
    {
        $query = $request->query('query');

        if ($query) {
            $loans = Loan::whereHas('user', function ($q) use ($query) {
                $q->where('name', 'like', '%' . $query . '%');
            })->with(['user', 'book'])->get();
        } else {
            $loans = Loan::with(['user', 'book'])->get();
        }

        return view('pegawai.loan.index', compact('loans'));
    }

    public function store(Request $request, Book $book)
    {
        $userId = Auth::id();

        $activeLoansCount = Loan::where('user_id', $userId)
                                ->where('status', 'dipinjam')
                                ->count();
    
        if ($activeLoansCount >= 3) {
            return response()->json(['success' => false, 'message' => 'Anda hanya dapat meminjam maksimal 3 buku dalam satu waktu.'], 400);
        }
    
        $alreadyBorrowed = Loan::where('user_id', $userId)
                                ->where('book_id', $book->id)
                                ->where('status', 'dipinjam')
                                ->exists();
    
        if ($alreadyBorrowed) {
            return response()->json(['success' => false, 'message' => 'Anda tidak dapat meminjam buku yang sama dalam satu waktu.'], 400);
        }
    
        if ($book->stok > 0) {
            Loan::create([
                'user_id' => $userId,
                'book_id' => $book->id,
                'tanggal_pinjam' => now(),
                'tanggal_kembali' => now()->addDays(3),
                'status' => 'dipinjam',
            ]);
    
            $book->decrement('stok');
    
            return response()->json(['success' => true], 200);
        }
    
        return response()->json(['success' => false, 'message' => 'Buku tidak tersedia, stok habis!'], 400);
    }
    

    public function returnBook(Request $request, Loan $loan)
    {
        if (in_array($loan->status, ['dikembalikan', 'menunggu konfirmasi'])) {
            return redirect()->back()->with('error', 'Buku ini sudah dikembalikan atau sedang menunggu konfirmasi.');
        }

        $loan->status = 'menunggu konfirmasi';
        $loan->tanggal_pengembalian = now(); 
        $loan->save();

        return redirect()->route('pegawai.loan.index')->with('success', 'Pengembalian buku berhasil dikonfirmasi.' . 
            ($loan->denda > 0 ? ' Denda: Rp' . number_format($loan->denda, 0, ',', '.') : ''));
    }

    public function confirmReturn(Request $request, Loan $loan)
    {
        if ($loan->status !== 'menunggu konfirmasi') {
            return redirect()->back()->with('error', 'Pengembalian tidak valid atau sudah dikonfirmasi.');
        }
    
        // Tambahkan validasi untuk tanggal jatuh tempo dan pengembalian
        if (!$loan->tanggal_kembali) {
            return redirect()->back()->with('error', 'Tanggal jatuh tempo tidak valid.');
        }
    
        if (!$loan->tanggal_pengembalian) {
            $loan->tanggal_pengembalian = now();
        }
        
        $loan->save();
    
        $loan->book->increment('stok');
    
        return redirect()->route('employee.return.index')->with(
            'success',
            'Pengembalian buku berhasil dikonfirmasi.' . 
            ($loan->denda > 0 ? " Dengan denda Rp" . number_format($loan->denda, 0, ',', '.') : "")
        );
    }        
        
    public function destroy($loan)
    {
        $loan = Loan::findOrFail($loan);

        $loan->delete();

        return redirect()->route('employee.loan.index')->with('success', 'Data berhasil dihapus');
    }

    public function riwayat()
    {
        $user = Auth::user();
        $returnedLoans = Loan::where('user_id', Auth::id())
                            ->where('status', 'dikembalikan')
                            ->with('book')
                            ->get();

        return view('mahasiswa.riwayat.returnBooks', compact('returnedLoans', 'user'));
    }
}