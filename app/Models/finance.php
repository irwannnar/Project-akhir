<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Finance extends Model
{
    protected $fillable = ['balance', 'initial_balance', 'description'];

    public function financeTransactions()
    {
        return $this->hasMany(FinanceTransaction::class);
    }

    // Method untuk update balance
    public function updateBalance()
    {
        $totalIncome = $this->financeTransactions()
            ->where('type', 'income')
            ->sum('amount');

        $totalExpense = $this->financeTransactions()
            ->where('type', 'expense')
            ->sum('amount');

        $this->balance = $this->initial_balance + $totalIncome - $totalExpense;
        $this->save();
    }

    // Method untuk mendapatkan summary
    public function getTotalIncomeAttribute()
    {
        return $this->financeTransactions()
            ->where('type', 'income')
            ->sum('amount');
    }

    public function getTotalExpenseAttribute()
    {
        return $this->financeTransactions()
            ->where('type', 'expense')
            ->sum('amount');
    }

    // Relasi ke transactions melalui financeTransactions
    public function transactions()
    {
        return $this->hasManyThrough(
            Transaction::class,
            FinanceTransaction::class,
            'finance_id',
            'id',
            'id',
            'sourceable_id'
        )->where('finance_transactions.sourceable_type', Transaction::class);
    }

    // Relasi ke spendings melalui financeTransactions
    public function spendings()
    {
        return $this->hasManyThrough(
            Spending::class,
            FinanceTransaction::class,
            'finance_id',
            'id',
            'id',
            'sourceable_id'
        )->where('finance_transactions.sourceable_type', Spending::class);
    }

    public function getDailyBalance($date = null)
    {
        $date = $date ?: now()->format('Y-m-d');

        $income = $this->financeTransactions()
            ->where('type', 'income')
            ->whereDate('created_at', $date)
            ->sum('amount');

        $expense = $this->financeTransactions()
            ->where('type', 'expense')
            ->whereDate('created_at', $date)
            ->sum('amount');

        return [
            'date' => $date,
            'income' => $income,
            'expense' => $expense,
            'net' => $income - $expense
        ];
    }

    public function getMonthlySummary($year = null, $month = null)
    {
        $year = $year ?: now()->year;
        $month = $month ?: now()->month;

        return $this->financeTransactions()
            ->selectRaw('type, SUM(amount) as total, COUNT(*) as count')
            ->whereYear('created_at', $year)
            ->whereMonth('created_at', $month)
            ->groupBy('type')
            ->pluck('total', 'type');
    }
}
