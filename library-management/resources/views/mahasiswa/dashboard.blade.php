@extends('mahasiswa.templates.master')

@section('content')
<div class="p-4 sm:ml-64 relative overflow-x-auto shadow-md sm:rounded-lg bg-white" style="border-radius: 25px; margin-right: 25px; margin-bottom: 25px; min-height: 100vh;">
    <div class="flex justify-end items-center pb-5 dark:bg-gray-900 mb-5">
        <!-- Judul -->
        <h3 class="text-2xl font-bold text-gray-900 mr-auto ml-3">Dashboard</h3>

        <!-- Notifikasi -->
        <div class="relative mr-3">
            <button type="button" class="flex items-center text-sm text-gray-900 dark:text-white" data-dropdown-toggle="dropdown-notifications">
                <i class="fas fa-bell text-3xl" style="color: #8499ae"></i>
            </button>

            <!-- Dropdown Notifikasi -->
            <div class="hidden absolute right-0 mt-2 bg-white text-base text-gray-700 divide-y divide-gray-100 rounded shadow dark:bg-gray-800 dark:text-white z-50" id="dropdown-notifications"
            style="width: 300px;">
                <div class="px-4 py-2">
                    <strong class="text-lg">Notification</strong>
                </div>
                <ul class="py-1 max-h-60 overflow-y-auto">
                    <!-- Daftar Notifikasi -->
                    @if($notifications) 
                        @foreach ($notifications as $notification)
                            <li>
                                <a href="#" class="block px-4 py-2 text-sm hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white">
                                    <strong>{{ $notification['title'] }}</strong>
                                    <p class="text-xs text-gray-500 dark:text-gray-400">{{ $notification['message'] }}</p>
                                </a>
                            </li>
                        @endforeach
                    @else
                        <li>
                            <p class="text-sm text-gray-500 dark:text-gray-400">No New Notification.</p>
                        </li>
                    @endif
                </ul>
            </div>
        </div>

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
                    <strong>{{ Auth::user()->name ?? 'Guest' }}</strong>
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

    <!-- Welcome Message -->
    <div class="flex items-center justify-between ml-3 mr-3 mb-5 shadow-sm" 
        style="background-color: #a2bcd7; padding: 50px; border-radius: 15px; height: 195px; margin-top: 50px; margin-bottom: 50px;">
        <!-- Teks -->
        <div>
            <span class="text-3xl font-medium text-white">
                <strong>Welcome, {{ Auth::user()->name ?? 'Guest' }}</strong>
            </span>
            <p class="font-medium text-white mt-3" style="font-size: 18px;">
                Ready for your next library adventure?
                From reserving books <br> to discovering something new in our collection,
                everything is at your fingertips.
            </p>
        </div>
        <!-- Gambar -->
        <div>
            <img src="{{ asset('img/dashboard.png') }}" alt="Library Illustration" class="rounded-lg"
            style="width: 270px; margin-right: 30px; position: relative; top: -20px;">
        </div>
    </div>

    <!-- Menampilkan Statistik Peminjaman -->
    <div class="grid" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 20px;">
        <!-- Buku Sedang Dipinjam -->
        <div class="card" style="background-color: #f9fafb; padding: 20px; border-radius: 10px; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);">
            <div class="flex" style="display: flex; align-items: center;">
                <div class="icon" style="font-size: 50px; color: #a2bcd7; margin-right: 30px;">
                    <i class="fas fa-book-open"></i> 
                </div>
                <div>
                    <h3 style="font-size: 18px; font-weight: bold; color: #3d426c;">Borrowed</h3>
                    <p style="font-size: 24px; font-weight: bold; color: #4e4e4e;">{{ $currentLoans }}</p>
                </div>
            </div>
        </div>

        <!-- Buku Sudah Dikembalikan -->
        <div class="card" style="background-color: #f9fafb; padding: 20px; border-radius: 10px; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);">
            <div class="flex" style="display: flex; align-items: center;">
                <div class="icon" style="font-size: 50px; color: #a2bcd7; margin-right: 30px;">
                    <i class="fas fa-book"></i> 
                </div>
                <div>
                    <h3 style="font-size: 18px; font-weight: bold; color: #3d426c;">Returned</h3>
                    <p style="font-size: 24px; font-weight: bold; color: #4e4e4e;">{{ $returnedBooks }}</p>
                </div>
            </div>
        </div>

        <!-- Peminjaman Terlambat -->
        <div class="card" style="background-color: #f9fafb; padding: 20px; border-radius: 10px; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);">
            <div class="flex" style="display: flex; align-items: center;">
                <div class="icon" style="font-size: 50px; color: #a2bcd7; margin-right: 30px;">
                    <i class="fas fa-exclamation-triangle"></i>
                </div>
                <div>
                    <h3 style="font-size: 18px; font-weight: bold; color: #3d426c;">Overdue</h3>
                    <p style="font-size: 24px; font-weight: bold; color: #e3342f;">{{ $overdueLoans }}</p>
                </div>
            </div>
        </div>
    </div>

