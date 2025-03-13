<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <title>@yield('title', 'My App')</title>

    <!-- Vite CSS & JS -->
    @vite(['resources/css/style.css', 'resources/js/app.js'])
</head>
<body>

<!-- Background Video
    
       
    

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
