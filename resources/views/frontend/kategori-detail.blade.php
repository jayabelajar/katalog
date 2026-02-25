@extends('frontend.layouts.app')

@section('title', 'Kategori Produk')
@section('main_class', '')

@section('content')
    <livewire:public.category-detail-page :slug="$slug" />
@endsection
