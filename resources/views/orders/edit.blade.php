@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">{{ __('Edit Order') }}</div>

                <div class="card-body">
                    @if ($errors->has('insufficient_stock'))
                        <div class="alert alert-danger">
                            <strong>{{ $errors->first('insufficient_stock') }}</strong>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('orders.update', $order->id) }}">
                        @csrf
                        @method('PUT')

                        <div class="form-group">
                            <label for="customer_id">Customer</label>
                            <input type="text" class="form-control" value="{{ $order->customer->name }}" disabled>
                            <input type="hidden" name="customer_id" value="{{ $order->customer_id }}">
                        </div>

                        <div class="form-group mt-3">
                            <label for="order_details">Order Details</label>
                            <div id="order_details">
                                @php
                                    $orderDetails = $order->order_details;
                                @endphp

                                @foreach($orderDetails as $index => $detail)
                                    <div class="row mb-2">
                                        <div class="col-md-5">
                                            @php
                                                $product = \App\Models\Product::find($detail['product_id']);
                                            @endphp
                                            <input type="text" class="form-control" value="{{ $product->name }}" disabled>
                                            <input type="hidden" name="order_details[{{ $index }}][product_id]" value="{{ $detail['product_id'] }}">
                                        </div>
                                        <div class="col-md-4">
                                            <input type="number" name="order_details[{{ $index }}][quantity]" class="form-control @error("order_details.{$index}.quantity") is-invalid @enderror" placeholder="Quantity" value="{{ $detail['quantity'] }}" required>
                                            @error("order_details.{$index}.quantity")
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        <div class="form-group mt-3">
                            <button type="submit" class="btn btn-success">Update Order</button>
                            <a href="{{ route('orders.index') }}" class="btn btn-secondary">Back to List</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
