@extends('Layouts.Default')

@section('maincontent')
<!-- Login Page -->
<div class="container">
    <!-- Left Side Welcome Message -->
    <div class="left-section">
        <h2>Welcome to My App</h2>
        <p>BUY AND SELL PC , LAPTOPS & PARTS AT YOUR FINGER TIPS.</p>
    </div>

    <!-- Right Side: Login Form -->
    <div class="right-section">
        @if(session('success'))
            <div id="successPopup" class="popup">
                <p>{{ session('success') }}</p>
                <button class="close-popup" data-popup="successPopup">OK</button>
            </div>
        @endif

        @if(session('error'))
            <div id="errorPopup" class="popup error">
                <p>{{ session('error') }}</p>
                <button class="close-popup" data-popup="errorPopup">OK</button>
            </div>
        @endif

        <form action="{{ route('signin.post') }}" method="POST">
            @csrf

            <label for="email">Email</label>
            <input type="email" id="email" name="email" placeholder="Enter your email" required>

            <label for="password">Password</label>
            <input type="password" id="password" name="password" placeholder="Enter your password" required>

            <button type="submit" class="signin-btn">Sign In</button>

            <p><a href="{{ route('password.request') }}">Forgot Password?</a></p>
        </form>

        <p>Don't have an account? <a href="{{ route('Signup') }}">Sign up here</a></p>
    </div>
</div>

<!-- Popup CSS -->
<style>
    .popup {
        position: fixed;
        top: 20px;
        right: 20px;
        background: #4CAF50;
        color: white;
        padding: 15px;
        border-radius: 5px;
        box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
        z-index: 1000;
        display: block !important;
    }

    .popup.error {
        background: #FF5733;
    }
    
    .popup button {
        margin-left: 10px;
        background: white;
        color: black;
        border: none;
        padding: 5px 10px;
        cursor: pointer;
        border-radius: 3px;
    }

    .signin-btn {
        background-color: #4CAF50;
        color: white;
        padding: 10px 15px;
        border: none;
        cursor: pointer;
        border-radius: 5px;
        width: 100%;
        font-size: 16px;
    }

    .signin-btn:hover {
        background-color: #45a049;
    }
</style>

@endsection
