<?php

namespace App\Http\Controllers;

use App\Models\Loan;
use App\Models\Book;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReturnController extends Controller
{
    public function index()
    {
        $loans = Loan::with(['user', 'book'])->where('status', 'menunggu konfirmasi')->get();
        return view('pegawai.return.index', compact('loans'));
    }

    public function store(Request $request, Book $book)
    {
        if ($book->stok > 0) {
            $loan = Loan::create([
                'user_id' => Auth::id(),  
                'book_id' => $book->id,   
                'tanggal_pinjam' => now(), 
                'tanggal_kembali' => now()->addDays(3), 
                'status' => 'dipinjam',   
            ]);

            $book->decrement('stok');

            return response()->json(['success' => true], 200);
        }

        return response()->json(['success' => false, 'message' => 'Buku tidak tersedia, stok habis!'], 400);
    }

    public function returnBook(Request $request, Loan $loan)
    {
        if (in_array($loan->status, ['dikembalikan', 'menunggu konfirmasi'])) {
            return redirect()->back()->with('error', 'Buku ini sudah dikembalikan atau sedang menunggu konfirmasi.');
        }
    
        $loan->status = 'menunggu konfirmasi';
        $loan->tanggal_pengembalian = now(); // Tambahkan tanggal pengembalian
        $loan->save();
    
        return redirect()->route('pegawai.return.index')->with('success', 'Pengembalian buku berhasil, menunggu konfirmasi dari pegawai.');
    }
    
    public function confirmReturn(Request $request, Loan $loan)
    {
        if ($loan->status !== 'menunggu konfirmasi') {
            return redirect()->back()->with('error', 'Pengembalian tidak valid atau sudah dikonfirmasi.');
        }
    
        // Tambahkan validasi untuk tanggal jatuh tempo dan pengembalian
        if (!$loan->tanggal_kembali) {
            return redirect()->back()->with('error', 'Tanggal jatuh tempo tidak valid.');
        }
    
        if (!$loan->tanggal_pengembalian) {
            $loan->tanggal_pengembalian = now(); // Set tanggal pengembalian default jika NULL
        }
    
        $loan->status = 'dikembalikan';
        $loan->save();
    
        $loan->book->increment('stok');
    
        return redirect()->route('employee.return.index')->with(
            'success',
            'Pengembalian buku berhasil dikonfirmasi.' . 
            ($loan->denda > 0 ? " Dengan denda Rp" . number_format($loan->denda, 0, ',', '.') : "")
        );
    }          
    

    public function destroy($loan)
    {
        $loan = Loan::findOrFail($loan);

        $loan->delete();

        return redirect()->route('employee.return.index')->with('success', 'Data berhasil dihapus');
    }
}
