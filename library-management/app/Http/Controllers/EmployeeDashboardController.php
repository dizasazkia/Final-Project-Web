<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\User;
use App\Models\Loan;
use Illuminate\Support\Facades\DB;

class EmployeeDashboardController extends Controller
{
    public function index()
    {
        $totalBooks = Book::count();

        $booksAvailability = Book::withCount('loans')->get()->map(function($book) {
            return [
                'title' => $book->judul,
                'available' => $book->stok - $book->loans_count
            ];
        });

        $pendingReturns = Loan::where('status', 'menunggu konfirmasi')->count();

        $loansPerDay = Loan::selectRaw('DATE(tanggal_pinjam) as day, COUNT(*) as total')
            ->groupBy('day')
            ->orderBy('day')
            ->get();

        return view('pegawai.dashboard', compact(
            'totalBooks', 
            'pendingReturns', 
            'booksAvailability',
            'loansPerDay'
        ));
    }
    
}

