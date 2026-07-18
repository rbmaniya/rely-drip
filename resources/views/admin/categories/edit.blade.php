@extends('admin.layouts.app')

@section('page-title', 'Edit Category')

@section('content')
    <form method="POST" action="{{ route('admin.categories.update', $category) }}" enctype="multipart/form-data">
        @include('admin.categories._form')
    </form>
@endsection
