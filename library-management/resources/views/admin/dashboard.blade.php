@extends('admin.templates.master')

@section('content')
<div class="p-4 sm:ml-64 relative overflow-x-auto shadow-md sm:rounded-lg bg-white" style="border-radius: 25px; margin-right: 25px; margin-bottom: 25px; min-height: 100vh;">
    <!-- Header Dashboard -->
    <div class="flex justify-end items-center pb-5 dark:bg-gray-900 mb-5">
        <!-- Judul -->
        <h3 class="text-2xl font-bold text-gray-900 mr-auto ml-3">Dashboard</h3>

        <!-- Profil User -->
        <div class="flex items-center mr-2 ml-3">
            <!-- Dropdown Profil -->
            <button type="button" class="flex items-center text-sm rounded-full focus:ring-4 focus:ring-gray-300 dark:focus:ring-gray-600" aria-expanded="false" data-dropdown-toggle="dropdown-user">
                <span class="sr-only">Open user menu</span>
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

    <!-- Selamat Datang -->
    <div class="flex items-center justify-between ml-3 mr-3 mb-5 shadow-sm" 
        style="background-color: #a2bcd7; padding: 50px; border-radius: 15px; height: 195px; margin-top: 50px; margin-bottom: 50px;">

        <div>
            <span class="text-3xl font-medium text-white">
                <strong>Welcome, {{ Auth::user()->name ?? 'Guest' }}</strong>
            </span>
            <p class="font-medium text-white mt-3" style="font-size: 18px;">
                This is your command center to monitor and manage the system. <br>
                Use the tools provided to ensure smooth operations and solve problems swiftly. <br>
                You're in charge!"
            </p>
        </div>

        <div>
            <img src="{{ asset('img/dashboard.png') }}" alt="Library Illustration" class="rounded-lg"
            style="width: 270px; margin-right: 30px; position: relative; top: -20px;">
        </div>
    </div>

    <!-- Statistik -->
    <div class="grid" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 10px; margin-bottom: 50px;">
        <!-- Total Buku -->
        <div class="card ml-3" style="background-color: #f9fafb; padding: 20px; border-radius: 10px; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1); max-width: 400px;">
            <div class="flex" style="display: flex; align-items: center;">
                <div class="icon" style="font-size: 50px; color: #a2bcd7; margin-right: 30px;">
                    <i class="fas fa-book" style="font-size: 50px;"></i> 
                </div>
                <div>
                    <h3 style="font-size: 18px; font-weight: bold; color: #3d426c;">Total Books</h3>
                    <p style="font-size: 24px; font-weight: bold; color: #4e4e4e;">{{ $totalBooks }}</p>
                </div>
            </div>
        </div>
    
        <!-- Total Pengguna -->
        <div class="card" style="background-color: #f9fafb; padding: 20px; border-radius: 10px; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1); max-width: 400px; margin-left: -100px;">
            <div class="flex" style="display: flex; align-items: center;">
                <div class="icon" style="font-size: 50px; color: #a2bcd7; margin-right: 30px;">
                    <i class="fas fa-users" style="font-size: 50px;"></i> 
                </div>
                <div>
                    <h3 style="font-size: 18px; font-weight: bold; color: #3d426c;">Total Users</h3>
                    <p style="font-size: 24px; font-weight: bold; color: #4e4e4e;">{{ $totalUsers }}</p>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Grafik -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mt-6 ml-3 mr-3 mb-5">
        <!-- Grafik Ketersediaan Buku -->
        <div class="bg-white p-5 rounded-lg shadow-md">
            <h3 class="font-bold text-xl mb-3 text-gray-800">Ketersediaan Buku</h3>
            <canvas id="booksAvailabilityChart" width="400" height="200"></canvas>
        </div>

        <!-- Grafik Statistik Peminjaman -->
        <div class="bg-white p-5 rounded-lg shadow-md">
            <h3 class="font-bold text-xl mb-3 text-gray-800">Statistik Peminjaman per Bulan</h3>
            <canvas id="loansPerMonthChart" width="400" height="200"></canvas>
        </div>
    </div>
</div>

<script>
    var ctx1 = document.getElementById('loansPerMonthChart').getContext('2d');
    var loansPerMonthChart = new Chart(ctx1, {
        type: 'line',
        data: {
            labels: @json($loansPerDay->pluck('day')), 
            datasets: [{
                label: 'Jumlah Peminjaman',
                data: @json($loansPerDay->pluck('total')),
                borderColor: 'rgb(75, 192, 192)',
                tension: 0.1,
                fill: false
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'top',
                },
                tooltip: {
                    callbacks: {
                        label: function(tooltipItem) {
                            return Math.round(tooltipItem.raw);
                        }
                    }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: function(value) {
                            return Math.round(value);
                        }
                    }
                }
            }
        }
    });


    var ctx2 = document.getElementById('booksAvailabilityChart').getContext('2d');
    var booksAvailabilityChart = new Chart(ctx2, {
        type: 'bar',
        data: {
            labels: @json($booksAvailability->pluck('title')).map(function(title) {
                return title.length > 20 ? title.slice(0, 20) + '...' : title; 
            }),
            datasets: [{
                label: 'Ketersediaan Buku',
                data: @json($booksAvailability->pluck('available')),
                backgroundColor: 'rgba(54, 162, 235, 0.2)',
                borderColor: 'rgba(54, 162, 235, 1)',
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            scales: {
                x: {
                    ticks: {
                        autoSkip: true, 
                        maxRotation: 45, 
                        minRotation: 30,
                    }
                },
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: function(value) {
                            return Math.round(value);
                        }
                    }
                }
            }
        }
    });

</script>
@endsection
