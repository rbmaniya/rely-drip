@extends('storefront.layouts.guest')

@section('title', 'Forgot Password')

@section('content')
    <h1 class="h4 mb-1">Forgot your password?</h1>
    <p class="text-muted small mb-4">Enter your email and we'll send you a password reset link.</p>

    <form method="POST" action="{{ route('storefront.password.email') }}" novalidate>
        @csrf

        <div class="mb-3">
            <label for="email" class="form-label">Email address</label>
            <input type="email" name="email" id="email" value="{{ old('email') }}"
                   class="form-control @error('email') is-invalid @enderror" required autofocus>
            @error('email')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <button type="submit" class="btn btn-primary w-100 mb-3">Email password reset link</button>
        <a href="{{ route('storefront.login') }}" class="d-block text-center small">Back to login</a>
    </form>
@endsection
