<?php

namespace App\Http\Controllers;

use App\Models\Book;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class EmployeeController extends Controller
{

    public function index(Request $request)
    {
        //query pencarian, kategori, dan sorting
        $searchQuery = $request->query('query');
        $filterKategori = $request->query('kategori');
        $sortStok = $request->query('sort');
    
        $query = Book::query();
    
        // Filter berdasarkan pencarian
        if ($searchQuery) {
            $query->where('judul', 'like', '%' . $searchQuery . '%')
                ->orWhere('penulis', 'like', '%' . $searchQuery . '%')
                ->orWhere('kategori', 'like', '%' . $searchQuery . '%');
        }
    
        // Filter berdasarkan kategori
        if ($filterKategori) {
            $query->where('kategori', $filterKategori);
        }
    
        // Sorting berdasarkan stok
        if ($sortStok) {
            if ($sortStok == 'stok_asc') {
                $query->orderBy('stok', 'asc');
            } elseif ($sortStok == 'stok_desc') {
                $query->orderBy('stok', 'desc');
            }
        }

        $daftarBuku = $query->get();
    
        // Ambil semua kategori unik untuk dropdown filter
        $categories = Book::select('kategori')->distinct()->pluck('kategori');
    
        return view('pegawai.book.index', [
            'daftarBuku' => $daftarBuku,
            'categories' => $categories,
        ]);
    }    

    public function create()
    {
        $categories = ['Fiction', 'Fantasy', 'Romance', 'Horror', 'Thriller'];
        return view('pegawai.book.create', compact('categories'));
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

        return redirect()->route('employee.book.index')->with('success', 'Buku berhasil ditambahkan!');
    }

    public function edit(string $id)
    {
        $book = Book::find($id);

        if (!$book) {
            return redirect()->route('employee.book.index')->with('error', 'Buku tidak ditemukan!');
        }
        
        $categories = ['Fiction', 'Fantasy', 'Romance', 'Horror', 'Thriller'];
        return view('pegawai.book.edit', compact('book', 'categories'));
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
            return redirect()->route('employee.book.index')->with('error', 'Buku tidak ditemukan!');
        }

        // Menghapus gambar lama jika ada dan mengganti dengan gambar baru
        if ($request->hasFile('cover_image')) {
            if ($book->cover_image) {
                Storage::delete('public/' . $book->cover_image);
            }

            $fileName = $request->file('cover_image')->store('book_covers', 'public');
            $validated['cover_image'] = $fileName;
        }

        // Update data buku
        $book->update($validated);

        return redirect()->route('employee.book.index')->with('success', 'Buku berhasil diperbarui!');
    }

    public function destroy(string $id)
    {
        $book = Book::find($id);

        if ($book && $book->cover_image) {
            Storage::delete('public/' . $book->cover_image);
        }

        Book::destroy($id);

        return redirect()->route('employee.book.index')->with('success', 'Buku berhasil dihapus!');
    }
}
