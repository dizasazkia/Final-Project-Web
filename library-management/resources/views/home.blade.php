@extends('templates.master')

@section('content')

<!-- Hero Section -->
<div id="home" class="hero-section d-flex align-items-center justify-content-between m-5" style="background-color: #a9bfd6; border-radius: 20px;">
    <div class="container d-flex align-items-center justify-content-between flex-column-reverse flex-lg-row ms-5 me-5">
        <div class="ms-3">
            <h1 class="display-4 fw-bold" style="color: white; font-size: 50px">Every page turns into a new adventure</h1>
            <p class="hero-subtext" style="color: white; font-size: 20px">Join us and find your favorite novels here.</p>
            <a href="{{ route('catalog.index') }}" class="btn btn-dark hero-button mt-5" 
            style="background-color: #e7effe; border-color: #e7effe; font-size: 18px; color: #536a80">Explore Our Book Collection</a>
        </div>
        <div class="hero-img-container me-5">
            <img src="{{ asset('img/hero.png') }}" alt="Hero Image" class="hero-image">
        </div>
    </div>
</div>

<!-- Genre Section -->
<div id="genres" class="container mt-5">
    <div class="row text-center">
        <!-- Romance -->
        <div class="col-md-3 col-sm-6 mb-4">
            <div class="card shadow-sm" style="border: none; border-radius: 10px; background-color: #f9fafb;">
                <div class="card-body d-flex flex-column align-items-center">
                    <div class="icon mb-3" style="font-size: 50px; color: #a2bcd7;">
                        <i class="fas fa-heart"></i>
                    </div>
                    <h5 class="fw-bold" style="color: #3d426c;">Romance</h5>
                    <p class="text-muted">Feel the love in every story.</p>
                </div>
            </div>
        </div>

        <!-- Fantasy -->
        <div class="col-md-3 col-sm-6 mb-4">
            <div class="card shadow-sm" style="border: none; border-radius: 10px; background-color: #f9fafb;">
                <div class="card-body d-flex flex-column align-items-center">
                    <div class="icon mb-3" style="font-size: 50px; color: #a2bcd7;">
                        <i class="fas fa-hat-wizard"></i>
                    </div>
                    <h5 class="fw-bold" style="color: #3d426c;">Fantasy</h5>
                    <p class="text-muted">Step into magical worlds.</p>
                </div>
            </div>
        </div>

        <!-- Thriller -->
        <div class="col-md-3 col-sm-6 mb-4">
            <div class="card shadow-sm" style="border: none; border-radius: 10px; background-color: #f9fafb;">
                <div class="card-body d-flex flex-column align-items-center">
                    <div class="icon mb-3" style="font-size: 50px; color: #a2bcd7;">
                        <i class="fas fa-user-secret"></i>
                    </div>
                    <h5 class="fw-bold" style="color: #3d426c;">Thriller</h5>
                    <p class="text-muted">Feel the suspense and excitement.</p>
                </div>
            </div>
        </div>

        <!-- Horror -->
        <div class="col-md-3 col-sm-6 mb-4">
            <div class="card shadow-sm" style="border: none; border-radius: 10px; background-color: #f9fafb;">
                <div class="card-body d-flex flex-column align-items-center">
                    <div class="icon mb-3" style="font-size: 50px; color: #a2bcd7;">
                        <i class="fas fa-ghost"></i>
                    </div>
                    <h5 class="fw-bold" style="color: #3d426c;">Horror</h5>
                    <p class="text-muted">Explore your deepest fears.</p>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Popular Now Section -->
<div id="popular" class="container mt-5">
    <div class="d-flex justify-content-center align-items-center p-2 shadow rounded-pill mb-5" 
        style="background-color: #bacce0; max-width: 250px; height: 50px; margin-left: 20px;">
        <h5 class="fw-bold text-white mb-0">Our Popular Collection</h5>
    </div>

    <div class="row mt-3">
        @foreach($popularBooks as $book)
            <div class="col-md-2 col-sm-4 mb-4 me-4 ms-3">
                <!-- Tambahkan link ke detail buku -->
                <a href="{{ route('book.details', $book->id) }}" style="text-decoration: none; color: inherit;">
                    <div class="card book-card" style="width: auto; display: flex; flex-direction: column; align-items: flex-start; padding: 0;">
                        <img src="{{ asset('storage/' . $book->cover_image) }}" 
                            alt="{{ $book->judul }}" 
                            style="width: 100%; height: 300px; object-fit: cover; display: block; border-radius: 10px;"> 
                        <div class="card-body text-start" style="width: 100%; padding: 10px;">
                            <h5 class="card-title fw-bold">{{ $book->judul }}</h5>
                            <p class="card-text">{{ $book->penulis }}</p>
                            <div class="stars" style="font-size: 24px; color: rgb(250, 215, 16); display: flex; justify-content: flex-start;">
                                @php
                                    $avgRating = $book->reviews->avg('rating');
                                @endphp
                                {!! str_repeat('&#9733;', floor($avgRating)) !!}
                                {!! str_repeat('&#9734;', 5 - floor($avgRating)) !!}
                            </div>
                        </div>
                    </div>
                </a>
            </div>
        @endforeach
    </div>
