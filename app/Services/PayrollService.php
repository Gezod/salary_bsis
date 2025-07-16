<?php

namespace App\Services;

use App\Models\Employee;
use App\Models\Payroll;
use App\Models\Attendance;
use App\Models\OvertimeRecord;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class PayrollService
{
    public static function calculateMonthlyPayroll($employeeId, $month, $year)
    {
        $employee = Employee::findOrFail($employeeId);

        // Get working days in month (excluding Sundays)
        $startDate = Carbon::create($year, $month, 1);
        $endDate = $startDate->copy()->endOfMonth();

        $workingDays = 0;
        $currentDate = $startDate->copy();

        while ($currentDate <= $endDate) {
            if ($currentDate->dayOfWeek !== Carbon::SUNDAY) {
                $workingDays++;
            }
            $currentDate->addDay();
        }

        // Get attendance data for the month
        $attendances = Attendance::where('employee_id', $employeeId)
            ->whereYear('tanggal', $year)
            ->whereMonth('tanggal', $month)
            ->get();

        // Count present days (days with scan1)
        $presentDays = $attendances->where('scan1', '!=', null)->count();

        // Calculate basic salary
        $dailySalary = $employee->daily_salary ?? 0;
        $basicSalary = $presentDays * $dailySalary;

        // Calculate meal allowance
        $mealAllowance = $presentDays * ($employee->meal_allowance ?? 0);

        // Calculate total fines
        $totalFines = $attendances->sum('total_fine');

        // Calculate overtime pay
        $overtimePay = OvertimeRecord::where('employee_id', $employeeId)
            ->whereYear('tanggal', $year)
            ->whereMonth('tanggal', $month)
            ->sum('overtime_pay');

        // Calculate gross and net salary
        $grossSalary = $basicSalary + $overtimePay + $mealAllowance;
        $netSalary = $grossSalary - $totalFines;

        return [
            'employee_id' => $employeeId,
            'month' => $month,
            'year' => $year,
            'working_days' => $workingDays,
            'present_days' => $presentDays,
            'basic_salary' => $basicSalary,
            'overtime_pay' => $overtimePay,
            'meal_allowance' => $mealAllowance,
            'total_fines' => $totalFines,
            'gross_salary' => $grossSalary,
            'net_salary' => $netSalary,
            'status' => 'pending'
        ];
    }

    public static function generatePayrollForAllEmployees($month, $year)
    {
        $employees = Employee::all();
        $generated = 0;

        foreach ($employees as $employee) {
            // Check if payroll already exists
            $existing = Payroll::where('employee_id', $employee->id)
                ->where('month', $month)
                ->where('year', $year)
                ->first();

            if (!$existing) {
                $payrollData = self::calculateMonthlyPayroll($employee->id, $month, $year);
                Payroll::create($payrollData);
                $generated++;
            }
        }

        return $generated;
    }

    public static function recalculatePayroll($payrollId)
    {
        $payroll = Payroll::findOrFail($payrollId);
        $newData = self::calculateMonthlyPayroll(
            $payroll->employee_id,
            $payroll->month,
            $payroll->year
        );

        // Keep payment information if already paid
        if ($payroll->status === 'paid') {
            $newData['payment_method'] = $payroll->payment_method;
            $newData['payment_date'] = $payroll->payment_date;
            $newData['payment_proof'] = $payroll->payment_proof;
            $newData['notes'] = $payroll->notes;
            $newData['status'] = $payroll->status;
        }

        $payroll->update($newData);
        return $payroll;
    }
}
