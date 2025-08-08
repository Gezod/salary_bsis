<?php

namespace App\Services;

use App\Models\Payroll;
use App\Models\Employee;
use App\Models\BpjsSetting;
use App\Models\BpjsPremium;
use App\Models\Absensi;
use App\Models\Attendance;
use App\Models\Fine;
use App\Models\Overtime;
use App\Models\OvertimeRecord;
use Carbon\Carbon;

class PayrollService
{
    public static function generatePayrollForAllEmployees($month, $year)
    {
        $employees = Employee::all();
        $generated = 0;

        foreach ($employees as $employee) {
            // Check if payroll already exists for this month/year
            $existingPayroll = Payroll::where('employee_id', $employee->id)
                ->where('month', $month)
                ->where('year', $year)
                ->first();

            if (!$existingPayroll) {
                self::generatePayrollForEmployee($employee, $month, $year);
                $generated++;
            }
        }

        return $generated;
    }

    public static function generatePayrollForEmployee($employee, $month, $year)
    {
        $workingDays = self::calculateWorkingDays($month, $year);
        $presentDays = self::calculatePresentDays($employee, $month, $year);

        // Calculate salary based on department
        if ($employee->departemen === 'staff') {
            $staffSetting = \App\Models\StaffPayrollSetting::where('employee_id', $employee->id)->first();
            $basicSalary = $staffSetting ? $staffSetting->monthly_salary : 0;
            $mealAllowance = $staffSetting ? ($staffSetting->daily_meal_allowance * $presentDays) : 0;
        } else {
            $basicSalary = ($employee->daily_salary ?? 0) * $presentDays;
            $mealAllowance = ($employee->meal_allowance ?? 0) * $presentDays;
        }

        $overtimePay = self::calculateOvertimePay($employee, $month, $year);
        $totalFines = self::calculateTotalFines($employee, $month, $year);

        // Calculate BPJS with premium logic
        $bpjsCalculation = self::calculateBpjsWithPremium($employee, $month, $year);
        $bpjsAllowance = $bpjsCalculation['allowance'];
        $bpjsCashPayment = $bpjsCalculation['cash_payment'];
        $bpjsNetDeduction = $bpjsCalculation['net_deduction'];

        // Gross salary = basic + overtime + meal allowance + BPJS allowance (company pays)
        $grossSalary = $basicSalary + $overtimePay + $mealAllowance + $bpjsAllowance;

        // Net salary = gross - fines - BPJS net deduction
        $netSalary = $grossSalary - $totalFines - $bpjsNetDeduction;

        return Payroll::create([
            'employee_id' => $employee->id,
            'month' => $month,
            'year' => $year,
            'working_days' => $workingDays,
            'present_days' => $presentDays,
            'basic_salary' => $basicSalary,
            'overtime_pay' => $overtimePay,
            'meal_allowance' => $mealAllowance,
            'total_fines' => $totalFines,
            'bpjs_deduction' => $bpjsNetDeduction,
            'bpjs_allowance' => $bpjsAllowance,
            'bpjs_cash_payment' => $bpjsCashPayment,
            'gross_salary' => $grossSalary,
            'net_salary' => $netSalary,
            'status' => 'pending'
        ]);
    }

