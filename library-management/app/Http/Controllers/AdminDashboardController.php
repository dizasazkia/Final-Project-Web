<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\User;
use App\Models\Loan;
use Illuminate\Support\Facades\DB;

class AdminDashboardController extends Controller
{
    public function index()
    {
        $totalBooks = Book::count();
        $totalUsers = User::count();

        // Mengambil data jumlah peminjaman per hari
        $loansPerDay = Loan::select(DB::raw('DATE(tanggal_pinjam) as day'), DB::raw('COUNT(*) as total'))
                            ->groupBy(DB::raw('DATE(tanggal_pinjam)'))
                            ->get();

        // Mengambil data ketersediaan buku (total stok - total peminjaman)
        $booksAvailability = Book::withCount('loans')
                                ->get()
                                ->map(function($book) {
                                    return [
                                        'title' => $book->judul,
                                        'available' => $book->stok - $book->loans_count
                                    ];
                                });

        return view('admin.dashboard', compact('totalBooks', 'totalUsers', 'loansPerDay', 'booksAvailability'));
    }
}

