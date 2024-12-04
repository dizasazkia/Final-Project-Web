<?php

namespace App\Http\Controllers;

use App\Models\Book;

class DetailsController extends Controller
{
    /**
     * Show the details of a specific book along with the average rating and reviews.
     *
     * @param int $id
     * @return \Illuminate\View\View
     */
    public function show($id)
    {
        $book = Book::findOrFail($id);

        $averageRating = $book->reviews()->avg('rating'); 

        $reviews = $book->reviews()->orderBy('created_at', 'desc')->get(); 

        $averageRating = $averageRating ? $averageRating : 0;

        return view('details', compact('book', 'averageRating', 'reviews'));
    }
}
