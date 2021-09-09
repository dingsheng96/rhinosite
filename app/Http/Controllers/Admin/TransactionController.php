<?php

namespace App\Http\Controllers\Admin;

use App\Models\Transaction;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\DataTables\Admin\TransactionDataTable;
use App\DataTables\Admin\TransactionHistoryDataTable;

class TransactionController extends Controller
{
    public function __construct()
    {
        $this->middleware(['can:transaction.read']);
        $this->middleware(['can:transaction.create'])->only(['create', 'store']);
        $this->middleware(['can:transaction.update'])->only(['edit', 'update']);
        $this->middleware(['can:transaction.delete'])->only(['delete']);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(TransactionDataTable $dataTable)
    {
        return $dataTable->render('admin.transaction.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Transaction $transaction, TransactionHistoryDataTable $dataTable)
    {
        $transaction->load(['sourceable', 'paymentMethod', 'transactionDetails']);

        return $dataTable->with(compact('transaction'))->render('admin.transaction.show', compact('transaction'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
