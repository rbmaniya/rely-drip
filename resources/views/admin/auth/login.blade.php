@extends('admin.layouts.guest')

@section('title', 'Login')

@section('content')
    <h1 class="h4 mb-1">Admin Login</h1>
    <p class="text-muted small mb-4">Sign in to manage your jewellery store.</p>

    <form method="POST" action="{{ route('admin.login') }}" novalidate>
        @csrf

        <div class="mb-3">
            <label for="email" class="form-label">Email address</label>
            <input type="email" name="email" id="email" value="{{ old('email') }}"
                   class="form-control @error('email') is-invalid @enderror" required autofocus autocomplete="username">
            @error('email')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <div class="d-flex justify-content-between">
                <label for="password" class="form-label">Password</label>
                {{-- <a href="{{ route('admin.password.request') }}" class="small">Forgot password?</a> --}}
            </div>
            <input type="password" name="password" id="password"
                   class="form-control @error('password') is-invalid @enderror" required autocomplete="current-password">
            @error('password')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3 form-check">
            <input type="checkbox" name="remember" id="remember" class="form-check-input">
            <label for="remember" class="form-check-label small">Remember me</label>
        </div>

        <button type="submit" class="btn btn-primary w-100">Sign in</button>
    </form>
@endsection
