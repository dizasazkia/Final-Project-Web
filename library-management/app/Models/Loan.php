<?php

namespace App\Models;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Loan extends Model
{
    use HasFactory;

    protected $dates = ['tanggal_pinjam', 'tanggal_kembali', 'tanggal_pengembalian'];

    protected $fillable = [
        'user_id', 
        'book_id', 
        'tanggal_pinjam', 
        'tanggal_kembali', 
        'tanggal_pengembalian',
        'status', 
        'denda', 
        'renewal_count',
    ];
    
    public function getDendaCalculatedAttribute()
    {
        $tanggalJatuhTempo = $this->tanggal_kembali ? Carbon::parse($this->tanggal_kembali) : null;
        $tanggalPengembalian = $this->tanggal_pengembalian ? Carbon::parse($this->tanggal_pengembalian) : null;
    
        if (!$tanggalJatuhTempo || !$tanggalPengembalian) {
            return 0; 
        }
    
        if ($tanggalPengembalian->isAfter($tanggalJatuhTempo)) {
            $lateDays = $tanggalPengembalian->diffInDays($tanggalJatuhTempo);
            return $lateDays * 5000;
        }
    
        return 0; 
    }                     

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function book()
    {
        return $this->belongsTo(Book::class, 'book_id');
    }

    public function review()
    {
        return $this->hasOne(Review::class, 'book_id', 'book_id');
    }
    
}
