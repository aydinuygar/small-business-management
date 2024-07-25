@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Edit Stock') }}</div>

                <div class="card-body">
                    <form method="POST" action="{{ route('stocks.update', $stock->id) }}">
                        @csrf
                        @method('PUT')

                        <div class="form-group">
                            <label for="product_id">Product</label>
                            <p class="form-control-plaintext">{{ $stock->product->name }}</p>
                        </div>

                        <div class="form-group mt-3">
                            <label for="quantity">Quantity</label>
                            <input type="number" id="quantity" name="quantity" class="form-control @error('quantity') is-invalid @enderror" value="{{ old('quantity', $stock->quantity) }}" required>
                            @error('quantity')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="form-group mt-3">
                            <button type="submit" class="btn btn-primary">Update Stock</button>
                            <a href="{{ route('stocks.index') }}" class="btn btn-secondary">Back to List</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
