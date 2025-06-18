@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Products</h2>
        @if(auth()->user()->role === 'admin')
        <a href="{{ route('products.create') }}" class="btn btn-primary">Add New Product</a>
        @endif
    </div>

    @if(session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
    @endif

    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>SKU</th>
                            <th>Description</th>
                            <th>Price</th>
                            <th>Stock</th>
                            <th>Low Stock Threshold</th>
                            @if(auth()->user()->role === 'admin')
                            <th>Actions</th>
                            @endif
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($products as $product)
                        <tr>
                            <td>{{ $product->name }}</td>
                            <td>{{ $product->sku }}</td>
                            <td>{{ $product->description }}</td>
                            <td>₹{{ number_format($product->price, 2) }}</td>
                            <td>
                                <span class="{{ $product->stock <= $product->low_stock_threshold ? 'text-danger' : '' }}">
                                    {{ $product->stock }}
                                </span>
                            </td>
                            <td>{{ $product->low_stock_threshold }}</td>
                            @if(auth()->user()->role === 'admin')
                            <td>
                                <div class="btn-group">
                                    <a href="{{ route('products.edit', $product) }}" class="btn btn-sm btn-info">Edit</a>
                                    <form action="{{ route('products.destroy', $product) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure?')">Delete</button>
                                    </form>
                                </div>
                            </td>
                            @endif
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection