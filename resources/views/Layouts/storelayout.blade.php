<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Add this base tag to help resolve relative paths like images -->
    <base href="{{ url('/') }}/">

    <title>@yield('title', 'My App')</title>

    <!-- Vite CSS & JS -->
    @vite(['resources/css/store.css', 'resources/js/app.js'])
    <!-- Bootstrap 5 CSS -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

<!-- FontAwesome for icons -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

</head>
<!-- Bootstrap Bundle JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<body>

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
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
