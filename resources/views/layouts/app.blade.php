<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    
    <title>@yield('title', 'Alfa Omega Community Church (AOCC) — Welcome Home')</title>
    <meta name="description" content="@yield('meta_description', 'Selamat datang di Alfa Omega Community Church (AOCC). Gereja yang memulihkan, memperlengkapi, dan mengutus generasi untuk kemuliaan Tuhan.')">
    <meta name="theme-color" content="#030712">

    <!-- Open Graph -->
    <meta property="og:title" content="@yield('title', 'Alfa Omega Community Church (AOCC)')">
    <meta property="og:description" content="Welcome Home — Gereja yang berakar pada Firman, bertumbuh dalam kasih karunia, dan berdampak bagi kota.">
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ url()->current() }}">

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cinzel:wght@500;600;700;800;900&family=Great+Vibes&family=Plus+Jakarta+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;0,800;1,400&display=swap" rel="stylesheet">

    <!-- Swiper CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />

    <!-- Tailwind / Vite -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        :root {
            --font-cinzel: 'Cinzel', serif;
            --font-script: 'Great Vibes', cursive;
            --font-sans: 'Plus Jakarta Sans', sans-serif;
        }

        body {
            font-family: var(--font-sans);
            background-color: #030712;
            color: #f1f5f9;
            overflow-x: hidden;
        }

        .font-cinzel {
            font-family: var(--font-cinzel);
        }

        .font-script {
            font-family: var(--font-script);
        }

        /* Glassmorphism helpers */
        .glass-panel {
            background: rgba(10, 15, 30, 0.72);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border: 1px solid rgba(59, 130, 246, 0.18);
        }

        .glass-panel-glow {
            background: rgba(8, 14, 28, 0.85);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border: 1px solid rgba(59, 130, 246, 0.25);
            box-shadow: 0 0 35px -5px rgba(37, 99, 235, 0.25);
        }

        .glow-text-blue {
            text-shadow: 0 0 20px rgba(59, 130, 246, 0.6), 0 0 40px rgba(37, 99, 235, 0.3);
        }

        .glow-border {
            box-shadow: 0 0 25px rgba(59, 130, 246, 0.22);
        }

        /* Custom scrollbar */
        ::-webkit-scrollbar {
            width: 8px;
        }
        ::-webkit-scrollbar-track {
            background: #030712;
        }
        ::-webkit-scrollbar-thumb {
            background: #1e3a8a;
            border-radius: 4px;
        }
        ::-webkit-scrollbar-thumb:hover {
            background: #2563eb;
        }
    </style>
    @stack('styles')
</head>
<body class="bg-[#030712] text-slate-100 antialiased selection:bg-blue-600 selection:text-white">

    <!-- Ambient Blue & Black Background Light Spheres -->
    <div class="fixed inset-0 pointer-events-none z-0 overflow-hidden" aria-hidden="true">
        <div class="absolute -top-40 left-1/2 -translate-x-1/2 w-[850px] h-[550px] bg-gradient-to-b from-blue-700/20 via-blue-900/10 to-transparent rounded-full blur-3xl"></div>
        <div class="absolute top-[35%] -left-64 w-[600px] h-[600px] bg-blue-950/25 rounded-full blur-[120px]"></div>
        <div class="absolute top-[70%] -right-64 w-[700px] h-[700px] bg-indigo-950/20 rounded-full blur-[140px]"></div>
        <div class="absolute bottom-0 left-1/3 w-[800px] h-[400px] bg-blue-900/15 rounded-full blur-[100px]"></div>
    </div>

    <!-- Main Navigation Bar -->
    @include('components.navbar')

    <!-- Main Content Area -->
    <main class="relative z-10">
        @yield('content')
    </main>

    <!-- Global Footer -->
    @include('components.footer')

    <!-- BETHEL Philosophy Modal -->
    @include('components.bethel-modal')

    <!-- Global YouTube Video Player Modal -->
    @include('components.youtube-modal')

    <!-- Swiper JS Script -->
    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>

    @stack('scripts')
</body>
</html>
