@extends('admin.templates.master')

@section('content')
<div class="p-4 sm:ml-64 relative overflow-x-auto shadow-md sm:rounded-lg bg-white" style="border-radius: 25px; margin-right: 25px; margin-bottom: 25px; min-height: 100vh;">
    <div class="flex justify-between items-center pb-5 dark:bg-gray-900 mb-5">
        <!-- Judul -->
        <h3 class="text-2xl font-bold text-gray-900 mr-auto ml-3">List Users</h3>
    
        <!-- Search Bar -->
        <form action="{{ route('users.index') }}" method="GET" class="relative flex items-center justify-start max-w-[400px] w-full mt-1 ml-0">
            <input 
                type="text" 
                name="query" 
                placeholder="Search users" 
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

    <!-- Add New User Button -->
    <div class="flex justify-end items-center pb-4">
        <a href="{{ url('/users/create') }}" 
            class="text-white p-3" 
            style="background-color: #7c99b8; border-radius: 10px; padding: 10px 20px;"
            onmouseover="this.style.backgroundColor='#7f94ab';"
            onmouseout="this.style.backgroundColor='#a2bcd7';">
            Add New User
        </a>
    </div>

    <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
        <thead class="text-xs text-gray-700 uppercase text-center dark:bg-gray-700 dark:text-gray-400" style="background-color: #e6edf5;">
            <tr>
                <th scope="col" class="px-6 py-3">
                    Name
                </th>
                <th scope="col" class="px-6 py-3">
                    Email
                </th>
                {{-- <th scope="col" class="px-6 py-3">
                    Password
                </th>                 --}}
                <th scope="col" class="px-6 py-3">
                    Role
                </th>
                <th scope="col" class="px-6 py-3">
                    Action
                </th>
            </tr>
        </thead>
        <tbody>
            @foreach($users as $user)
            <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 text-center">
                <td class="px-6 py-4">{{ $user->name }}</td>
                <td class="px-6 py-4">{{ $user->email }}</td>
                {{-- <td class="px-6 py-4">{{ $user->password }}</td>                 --}}
                <td class="px-6 py-4">{{ ucfirst($user->role) }}</td>
                <td class="px-6 py-4 flex justify-center items-center gap-4 h-full">
                    <a href="{{ route('users.edit', $user) }}" class="text-yellow-400 hover:text-yellow-600 focus:outline-none">
                        <i class="fas fa-edit text-lg"></i>
                    </a>
                    <form method="POST" action="{{ route('users.destroy', $user) }}" onsubmit="return confirm('Apakah kamu yakin ingin menghapus data?')" class="d-inline">
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
