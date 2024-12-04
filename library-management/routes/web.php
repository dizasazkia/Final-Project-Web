<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\CatalogController;
use App\Http\Controllers\DetailsController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\AdminDashboardController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\EmployeeDashboardController;
use App\Http\Controllers\BookController;
use App\Http\Controllers\LoanController;
use App\Http\Controllers\BookCatalogController;
use App\Http\Controllers\BookDetailsController;
use App\Http\Controllers\BorrowController;
use App\Http\Controllers\ReturnController;
use App\Http\Controllers\MahasiswaDashboardController;
use App\Http\Controllers\ProfileMahasiswaController;
use Illuminate\Support\Facades\Route;

// Route::get('/dashboard', function () {
//     return view('dashboard');
// })->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::get('/', [HomeController::class, 'index'])->name('home');

Route::get('/catalog', [CatalogController::class, 'index'])->name('catalog.index');
Route::get('/search', [CatalogController::class, 'search'])->name('search');
Route::get('/book/{id}', [DetailsController::class, 'show'])->name('book.details');

Route::post('/loan/{book}', [LoanController::class, 'store'])->name('loan.store');
Route::post('/borrow/{book}', [LoanController::class, 'store'])->name('borrow.store');
Route::get('/employee/loans', [LoanController::class, 'index'])->name('employee.loans');

Route::resource('/users', AdminController::class);

Route::prefix('employee')->name('employee.')->group(function() {
    Route::resource('book', EmployeeController::class);
});

Route::prefix('employee')->name('employee.')->middleware(['auth'])->group(function () {
    Route::get('/loan', [LoanController::class, 'index'])->name('loan.index');
});
Route::post('/loan/return/{loan}', [LoanController::class, 'returnBook'])->name('loan.return');
Route::delete('/employee/book/{id}', [EmployeeController::class, 'destroy'])->name('employee.destroy');


Route::post('/clear-notifications', [MahasiswaDashboardController::class, 'clearNotifications'])->name('clear.notifications');

Route::prefix('mahasiswa')->name('mahasiswa.')->group(function () {
    Route::get('/catalog', [BookCatalogController::class, 'index'])->name('catalog.index');
    Route::get('/catalog/search', [BookCatalogController::class, 'search'])->name('catalog.search');
});

Route::prefix('mahasiswa')->name('mahasiswa.')->group(function () {
    // Route untuk menampilkan detail buku
    Route::get('/catalog/details/{id}', [BookDetailsController::class, 'show'])->name('catalog.details');
});

Route::prefix('mahasiswa')->name('mahasiswa.')->middleware('auth')->group(function () {
    // Menampilkan daftar buku yang dipinjam oleh mahasiswa
    Route::get('/borrowed-books', [BorrowController::class, 'borrowedBooks'])->name('borrowedBooks');
    // Menampilkan riwayat buku yang sudah dikembalikan
    Route::get('/returned-books', [BorrowController::class, 'showLoans'])->name('returnedBooks');
    // Meminjam buku
    Route::post('/borrow-book/{book}', [BorrowController::class, 'borrowBook'])->name('borrowBook');
    // Mengembalikan buku
    Route::post('/return-book/{loanId}', [BorrowController::class, 'returnBook'])->name('returnBook');
});

Route::post('/loan/return/{loan}', [LoanController::class, 'returnBook'])->name('loan.return');
Route::post('/loan/confirm-return/{loan}', [LoanController::class, 'confirmReturn'])->middleware('auth')->name('loan.confirm-return');


Route::post('review/{loanId}', [BorrowController::class, 'addReview'])->name('mahasiswa.review');

Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/admin/dashboard', [AdminDashboardController::class, 'index'])->name('admin.dashboard');
    Route::get('/admin/peminjaman', [BookController::class, 'peminjaman'])->name('admin.peminjaman');
    Route::resource('/admin', BookController::class);
});

Route::middleware(['auth', 'role:mahasiswa'])->group(function () {
    Route::get('/mahasiswa/dashboard', [MahasiswaDashboardController::class, 'index'])->name('mahasiswa.dashboard');
    Route::post('/pinjam/{loanId}/perpanjang', [BorrowController::class, 'extendLoan'])->name('mahasiswa.extendLoan');
    Route::get('/mahasiswa/riwayat', [LoanController::class, 'riwayat'])->name('mahasiswa.riwayat');
    Route::get('/mahasiswa/borrowed/search', [BorrowController::class, 'search'])->name('mahasiswa.borrowed.search');
    Route::get('/mahasiswa/borrowed/filter', [BorrowController::class, 'filter'])->name('mahasiswa.borrowed.filter');
    Route::get('/returned-books/search', [BorrowController::class, 'searchReturnedBooks'])->name('mahasiswa.returnBooks.search');
    Route::post('/mahasiswa/loans/{loan}/request-return', [BorrowController::class, 'requestReturn'])->name('mahasiswa.requestReturn');
    Route::get('/profile/{id}', [ProfileMahasiswaController::class, 'edit'])->name('profileMahasiswa.edit');
    Route::put('/profile/{id}', [ProfileMahasiswaController::class, 'update'])->name('profileMahasiswa.update');
});

Route::middleware(['auth', 'role:pegawai'])->group(function () {
    Route::get('/employee/dashboard', [EmployeeDashboardController::class, 'index'])->name('employee.dashboard');
    Route::delete('/employee/loans/{loan}', [LoanController::class, 'destroy'])->name('loan.delete');
    Route::get('/employee/returns', [ReturnController::class, 'index'])->name('employee.return.index');
    Route::post('/employee/loans/{loan}/confirm-return', [ReturnController::class, 'confirmReturn'])->name('employee.confirmReturn');
    Route::get('/pegawai/books/search', [EmployeeController::class, 'index'])->name('employee.book.search');
    Route::get('/employee/loan/search', [LoanController::class, 'search'])->name('employee.loan.search');
    Route::get('/employee/loans', [LoanController::class, 'index'])->name('pegawai.loan.index');
});


require __DIR__.'/auth.php';
