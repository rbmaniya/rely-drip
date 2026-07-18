@extends('admin.layouts.app')

@section('page-title', 'My Profile')

@section('content')
    <div class="row g-3">
        <div class="col-lg-6">
            <div class="stat-card h-100">
                <h2 class="h6 mb-3">Profile Information</h2>
                <form method="POST" action="{{ route('admin.profile.update') }}" enctype="multipart/form-data">
                    @csrf @method('PUT')

                    <div class="text-center mb-3">
                        <img id="avatar-preview"
                             src="{{ $admin->avatar ? asset('storage/'.$admin->avatar) : 'https://ui-avatars.com/api/?name='.urlencode($admin->name).'&background=7c3aed&color=fff' }}"
                             class="rounded-circle mb-2" width="80" height="80" alt="Avatar">
                        <input type="file" name="avatar" accept="image/*" data-image-preview-input="avatar-preview" class="form-control form-control-sm">
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Name</label>
                        <input type="text" name="name" value="{{ old('name', $admin->name) }}" class="form-control @error('name') is-invalid @enderror" required>
                        @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Email</label>
                        <input type="email" name="email" value="{{ old('email', $admin->email) }}" class="form-control @error('email') is-invalid @enderror" required>
                        @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Mobile Number</label>
                        <input type="text" name="mobile" value="{{ old('mobile', $admin->mobile) }}" class="form-control @error('mobile') is-invalid @enderror">
                        @error('mobile')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <button type="submit" class="btn btn-primary">Save Changes</button>
                </form>
            </div>
        </div>

        <div class="col-lg-6">
            <div class="stat-card h-100">
                <h2 class="h6 mb-3">Change Password</h2>
                <form method="POST" action="{{ route('admin.profile.password') }}">
                    @csrf @method('PUT')

                    <div class="mb-3">
                        <label class="form-label">Current Password</label>
                        <input type="password" name="current_password" class="form-control @error('current_password') is-invalid @enderror" required>
                        @error('current_password')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">New Password</label>
                        <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" required>
                        @error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Confirm New Password</label>
                        <input type="password" name="password_confirmation" class="form-control" required>
                    </div>

                    <button type="submit" class="btn btn-primary">Update Password</button>
                </form>
            </div>
        </div>
    </div>
@endsection
