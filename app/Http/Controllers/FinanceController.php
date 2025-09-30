<?php

namespace App\Http\Controllers;

use App\Models\finance;
use App\Models\Spending;
use App\Models\Transaction;
use App\Models\FinanceTransaction;
use Illuminate\Http\Request;
use illuminate\Support\Facades\DB;

class FinanceController extends Controller
{
    
    /**
     * Display finance summary with filters
     */
    public function index(Request $request)
    {
        return view('finance.index');
    }
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(finance $finance)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(finance $finance)
    {
       //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, finance $finance)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(finance $finance)
    {
        //
    }
}
