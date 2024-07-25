@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">{{ __('Stock Management') }}</div>

                <div class="card-body">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Product Name</th>
                                <th>Stock Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($products as $product)
                                <tr>
                                    <td>{{ $product->name }}</td>
                                    <td>
                                        @if($product->stocks->sum('quantity') == 0)
                                            <span class="text-warning">No stock available</span>
                                        @else
                                            {{ $product->stocks->sum('quantity') }} units available
                                        @endif
                                    </td>
                                    <td>
                                        @if($product->stocks->sum('quantity') == 0)
                                            <a href="{{ route('stocks.edit', $product->stocks->first()->id) }}" class="btn btn-success">Add Stock</a>
                                        @else
                                            <a href="{{ route('stocks.edit', $product->stocks->first()->id) }}" class="btn btn-primary">Edit</a>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
