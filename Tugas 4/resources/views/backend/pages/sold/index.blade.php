@extends('backend.layouts.master')

@section('title', 'Sold Products')

@section('content')
    <h1>Sold Products</h1>

    <table class="table">
        <thead>
            <tr>
                <th>Name</th>
                <th>Description</th>
                <th>Price</th>
                <th>Date Sold</th>
            </tr>
        </thead>
        <tbody>
            @foreach($soldItems as $product)
            <tr>
                <td>{{ $product->name }}</td>
                <td>{{ $product->description }}</td>
                <td>{{ $product->price }}</td>
                <td>{{ $product->sold_at }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
@endsection
