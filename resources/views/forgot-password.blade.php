@extends('Layouts.Default')

@section('maincontent')
<div class="container">
    <h2>Forgot Password?</h2>

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <form action="{{ route('password.email') }}" method="POST">
        @csrf
        <label for="email">Enter your email</label>
        <input type="email" id="email" name="email" placeholder="Enter your email" required>

        <button type="submit">Send Reset Link</button>
    </form>

    <p><a href="{{ route('Signin') }}">Back to login</a></p>
</div>
@endsection
