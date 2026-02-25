@extends('frontend.layouts.app')

@section('title', 'Detail Produk')
@section('main_class', '')

@section('content')
    <livewire:public.product-detail :slug="$slug" />
@endsection
