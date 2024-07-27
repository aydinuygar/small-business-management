@extends('layouts.app')

@section('content')
@php
@endphp
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">{{ __('Order Details') }}</div>

                <div class="card-body">
                    <p><strong>Customer:</strong> {{ $order->customer->name }}</p>
                    <p><strong>Order Date:</strong> {{ $order->order_date }}</p>
                    <p><strong>Status:</strong> {{ $order->status }}</p>
                    <p><strong>Total Amount:</strong> ${{ $order->total_amount }}</p>

                    <h5>Order Details:</h5>
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Product Name</th>
                                <th>Quantity</th>
                                <th>Price</th>
                                <th>Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($order->order_details as $detail)
                                @php
                                    $product = App\Models\Product::find($detail['product_id']);
                                @endphp
                                <tr>
                                    <td>{{ $product->name }}</td>
                                    <td>{{ $detail['quantity'] }}</td>
                                    <td>${{ $detail['price'] }}</td>
                                    <td>${{ $detail['quantity'] * $detail['price'] }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                    <a href="{{ route('orders.index') }}" class="btn btn-secondary">Back to List</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
