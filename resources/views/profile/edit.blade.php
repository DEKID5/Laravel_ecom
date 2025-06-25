@extends('layouts.storelayout')
<link rel="stylesheet" href="{{ asset('css/editproduct.css') }}">

@section('maincontent')
<div class="container">
    <h2>Edit Product</h2>

    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <form action="{{ route('updateProduct', $product->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT') <!-- Ensure Laravel recognizes this as a PUT request -->

        <!-- Category -->
        <div class="mb-3">
            <label class="form-label">Category</label>
            <select name="category" class="form-control">
                <option value="accessory" {{ $product->category == 'accessory' ? 'selected' : '' }}>Accessory</option>
                <option value="laptop" {{ $product->category == 'laptop' ? 'selected' : '' }}>Laptop</option>
            </select>
        </div>

        <!-- Product Name -->
        <div class="mb-3">
            <label class="form-label">Product Name</label>
            <input type="text" name="name" class="form-control" value="{{ $product->name }}" required>
        </div>

        <!-- Description -->
        <div class="mb-3">
            <label class="form-label">Description</label>
            <textarea name="description" class="form-control" required>{{ $product->description }}</textarea>
        </div>

        <!-- Price -->
        <div class="mb-3">
            <label class="form-label">Price</label>
            <input type="number" name="price" class="form-control" value="{{ $product->price }}" required>
        </div>

        <!-- Image Upload -->
        <div class="mb-3">
            <label class="form-label">Product Image</label>
            <input type="file" name="image" class="form-control">
            <small>Leave empty if you don't want to change the image.</small>
            <br>
            <img src="{{ asset('storage/' . $product->image) }}" alt="Product Image" width="100">
        </div>

        <!-- Laptop-Specific Fields -->
        <div id="laptopFields" style="display: {{ $product->category == 'laptop' ? 'block' : 'none' }}">
            <div class="mb-3">
                <label class="form-label">Brand</label>
                <input type="text" name="brand" class="form-control" value="{{ $product->brand }}">
            </div>
            <div class="mb-3">
                <label class="form-label">Model</label>
                <input type="text" name="model" class="form-control" value="{{ $product->model }}">
            </div>
        </div>

        <!-- Submit Button -->
        <button type="submit" class="btn btn-primary">Update Product</button>
    </form>

    <!-- Delete Product Form -->
    <form action="{{ route('deleteProduct', $product->id) }}" method="POST" style="margin-top: 20px;">
        @csrf
        @method('DELETE') <!-- DELETE method to prevent GET request issues -->
        <button type="submit" class="btn btn-danger">Delete Product</button>
    </form>

</div>
@endsection
