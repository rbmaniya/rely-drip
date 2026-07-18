@extends('admin.layouts.app')

@section('page-title', 'Add Category')

@section('content')
    <form method="POST" action="{{ route('admin.categories.store') }}" enctype="multipart/form-data">
        @include('admin.categories._form')
    </form>
@endsection