    private static function calculateWorkingDays($month, $year)
    {
        $startDate = Carbon::create($year, $month, 1);
        $endDate = $startDate->copy()->endOfMonth();
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

    private static function calculatePresentDays($employee, $month, $year)
    {
        $startDate = Carbon::create($year, $month, 1)->format('Y-m-d');
        $endDate = Carbon::create($year, $month, 1)->endOfMonth()->format('Y-m-d');

        return Attendance::where('employee_id', $employee->id)
            ->whereBetween('tanggal', [$startDate, $endDate])
            ->where(function ($query) {
                $query->whereNotNull('scan1') // Has check-in
                    ->orWhere('is_half_day', true); // Or is half day
            })
            ->count();
    }

    private static function calculateOvertimePay($employee, $month, $year)
    {
        $startDate = Carbon::create($year, $month, 1)->format('Y-m-d');
        $endDate = Carbon::create($year, $month, 1)->endOfMonth()->format('Y-m-d');

        $overtimeMinutes = OvertimeRecord::where('employee_id', $employee->id)
            ->whereBetween('tanggal', [$startDate, $endDate])
            ->sum('overtime_minutes');

        $overtimeHours = $overtimeMinutes / 60; // Convert minutes to hours
        $hourlyRate = ($employee->daily_salary ?? 0) / 8; // Assuming 8 hours per day

        return $overtimeHours * $hourlyRate * 1.5; // 1.5x rate for overtime
    }

    private static function calculateTotalFines($employee, $month, $year)
    {
        // Only apply fines to karyawan, not staff
        if ($employee->departemen === 'staff') {
            return 0;
        }

        $startDate = Carbon::create($year, $month, 1)->format('Y-m-d');
        $endDate = Carbon::create($year, $month, 1)->endOfMonth()->format('Y-m-d');

        return Attendance::where('employee_id', $employee->id)
            ->whereBetween('tanggal', [$startDate, $endDate])
            ->sum('total_fine');
    }

    private static function calculateBpjsWithPremium($employee, $month, $year)
    {
        // Get BPJS setting (allowance from company)
        $bpjsSetting = BpjsSetting::where('employee_id', $employee->id)
            ->where('is_active', true)
            ->first();

        $bpjsAllowance = $bpjsSetting ? $bpjsSetting->bpjs_monthly_amount : 0;

        // Get premium for this month
        $bpjsPremium = BpjsPremium::where('employee_id', $employee->id)
            ->where('month', $month)
            ->where('year', $year)
            ->first();

        $premiumAmount = $bpjsPremium ? $bpjsPremium->premium_amount : 0;

        // Calculate cash payment needed (if premium > allowance)
        $cashPayment = max(0, $premiumAmount - $bpjsAllowance);

        // Calculate net deduction from salary
        // If there's premium, deduct the allowance amount from salary
        // If there's cash payment, it's additional debt shown in PDF
        $netDeduction = $premiumAmount > 0 ? min($bpjsAllowance, $premiumAmount) : $bpjsAllowance;

        return [
            'allowance' => $bpjsAllowance,
            'premium' => $premiumAmount,
            'cash_payment' => $cashPayment,
            'net_deduction' => $netDeduction
        ];
    }

    public static function recalculatePayroll($payrollId)
    {
        $payroll = Payroll::findOrFail($payrollId);
        $employee = $payroll->employee;
        $month = $payroll->month;
        $year = $payroll->year;

        $workingDays = self::calculateWorkingDays($month, $year);
        $presentDays = self::calculatePresentDays($employee, $month, $year);

        // Calculate salary based on department
        if ($employee->departemen === 'staff') {
            $staffSetting = \App\Models\StaffPayrollSetting::where('employee_id', $employee->id)->first();
            $basicSalary = $staffSetting ? $staffSetting->monthly_salary : 0;
            $mealAllowance = $staffSetting ? ($staffSetting->daily_meal_allowance * $presentDays) : 0;
        } else {
            $basicSalary = ($employee->daily_salary ?? 0) * $presentDays;
            $mealAllowance = ($employee->meal_allowance ?? 0) * $presentDays;
        }

        $overtimePay = self::calculateOvertimePay($employee, $month, $year);
        $totalFines = self::calculateTotalFines($employee, $month, $year);

        // Calculate BPJS with premium logic
        $bpjsCalculation = self::calculateBpjsWithPremium($employee, $month, $year);
        $bpjsAllowance = $bpjsCalculation['allowance'];
        $bpjsCashPayment = $bpjsCalculation['cash_payment'];
        $bpjsNetDeduction = $bpjsCalculation['net_deduction'];

        // Gross salary = basic + overtime + meal allowance + BPJS allowance (company pays)
        $grossSalary = $basicSalary + $overtimePay + $mealAllowance + $bpjsAllowance;

        // Net salary = gross - fines - BPJS net deduction
        $netSalary = $grossSalary - $totalFines - $bpjsNetDeduction;

        $payroll->update([
            'working_days' => $workingDays,
            'present_days' => $presentDays,
            'basic_salary' => $basicSalary,
            'overtime_pay' => $overtimePay,
            'meal_allowance' => $mealAllowance,
            'total_fines' => $totalFines,
            'bpjs_deduction' => $bpjsNetDeduction,
            'bpjs_allowance' => $bpjsAllowance,
            'bpjs_cash_payment' => $bpjsCashPayment,
            'gross_salary' => $grossSalary,
            'net_salary' => $netSalary
        ]);

        return $payroll;
    }
}
