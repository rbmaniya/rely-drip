@php($admin = auth('admin')->user())

<header class="admin-topbar">
    <div class="d-flex align-items-center gap-2">
        <button type="button" class="btn btn-light d-lg-none" data-admin-sidebar-toggle aria-label="Toggle sidebar">
            <i class="bi bi-list fs-5"></i>
        </button>
        <h1 class="h5 mb-0 d-none d-sm-block">@yield('page-title', 'Dashboard')</h1>
    </div>

    <div class="dropdown">
        <button class="btn btn-light d-flex align-items-center gap-2" type="button" data-bs-toggle="dropdown" aria-expanded="false">
            <img src="{{ $admin->avatar ? asset('storage/'.$admin->avatar) : 'https://ui-avatars.com/api/?name='.urlencode($admin->name).'&background=7c3aed&color=fff' }}"
                 class="rounded-circle" width="32" height="32" alt="{{ $admin->name }}">
            <span class="d-none d-sm-inline">{{ $admin->name }}</span>
            <i class="bi bi-chevron-down small"></i>
        </button>
        <ul class="dropdown-menu dropdown-menu-end">
            <li><span class="dropdown-item-text small text-muted">{{ $admin->role->label() }}</span></li>
            <li><hr class="dropdown-divider"></li>
            <li><a class="dropdown-item" href="{{ route('admin.profile.edit') }}"><i class="bi bi-person-circle me-2"></i>My Profile</a></li>
            <li>
                <form method="POST" action="{{ route('admin.logout') }}">
                    @csrf
                    <button type="submit" class="dropdown-item text-danger"><i class="bi bi-box-arrow-right me-2"></i>Logout</button>
                </form>
            </li>
        </ul>
    </div>
</header>
