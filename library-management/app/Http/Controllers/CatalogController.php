<?php

namespace App\Http\Controllers;

use App\Models\Book;
use Illuminate\Http\Request;

class CatalogController extends Controller
{
    public function index()
    {
        // Ambil semua buku dengan rata-rata rating
        $daftarBuku = Book::withAvg('reviews', 'rating')->get();

        // Ambil kategori dan tahun terbit yang unik
        $categories = Book::select('kategori')->distinct()->get()->pluck('kategori');
        $years = Book::selectRaw('DISTINCT YEAR(tahun_terbit) as year')->get()->pluck('year');

        return view('catalog', [
            'daftarBuku' => $daftarBuku,
            'categories' => $categories,
            'years' => $years,
        ]);
    }

    public function search(Request $request)
    {
        $query = $request->input('query');
        $kategori = $request->input('kategori');
        $tahun = $request->input('tahun');
        $sort = $request->input('sort', 'judul_asc');

        // Ambil kategori dan tahun terbit yang unik
        $categories = Book::select('kategori')->distinct()->get()->pluck('kategori');
        $years = Book::selectRaw('DISTINCT YEAR(tahun_terbit) as year')->get()->pluck('year');

        // Query buku dengan filter
        $daftarBuku = Book::query()->withAvg('reviews', 'rating');

        if ($query) {
            $daftarBuku->where('judul', 'like', "%{$query}%")
                        ->orWhere('penulis', 'like', "%{$query}%");
        }

        if ($kategori) {
            $daftarBuku->where('kategori', $kategori);
        }

        if ($tahun) {
            $daftarBuku->whereYear('tahun_terbit', $tahun);
        }

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

        $daftarBuku = $daftarBuku->get();

        return view('catalog', compact('daftarBuku', 'categories', 'years'));
    }
}
