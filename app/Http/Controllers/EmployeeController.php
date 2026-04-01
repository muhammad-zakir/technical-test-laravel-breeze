<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreEmployeeRequest;
use App\Http\Requests\UpdateEmployeeRequest;
use App\Http\Resources\CompanyResource;
use App\Http\Resources\EmployeeCollection;
use App\Http\Resources\EmployeeResource;
use App\Models\Company;
use App\Models\Employee;
use App\Notifications\NewEmployeeNotification;
use Inertia\Inertia;

class EmployeeController extends Controller
{
    public function index()
    {
        $employees = Employee::with('company')
            ->when(request('search'), function ($query, $search) {
                $query->where(function ($subQuery) use ($search) {
                    $subQuery->where('first_name', 'like', "%{$search}%")
                        ->orWhere('last_name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%")
                        ->orWhereHas('company', function ($companyQuery) use ($search) {
                            $companyQuery->where('name', 'like', "%{$search}%");
                        });
                });
            })
            ->orderBy(
                request('sort_field', 'id'),
                request('sort_order', 'ascend') === 'ascend' ? 'asc' : 'desc'
            )
            ->paginate(request('per_page', 10))
            ->withQueryString();

        $companies = Company::select('id', 'name')->orderBy('name')->get();

        return Inertia::render('Employees/Index', [
            'employees' => new EmployeeCollection($employees),
            'pagination' => [
                'current_page' => $employees->currentPage(),
                'last_page' => $employees->lastPage(),
                'per_page' => $employees->perPage(),
                'total' => $employees->total(),
            ],
            'companies' => CompanyResource::collection($companies),
            'filters' => request()->only(['search', 'sort_field', 'sort_order', 'per_page']),
        ]);
    }

    public function store(StoreEmployeeRequest $request)
    {
        $employee = Employee::create($request->validated());

        $company = $employee->company;
        if ($company && $company->email) {
            $company->notify(new NewEmployeeNotification($employee));
        }

        return redirect()->route('employees.index')->with('success', 'Employee created successfully.');
    }

    public function show(Employee $employee)
    {
        $employee->load('company');
        return response()->json(new EmployeeResource($employee));
    }

    public function update(UpdateEmployeeRequest $request, Employee $employee)
    {
        $employee->update($request->validated());
        return redirect()->route('employees.index')->with('success', 'Employee updated successfully.');
    }

    public function destroy(Employee $employee)
    {
        $employee->delete();
        return redirect()->route('employees.index')->with('success', 'Employee deleted successfully.');
    }
}
