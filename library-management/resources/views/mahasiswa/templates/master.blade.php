<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <script src="https://cdn.jsdelivr.net/npm/flowbite@2.5.2/dist/flowbite.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/feather-icons/dist/feather.min.js"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/flowbite@2.5.2/dist/flowbite.min.css"  rel="stylesheet" />\
    <title>Document</title>
    
</head>
<body style="background-color: #e7effe;">
    
<button data-drawer-target="default-sidebar" data-drawer-toggle="default-sidebar" aria-controls="default-sidebar" type="button" class="inline-flex items-center p-2 mt-2 ms-3 text-sm text-gray-500 rounded-lg sm:hidden hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-gray-200 dark:text-gray-400 dark:hover:bg-gray-700 dark:focus:ring-gray-600">
    <span class="sr-only">Open sidebar</span>
    <svg class="w-6 h-6" aria-hidden="true" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
    <path clip-rule="evenodd" fill-rule="evenodd" d="M2 4.75A.75.75 0 012.75 4h14.5a.75.75 0 010 1.5H2.75A.75.75 0 012 4.75zm0 10.5a.75.75 0 01.75-.75h7.5a.75.75 0 010 1.5h-7.5a.75.75 0 01-.75-.75zM2 10a.75.75 0 01.75-.75h14.5a.75.75 0 010 1.5H2.75A.75.75 0 012 10z"></path>
    </svg>
</button>

<aside id="default-sidebar" class="fixed top-0 left-0 z-40 w-64 h-screen transition-transform -translate-x-full sm:translate-x-0" aria-label="Sidebar">
    <div class="h-full px-3 py-4 overflow-y-auto" style="background-color: #e7effe;">
        <h1 class="text-2xl font-bold">Booksy</h1>
        <ul class="space-y-6 font-medium mt-14">
            <li>
                <a href="{{ route('mahasiswa.dashboard') }}" class="flex items-center p-2 text-gray-600 rounded-lg hover:bg-white group">
                    <i class="fas fa-home" style="color: #88a5c3; font-size: 26px; transition: color 0.3s;"></i>
                    <span class="ms-3 text-md font-bold">Dashboard</span>
                </a>
            </li>
            <li>
                <a href="{{ route('mahasiswa.catalog.index') }}" class="flex items-center p-2 text-gray-600 rounded-lg hover:bg-white group">
                    <i class="fas fa-book" style="color: #88a5c3; font-size: 26px; transition: color 0.3s;"></i>
                    <span class="ms-5 text-md font-bold">Book</span>
                </a>
            </li>
            <li>
                <a href="/mahasiswa/borrowed-books" class="flex items-center p-2 text-gray-600 rounded-lg hover:bg-white group">
                    <i class="fas fa-book-reader" style="color: #88a5c3; font-size: 26px; transition: color 0.3s;"></i>
                    <span class="ms-5 text-md font-bold">Loans</span>
                </a>
            </li>            
            <li>
                <a href="/mahasiswa/returned-books" class="flex items-center p-2 text-gray-600 rounded-lg hover:bg-white group">
                    <i class="fas fa-history" style="color: #88a5c3; font-size: 26px; transition: color 0.3s;"></i>
                    <span class="ms-5 text-md font-bold">History</span>
                </a>
            </li>
            <!-- Profile Settings -->
            <li>
                <a href="{{ route('profileMahasiswa.edit', ['id' => $user->id]) }}" class="flex items-center p-2 text-gray-600 rounded-lg hover:bg-white group">
                    <i class="fas fa-user-cog" style="color: #88a5c3; font-size: 26px; transition: color 0.3s;"></i>
                    <span class="ms-4 text-md font-bold">Settings</span>
                </a>                               
            </li>
        </ul>
        
        <!-- Bagian bawah hanya untuk tombol Logout -->
        <div class="absolute bottom-5 left-0 w-full px-3">
            <ul>
                <!-- Logout -->
                <li>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="flex items-center w-full p-2 text-gray-900 rounded-lg hover:bg-white group">
                            <i class="fas fa-sign-out-alt" style="color: #88a5c3; font-size: 26px; transition: color 0.3s;"></i>
                            <span class="ms-5 text-lg font-bold">Logout</span>
                        </button>
                    </form>
                </li>
            </ul>
        </div>        
    </div>
</aside>




<main>
    @yield('content')
</main>

</body>
</html>