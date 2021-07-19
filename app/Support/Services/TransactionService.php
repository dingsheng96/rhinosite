<?php

namespace App\Support\Services;

use App\Models\Order;
use App\Models\Transaction;
use Illuminate\Support\Facades\Http;
use App\Support\Services\BaseService;

class TransactionService extends BaseService
{
    public $signature;

    public function __construct()
    {
        parent::__construct(Transaction::class);
    }

    public function newTransaction()
    {
        $this->model->transaction_no    =   $this->generateReportNo(Transaction::class, 'transaction_no', Transaction::REPORT_PREFIX);
        $this->model->currency_id       =   $this->parent->currency_id;
        $this->model->amount            =   $this->parent->grand_total;
        $this->model->payment_method_id =   $this->request->get('payment_method');
        $this->model->status            =   Transaction::STATUS_PENDING;

        $this->parent->transaction()->save($this->model);

        $this->setModel($this->model);

        return $this;
    }
}
