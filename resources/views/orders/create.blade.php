@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">{{ __('Create Order') }}</div>

                <div class="card-body">
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('orders.store') }}">
                        @csrf

                        <div class="form-group">
                            <label for="customer_id">Customer</label>
                            <select id="customer_id" name="customer_id" class="form-control @error('customer_id') is-invalid @enderror" required>
                                <option value="">Select a customer</option>
                                @foreach($customers as $customer)
                                    <option value="{{ $customer->id }}">{{ $customer->name }}</option>
                                @endforeach
                            </select>
                            @error('customer_id')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="form-group mt-3">
                            <label for="order_details">Order Details</label>
                            <div id="order_details">
                                <div class="row mb-2">
                                    <div class="col-md-5">
                                        <select name="order_details[0][product_id]" class="form-control @error('order_details.0.product_id') is-invalid @enderror" required>
                                            <option value="">Select a product</option>
                                            @foreach($products as $product)
                                                <option value="{{ $product->id }}">{{ $product->name }}</option>
                                            @endforeach
                                        </select>
                                        @error('order_details.0.product_id')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                    <div class="col-md-4">
                                        <input type="number" name="order_details[0][quantity]" class="form-control @error('order_details.0.quantity') is-invalid @enderror" placeholder="Quantity" required>
                                        @error('order_details.0.quantity')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                    <div class="col-md-3">
                                        <button type="button" class="btn btn-danger btn-sm remove-detail">Remove</button>
                                    </div>
                                </div>
                            </div>
                            <button type="button" class="btn btn-primary" id="add-detail">Add More</button>
                        </div>

                        <div class="form-group mt-3">
                            <button type="submit" class="btn btn-success">Create Order</button>
                            <a href="{{ route('orders.index') }}" class="btn btn-secondary">Back to List</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.getElementById('add-detail').addEventListener('click', function() {
        let index = document.querySelectorAll('#order_details .row').length;
        let newRow = document.createElement('div');
        newRow.classList.add('row', 'mb-2');
        newRow.innerHTML = `
            <div class="col-md-5">
                <select name="order_details[${index}][product_id]" class="form-control" required>
                    <option value="">Select a product</option>
                    @foreach($products as $product)
                        <option value="{{ $product->id }}">{{ $product->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-4">
                <input type="number" name="order_details[${index}][quantity]" class="form-control" placeholder="Quantity" required>
            </div>
            <div class="col-md-3">
                <button type="button" class="btn btn-danger btn-sm remove-detail">Remove</button>
            </div>
        `;
        document.getElementById('order_details').appendChild(newRow);
    });

    document.getElementById('order_details').addEventListener('click', function(e) {
        if (e.target.classList.contains('remove-detail')) {
            e.target.parentElement.parentElement.remove();
        }
    });
</script>
@endsection
