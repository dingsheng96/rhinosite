<?php

namespace App\Support\Services;

use App\Models\Transaction;

class TransactionService extends BaseService
{
    public function __construct()
    {
        parent::__construct(Transaction::class);
    }

    public function newTransaction()
    {
        $this->model = $this->parent->transaction()
            ->create([
                'transaction_no'    =>  $this->generateReportNo(Transaction::class, 'transaction_no', Transaction::REPORT_PREFIX),
                'currency_id'       =>  $this->parent->currency_id,
                'amount'            =>  $this->parent->grand_total,
                'payment_method_id' =>  $this->request->get('payment_method'),
                'status'            =>  Transaction::STATUS_PENDING
            ]);

        return $this;
    }

    public function updateTransaction()
    {
        $this->model->update([
            'status'    => '',
            'remarks'   => ''
        ]);
    }
}
