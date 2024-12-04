@extends('admin.templates.master')

@section('content')
<div class="p-4 sm:ml-64 relative overflow-x-auto shadow-md sm:rounded-lg bg-white" style="border-radius: 25px; margin-right: 25px; margin-bottom: 25px; min-height: 100vh;">
    <div class="flex justify-between items-center pb-5 dark:bg-gray-900 mb-5">
        <!-- Judul -->
        <h3 class="text-2xl font-bold text-gray-900 mr-auto ml-3">List Book</h3>
    
        <!-- Search Bar -->
        <form action="{{ route('admin.index') }}" method="GET" class="relative flex items-center justify-start max-w-[400px] w-full mt-1 ml-0">
            <input 
                type="text" 
                name="query" 
                placeholder="Search books" 
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
    
    <div class="flex justify-between items-center pb-4">
        <div class="flex justify-between">
            <a href="{{ url('/admin/create') }}" 
                class="text-white p-3" 
                style="background-color: #7c99b8; border-radius: 10px; padding: 10px 20px;"
                onmouseover="this.style.backgroundColor='#7f94ab';"
                onmouseout="this.style.backgroundColor='#a2bcd7';">
                Add New Book
            </a>
        </div>

        <!-- Filters and Sorting -->
        <div class="flex items-center gap-4 mt-4 lg:mt-0">
            <!-- Filter Kategori Buku -->
            <form action="{{ route('admin.index') }}" method="GET" class="w-full md:w-auto">
                <select 
                    name="kategori" 
                    class="rounded-lg px-3 py-2 w-full focus:outline-none focus:ring-0 text-white" 
                    style="border: none; box-shadow: none; background-color:#a2bcd7;" 
                    onchange="this.form.submit()">
                    <option value="">All Categories</option>
                    @foreach($categories as $category)
                        <option value="{{ $category }}" 
                            {{ request()->query('kategori') == $category ? 'selected' : '' }}>
                            {{ $category }}
                        </option>
                    @endforeach
                </select>
            </form>

            <!-- Filter Stok Buku dengan Sorting -->
            <form action="{{ route('admin.index') }}" method="GET" class="w-full md:w-auto">
                <select 
                    name="stok_filter" 
                    class="rounded-lg px-3 py-2 w-full focus:outline-none focus:ring-0 text-white" 
                    style="border: none; box-shadow: none; background-color:#a2bcd7;" 
                    onchange="this.form.submit()">
                    <option value="">All Stocks</option>
                    <option value="low_to_high" {{ request()->query('stok_filter') == 'low_to_high' ? 'selected' : '' }}>Low to High</option>
                    <option value="high_to_low" {{ request()->query('stok_filter') == 'high_to_low' ? 'selected' : '' }}>High to Low</option>
                </select>
            </form>

        </div>
    </div>

    <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
        <thead class="text-xs text-gray-700 uppercase text-center dark:bg-gray-700 dark:text-gray-400" style="background-color: #e6edf5;">
            <tr>
                <th scope="col" class="p-4">
                    Cover
                </th>
                <th scope="col" class="px-6 py-3">
                    Judul
                </th>
                <th scope="col" class="px-6 py-3">
                    Penulis
                </th>
                <th scope="col" class="px-6 py-3">
                    Penerbit
                </th>
                <th scope="col" class="px-6 py-3">
                    Tahun Penerbit
                </th>
                <th scope="col" class="px-6 py-3">
                    Kategori
                </th>
                <th scope="col" class="px-6 py-3">
                    Stok
                </th>
                <th scope="col" class="px-6 py-3">
                    Action
                </th>
            </tr>
        </thead>
        <tbody>
            @foreach($daftarBuku as $buku)
            <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 text-center">
                <td class="px-6 py-4">
                    <img src="{{ asset('storage/' . $buku->cover_image) }}" alt="Cover Image" class="w-32 h-32 object-contain">
                </td>
                <td class="px-6 py-4">{{ $buku->judul }}</td>
                <td class="px-6 py-4">{{ $buku->penulis }}</td>
                <td class="px-6 py-4">{{ $buku->penerbit }}</td>
                <td class="px-6 py-4">{{ $buku->tahun_terbit }}</td>
                <td class="px-6 py-4">{{ $buku->kategori }}</td>
                <td class="px-6 py-4">{{ $buku->stok }}</td>
                <td class="px-6 py-4 flex justify-center items-center gap-4 h-full" style="margin-top: 52px;">
                    <a href="{{ route('admin.edit', $buku) }}" class="text-yellow-400 hover:text-yellow-600 focus:outline-none">
                        <i class="fas fa-edit text-lg"></i>
                    </a>
                    <form method="POST" action="{{ route('admin.destroy', $buku->id) }}" onsubmit="return confirm('Apakah kamu yakin ingin menghapus data?')" class="d-inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="text-red-700 hover:text-red-800 focus:outline-none">
                            <i class="fas fa-trash-alt text-lg"></i>
                        </button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
