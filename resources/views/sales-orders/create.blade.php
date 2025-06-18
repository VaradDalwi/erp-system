@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card">
                <div class="card-header">
                    <h3 class="mb-0">Create Sales Order</h3>
                </div>

                <div class="card-body">
                    <form method="POST" action="{{ route('sales-orders.store') }}" id="salesOrderForm">
                        @csrf

                        <div class="mb-4">
                            <h4>Order Items</h4>
                            <div id="orderItems">
                                <div class="order-item mb-3 border p-3 rounded">
                                    <div class="row">
                                        <div class="col-md-5">
                                            <label class="form-label">Product</label>
                                            <select name="products[0][id]" class="form-select product-select" required>
                                                <option value="">Select Product</option>
                                                @foreach($products as $product)
                                                    <option value="{{ $product->id }}" 
                                                            data-price="{{ $product->price }}"
                                                            data-stock="{{ $product->stock }}">
                                                        {{ $product->name }} (Stock: {{ $product->stock }})
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-md-3">
                                            <label class="form-label">Quantity</label>
                                            <input type="number" name="products[0][quantity]" 
                                                   class="form-control quantity-input" min="1" required>
                                        </div>
                                        <div class="col-md-3">
                                            <label class="form-label">Subtotal</label>
                                            <div class="input-group">
                                                <span class="input-group-text">₹</span>
                                                <input type="text" class="form-control subtotal" readonly>
                                            </div>
                                        </div>
                                        <div class="col-md-1 d-flex align-items-end">
                                            <button type="button" class="btn btn-danger remove-item">×</button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <button type="button" id="addItem" class="btn btn-secondary">Add Item</button>
                        </div>

                        <div class="mb-3">
                            <h4>Order Summary</h4>
                            <div class="d-flex justify-content-between">
                                <span>Total Amount:</span>
                                <span>₹<span id="totalAmount">0.00</span></span>
                            </div>
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="{{ route('sales-orders.index') }}" class="btn btn-secondary">Cancel</a>
                            <button type="submit" class="btn btn-primary">Create Order</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        let itemCount = 1;

        // Add new item
        document.getElementById('addItem').addEventListener('click', function() {
            const template = document.querySelector('.order-item').cloneNode(true);
            template.querySelector('.product-select').name = `products[${itemCount}][id]`;
            template.querySelector('.quantity-input').name = `products[${itemCount}][quantity]`;
            template.querySelector('.quantity-input').value = '';
            template.querySelector('.subtotal').value = '';
            itemCount++;

            document.getElementById('orderItems').appendChild(template);
            setupEventListeners(template);
        });

        // Remove item
        document.addEventListener('click', function(e) {
            if (e.target.classList.contains('remove-item')) {
                if (document.querySelectorAll('.order-item').length > 1) {
                    e.target.closest('.order-item').remove();
                    calculateTotal();
                }
            }
        });

        // Calculate subtotal and total
        function setupEventListeners(item) {
            const productSelect = item.querySelector('.product-select');
            const quantityInput = item.querySelector('.quantity-input');

            productSelect.addEventListener('change', updateSubtotal);
            quantityInput.addEventListener('input', updateSubtotal);

            function updateSubtotal() {
                const selectedOption = productSelect.selectedOptions[0];
                const quantity = parseInt(quantityInput.value) || 0;

                if (selectedOption && selectedOption.dataset.price) {
                    const price = parseFloat(selectedOption.dataset.price);
                    const stock = parseInt(selectedOption.dataset.stock);

                    if (quantity > stock) {
                        alert('Quantity cannot exceed available stock!');
                        quantityInput.value = stock;
                        return updateSubtotal();
                    }

                    const subtotal = price * quantity;
                    item.querySelector('.subtotal').value = subtotal.toFixed(2);
                    calculateTotal();
                }
            }
        }

        function calculateTotal() {
            let total = 0;
            document.querySelectorAll('.subtotal').forEach(input => {
                total += parseFloat(input.value) || 0;
            });
            document.getElementById('totalAmount').textContent = total.toFixed(2);
        }

        // Setup initial event listeners
        document.querySelectorAll('.order-item').forEach(setupEventListeners);

        // Form validation
        document.getElementById('salesOrderForm').addEventListener('submit', function(e) {
            const items = document.querySelectorAll('.order-item');
            let valid = false;

            items.forEach(item => {
                const productId = item.querySelector('.product-select').value;
                const quantity = item.querySelector('.quantity-input').value;
                if (productId && quantity) valid = true;
            });

            if (!valid) {
                e.preventDefault();
                alert('Please add at least one product to the order.');
            }
        });
    });
</script>
@endpush
@endsection