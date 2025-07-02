<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Employee;

class EmployeeController extends Controller
{
    public function search(Request $request)
    {
        $q = $request->input('q');

        $employees = Employee::where('nama', 'like', '%' . $q . '%')
            ->orWhere('nip', 'like', '%' . $q . '%')
            ->limit(10)
            ->get();

        return response()->json($employees);
    }
}
