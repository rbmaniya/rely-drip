<?php

namespace App\Models;

use App\Enums\AdminRole;
use App\Models\Concerns\HasUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Admin extends Authenticatable
{
    use HasFactory, HasUuid, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'mobile',
        'avatar',
        'password',
        'role',
        'permissions',
        'is_active',
        'last_login_at',
        'last_login_ip',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'role' => AdminRole::class,
            'permissions' => 'array',
            'is_active' => 'boolean',
            'last_login_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * $ability may be a bare module ("categories") or a granular
     * "module.action" permission (e.g. "categories.edit"). A role/employee
     * holding the bare module name is granted every action under it; this
     * lets fixed roles (Admin, ProductManager, ...) keep their existing
     * whole-module grants while employees can be scoped to specific actions.
     */
    public function hasAbility(string $ability): bool
    {
        $granted = $this->role === AdminRole::Employee
            ? ($this->permissions ?? [])
            : $this->role->abilities();

        if (in_array('*', $granted, true) || in_array($ability, $granted, true)) {
            return true;
        }

        if (str_contains($ability, '.')) {
            [$module] = explode('.', $ability, 2);

            return in_array($module, $granted, true);
        }

        return false;
    }

    public function isSuperAdmin(): bool
    {
        return $this->role === AdminRole::SuperAdmin;
    }

    /**
     * First section this admin has access to, in sidebar priority order.
     * Used to redirect after login instead of assuming the dashboard is
     * always allowed (an employee may not have that permission).
     */
    public function defaultRoute(): string
    {
        $routeByAbility = [
            'dashboard.view' => 'admin.dashboard',
            'categories.view' => 'admin.categories.index',
            'products.view' => 'admin.products.index',
            'orders.view' => 'admin.orders.index',
            'customers.view' => 'admin.customers.index',
            'reviews.view' => 'admin.reviews.index',
            'contact_messages.view' => 'admin.contact-messages.index',
            'custom_orders.view' => 'admin.custom-order-requests.index',
            'website_settings.view' => 'admin.settings.edit',
            'employees' => 'admin.employees.index',
        ];

        foreach ($routeByAbility as $ability => $route) {
            if ($this->hasAbility($ability)) {
                return $route;
            }
        }

        return 'admin.profile.edit';
    }

    public function activityLogs()
    {
        return $this->hasMany(ActivityLog::class);
    }

    public function orderStatusChanges()
    {
        return $this->hasMany(OrderStatusHistory::class, 'changed_by');
    }
}
