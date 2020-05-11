<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use Illuminate\Http\Request;

class EmployeeController extends Controller
{
    public function create(Request $request)
    {
        $request->validate([
            'name' => 'required|max:55|unique:employees',
            'priority' => 'required|in:1,2,3',
        ]);

        $employee = Employee::create($request->only(['name', 'priority']));
        if ($employee)
            return response(['message' => 'done']);

        return response(['message' => 'Error'], 500);
    }

    public function get(Employee $employee)
    {
        return response(['employee' => $employee]);
    }
}
