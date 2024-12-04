<?php

namespace App\Http\Controllers;

use App\Models\Book;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;

class BookCatalogController extends Controller
{

    public function __construct()
    {
        $user = Auth::user();
        View::share('user', $user);
    }

    public function index()
    {
        $user = Auth::user();
        $daftarBuku = Book::withAvg('reviews', 'rating')->get();

        // Ambil kategori dan tahun terbit yang unik
        $categories = Book::select('kategori')->distinct()->get()->pluck('kategori');
        $years = Book::selectRaw('DISTINCT YEAR(tahun_terbit) as year')->get()->pluck('year');

        // Kirim data ke view
        return view('mahasiswa.catalog.catalog', [
            'daftarBuku' => $daftarBuku,
            'categories' => $categories,
            'years' => $years,
            'user' => $user,
        ]);
    }

    public function search(Request $request)
    {
        // Ambil input filter dan pencarian
        $query = $request->input('query');
        $kategori = $request->input('kategori');
        $tahun = $request->input('tahun');
        $sort = $request->input('sort', 'judul_asc'); 

        // Ambil kategori dan tahun terbit yang unik
        $categories = Book::select('kategori')->distinct()->get()->pluck('kategori');
        $years = Book::selectRaw('DISTINCT YEAR(tahun_terbit) as year')->get()->pluck('year');

        // Query buku dengan rata-rata rating
        $daftarBuku = Book::query()->withAvg('reviews', 'rating');

        // Tambahkan filter pencarian 
        if ($query) {
            $daftarBuku->where(function ($q) use ($query) {
                $q->where('judul', 'like', "%{$query}%")
                ->orWhere('penulis', 'like', "%{$query}%");
            });
        }

        // Tambahkan filter kategori
        if ($kategori) {
            $daftarBuku->where('kategori', $kategori);
        }

        // Tambahkan filter tahun terbit
        if ($tahun) {
            $daftarBuku->whereYear('tahun_terbit', $tahun);
        }

        // Sorting berdasarkan input
        switch ($sort) {
            case 'judul_asc':
                $daftarBuku->orderBy('judul', 'asc');
                break;
            case 'judul_desc':
                $daftarBuku->orderBy('judul', 'desc');
                break;
            case 'tahun_asc':
                $daftarBuku->orderBy('tahun_terbit', 'asc');
                break;
            case 'tahun_desc':
                $daftarBuku->orderBy('tahun_terbit', 'desc');
                break;
        }

        // Ambil data dari database
        $daftarBuku = $daftarBuku->get();

        // Kirim data ke view
        return view('mahasiswa.catalog.catalog', compact('daftarBuku', 'categories', 'years'));
    }
}
