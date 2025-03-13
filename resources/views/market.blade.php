@extends('layouts.layoutmarket')

@section('title', 'Market Dashboard')


@section('maincontent')
<a class="nav-link" href="#">Welcome, {{ Auth::user()->name }}</a>
    <div class="market-container" style="display: grid; grid-template-columns: repeat(4, 1fr); gap: 20px;">
        <div class="market-item">
            <img src="" alt="HP Pavilion 16">
            <h3>HP Pavilion 16</h3>
            <p>Elevated power and performance to do more.</p>
            <span class="price">$459.99</span>
            <button>Shop</button>
        </div>
        <div class="market-item">
            <img src="" alt="HP Pavilion x360">
            <h3>HP Pavilion x360</h3>
            <p>Flexible design built for performance.</p>
            <span class="price">$449.99</span>
            <button>Shop</button>
        </div>
        <div class="market-item">
            <img src="" alt="HP Pavilion Aero">
            <h3>HP Pavilion Aero</h3>
            <p>Incredibly lightweight and immersive entertainment.</p>
            <span class="price">$599.99</span>
            <button>Shop</button>
        </div>
        
        <div class="market-item empty">
            <h3>Opening Soon</h3>
        </div>
        <div class="market-item empty">
            <h3>Opening Soon</h3>
        </div>
    </div>
@endsection
