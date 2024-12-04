<?php

namespace App\Http\Controllers;
use App\Models\Book;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $popularBooks = Book::select('books.id', 'books.judul', 'books.penulis', 'books.cover_image') 
            ->leftJoin('reviews', 'books.id', '=', 'reviews.book_id') 
            ->groupBy('books.id', 'books.judul', 'books.penulis', 'books.cover_image') 
            ->orderByRaw('AVG(reviews.rating) DESC') 
            ->take(5) 
            ->get();
        
        $newBooks = Book::latest() 
            ->take(5) 
            ->get();

        return view('home', compact('popularBooks', 'newBooks'));
    }
}