<!-- For You Section -->
<div class="mt-5" style="margin-top: 50px;">
    <div class="flex justify-center items-center p-2 shadow-md rounded-lg" 
        style="background-color: #a2bcd7; max-width: 150px; height: 50px; border-radius: 25px; margin-bottom: 50px; margin-left: 20px;">
        <h5 class="font-bold mb-0" style="color: white;">For You</h5>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
        @foreach($recommendedBooks as $book)
        <div class="bg-white overflow-hidden flex flex-col items-center p-0 m-0">
            <a href="{{ route('mahasiswa.catalog.details', $book->id) }}" class="flex justify-center items-center w-full">
                <img src="{{ asset('storage/' . $book->cover_image) }}" alt="{{ $book->judul }}" 
                    class="w-full h-48 object-cover rounded-t-lg shadow-md"
                    style="width: 200px; height: 300px; object-fit: cover; border-radius: 10px;">
            </a>
            
            <!-- Konten -->
            <div class="p-4 text-center">
                <h5 class="text-lg font-bold text-gray-900">{{ $book->judul }}</h5>
                <p class="text-gray-600">{{ $book->penulis }}</p>
                <div class="mt-2 text-yellow-300 flex justify-center">
                    @php
                        $avgRating = $book->reviews->avg('rating');
                    @endphp
                    <span class="text-2xl">{!! str_repeat('&#9733;', floor($avgRating)) !!}</span>
                    <span class="text-2xl">{!! str_repeat('&#9734;', 5 - floor($avgRating)) !!}</span>
                </div>
            </div>
        </div>
        @endforeach
    </div>
</div>


<!-- Popular Now Section -->
<div class="mt-5" style="margin-top: 50px;">
    <div class="flex justify-center items-center p-2 shadow-md rounded-lg" 
        style="background-color: #a2bcd7; max-width: 200px; height: 50px; border-radius: 25px; margin-bottom: 50px; margin-left: 20px;">
        <h5 class="font-bold mb-0" style="color: white;">Our Popular Collection</h5>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
        @foreach($popularBooks as $book)
        <div class="bg-white overflow-hidden flex flex-col items-center p-0 m-0">
            <a href="{{ route('mahasiswa.catalog.details', $book->id) }}" class="flex justify-center items-center w-full">
                <img src="{{ asset('storage/' . $book->cover_image) }}" alt="{{ $book->judul }}" 
                    class="w-full h-48 object-cover rounded-t-lg shadow-md"
                    style="width: 200px; height: 300px; object-fit: cover; border-radius: 10px;">
            </a>
            
            <!-- Konten -->
            <div class="p-4 text-center">
                <h5 class="text-lg font-bold text-gray-900">{{ $book->judul }}</h5>
                <p class="text-gray-600">{{ $book->penulis }}</p>
                <div class="mt-2 text-yellow-300 flex justify-center">
                    @php
                        $avgRating = $book->reviews->avg('rating');
                    @endphp
                    <span class="text-2xl">{!! str_repeat('&#9733;', floor($avgRating)) !!}</span>
                    <span class="text-2xl">{!! str_repeat('&#9734;', 5 - floor($avgRating)) !!}</span>
                </div>
            </div>
        </div>
        @endforeach
    </div>
</div>


<!-- Newly Added Books Section -->
<div class="mt-5" style="margin-top: 35px;">
    <div class="flex justify-center items-center p-2 shadow-md rounded-lg" 
        style="background-color: #a2bcd7; max-width: 200px; height: 50px; border-radius: 25px; margin-bottom: 50px; margin-left: 20px;">
        <h5 class="font-bold mb-0" style="color: white;">Newly Added Books</h5>
    </div>
    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
        @foreach($newBooks as $book)
        <div class="bg-white overflow-hidden flex flex-col items-center p-0 m-0">
            <a href="{{ route('mahasiswa.catalog.details', $book->id) }}" class="flex justify-center items-center w-full">
                <img src="{{ asset('storage/' . $book->cover_image) }}" alt="{{ $book->judul }}" 
                    class="w-full h-48 object-cover rounded-t-lg shadow-md"
                    style="width: 200px; height: 300px; object-fit: cover; border-radius: 10px;">
            </a>

            <!-- Konten -->
            <div class="p-4 text-center">
                <a href="{{ route('mahasiswa.catalog.details', $book->id) }}">
                    <h5 class="text-lg font-bold text-gray-900">{{ $book->judul }}</h5>
                </a>
                <p class="text-gray-600">{{ $book->penulis }}</p>
                <div class="flex justify-center mt-2 text-yellow-300">
                    @php
                        $avgRating = $book->reviews->avg('rating');
                    @endphp
                    <span class="text-2xl">{!! str_repeat('&#9733;', floor($avgRating)) !!}</span>
                    <span class="text-2xl">{!! str_repeat('&#9734;', 5 - floor($avgRating)) !!}</span>
                </div>
            </div>
        </div>
        @endforeach
    </div>
</div>
</div>

<script>
    document.querySelector('[data-dropdown-toggle="dropdown-notifications"]').addEventListener('click', function () {
        const dropdown = document.getElementById('dropdown-notifications');
        dropdown.classList.toggle('hidden');
    });

    // Close the notification dropdown if clicked outside of it
    document.addEventListener('click', function (event) {
        const dropdown = document.getElementById('dropdown-notifications');
        if (!dropdown.contains(event.target) && !event.target.closest('[data-dropdown-toggle="dropdown-notifications"]')) {
            dropdown.classList.add('hidden');
        }
    });
</script>

@endsection
