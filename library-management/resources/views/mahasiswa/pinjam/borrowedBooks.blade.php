@extends('mahasiswa.templates.master')

@section('content')
<div class="p-4 sm:ml-64 relative overflow-x-auto shadow-md sm:rounded-lg bg-white" style="border-radius: 25px; margin-right: 25px; margin-bottom: 25px; min-height: 100vh;">
    <div class="flex justify-end items-center pb-5 dark:bg-gray-900 mb-5">
        <!-- Judul -->
        <h3 class="text-2xl font-bold text-gray-900 mr-auto ml-3">Borrowed Books</h3>
        <!-- Search Bar -->
        <form action="{{ route('mahasiswa.borrowed.search') }}" method="GET" class="relative flex items-center justify-start max-w-[400px] w-full mt-1 ml-0">
            <input 
                type="text" 
                name="query" 
                placeholder="Search Title" 
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
                    <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-600 dark:hover:text-white" role="menuitem">Dashboard</a>
                </li>
                <li>
                    <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-600 dark:hover:text-white" role="menuitem">Settings</a>
                </li>
                <li>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-600 dark:hover:text-white">Sign out</button>
                    </form>
                </li>
            </ul>
        </div>
    </div>

        <!-- Filter Status -->
        <form action="{{ route('mahasiswa.borrowed.filter') }}" method="GET" class="flex justify-end items-center mt-4 lg:mt-0 mb-5">
            <select 
                name="status" 
                class="rounded-lg px-3 py-2 w-auto focus:outline-none focus:ring-0 text-white" 
                style="border: none; box-shadow: none; background-color:#a2bcd7;" 
                onchange="this.form.submit()">
                <option value="">All Status</option>
                <option value="dipinjam" {{ request()->query('status') == 'dipinjam' ? 'selected' : '' }}>Dipinjam</option>
                <option value="menunggu konfirmasi" {{ request()->query('status') == 'menunggu konfirmasi' ? 'selected' : '' }}>Menunggu Konfirmasi</option>
                <option value="dikembalikan" {{ request()->query('status') == 'dikembalikan' ? 'selected' : '' }}>Dikembalikan</option>
            </select>
        </form>        

    <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
        <thead class="text-xs text-gray-700 uppercase text-center dark:bg-gray-700 dark:text-gray-400" style="background-color: #e6edf5;">
            <tr>
                <th scope="col" class="p-4">
                    Cover
                </th>
                <th scope="col" class="px-6 py-3">
                    Title
                </th>
                <th scope="col" class="px-6 py-3">
                    Loan Date
                </th>
                <th scope="col" class="px-6 py-3">
                    Due Date
                </th>
                <th scope="col" class="px-6 py-3">
                    Status
                </th>
                <th scope="col" class="px-6 py-3">
                    Action
                </th>
            </tr>
        </thead>
        <tbody>
            @foreach ($loans as $loan)
            <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 text-center">
                <td class="px-6 py-4">
                    <img src="{{ asset('storage/' . $loan->book->cover_image) }}" alt="Cover Buku" class="w-32 h-32 object-contain">
                </td>
                <td class="px-6 py-4">{{ $loan->book->judul }}</td>
                <td class="px-6 py-4">{{ $loan->tanggal_pinjam }}</td>
                <td class="px-6 py-4">{{ $loan->tanggal_kembali }}</td>
                <td class="px-6 py-4">
                    @if ($loan->status == 'dipinjam')
                        <span class="text-yellow-500 font-semibold">Dipinjam</span>
                    @elseif ($loan->status == 'menunggu konfirmasi')
                        <span class="text-blue-500 font-semibold">Menunggu Konfirmasi</span>
                    @else
                        <span class="text-green-500 font-semibold">Dikembalikan</span>
                    @endif
                </td>
                <td class="px-6 py-4">
                    @if ($loan->status == 'dipinjam')
                    <div class="flex space-x-2">
                        <form action="{{ route('mahasiswa.requestReturn', $loan->id) }}" method="POST">
                            @csrf
                            <button type="submit" class="bg-red-500 text-white px-2 py-1 text-sm rounded hover:bg-red-600">
                                Return
                            </button>
                        </form>
                    
                        <form action="{{ route('mahasiswa.extendLoan', $loan->id) }}" method="POST">
                            @csrf
                            <button type="submit" class="bg-blue-500 text-white px-2 py-1 text-sm rounded hover:bg-blue-600">
                                Extend
                            </button>
                        </form>
                    </div>
                    
                    
                    @elseif ($loan->status == 'menunggu konfirmasi')
                        <span class="text-gray-500">Menunggu Konfirmasi Admin</span>
                    @else
                        <span class="text-green-500">Buku Sudah Dikembalikan</span>
                    @endif
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
