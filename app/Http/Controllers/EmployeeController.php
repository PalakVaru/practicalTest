<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\Department;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class EmployeeController extends Controller
{
    /**
     * Display employee listing
     */
    public function index(Request $request)
    {
        if ($request->wantsJson() || $request->ajax()) {
            return $this->getEmployeesData($request);
        }

        // Return view for initial page load
        return view('employees');
    }

    /**
     * Get employees data with filters (API endpoint)
     * Supports both DataTables and standard pagination
     */
    private function getEmployeesData(Request $request): JsonResponse
    {
        $query = Employee::with(['department', 'manager'])
            ->select('employees.*');

        // Check if this is a DataTables request
        $isDatatable = $request->has('draw');

        // Filter by department
        if ($request->filled('department_id')) {
            $query->where('department_id', $request->input('department_id'));
        }

        // Filter by manager
        if ($request->filled('manager_id')) {
            $query->where('manager_id', $request->input('manager_id'));
        }

        // Date range filter
        if ($request->filled('joining_date_from')) {
            $query->whereDate('joining_date', '>=', $request->input('joining_date_from'));
        }

        if ($request->filled('joining_date_to')) {
            $query->whereDate('joining_date', '<=', $request->input('joining_date_to'));
        }

        // Get total count before pagination

        $totalRecords = $query->count();
        if ($isDatatable) {
            // DataTables server-side processing format
            $draw = $request->input('draw', 1);
            $start = $request->input('start', 0);
            $length = $request->input('length', 10);

            // Calculate page number for Laravel pagination
            $page = ($start / $length) + 1;
            $employees = $query->skip($start)->take($length)->get();

            return response()->json([
                'draw' => intval($draw),
                'recordsTotal' => $totalRecords,
                'recordsFiltered' => $totalRecords,
                'data' => $employees
            ]);
        } else {
            // Standard pagination format
            $perPage = $request->input('per_page', 10);
            $employees = $query->paginate($perPage);

            return response()->json($employees);
        }
    }

    /**
     * Show form for creating a new employee
     */
    public function create(): JsonResponse
    {
        $departments = Department::select('id', 'name')->get();
        $managers = Employee::select('id', 'full_name')->get();

        return response()->json([
            'departments' => $departments,
            'managers' => $managers
        ]);
    }

    /**
     * Store a newly created employee
     */
    public function store(Request $request): JsonResponse
    {
        try {
            $validated = $request->validate([
                'employee_code' => 'required|string|unique:employees',
                'full_name' => 'required|string|max:255',
                'department_id' => 'nullable|exists:departments,id',
                'manager_id' => 'nullable|exists:employees,id',
                'joining_date' => 'required|date',
                'email' => 'required|email|unique:employees',
                'phone_number' => 'nullable|string|max:20',
                'address' => 'nullable|string|max:500'
            ]);

            $employee = Employee::create($validated);
            $employee->load(['department', 'manager']);

            return response()->json([
                'success' => true,
                'message' => 'Employee created successfully',
                'data' => $employee
            ], 201);
        } catch (\Illuminate\Validation\ValidationException $e) {
            // Return validation errors in proper format
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 422);
        }
    }

    /**
     * Show employee details for editing
     */
    public function edit(Employee $employee): JsonResponse
    {
        $employee->load(['department', 'manager']);
        $departments = Department::select('id', 'name')->get();
        $managers = Employee::where('id', '!=', $employee->id)->select('id', 'full_name')->get();

        return response()->json([
            'employee' => $employee,
            'departments' => $departments,
            'managers' => $managers
        ]);
    }

    /**
     * Update the specified employee
     */
    public function update(Request $request, Employee $employee): JsonResponse
    {
        try {
            $validated = $request->validate([
                'employee_code' => 'required|string|unique:employees,employee_code,' . $employee->id,
                'full_name' => 'required|string|max:255',
                'department_id' => 'nullable|exists:departments,id',
                'manager_id' => 'nullable|exists:employees,id',
                'joining_date' => 'required|date',
                'email' => 'required|email|unique:employees,email,' . $employee->id,
                'phone_number' => 'nullable|string|max:20',
                'address' => 'nullable|string|max:500'
            ]);

            $employee->update($validated);
            $employee->load(['department', 'manager']);
            return response()->json([
                'success' => true,
                'message' => 'Employee updated successfully',
                'data' => $employee
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            // Return validation errors in proper format
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 422);
        }
    }

    /**
     * Remove the specified employee
     */
    public function destroy(Employee $employee): JsonResponse
    {
        try {
            $employee->delete();

            return response()->json([
                'success' => true,
                'message' => 'Employee deleted successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 422);
        }
    }

    /**
     * Get all departments for dropdown
     */
    public function getDepartments(): JsonResponse
    {
        $departments = Department::select('id', 'name')->orderBy('name')->get();
        return response()->json($departments);
    }

    /**
     * Get all managers for dropdown
     */
    public function getManagers(): JsonResponse
    {
        $managers = Employee::select('id', 'full_name')->orderBy('full_name')->get();
        return response()->json($managers);
    }
}