</div>


<div class="container mt-5">
    <div class="d-flex justify-content-center align-items-center p-2 shadow rounded-pill mb-5" 
        style="background-color: #bacce0; max-width: 180px; height: 50px; margin-left: 20px;">
        <h5 class="fw-bold text-white mb-0">New Arrivals</h5>
    </div>
    <div class="row mt-3 ">
        @foreach($newBooks as $book)
            <div class="col-md-2 col-sm-4 mb-4 me-4 ms-3">
                <!-- Link ke detail buku -->
                <a href="{{ route('book.details', $book->id) }}" style="text-decoration: none; color: inherit;">
                    <div class="card book-card" style="width: auto; display: flex; flex-direction: column; align-items: flex-start; padding: 0;">
                        <img src="{{ asset('storage/' . $book->cover_image) }}" 
                            alt="{{ $book->judul }}" 
                            style="width: 100%; height: 300px; object-fit: cover; display: block; border-radius: 10px;"> 
                        <div class="card-body text-start" style="width: 100%; padding: 10px;"> 
                            <h5 class="card-title fw-bold">{{ $book->judul }}</h5> 
                            <p class="card-text">{{ $book->penulis }}</p>
                            <div class="stars" style="font-size: 24px; color: rgb(250, 215, 16); display: flex; justify-content: flex-start;">
                                @php
                                    $avgRating = $book->reviews->avg('rating'); 
                                @endphp
                                {!! str_repeat('&#9733;', floor($avgRating)) !!}
                                {!! str_repeat('&#9734;', 5 - floor($avgRating)) !!}
                            </div>
                        </div>
                    </div>
                </a>
            </div>
        @endforeach
    </div>
</div>

<!-- Quotes or Excerpts Section -->
<div id="quotes" class="container mt-5">
    <div class="d-flex justify-content-center align-items-center p-2 shadow rounded-pill mb-5" 
        style="background-color: #bacce0; max-width: 250px; height: 50px; margin-left: 20px;">
        <h5 class="fw-bold text-white mb-0">Quotes or Excerpts</h5>
    </div>

    <div class="row">
        <!-- Inspirational Quote 1 -->
        <div class="col-md-4 mb-4 d-flex justify-content-center">
            <div class="card shadow-sm" 
                style="border: none; border-radius: 10px; background-color: #f9fafb; width: 100%; height: 300px; max-width: 350px;">
                <div class="card-body text-center d-flex flex-column align-items-center justify-content-center">
                    <div style="font-size: 40px; color: #a2bcd7; margin-bottom: 15px;">
                        <i class="fas fa-quote-left"></i>
                    </div>
                    <blockquote class="blockquote" style="font-size: 18px; font-style: italic; margin: 0;">
                        <p class="mb-3 fw-bold" style="color: #3d426c;">
                            "It is our choices that show what we truly are, far more than our abilities."
                        </p>
                        <footer style="font-size: 14px; color: #6c757d;">
                            J.K. Rowling, <cite title="Harry Potter">Harry Potter and the Chamber of Secrets</cite>
                        </footer>
                    </blockquote>
                </div>
            </div>
        </div>

        <!-- Inspirational Quote 2 -->
        <div class="col-md-4 mb-4 d-flex justify-content-center">
            <div class="card shadow-sm" 
                style="border: none; border-radius: 10px; background-color: #f9fafb; width: 100%; height: 300px; max-width: 350px;">
                <div class="card-body text-center d-flex flex-column align-items-center justify-content-center">
                    <div style="font-size: 40px; color: #a2bcd7; margin-bottom: 15px;">
                        <i class="fas fa-quote-left"></i>
                    </div>
                    <blockquote class="blockquote" style="font-size: 18px; font-style: italic; margin: 0;">
                        <p class="mb-3 fw-bold" style="color: #3d426c;">
                            "Sometimes it is the one who loves you who hurts you the most."
                        </p>
                        <footer style="font-size: 14px; color: #6c757d;">
                            Colleen Hoover, <cite title="It Ends with Us">It Ends with Us</cite>
                        </footer>
                    </blockquote>
                </div>
            </div>
        </div>

        <!-- Inspirational Quote 3 -->
        <div class="col-md-4 mb-4 d-flex justify-content-center">
            <div class="card shadow-sm" 
                style="border: none; border-radius: 10px; background-color: #f9fafb; width: 100%; height: 300px; max-width: 350px;">
                <div class="card-body text-center d-flex flex-column align-items-center justify-content-center">
                    <div style="font-size: 40px; color: #a2bcd7; margin-bottom: 15px;">
                        <i class="fas fa-quote-left"></i>
                    </div>
                    <blockquote class="blockquote" style="font-size: 18px; font-style: italic; margin: 0;">
                        <p class="mb-3 fw-bold" style="color: #3d426c;">
                            "The only way to make sense out of change is to plunge into it, move with it, and join the dance."
                        </p>
                        <footer style="font-size: 14px; color: #6c757d;">
                            Alan Watts
                        </footer>
                    </blockquote>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Footer Section -->
