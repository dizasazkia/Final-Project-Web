@extends('mahasiswa.templates.master')

@section('content')
<div class="p-4 sm:ml-64 relative overflow-x-auto shadow-md sm:rounded-lg bg-white" style="border-radius: 25px; margin-right: 25px; margin-bottom: 25px; min-height: 100vh;">
    <div class="flex justify-end items-center pb-5 dark:bg-gray-900 mb-5">
        <!-- Judul -->
        <h3 class="text-2xl font-bold text-gray-900 mr-auto ml-3">Book Catalog</h3>
            <!-- Search Bar -->
            <form action="{{ route('mahasiswa.catalog.search') }}" method="GET" class="relative flex items-center justify-start max-w-[400px] w-full mt-1 ml-0">
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
                    <!-- Ambil inisial dari nama pengguna -->
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
    
<!-- Filters and Sorting -->
<div class="flex justify-end items-center gap-4 mt-4 lg:mt-0 mb-5 mr-3">
    <!-- Category Filter -->
    <form action="{{ route('mahasiswa.catalog.search') }}" method="GET" class="w-full md:w-auto">
        <select 
            name="kategori" 
            class="rounded-lg px-3 py-2 w-full focus:outline-none focus:ring-0 text-white" 
            style="border: none; box-shadow: none; background-color:#a2bcd7;" 
            onchange="this.form.submit()">
            <option value="" class="text-white">All Categories</option>
            @foreach($categories as $category)
                <option value="{{ $category }}" 
                    {{ request()->query('kategori') == $category ? 'selected' : '' }} 
                    class="text-white">
                    {{ $category }}
                </option>
            @endforeach
        </select>
    </form>
    
    
    <!-- Sorting Options -->
    <form action="{{ route('mahasiswa.catalog.search') }}" method="GET" class="w-full md:w-auto">
        <select 
            name="sort" 
            class="rounded-lg px-3 py-2 w-full focus:outline-none focus:ring-0 text-white" 
            style="border: none; box-shadow: none; background-color:#a2bcd7" 
            onchange="this.form.submit()">
            <option value="tahun_asc" {{ request()->query('sort') == 'tahun_asc' ? 'selected' : '' }}>Year (Asc)</option>
            <option value="tahun_desc" {{ request()->query('sort') == 'tahun_desc' ? 'selected' : '' }}>Year (Desc)</option>
        </select>
    </form>
</div>



<!-- Book List -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3">
    @foreach ($daftarBuku as $book)
        <div class="col-4 mb-3 mt-5">
            <div class="card mb-4 mt-3 bg-[#f1f4fa] border-none " style="height: 250px;">
                <div class="flex h-full">
                    <!-- Image -->
                    <div class="w-1/4 relative" style="aspect-ratio: 2/3;">
                        <img 
                            src="{{ asset('storage/' . $book->cover_image) }}" 
                            alt="Cover Buku" 
                            class="object-cover w-full h-full rounded-l-lg shadow-md" 
                            style="border-radius: 10px;">
                    </div>
                    <!-- Details -->
                    <div class="w-3/4 p-3 flex flex-col justify-between">
                        <div class="flex-grow">
                            <h6 class="font-bold text-lg text-gray-800 mb-2">{{ $book->judul }}</h6>
                            <p class="text-sm text-gray-600 mb-1">By {{ $book->penulis }}</p>
                            <p class="text-sm text-gray-600 mb-1">Kategori: {{ $book->kategori }}</p>
                            <p class="text-sm text-gray-600 mb-2"><strong>Stok:</strong> {{ $book->stok }}</p>

                            <!-- Rating -->
                            <p class="mb-4 text-sm">
                                <span class="flex items-center space-x-1">
                                    @for ($i = 1; $i <= 5; $i++)
                                        @if ($i <= floor($book->reviews_avg_rating ?? 0))
                                            <i class="fas fa-star text-yellow-300"></i>
                                        @elseif ($i - floor($book->reviews_avg_rating ?? 0) < 1)
                                            <i class="fas fa-star-half-alt text-yellow-300"></i> 
                                        @else
                                            <i class="far fa-star text-gray-300"></i>
                                        @endif
                                    @endfor
                                </span>
                            </p>                                                       
                        </div>
                        <a 
                            href="{{ route('mahasiswa.catalog.details', ['id' => $book->id]) }}" 
                            class="rounded mt-auto flex justify-center items-center"
                            style="background-color: #a2bcd7; font-size: 16px; width: 70px; height: 35px; color: white;">
                            More
                        </a>
                    </div>
                </div>
            </div>
        </div>
    @endforeach
</div>    
</div>
@endsection
