@extends('admin.layouts.app')

@section('page-title', 'Employees')
@section('page-subtitle', 'Staff accounts with menu-level permissions')

@section('page-actions')
    <a href="{{ route('admin.employees.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-lg me-1"></i> Add Employee
    </a>
@endsection

@section('content')
    <div class="stat-card p-0">
        <div class="p-3 border-bottom">
            <form method="GET" class="row g-2 align-items-end">
                <div class="col-12 col-sm-6 col-md-4">
                    <label class="form-label small mb-1">Search</label>
                    <input type="text" name="search" value="{{ request('search') }}" class="form-control" placeholder="Name or email">
                </div>
                <div class="col-6 col-md-2 d-grid">
                    <button type="submit" class="btn btn-outline-secondary">Filter</button>
                </div>
            </form>
        </div>

        <div class="table-responsive">
            <table class="table align-middle mb-0">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Permissions</th>
                        <th>Status</th>
                        <th class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($employees as $employee)
                        <tr>
                            <td>{{ $employee->name }}</td>
                            <td class="text-muted small">{{ $employee->email }}</td>
                            <td style="max-width:360px">
                                @php
                                    $grantedByModule = collect($employee->permissions ?? [])
                                        ->filter(fn ($p) => str_contains($p, '.'))
                                        ->map(fn ($p) => explode('.', $p, 2))
                                        ->groupBy(fn ($p) => $p[0])
                                        ->map(fn ($group) => $group->pluck(1));
                                @endphp

                                @forelse ($grantedByModule as $module => $actions)
                                    @php($moduleConfig = \App\Enums\AdminRole::permissionModules()[$module] ?? null)
                                    @if ($moduleConfig)
                                        <span class="badge text-bg-secondary d-inline-block mb-1">
                                            {{ $moduleConfig['label'] }}: {{ $actions->map(fn ($a) => $moduleConfig['actions'][$a] ?? $a)->implode(', ') }}
                                        </span>
                                    @endif
                                @empty
                                    <span class="text-muted small">No menus assigned</span>
                                @endforelse
                            </td>
                            <td>
                                <span class="badge {{ $employee->is_active ? 'text-bg-success' : 'text-bg-secondary' }}">
                                    {{ $employee->is_active ? 'Active' : 'Inactive' }}
                                </span>
                            </td>
                            <td class="text-end">
                                <div class="btn-group">
                                    <a href="{{ route('admin.employees.edit', $employee) }}" class="btn btn-sm btn-outline-secondary" title="Edit"><i class="bi bi-pencil"></i></a>
                                    <form action="{{ route('admin.employees.toggle-status', $employee) }}" method="POST" class="d-inline">
                                        @csrf @method('PATCH')
                                        <button type="submit" class="btn btn-sm btn-outline-secondary" title="{{ $employee->is_active ? 'Deactivate' : 'Activate' }}">
                                            <i class="bi {{ $employee->is_active ? 'bi-slash-circle' : 'bi-check-circle' }}"></i>
                                        </button>
                                    </form>
                                    <form action="{{ route('admin.employees.destroy', $employee) }}" method="POST" class="d-inline"
                                          onsubmit="return confirm('Delete this employee? This cannot be undone.');">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger" title="Delete"><i class="bi bi-trash"></i></button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="text-center text-muted py-5">No employees found.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($employees->hasPages())
            <div class="p-3 border-top">{{ $employees->links() }}</div>
        @endif
    </div>
@endsection