<footer id="contact" class="text-white py-5 mt-5" style="background-color: #45678b">
    <div class="container">
        <div class="row">
            <!-- About Us Section -->
            <div class="col-lg-3 col-md-6 mb-4">
                <h5 class="fw-bold">About Us</h5>
                <p>We are a leading online library providing digital books to readers worldwide. Explore thousands of e-books across different genres, available anytime, anywhere.</p>
            </div>

            <!-- Contact Us Section -->
            <div class="col-lg-3 col-md-6 mb-4">
                <h5 class="fw-bold">Contact Us</h5>
                <p>Email: <a href="mailto:support@onlinelibrary.com" class="text-white text-decoration-none">support@onlinelibrary.com</a></p>
                <p>Phone: +1 (234) 567-890</p>
                <p>Website: <a href="https://www.onlinelibrary.com" class="text-white text-decoration-none">www.onlinelibrary.com</a></p>
            </div>

            <!-- Help & Resources Section -->
            <div class="col-lg-3 col-md-6 mb-4">
                <h5 class="fw-bold">Help & Resources</h5>
                <p><a href="#" class="text-white text-decoration-none">FAQ</a></p>
                <p><a href="#" class="text-white text-decoration-none">User Guides</a></p>
                <p><a href="#" class="text-white text-decoration-none">Browse Our Catalog</a></p>
            </div>

            <!-- Newsletter Subscription Section -->
            <div class="col-lg-3 col-md-6 mb-4">
                <h5 class="fw-bold">Newsletter Subscription</h5>
                <p>Stay informed about our latest book releases, updates, and events. Subscribe to our newsletter for regular updates!</p>
                <form action="#" method="POST">
                    <div class="input-group">
                        <input type="email" class="form-control" placeholder="Enter your email" aria-label="Enter your email" required>
                        <button class="btn btn-light" type="submit">Subscribe</button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Social Media Section -->
        <div class="text-center mt-4">
            <a href="#" class="text-white me-3"><i class="bi bi-facebook" style="font-size: 24px;"></i></a>
            <a href="#" class="text-white me-3"><i class="bi bi-twitter" style="font-size: 24px;"></i></a>
            <a href="#" class="text-white me-3"><i class="bi bi-instagram" style="font-size: 24px;"></i></a>
            <a href="#" class="text-white me-3"><i class="bi bi-linkedin" style="font-size: 24px;"></i></a>
        </div>

        <!-- Footer Bottom -->
        <div class="text-center mt-4">
            <p>&copy; 2024 Online Library. All rights reserved.</p>
        </div>
    </div>
</footer>

<style>
    .display-4 {
        color: #5c3d6c;
    }

    .btn-dark {
        background-color: #6a4c93;
        border-color: #6a4c93;
    }

    .hero-img-container {
        width: 100%;
        max-width: 470px;
        padding: 20px;
    }

    .hero-image {
        width: 100%;
        height: auto;
        object-fit: cover;
        border-radius: 10px;
    }

    .card-body {
        text-align: center;
    }

    .stars {
        color: #f6c23e;
    }

    .book-card {
        border: none;
    }

    .form-control {
        max-width: 600px;
        background-color: #f4f4f4;
        border-color: #ccc; 
    }

    .form-control:focus {
        background-color: #e0e0e0;
        border-color: #999;
    }

    .card-img-top {
        width: 100%;
        height: 200px; 
        object-fit: contain;  
    }

    .card-body .card-title, 
    .card-body .card-text {
        margin-bottom: 1px; 
    }

    html {
        scroll-behavior: smooth;
    }

    @media (max-width: 768px) {
        .hero-section {
            flex-direction: column;
            text-align: center;
        }

        .hero-img-container {
            width: 80%;
            max-width: 500px;
            margin-top: 20px;
        }

        .hero-image {
            max-width: 100%;
            height: auto;
        }

        .form-control {
            max-width: 100%;
        }
    }
</style>

@endsection
