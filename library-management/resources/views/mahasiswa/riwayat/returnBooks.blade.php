@extends('mahasiswa.templates.master')

@section('content')
<div class="p-4 sm:ml-64 relative overflow-x-auto shadow-md sm:rounded-lg bg-white" style="border-radius: 25px; margin-right: 25px; margin-bottom: 25px; min-height: 100vh;">
    <div class="flex justify-end items-center pb-5 dark:bg-gray-900 mb-5">
        <!-- Judul -->
        <h3 class="text-2xl font-bold text-gray-900 mr-auto ml-3">Returned Books</h3>
            <!-- Search Bar -->
            <form action="{{ route('mahasiswa.returnBooks.search') }}" method="GET" class="relative flex items-center justify-start max-w-[400px] w-full mt-1 ml-0">
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

    <!-- Tabel Riwayat Buku yang Dikembalikan -->
    <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
        <thead class="text-xs  text-center text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400" style="background-color: #e6edf5;">
            <tr>
                <th scope="col" class="p-4">Cover</th>
                <th scope="col" class="px-6 py-3">Title</th>
                <th scope="col" class="px-6 py-3">Loan Date</th>
                <th scope="col" class="px-6 py-3">Due Date</th>
                <th scope="col" class="px-6 py-3">Return Date</th>
                <th scope="col" class="px-6 py-3">Late Charge</th>
                <th scope="col" class="px-6 py-3">Action</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($returnedLoans as $loan)
            <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 text-center">
                <td class="px-6 py-4">
                    <img src="{{ asset('storage/' . $loan->book->cover_image) }}" alt="Cover Buku" class="w-32 h-32 object-contain">
                </td>
                <td class="px-6 py-4">{{ $loan->book->judul }}</td>
                <td class="px-6 py-4">{{ $loan->tanggal_pinjam }}</td>
                <td class="px-6 py-4">{{ $loan->tanggal_kembali }}</td>
                <td class="px-6 py-4">{{ $loan->tanggal_pengembalian ?? '-' }}</td>
                <td class="px-6 py-4">
                    {{ $loan->denda_calculated > 0 ? 'Rp' . number_format($loan->denda_calculated) : '-' }}
                </td>  
                <td class="px-6 py-4">
                    <!-- Button untuk membuka modal -->
                    <button class="bg-blue-500 text-white px-4 py-2 rounded" onclick="openModal({{ $loan->id }})">
                        Review
                    </button>
                </td>
            </tr>

            <!-- Modal untuk memberikan review -->
            <div id="reviewModal{{ $loan->id }}" class="modal hidden fixed inset-0 z-50 backdrop-blur-sm flex items-center justify-center">
                <div class="modal-dialog bg-gray-50 rounded-lg shadow-lg w-1/3 p-6">
                    <div class="modal-header flex justify-between items-center">
                        <h5 class="text-xl font-bold">Berikan Review untuk Buku {{ $loan->book->judul }}</h5>
                        <button type="button" class="text-xl" onclick="closeModal({{ $loan->id }})">&times;</button>
                    </div>
                    <div class="modal-body">
                        <form action="{{ route('mahasiswa.review', $loan->id) }}" method="POST">
                            @csrf
                            <div class="mb-4">
                                <label for="rating" class="block text-sm font-medium text-gray-700">Rating</label>
                                <!-- Rating Bintang -->
                                <div class="flex text-3xl">
                                    @for ($i = 1; $i <= 5; $i++)
                                        <span class="star text-gray-400 cursor-pointer" 
                                            data-value="{{ $i }}" 
                                            onclick="setRating(this, {{ $loan->id }}, {{ $i }})">
                                            &#9733;
                                        </span>
                                    @endfor
                                </div>
                                <input type="hidden" name="rating" id="rating{{ $loan->id }}" value="0">
                            </div>
                            <div class="mb-4">
                                <label for="komentar" class="block text-sm font-medium text-gray-700">Komentar</label>
                                <textarea name="komentar" id="komentar" class="mt-1 block w-full p-2 border border-gray-300 rounded" rows="3" required></textarea>
                            </div>
                            <div class="modal-footer flex justify-end">
                                <button type="button" class="bg-gray-300 text-gray-700 px-4 py-2 rounded mr-2" onclick="closeModal({{ $loan->id }})">Tutup</button>
                                <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">Kirim Review</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            
            @endforeach
        </tbody>
    </table>
</div>

<script>
    function openModal(loanId) {
        document.getElementById('reviewModal' + loanId).classList.remove('hidden');
    }

    function closeModal(loanId) {
        document.getElementById('reviewModal' + loanId).classList.add('hidden');
    }

    function setRating(element, loanId, ratingValue) {
        // Update bintang yang dipilih
        let stars = element.parentNode.children;
        for (let i = 0; i < stars.length; i++) {
            if (i < ratingValue) {
                stars[i].classList.add('text-yellow-500');
                stars[i].classList.remove('text-gray-400');
            } else {
                stars[i].classList.remove('text-yellow-500');
                stars[i].classList.add('text-gray-400');
            }
        }
        // Set nilai rating pada input hidden
        document.getElementById('rating' + loanId).value = ratingValue;
    }
</script>
@endsection
