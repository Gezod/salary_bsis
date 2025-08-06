<?php

namespace App\Services;

use App\Models\WeeklyPayroll;
use App\Models\Employee;
use App\Models\BpjsSetting;
use App\Models\Absensi;
use App\Models\Attendance;
use App\Models\Fine;
use App\Models\Overtime;
use App\Models\OvertimeRecord;
use Carbon\Carbon;

class WeeklyPayrollService
{
    public static function generateWeeklyPayrollForAllEmployees($startDate, $endDate)
    {
        $employees = Employee::all();
        $generated = 0;

        foreach ($employees as $employee) {
            // Check if payroll already exists for this period
            $existingPayroll = WeeklyPayroll::where('employee_id', $employee->id)
                ->where('start_date', $startDate->format('Y-m-d'))
                ->where('end_date', $endDate->format('Y-m-d'))
                ->first();

            if (!$existingPayroll) {
                self::generateWeeklyPayrollForEmployee($employee, $startDate, $endDate);
                $generated++;
            }
        }

        return $generated;
    }

    public static function generateWeeklyPayrollForEmployee($employee, $startDate, $endDate)
    {
        $workingDays = self::calculateWorkingDays($startDate, $endDate);
        $presentDays = self::calculatePresentDays($employee, $startDate, $endDate);

        // Calculate salary based on department
        if ($employee->departemen === 'staff') {
            $basicSalary = self::calculateStaffWeeklySalary($employee, $startDate, $endDate);
            $mealAllowance = self::calculateStaffWeeklyMealAllowance($employee, $presentDays);
        } else {
            $basicSalary = ($employee->daily_salary ?? 0) * $presentDays;
            $mealAllowance = ($employee->meal_allowance ?? 0) * $presentDays;
        }

        $overtimePay = self::calculateOvertimePay($employee, $startDate, $endDate);
        $totalFines = self::calculateTotalFines($employee, $startDate, $endDate);

        // No BPJS deduction for weekly payroll - only for monthly payroll
        $bpjsDeduction = 0;

        $grossSalary = $basicSalary + $overtimePay + $mealAllowance;
        $netSalary = $grossSalary - $totalFines - $bpjsDeduction;

        return WeeklyPayroll::create([
            'employee_id' => $employee->id,
            'start_date' => $startDate,
            'end_date' => $endDate,
            'working_days' => $workingDays,
            'present_days' => $presentDays,
            'basic_salary' => $basicSalary,
            'overtime_pay' => $overtimePay,
            'meal_allowance' => $mealAllowance,
            'total_fines' => $totalFines,
            'bpjs_deduction' => $bpjsDeduction,
            'gross_salary' => $grossSalary,
            'net_salary' => $netSalary,
            'status' => 'pending'
        ]);
    }

    private static function calculateWorkingDays($startDate, $endDate)
    {
        $workingDays = 0;
        $currentDate = $startDate->copy();

        while ($currentDate->lte($endDate)) {
            if ($currentDate->isWeekday()) {
                $workingDays++;
            }
            $currentDate->addDay();
        }

        return $workingDays;
    }

    private static function calculatePresentDays($employee, $startDate, $endDate)
    {
        return Attendance::where('employee_id', $employee->id)
            ->whereBetween('tanggal', [$startDate->format('Y-m-d'), $endDate->format('Y-m-d')])
            ->where(function ($query) {
                $query->whereNotNull('scan1') // Has check-in
                    ->orWhere('is_half_day', true); // Or is half day
            })
            ->count();
    }

    private static function calculateStaffWeeklySalary($employee, $startDate, $endDate)
    {
        $staffSetting = \App\Models\StaffPayrollSetting::where('employee_id', $employee->id)->first();

        if (!$staffSetting) {
            return 0;
        }

        // Calculate weekly portion of monthly salary
        $daysInPeriod = $startDate->diffInDays($endDate) + 1;
        $dailySalary = $staffSetting->monthly_salary / 30; // Assuming 30 days per month

        return $dailySalary * $daysInPeriod;
    }

    private static function calculateStaffWeeklyMealAllowance($employee, $presentDays)
    {
        $staffSetting = \App\Models\StaffPayrollSetting::where('employee_id', $employee->id)->first();

        if (!$staffSetting) {
            return 0;
        }

        return $staffSetting->daily_meal_allowance * $presentDays;
    }

    private static function calculateOvertimePay($employee, $startDate, $endDate)
    {
        $overtimeMinutes = OvertimeRecord::where('employee_id', $employee->id)
            ->whereBetween('tanggal', [$startDate->format('Y-m-d'), $endDate->format('Y-m-d')])
            ->sum('overtime_minutes');

        $overtimeHours = $overtimeMinutes / 60; // Convert minutes to hours
        $hourlyRate = ($employee->daily_salary ?? 0) / 8; // Assuming 8 hours per day
        return $overtimeHours * $hourlyRate * 1.5; // 1.5x rate for overtime
    }

    private static function calculateTotalFines($employee, $startDate, $endDate)
    {
        // Only apply fines to karyawan, not staff
        if ($employee->departemen === 'staff') {
            return 0;
        }

        return Attendance::where('employee_id', $employee->id)
            ->whereBetween('tanggal', [$startDate->format('Y-m-d'), $endDate->format('Y-m-d')])
            ->sum('total_fine');  // Changed from 'jumlah_denda' to 'total_fine'
    }

    public static function recalculateWeeklyPayroll($weeklyPayrollId)
    {
        $weeklyPayroll = WeeklyPayroll::findOrFail($weeklyPayrollId);
        $employee = $weeklyPayroll->employee;

        $startDate = $weeklyPayroll->start_date;
        $endDate = $weeklyPayroll->end_date;

        $workingDays = self::calculateWorkingDays($startDate, $endDate);
        $presentDays = self::calculatePresentDays($employee, $startDate, $endDate);

        // Calculate salary based on department
        if ($employee->departemen === 'staff') {
            $basicSalary = self::calculateStaffWeeklySalary($employee, $startDate, $endDate);
            $mealAllowance = self::calculateStaffWeeklyMealAllowance($employee, $presentDays);
        } else {
            $basicSalary = ($employee->daily_salary ?? 0) * $presentDays;
            $mealAllowance = ($employee->meal_allowance ?? 0) * $presentDays;
        }

        $overtimePay = self::calculateOvertimePay($employee, $startDate, $endDate);
        $totalFines = self::calculateTotalFines($employee, $startDate, $endDate);

        // No BPJS deduction for weekly payroll - only for monthly payroll
        $bpjsDeduction = 0;

        $grossSalary = $basicSalary + $overtimePay + $mealAllowance;
        $netSalary = $grossSalary - $totalFines - $bpjsDeduction;

        $weeklyPayroll->update([
            'working_days' => $workingDays,
            'present_days' => $presentDays,
            'basic_salary' => $basicSalary,
            'overtime_pay' => $overtimePay,
            'meal_allowance' => $mealAllowance,
            'total_fines' => $totalFines,
            'bpjs_deduction' => $bpjsDeduction,
            'gross_salary' => $grossSalary,
            'net_salary' => $netSalary
        ]);

        return $weeklyPayroll;
    }
}
