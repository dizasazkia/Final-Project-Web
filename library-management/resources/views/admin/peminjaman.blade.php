@extends('admin.templates.master')

@section('content')
<div class="p-4 sm:ml-64 relative overflow-x-auto shadow-md sm:rounded-lg bg-white" style="border-radius: 25px; margin-right: 25px; margin-bottom: 25px; min-height: 100vh;">
    <div class="flex justify-between items-center pb-5 dark:bg-gray-900 mb-5">
        <!-- Judul -->
        <h3 class="text-2xl font-bold text-gray-900 mr-auto ml-3">Daftar Peminjaman Buku</h3>
    
        <!-- Search Bar -->
        <form action="{{ route('admin.peminjaman') }}" method="GET" class="relative flex items-center justify-start max-w-[400px] w-full mt-1 ml-0">
            <input 
                type="text" 
                name="query" 
                placeholder="Search users or books" 
                value="{{ request()->query('query') }}" 
                class="block p-2 pl-10 text-sm text-gray-900 rounded-lg w-full bg-gray-200 focus:ring-0 dark:bg-gray-700 dark:placeholder-gray-400 dark:text-white"
                style="border: none; outline: none; box-shadow: none; border-radius: 10px;">
            <button type="submit" class="absolute left-0 top-0 bottom-0 px-4 py-2 text-gray-600">
                <i class="fas fa-search"></i> 
            </button>
        </form>        
    
        <!-- Profil User -->
        <div class="flex items-center mr-2 ml-3">
            <!-- Dropdown Profil -->
            <button type="button" class="flex items-center text-sm rounded-full focus:ring-4 focus:ring-gray-300 dark:focus:ring-gray-600" aria-expanded="false" data-dropdown-toggle="dropdown-user">
                <span class="sr-only">Open user menu</span>
                <!-- Ganti gambar dengan inisial -->
                <div class="w-10 h-10 bg-gray-500 text-white rounded-full flex items-center justify-center font-semibold">
                    @php
                        $name = Auth::user()->name ?? 'Guest';
                        $name_parts = explode(' ', $name); 
                        $initials = strtoupper(substr($name_parts[0], 0, 1)); 
                        if (count($name_parts) > 1) {
                            $initials .= strtoupper(substr($name_parts[1], 0, 1)); 
                        }
                    @endphp
                    {{ $initials }}
                </div>
            </button>
    
            <!-- Informasi Nama Pengguna dan Role -->
            <div class="ml-3">
                <span class="text-sm font-medium text-gray-900 dark:text-white">
                    {{ Auth::user()->name ?? 'Guest' }}
                </span>
                <p class="text-sm text-gray-600 dark:text-gray-250">
                    {{ Auth::user()->role ?? 'No Role Assigned' }}
                </p>
            </div>
        </div>
    
        <!-- Dropdown Menu -->
        <div class="z-50 hidden my-4 text-base list-none bg-white divide-y divide-gray-100 rounded shadow dark:bg-gray-700 dark:divide-gray-600" id="dropdown-user">
            <div class="px-4 py-3" role="none">
                <p class="text-sm text-gray-900 dark:text-white" role="none">{{ Auth::user()->name ?? 'Guest' }}</p>
                <p class="text-sm font-medium text-gray-900 truncate dark:text-gray-300" role="none">{{ Auth::user()->email ?? 'Not Logged In' }}</p>
            </div>
            <ul class="py-1" role="none">
                <li>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-600 dark:hover:text-white">Sign out</button>
                    </form>
                </li>
            </ul>
        </div>
    </div>

    <!-- Tabel Peminjaman -->
    <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
        <thead class="text-xs text-gray-700 uppercase text-center bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
            <tr>
                <th class="px-6 py-3">No</th>
                <th class="px-6 py-3">Nama Pengguna</th>
                <th class="px-6 py-3">Buku</th>
                <th class="px-6 py-3">Tanggal Pinjam</th>
                <th class="px-6 py-3">Tanggal Kembali</th>
                <th class="px-6 py-3">Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($dataPeminjaman as $peminjaman)
            <tr class="bg-white text-center border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                <td class="px-6 py-4">{{ $loop->iteration }}</td>
                <td class="px-6 py-4">{{ $peminjaman->user->name }}</td>
                <td class="px-6 py-4">{{ $peminjaman->book->judul }}</td>
                <td class="px-6 py-4">{{ $peminjaman->tanggal_pinjam }}</td>
                <td class="px-6 py-4">{{ $peminjaman->tanggal_kembali ?? '-' }}</td>
                <td class="px-6 py-4">
                    @if ($peminjaman->status === 'dikembalikan')
                        <span class="text-green-500">Dikembalikan</span>
                    @else
                        <span class="text-red-500">Dipinjam</span>
                    @endif
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
