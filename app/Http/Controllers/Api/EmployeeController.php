<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Employee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class EmployeeController extends Controller
{
    public function search(Request $request)
    {
        try {
            $query = $request->get('q', '');

            if (strlen($query) < 2) {
                return response()->json([]);
            }

            $employees = Employee::where(function($q) use ($query) {
                $q->where('nama', 'LIKE', "%{$query}%")
                  ->orWhere('nip', 'LIKE', "%{$query}%")
                  ->orWhere('pin', 'LIKE', "%{$query}%");
            })
            ->select('id', 'pin', 'nip', 'nama', 'jabatan', 'departemen', 'kantor')
            ->limit(10)
            ->get();

            return response()->json($employees);
        } catch (\Exception $e) {
            Log::error('Employee search error: ' . $e->getMessage());
            return response()->json(['error' => 'Search failed'], 500);
        }
    }
}
