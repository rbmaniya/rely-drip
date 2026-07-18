@csrf
@isset($employee)
    @method('PUT')
@endisset

@php
    $selectedPermissions = old('permissions', $employee->permissions ?? []);
@endphp

<div class="row g-3">
    <div class="col-lg-6">
        <div class="stat-card h-100">
            <h2 class="h6 mb-3">Basic Information</h2>

            <div class="row g-3">
                <div class="col-md-6">
                    <label for="name" class="form-label">Full Name <span class="text-danger">*</span></label>
                    <input type="text" name="name" id="name" value="{{ old('name', $employee->name ?? '') }}"
                           class="form-control @error('name') is-invalid @enderror" required>
                    @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-6">
                    <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
                    <input type="email" name="email" id="email" value="{{ old('email', $employee->email ?? '') }}"
                           class="form-control @error('email') is-invalid @enderror" required>
                    @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-6">
                    <label for="mobile" class="form-label">Mobile Number</label>
                    <input type="text" name="mobile" id="mobile" value="{{ old('mobile', $employee->mobile ?? '') }}"
                           class="form-control @error('mobile') is-invalid @enderror">
                    @error('mobile')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-6">
        <div class="stat-card h-100">
            <h2 class="h6 mb-3">Password {{ isset($employee) ? '' : '*' }}</h2>
            <div class="row g-3">
                <div class="col-md-6">
                    <label for="password" class="form-label">Password</label>
                    <input type="password" name="password" id="password"
                           class="form-control @error('password') is-invalid @enderror" {{ isset($employee) ? '' : 'required' }}>
                    @error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    @isset($employee)
                        <div class="form-text">Leave blank to keep the current password.</div>
                    @endisset
                </div>
                <div class="col-md-6">
                    <label for="password_confirmation" class="form-label">Confirm Password</label>
                    <input type="password" name="password_confirmation" id="password_confirmation" class="form-control">
                </div>
            </div>
        </div>
    </div>

    <div class="col-12">
        <div class="stat-card p-0">
            <div class="p-3 pb-0">
                <h2 class="h6 mb-1">Menu Permissions</h2>
                <p class="text-muted small">Tick the specific actions this employee can perform in each section. A section only appears in their sidebar if "View" is checked.</p>
            </div>

            <div class="table-responsive">
                <table class="table align-middle mb-0">
                    <thead>
                        <tr>
                            <th>Module</th>
                            <th class="text-center">View</th>
                            <th class="text-center">Add</th>
                            <th class="text-center">Edit</th>
                            <th class="text-center">Delete</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach (\App\Enums\AdminRole::permissionModules() as $module => $config)
                            <tr>
                                <td>{{ $config['label'] }}</td>
                                @foreach (['view', 'create', 'edit', 'delete'] as $action)
                                    <td class="text-center">
                                        @if (isset($config['actions'][$action]))
                                            @php($key = "{$module}.{$action}")
                                            <input type="checkbox" name="permissions[]" value="{{ $key }}" id="perm_{{ $key }}"
                                                   class="form-check-input" title="{{ $config['actions'][$action] }}"
                                                   {{ in_array($key, $selectedPermissions, true) ? 'checked' : '' }}>
                                        @else
                                            <span class="text-muted">—</span>
                                        @endif
                                    </td>
                                @endforeach
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="col-12">
        <div class="d-flex gap-2">
            <button type="submit" class="btn btn-primary"><i class="bi bi-check-lg me-1"></i> Save Employee</button>
            <a href="{{ route('admin.employees.index') }}" class="btn btn-outline-secondary">Cancel</a>
        </div>
    </div>
</div>
