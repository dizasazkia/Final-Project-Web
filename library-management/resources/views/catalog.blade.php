@extends('templates.master')

@section('content')
<div class="catalog" style="background-color: white; min-height: 100vh; background-size: cover; background-position: center; background-repeat: no-repeat;">
    <div class="content" style="padding-top: 40px; margin-left: 70px; margin-right: 70px;">
        <div id="carouselExampleAutoplaying" class="carousel slide" data-bs-ride="carousel">
            <div class="carousel-indicators">
                <button type="button" data-bs-target="#carouselExampleAutoplaying" data-bs-slide-to="0" class="active" aria-current="true" aria-label="Slide 1"></button>
                <button type="button" data-bs-target="#carouselExampleAutoplaying" data-bs-slide-to="1" aria-label="Slide 2"></button>
                <button type="button" data-bs-target="#carouselExampleAutoplaying" data-bs-slide-to="2" aria-label="Slide 3"></button>
                <button type="button" data-bs-target="#carouselExampleAutoplaying" data-bs-slide-to="3" aria-label="Slide 4"></button>
            </div>
            <div class="carousel-inner" style="border-radius: 20px; height: 300px; margin-bottom: 50px;">
                <div class="carousel-item active" data-bs-interval="3000">
                    <img src="{{ asset('img/1.png') }}" class="d-block w-100" alt="...">
                </div>
                <div class="carousel-item" data-bs-interval="3000">
                    <img src="{{ asset('img/2.png') }}" class="d-block w-100" alt="...">
                </div>
                <div class="carousel-item" data-bs-interval="3000">
                    <img src="{{ asset('img/3.png') }}" class="d-block w-100" alt="...">
                </div>
                <div class="carousel-item" data-bs-interval="3000">
                    <img src="{{ asset('img/4.png') }}" class="d-block w-100" alt="...">
                </div>
            </div>
        </div>                     
        <!-- Title and Filters -->
        <div class="row align-items-center mb-5 mt-3">
            <!-- Title Column -->
            <div class="col-md-6 d-flex align-items-center shadow rounded-pill d-flex justify-content-center" 
                style="background-color: #bacce0; max-width: 200px; height: 50px; margin-left: 10px;">
                <h4 class="title fw-bold me-3  text-white mb-0">Book Catalog</h4>
            </div>
            <!-- Filters -->
            <div class="col-md-6 d-flex justify-content-end ms-auto" style="gap: 10px;">
                <form action="{{ route('search') }}" method="GET" style="max-width: 300px;">
                    <div class="d-flex">
                        <!-- Category Filter -->
                        <select class="form-select me-2" style="border: none; box-shadow: none; background-color:#a2bcd7; color: white;" name="kategori" aria-label="Filter by Category" onchange="this.form.submit()">
                            <option value="">All Categories</option>
                            @foreach($categories as $category)
                                <option value="{{ $category }}" {{ request()->query('kategori') == $category ? 'selected' : '' }}>
                                    {{ $category }}
                                </option>
                            @endforeach
                        </select>

                        <!-- Year Filter -->
                        <select class="form-select" style="border: none; box-shadow: none; background-color:#a2bcd7; color: white;" name="tahun" aria-label="Filter by Year" onchange="this.form.submit()">
                            <option value="">All Years</option>
                            @foreach($years as $year)
                                <option value="{{ $year }}" {{ request()->query('tahun') == $year ? 'selected' : '' }}>
                                    {{ $year }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </form>
            </div>
        </div>

        <!-- Book List -->
        <div class="row">
            @foreach ($daftarBuku as $book)
                <div class="col-4 mb-3">
                    <div class="card mb-5 mt-3" style="height: 280px; background-color: white; border: none;">
                        <div class="row g-0" style="height: 280px;">
                            <div class="col-5">
                                <img src="{{ asset('storage/' . $book->cover_image) }}" 
                                    class="img-fluid rounded-start" alt="Cover Buku" 
                                    style="object-fit: cover; height: 100%; width: 100%; border-radius: 15px; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.5);"> 
                            </div>
                            <div class="col-7">
                                <div class="card-body d-flex flex-column" style="height: 100%; padding: 15px;">
                                    <h6 class="card-title" style="margin-bottom: 10px; font-weight: bold; font-size: 20px;">{{ $book->judul }}</h6> 
                                    <p class="card-text" style="margin-bottom: 8px; font-size: 16px;">By {{ $book->penulis }}</p> 
                                    <p class="card-text" style="margin-bottom: 8px; font-size: 16px;">Kategori: {{ $book->kategori }}</p> 
                                    <p class="card-text" style="margin-bottom: 8px; font-size: 16px;"><strong>Stok: </strong>{{ $book->stok }}</p> 
        
                                    <!-- Rating -->
                                    <p class="card-text" style="margin-bottom: 15px; font-size: 16px;">
                                        <span class="stars">
                                            @for ($i = 1; $i <= 5; $i++)
                                                @if ($i <= floor($book->reviews_avg_rating ?? 0))
                                                    <i class="fas fa-star text-warning"></i> <!-- Bintang Penuh -->
                                                @elseif ($i - $book->reviews_avg_rating < 1)
                                                    <i class="fas fa-star-half-alt text-warning"></i> <!-- Bintang Setengah -->
                                                @else
                                                    <i class="far fa-star text-warning"></i> <!-- Bintang Kosong -->
                                                @endif
                                            @endfor
                                        </span>
                                    </p>
        
                                    <!-- Button -->
                                    <div class="mt-auto">
                                        <a href="{{ route('book.details', ['id' => $book->id]) }}" 
                                            class="btn text-light btn-sm w-50" style="background-color: #97a7c9; font-size: 14px;">More</a> 
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
        
        
        <a href="{{ route('home') }}" class="btn btn-secondary mt-3 mb-5">Back</a>
    </div>
</div>
@endsection
