<?php

namespace App\Http\Controllers\Admin;

use App\Enums\AdminRole;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Employee\StoreEmployeeRequest;
use App\Http\Requests\Admin\Employee\UpdateEmployeeRequest;
use App\Models\ActivityLog;
use App\Models\Admin;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class EmployeeController extends Controller
{
    public function index(Request $request): View
    {
        $employees = Admin::query()
            ->where('role', AdminRole::Employee)
            ->when($request->filled('search'), function ($query) use ($request) {
                $term = $request->string('search');
                $query->where(fn ($q) => $q->where('name', 'like', "%{$term}%")
                    ->orWhere('email', 'like', "%{$term}%"));
            })
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return view('admin.employees.index', compact('employees'));
    }

    public function create(): View
    {
        return view('admin.employees.create');
    }

    public function store(StoreEmployeeRequest $request): RedirectResponse
    {
        $data = $request->safe()->except('permissions');
        $data['role'] = AdminRole::Employee;
        $data['permissions'] = $request->input('permissions', []);

        $employee = Admin::create($data);

        ActivityLog::record('employee.created', "Employee \"{$employee->name}\" created.");

        return redirect()->route('admin.employees.index')->with('success', 'Employee created successfully.');
    }

    public function edit(Admin $employee): View
    {
        abort_unless($employee->role === AdminRole::Employee, 404);

        return view('admin.employees.edit', compact('employee'));
    }

    public function update(UpdateEmployeeRequest $request, Admin $employee): RedirectResponse
    {
        abort_unless($employee->role === AdminRole::Employee, 404);

        $data = $request->safe()->except(['permissions', 'password']);
        $data['permissions'] = $request->input('permissions', []);

        if ($request->filled('password')) {
            $data['password'] = $request->validated('password');
        }

        $employee->update($data);

        ActivityLog::record('employee.updated', "Employee \"{$employee->name}\" updated.");

        return redirect()->route('admin.employees.index')->with('success', 'Employee updated successfully.');
    }

    public function toggleStatus(Admin $employee): RedirectResponse
    {
        abort_unless($employee->role === AdminRole::Employee, 404);

        $employee->update(['is_active' => ! $employee->is_active]);

        ActivityLog::record('employee.status_toggled', "Employee \"{$employee->name}\" ".($employee->is_active ? 'activated.' : 'deactivated.'));

        return back()->with('success', 'Employee status updated.');
    }

    public function destroy(Admin $employee): RedirectResponse
    {
        abort_unless($employee->role === AdminRole::Employee, 404);

        ActivityLog::record('employee.deleted', "Employee \"{$employee->name}\" deleted.");

        $employee->delete();

        return redirect()->route('admin.employees.index')->with('success', 'Employee deleted successfully.');
    }
}
