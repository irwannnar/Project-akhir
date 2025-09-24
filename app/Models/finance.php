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
}