@extends('frontend.layouts.app')

@section('title', 'Detail Produk')

@section('content')
    <h1 class="text-2xl font-semibold">Detail Produk</h1>
    <p class="mt-2 text-gray-700">Slug: {{ $slug ?? '-' }}</p>
@endsection