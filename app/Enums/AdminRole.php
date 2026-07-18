<?php

namespace App\Enums;

enum AdminRole: string
{
    case SuperAdmin = 'super_admin';
    case Admin = 'admin';
    case ProductManager = 'product_manager';
    case OrderManager = 'order_manager';
    case Employee = 'employee';

    public function label(): string
    {
        return match ($this) {
            self::SuperAdmin => 'Super Administrator',
            self::Admin => 'Administrator',
            self::ProductManager => 'Product Manager',
            self::OrderManager => 'Order Manager',
            self::Employee => 'Employee',
        };
    }

    /**
     * Ability keys granted to this role. Checked via Admin::hasAbility().
     * Employee has no fixed set — their abilities come from Admin::$permissions instead.
     *
     * @return array<int, string>
     */
    public function abilities(): array
    {
        return match ($this) {
            self::SuperAdmin => ['*'],
            self::Admin => ['dashboard', 'categories', 'products', 'inventory', 'orders', 'customers', 'reports', 'reviews', 'contact_messages', 'custom_orders'],
            self::ProductManager => ['dashboard', 'categories', 'products', 'inventory'],
            self::OrderManager => ['dashboard', 'orders', 'customers', 'reports'],
            self::Employee => [],
        };
    }

    /**
     * @return array<int, self>
     */
    public static function all(): array
    {
        return self::cases();
    }

    /**
     * Modules an employee can be individually granted access to, and the
     * specific actions available within each one. Stored/checked as
     * "module.action" strings (e.g. "categories.edit") via
     * Admin::hasAbility() and the admin.ability middleware.
     *
     * @return array<string, array{label: string, actions: array<string, string>}>
     */
    public static function permissionModules(): array
    {
        return [
            'dashboard' => ['label' => 'Dashboard', 'actions' => ['view' => 'View']],
            'categories' => ['label' => 'Categories', 'actions' => ['view' => 'View', 'create' => 'Add', 'edit' => 'Edit', 'delete' => 'Delete']],
            'products' => ['label' => 'Products', 'actions' => ['view' => 'View', 'create' => 'Add', 'edit' => 'Edit', 'delete' => 'Delete']],
            'orders' => ['label' => 'Orders', 'actions' => ['view' => 'View', 'edit' => 'Update Status']],
            'customers' => ['label' => 'Customers', 'actions' => ['view' => 'View', 'edit' => 'Block / Unblock']],
            'reviews' => ['label' => 'Reviews', 'actions' => ['view' => 'View', 'edit' => 'Approve / Reject', 'delete' => 'Delete']],
            'contact_messages' => ['label' => 'Contact Messages', 'actions' => ['view' => 'View', 'delete' => 'Delete']],
            'custom_orders' => ['label' => 'Custom Order Requests', 'actions' => ['view' => 'View', 'delete' => 'Delete']],
            'website_settings' => ['label' => 'Website Settings', 'actions' => ['view' => 'View', 'edit' => 'Edit']],
        ];
    }

    /**
     * Every valid "module.action" permission string, for validating an
     * employee's submitted permissions array.
     *
     * @return array<int, string>
     */
    public static function allPermissionKeys(): array
    {
        $keys = [];

        foreach (self::permissionModules() as $module => $config) {
            foreach (array_keys($config['actions']) as $action) {
                $keys[] = "{$module}.{$action}";
            }
        }

        return $keys;
    }
}
