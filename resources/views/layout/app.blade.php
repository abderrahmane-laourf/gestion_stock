<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Gestion Stock')</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f8f9fa;
        }
        .wrapper {
            display: flex;
            width: 100%;
            align-items: stretch;
        }
        #sidebar {
            min-width: 250px;
            max-width: 250px;
            height: 100vh;
            position: fixed;
            top: 0;
            left: 0;
            background: #343a40;
            color: #fff;
            overflow-y: auto;
            transition: all 0.3s;
            z-index: 1000;
        }
        #sidebar .sidebar-header {
            padding: 20px;
            background: #343a40;
        }
        #content {
            width: 100%;
            padding: 20px;
            margin-left: 250px; /* Same as sidebar width */
            min-height: 100vh;
            transition: all 0.3s;
        }
    </style>
</head>
<body>

    <div class="wrapper">
        <!-- Sidebar -->
        <!-- Sidebar -->
        <nav id="sidebar">
            <div class="sidebar-header">
                <h3>Stock.ma</h3>
            </div>

            <ul class="list-unstyled components p-2">
                <li class="mb-2">
                    <a href="{{ route('dashboard') }}" class="text-white text-decoration-none d-block p-2 rounded hover-effect
                        {{ request()->routeIs('dashboard') ? 'bg-primary' : '' }}">
                        <i class="bi bi-speedometer2 me-2"></i> Dashboard
                    </a>
                </li>
                <li class="mb-2">
                    <a href="{{ route('clients.index') }}" class="text-white text-decoration-none d-block p-2 rounded hover-effect
                        {{ request()->is('clients*') ? 'bg-primary' : '' }}">
                        <i class="bi bi-people-fill me-2"></i> Clients
                    </a>
                </li>
                <li class="mb-2">
                    <a href="{{ route('categories.index') }}" class="text-white text-decoration-none d-block p-2 rounded hover-effect
                        {{ request()->is('categories*') ? 'bg-primary' : '' }}">
                        <i class="bi bi-tags me-2"></i> Catégories
                    </a>
                </li>
                <li class="mb-2">
                    <a href="{{ route('produits.index') }}" class="text-white text-decoration-none d-block p-2 rounded hover-effect
                        {{ request()->is('produits*') ? 'bg-primary' : '' }}">
                        <i class="bi bi-box-seam me-2"></i> Produits
                    </a>
                </li>
                <li class="mb-2">
                    <a href="{{ route('commandes.index') }}" class="text-white text-decoration-none d-block p-2 rounded hover-effect
                        {{ request()->is('commandes*') ? 'bg-primary' : '' }}">
                        <i class="bi bi-cart-check me-2"></i> Commandes
                    </a>
                </li>
                <li class="mb-2">
                    <hr class="text-white">
                </li>
                <li class="mb-2">
                    <a href="{{ route('home.index') }}" class="text-white text-decoration-none d-block p-2 rounded hover-effect
                        {{ request()->routeIs('home.index') ? 'bg-primary' : '' }}">
                        <i class="bi bi-shop me-2"></i> Catalogue
                    </a>
                </li>
                <li class="mb-2">
                    <a href="{{ route('home.cart') }}" class="text-white text-decoration-none d-block p-2 rounded hover-effect
                        {{ request()->routeIs('home.cart') ? 'bg-primary' : '' }}">
                        <i class="bi bi-cart3 me-2"></i> Panier
                    </a>
                </li>
                
                @auth
                <li class="mb-2 mt-auto">
                    <form action="{{ route('logout') }}" method="POST" class="d-inline">
                        @csrf
                        <button type="submit" class="text-white text-decoration-none d-block p-2 rounded bg-danger border-0 w-100 text-start">
                            <i class="bi bi-box-arrow-right me-2"></i> Logout ({{ auth()->user()->username }})
                        </button>
                    </form>
                </li>
                @endauth
            </ul>
        </nav>

        <!-- Page Content -->
        <div id="content">


            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @yield('content')
        </div>
    </div>

    <!-- Bootstrap JS Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
    </script>
</body>
</html>
