<?php

namespace App\Http\Controllers;

use App\Models\Loan;
use App\Models\Book;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class MahasiswaDashboardController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $userId = Auth::id();

        $currentLoans = Loan::where('user_id', $userId)
            ->where('status', 'dipinjam')
            ->count();

        $returnedBooks = Loan::where('user_id', $userId)
            ->where('status', 'dikembalikan')
            ->count();

        $overdueLoans = Loan::where('user_id', $userId)
            ->where('status', 'dipinjam')
            ->whereDate('tanggal_kembali', '<', now())
            ->count();

        $popularBooks = Book::withAvg('reviews', 'rating')
            ->orderByDesc('reviews_avg_rating')
            ->take(4)
            ->get();

        $newBooks = Book::orderByDesc('created_at')
            ->take(4)
            ->get();

        // Menentukan kategori yang paling sering dipinjam oleh user
        $mostFrequentCategory = Loan::where('user_id', $userId)
            ->join('books', 'loans.book_id', '=', 'books.id')
            ->select('books.kategori', DB::raw('count(books.kategori) as count'))
            ->groupBy('books.kategori')
            ->orderByDesc('count')
            ->value('books.kategori');

        // Jika user belum pernah meminjam buku
        if (!$mostFrequentCategory) {
            $recommendedBooks = Book::joinSub(
                Loan::select('book_id', DB::raw('count(book_id) as loan_count'))
                    ->groupBy('book_id'),
                'loan_counts',
                'books.id',
                '=',
                'loan_counts.book_id'
            )
            ->orderByDesc('loan_count')
            ->take(4)
            ->get();        
        } else {
            // Jika ada kategori, ambil 4 buku dari kategori tersebut
            $recommendedBooks = Book::where('kategori', $mostFrequentCategory)
                ->inRandomOrder()
                ->take(4)
                ->get();
        }

        $notifications = session('notifications', []);

        if ($request->has('clear_notifications')) {
            session()->forget('notifications');
            return redirect()->route('mahasiswa.dashboard')->with('status', 'Notifikasi telah dihapus.');
        }

        return view('mahasiswa.dashboard', compact(
            'currentLoans',
            'returnedBooks',
            'overdueLoans',
            'popularBooks',
            'newBooks',
            'recommendedBooks',
            'user',
            'notifications'
        ));
    }

    public function master()
    {
        $user = Auth::user();
        return view('mahasiswa.templates.master', compact('user'));
    }
}
