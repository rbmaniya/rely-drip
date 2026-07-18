@extends('admin.layouts.app')

@section('page-title', 'Add Employee')

@section('content')
    <form method="POST" action="{{ route('admin.employees.store') }}">
        @include('admin.employees._form')
    </form>
@endsection
