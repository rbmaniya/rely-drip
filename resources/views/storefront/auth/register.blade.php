@extends('storefront.layouts.guest')

@section('title', 'Create Account')

@section('content')
    <h1 class="h4 mb-1">Create your account</h1>
    <p class="text-muted small mb-4">Join us to track orders, save addresses and build your wishlist.</p>

    <form method="POST" action="{{ route('storefront.register') }}" novalidate>
        @csrf

        <div class="mb-3">
            <label for="name" class="form-label">Full name</label>
            <input type="text" name="name" id="name" value="{{ old('name') }}"
                   class="form-control @error('name') is-invalid @enderror" required autofocus>
            @error('name')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="email" class="form-label">Email address</label>
            <input type="email" name="email" id="email" value="{{ old('email') }}"
                   class="form-control @error('email') is-invalid @enderror" required autocomplete="username">
            @error('email')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="mobile" class="form-label">Mobile number</label>
            <input type="text" name="mobile" id="mobile" value="{{ old('mobile') }}"
                   class="form-control @error('mobile') is-invalid @enderror">
            @error('mobile')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="password" class="form-label">Password</label>
            <input type="password" name="password" id="password"
                   class="form-control @error('password') is-invalid @enderror" required autocomplete="new-password">
            @error('password')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="password_confirmation" class="form-label">Confirm password</label>
            <input type="password" name="password_confirmation" id="password_confirmation" class="form-control" required autocomplete="new-password">
        </div>

        <button type="submit" class="btn btn-primary w-100 mb-3">Create account</button>
        <p class="text-center small mb-0">
            Already have an account? <a href="{{ route('storefront.login') }}">Sign in</a>
        </p>
    </form>
@endsection
