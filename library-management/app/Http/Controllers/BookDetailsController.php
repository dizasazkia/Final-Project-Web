<?php

namespace App\Http\Controllers;
use App\Models\Book;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BookDetailsController extends Controller
{
    /**
     * Show the details of a specific book.
     *
     * @param int $id
     * @return \Illuminate\View\View
     */
    public function show($id)
    {
        $user = Auth::user();
        $book = Book::findOrFail($id);

        $averageRating = $book->reviews()->avg('rating');

        // Mengambil semua review yang terkait dengan buku
        $reviews = $book->reviews()->orderBy('created_at', 'desc')->get(); 

        $averageRating = $averageRating ? $averageRating : 0;

        return view('mahasiswa.catalog.details', compact('book', 'averageRating', 'reviews', 'user'));
    }

    
}
