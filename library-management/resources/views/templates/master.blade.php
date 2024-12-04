<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>ReadWay</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

    <style>
        .login-register-background {
            min-height: 100vh;
        }

        .btn-login-register {
            background-color: #9caec2;
            color: white;
            border-radius: 10px;
        }

        .btn-login-register:hover {
            background-color: #8f9eaf;
            color: white;
        }

        #search-input {
            display: none;
            width: 200px;
            border-radius: 20px;
            border: none;
        }

        #search-icon {
            outline: none;
            border: none;
            cursor: pointer;
        }

        #submit-btn {
            display: none;
        }

        #search-form {
            display: flex;
            align-items: center;
        }

        html {
            scroll-behavior: smooth;
        }

    </style>
</head>

<body class="login-register-background">
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark sticky-top bg-white">
        <div class="container-fluid">
            <a class="navbar-brand fw-bold text-dark fs-6" href="#">Booksy</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <!-- Menu -->
                <ul class="navbar-nav mx-auto">
                    <li class="nav-item">
                        <a class="nav-link active text-black" aria-current="page" href="{{ url('/') }}">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-black" href="#popular">Popular</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-black" href="#contact">Contact</a>
                    </li>
                </ul>                
                <!-- Login and Search Section -->
                <div class="d-flex align-items-center">
                    <!-- Search Form -->
                    <form action="{{ route('search') }}" method="GET" class="d-flex me-3" id="search-form">
                        <input class="form-control me-2" 
                            type="search" 
                            id="search-input" 
                            name="query" 
                            placeholder="Search for books..." 
                            aria-label="Search" 
                            value="{{ request()->query('query') }}">
                    </form>
                    
                    <!-- Search Icon -->
                    <button type="button" class="btn me-3" id="search-icon">
                        <i class="fas fa-search"></i>
                    </button>

                    <!-- Login Button -->
                    <a href="{{ route('login') }}" class="btn btn-login-register me-3 btn-sm">Login</a>
                </div>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <div>
        @yield('content')
    </div>

    <script>
        document.getElementById('search-icon').addEventListener('click', function() {
            document.getElementById('search-input').style.display = 'inline-block';
            document.getElementById('search-input').focus();

            document.getElementById('submit-btn').style.display = 'none';
        });

        document.getElementById('search-input').addEventListener('keydown', function(event) {
            if (event.key === 'Enter') {
                document.getElementById('search-form').submit();
            }
        });
    </script>
</body>
</html>
