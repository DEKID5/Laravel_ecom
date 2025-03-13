<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <title>@yield('title', 'My App')</title>

    <!-- Vite CSS & JS -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body>

    <!-- Background Video -->
    <div class="video-container">
        <video autoplay muted loop class="background-video">
            <source src="{{ asset('img/Portal.mp4') }}" type="video/mp4">
            Your browser does not support the video tag.
        </video>
        <noscript>
            <p>Your browser does not support JavaScript, please enable it to view the background video.</p>
        </noscript>
        <div class="overlay"></div>
    </div>

    <!-- Header -->
    <header class="animated-border">
        @yield('header')
    </header>

    <!-- Main Content -->
    <main>
        @yield('maincontent')  
    </main>

    <!-- Footer -->
    <footer class="animated-border">
        @yield('footer')
    </footer>

</body>
</html>
