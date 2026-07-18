@extends('admin.layouts.app')

@section('page-title', 'Edit Employee')

@section('content')
    <form method="POST" action="{{ route('admin.employees.update', $employee) }}">
        @include('admin.employees._form')
    </form>
@endsection
