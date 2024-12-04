<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Book extends Model
{
    use HasFactory;

    protected $fillable = ['judul', 'penulis', 'penerbit', 'tahun_terbit', 'kategori', 'stok', 'deskripsi'];

    public function loans()
    {
        return $this->hasMany(Loan::class, 'book_id');
    }

    public function reservations()
    {
        return $this->hasMany(Reservation::class, 'book_id');
    }

    public function reviews()
    {
        return $this->hasMany(Review::class, 'book_id');
    }
}

