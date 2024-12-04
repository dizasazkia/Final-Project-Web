<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Loan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;


class BookController extends Controller
{
    public function index(Request $request)
    {
        $searchQuery = $request->query('query');
        $filterKategori = $request->query('kategori');
        $stokFilter = $request->query('stok_filter');
        
        $query = Book::query();
        
        if ($searchQuery) {
            $query->where('judul', 'like', '%' . $searchQuery . '%')
                ->orWhere('penulis', 'like', '%' . $searchQuery . '%')
                ->orWhere('penerbit', 'like', '%' . $searchQuery . '%')
                ->orWhere('kategori', 'like', '%' . $searchQuery . '%');
        }
        
        // Filter berdasarkan kategori 
        if ($filterKategori) {
            $query->where('kategori', $filterKategori);
        }
        
        // Pengurutan berdasarkan stok
        if ($stokFilter == 'low_to_high') {

            $query->orderBy('stok', 'asc');

        } elseif ($stokFilter == 'high_to_low') {

            $query->orderBy('stok', 'desc');
        }
        
        $daftarBuku = $query->get();

        $categories = Book::select('kategori')->distinct()->pluck('kategori');

        return view('admin.index', [
            'daftarBuku' => $daftarBuku,
            'categories' => $categories,
        ]);
    }    
    

    public function show($id)
    {
        $book = Book::findOrFail($id);
        return view('books.show', compact('book'));
    }
    public function create()
    {
        $categories = ['Fiction', 'Fantasy', 'Romance', 'Horror', 'Thriller'];
        return view('admin.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'judul' => 'required|string|max:255',
            'penulis' => 'required|string|max:255',
            'penerbit' => 'required|string|max:255',
            'tahun_terbit' => 'required|digits:4|integer',
            'kategori' => 'required|in:Fiction,Fantasy,Romance,Horror,Thriller',
            'stok' => 'required|integer|min:0',
            'deskripsi' => 'nullable|string',
            'cover_image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048'
        ], [
            'judul.required' => 'Judul buku harus diisi.',
            'penulis.required' => 'Penulis buku harus diisi.',
            'penerbit.required' => 'Penerbit buku harus diisi.',
            'tahun_terbit.required' => 'Tahun terbit harus diisi.',
            'kategori.required' => 'Kategori buku harus diisi.',
            'kategori.in' => 'Kategori yang dipilih tidak valid.',
            'stok.required' => 'Stok buku harus diisi.',
            'stok.min' => 'Stok buku tidak boleh kurang dari 0.',
            'cover_image.image' => 'File cover harus berupa gambar.',
            'cover_image.mimes' => 'Cover image harus berupa file dengan format jpeg, png, atau jpg.',
            'cover_image.max' => 'Ukuran file cover image maksimal 2MB.'
        ]);

        $data = $validated;

        // Menyimpan file gambar cover 
        if ($request->hasFile('cover_image')) {
            $coverImagePath = $request->file('cover_image')->store('covers', 'public');
            $data['cover_image'] = $coverImagePath; // Simpan path gambar ke dalam database
        }

        // Simpan data buku ke dalam database
        Book::create($data);

        return redirect()->route('admin.index')->with('success', 'Buku berhasil ditambahkan!');
    }

    public function edit(string $id)
    {
        $book = Book::find($id);

        if (!$book) {
            return redirect()->route('admin.index')->with('error', 'Buku tidak ditemukan!');
        }
        $categories = ['Fiction', 'Fantasy', 'Romance', 'Horror', 'Thriller'];
        return view('admin.edit', compact('book', 'categories'));
    }

    public function update(Request $request, string $id)
    {
        $validated = $request->validate([
            'judul' => 'required|string|max:255',
            'penulis' => 'required|string|max:255',
            'penerbit' => 'required|string|max:255',
            'tahun_terbit' => 'required|digits:4|integer',
            'kategori' => 'required|in:Fiction,Fantasy,Romance,Horror,Thriller',
            'stok' => 'required|integer|min:0',
            'deskripsi' => 'nullable|string',
            'cover_image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048'
        ], [
            'judul.required' => 'Judul buku harus diisi.',
            'penulis.required' => 'Penulis buku harus diisi.',
            'penerbit.required' => 'Penerbit buku harus diisi.',
            'tahun_terbit.required' => 'Tahun terbit harus diisi.',
            'kategori.required' => 'Kategori buku harus diisi.',
            'kategori.in' => 'Kategori yang dipilih tidak valid.',
            'stok.required' => 'Stok buku harus diisi.',
            'stok.min' => 'Stok buku tidak boleh kurang dari 0.',
            'cover_image.image' => 'File cover harus berupa gambar.',
            'cover_image.mimes' => 'Cover image harus berupa file dengan format jpeg, png, atau jpg.',
            'cover_image.max' => 'Ukuran file cover image maksimal 2MB.'
        ]);

        $book = Book::find($id);

        if (!$book) {
            return redirect()->route('admin.index')->with('error', 'Buku tidak ditemukan!');
        }

        // Menghapus gambar lama jika ada dan mengganti dengan gambar baru
        if ($request->hasFile('cover_image')) {
            if ($book->cover_image) {
                Storage::delete('public/' . $book->cover_image);
            }

            $fileName = $request->file('cover_image')->store('book_covers', 'public');
            $validated['cover_image'] = $fileName;
        }

        $book->update($validated);

        return redirect()->route('admin.index')->with('success', 'Buku berhasil diperbarui!');
    }

    public function destroy(string $id)
    {
        $book = Book::find($id);

        if ($book && $book->cover_image) {
            Storage::delete('public/' . $book->cover_image);
        }

        Book::destroy($id);

        return redirect()->route('admin.index')->with('success', 'Buku berhasil dihapus!');
    }

    public function peminjaman(Request $request)
    {
        $query = $request->query('query');
        
        // search query
        if ($query) {
            $dataPeminjaman = Loan::with(['book', 'user'])
                ->whereHas('user', function($queryBuilder) use ($query) {
                    $queryBuilder->where('name', 'like', '%' . $query . '%');
                })
                ->orWhereHas('book', function($queryBuilder) use ($query) {
                    $queryBuilder->where('judul', 'like', '%' . $query . '%');
                })
                ->get();
        } else {
            $dataPeminjaman = Loan::with(['book', 'user'])->get();
        }
    
        return view('admin.peminjaman', [
            'dataPeminjaman' => $dataPeminjaman,
        ]);
    }
    
}
