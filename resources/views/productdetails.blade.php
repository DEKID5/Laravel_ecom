
@extends('layouts.layoutmarket')

@section('title', $product->name ?? 'Product Details')

@section('maincontent')

@if (session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
@endif

<div class="product-details-container">
    <!-- Product Image -->
    <div class="product-image">
        <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" onerror="this.src='/images/placeholder.png';">
    </div>
    
    <!-- Product Info -->
    <div class="product-info">
        <h1>{{ $product->name }}</h1>
        <p class="product-description">{{ $product->description }}</p>
        <div class="product-price">GH₵{{ number_format($product->price, 2) }}</div>

        <!-- Store Information -->
        <div class="store-info">
            <h3>Seller: {{ optional($product->store)->name ?? 'Unknown' }}</h3>
            <p>Contact: {{ optional($product->store)->phone ?? 'N/A' }}</p>
        </div>

        <!-- Add to Cart -->
        <form action="{{ route('cart.add', ['product' => $product->id]) }}" method="POST" class="add-to-cart-form" style="margin-top: 20px;">
            @csrf
            <button type="submit" class="add-to-cart-btn">Add to Cart</button>
        </form>

        <!-- Message Store -->
        <div class="message-store" style="margin-top: 30px;">
            <h3>Message Seller</h3>
            <form id="messageForm" action="{{ route('messages.send') }}" method="POST">
                @csrf
                <input type="hidden" name="store_id" value="{{ $product->store_id }}">
                <input type="hidden" name="product_id" value="{{ $product->id }}">
                <textarea name="message" placeholder="Send a message to the store..." required></textarea>
                <button type="submit">Send Message</button>
            </form>

            <div id="messageSuccess" style="display: none; margin-top: 10px; color: green; font-weight: bold;">
                <!-- Success message will be displayed here -->
            </div>
        </div>
    </div>
</div>

<!-- Back Button -->
<div class="back-to-market" style="margin-top: 40px;">
    <a href="{{ route('market.index') }}">&larr; Back to Market</a>
</div>

<!-- Fullscreen Image Viewer Script -->
<script>
    document.addEventListener("DOMContentLoaded", function () {
        const img = document.querySelector('.product-image img');

        img.addEventListener('click', function () {
            const overlay = document.createElement('div');
            overlay.style.position = 'fixed';
            overlay.style.top = 0;
            overlay.style.left = 0;
            overlay.style.width = '100%';
            overlay.style.height = '100%';
            overlay.style.backgroundColor = 'rgba(0,0,0,0.8)';
            overlay.style.display = 'flex';
            overlay.style.alignItems = 'center';
            overlay.style.justifyContent = 'center';
            overlay.style.zIndex = '9999';

            const fullImg = document.createElement('img');
            fullImg.src = img.src;
            fullImg.style.maxWidth = '90%';
            fullImg.style.maxHeight = '90%';
            fullImg.style.border = '5px solid #fff';
            fullImg.style.borderRadius = '10px';

            const closeBtn = document.createElement('span');
            closeBtn.innerText = '×';
            closeBtn.style.position = 'absolute';
            closeBtn.style.top = '20px';
            closeBtn.style.right = '30px';
            closeBtn.style.fontSize = '40px';
            closeBtn.style.color = '#fff';
            closeBtn.style.cursor = 'pointer';
            closeBtn.style.fontWeight = 'bold';

            closeBtn.onclick = () => document.body.removeChild(overlay);
            overlay.appendChild(fullImg);
            overlay.appendChild(closeBtn);
            document.body.appendChild(overlay);
        });

        // Message Form Submission Handler
        document.getElementById('messageForm').addEventListener('submit', function (e) {
            e.preventDefault();

            const form = this;
            const formData = new FormData(form);

            fetch(form.action, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value,
                    'Accept': 'application/json',
                }
            })
            .then(response => {
                if (!response.ok) throw new Error("Failed to send message.");
                return response.json();
            })
            .then(data => {
                // Show success message
                const successMessage = document.getElementById('messageSuccess');
                if (successMessage) {
                    successMessage.style.display = 'block';
                    successMessage.innerHTML = data.message;
                }

                // Reset form
                form.reset();

        
                setTimeout(() => {
                    window.location.href = "{{ route('customers.index') }}"; /
                }, 2000);
            })
            .catch(error => {
                alert("Failed to send message. Please try again.");
                console.error(error);
            });
        });
    });
    
</script>

@endsection
