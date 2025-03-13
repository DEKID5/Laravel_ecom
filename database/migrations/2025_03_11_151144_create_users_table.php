@extends('Layouts.Default')

@section('maincontent')
<!-- Signup Page -->
<div class="signup-container">
    <h2>Sign Up</h2>
    <p>Select your account type to proceed:</p>

    <!-- Buyer & Seller Selection Buttons -->
    <div class="user-type-buttons">
        <button type="button" onclick="showForm('buyer')">Buyer</button>
        <button type="button" onclick="showForm('seller')">Seller</button>
    </div>

    <!-- Buyer Signup Form -->
    <form id="buyer-form" action="{{ route('signup.buyer') }}" method="POST" style="display: none;">
        @csrf
        <label for="buyer-name">Full Name</label>
        <input type="text" id="buyer-name" name="name" placeholder="Enter your full name" required>

        <label for="buyer-email">Email</label>
        <input type="email" id="buyer-email" name="email" placeholder="Enter your email" required>
        
        <label for="buyer-phone">Phone</label>
        <input type="text" id="buyer-phone" name="phone" placeholder="Enter your phone number" required>

        <label for="buyer-password">Password</label>
        <input type="password" id="buyer-password" name="password" placeholder="Enter your password" required>

        <button type="submit">Sign Up as Buyer</button>
    </form>

    <!-- Seller Signup Form -->
    <form id="seller-form" action="{{ route('signup.seller') }}" method="POST" style="display: none;">
        @csrf
        <label for="seller-name">Full Name</label>
        <input type="text" id="seller-name" name="name" placeholder="Enter your full name" required>

        <label for="seller-email">Email</label>
        <input type="email" id="seller-email" name="email" placeholder="Enter your email" required>
        
        <label for="seller-phone">Phone</label>
        <input type="text" id="seller-phone" name="phone" placeholder="Enter your phone number" required>
        
        <label for="seller-location">Location</label>
        <input type="text" id="seller-location" name="location" placeholder="Enter your location" required>
        
        <label for="seller-business">Store Name</label>
        <input type="text" id="seller-business" name="store_name" placeholder="Enter your store name" required>

        <label for="seller-password">Password</label>
        <input type="password" id="seller-password" name="password" placeholder="Enter your password" required>

        <button type="submit">Sign Up as Seller</button>
    </form>
</div>

<!-- Success Popup -->
@if(session('success'))
<div id="successPopup" class="popup">
    <p>{{ session('success') }}</p>
    <button onclick="closePopup()">OK</button>
</div>
@endif

<!-- JavaScript to Toggle Forms & Handle Popup -->
<script>
    function showForm(type) {
        document.getElementById('buyer-form').style.display = type === 'buyer' ? 'block' : 'none';
        document.getElementById('seller-form').style.display = type === 'seller' ? 'block' : 'none';
    }
    
    function closePopup() {
        document.getElementById('successPopup').style.display = 'none';
    }
</script>

@endsection
