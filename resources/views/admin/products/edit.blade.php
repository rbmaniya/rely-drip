@extends('admin.layouts.app')

@section('page-title', 'Edit Product')

@section('content')
    <form method="POST" action="{{ route('admin.products.update', $product) }}" enctype="multipart/form-data">
        @include('admin.products._form')
    </form>
@endsection
