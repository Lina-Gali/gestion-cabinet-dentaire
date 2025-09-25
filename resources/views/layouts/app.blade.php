<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Dentina')</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href="https://fonts.googleapis.com/css2?family=Mulish:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    @stack('styles')
  
</head>

<body>
    <div class="layout-container">
        <nav class="sidebar">
            <div class="logo">
                <div class="logo-icon">
                    <i class="fas fa-heart"></i>
                </div>
                <span class="logo-text">Dentina</span>
            </div>

            <ul class="nav-menu">
                <li class="nav-item">
                    <a href="{{ route('home.acceuil') }}" class="nav-link {{ request()->routeIs('home.acceuil') ? 'active' : '' }}">
                        <i class="fas fa-home"></i>
                        <span>Accueil</span>
                    </a>
                <li class="nav-item">
                    <a href="{{ route('patients.index') }}" class="nav-link {{ request()->routeIs('patients.*') ? 'active' : '' }}">
                        <i class="fas fa-users"></i>
                        <span>Patients</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('rendezvous.calendar') }}" class="nav-link {{ request()->routeIs('rendezvous.calendar') ? 'active' : '' }}">
                        <i class="fas fa-calendar"></i>
                        <span>Planning</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('rendezvous.index') }}" class="nav-link {{ request()->routeIs('rendezvous.index') ? 'active' : '' }}">
                        <i class="fas fa-list"></i>
                        <span>Liste RDV</span>
                    </a>
                </li>
            </ul>
        </nav>

        <!-- Main Content -->
        <div class="main-content">
            <!-- Header -->
            <header class="header">

            </header>

            <!-- Content -->
            <main class="content">
                @yield('content')
            </main>
        </div>
    </div>

    @stack('scripts')
</body>

</html>