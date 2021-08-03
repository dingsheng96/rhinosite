<?php

namespace App\Support\Services;

use App\Models\Transaction;
use App\Models\TransactionDetail;
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
        $this->model->transaction_no    =   $this->request->get('trans_no', $this->generateReportNo(Transaction::class, 'transaction_no', Transaction::REPORT_PREFIX));
        $this->model->currency_id       =   $this->parent->currency_id;
        $this->model->amount            =   $this->parent->grand_total;
        $this->model->payment_method_id =   $this->request->get('payment_method');
        $this->model->status            =   Transaction::STATUS_PENDING;

        $this->parent->transaction()->save($this->model);

        $this->setModel($this->model);

        return $this;
    }

    public function setTransactionStatus(string $status = Transaction::STATUS_PENDING)
    {
        $this->model->status = $status;

        if ($this->model->isDirty()) {
            $this->model->save();
        }

        return $this;
    }

    public function storeTransactionDetails()
    {
        $tran_details = new TransactionDetail();
        $tran_details->remark = $this->request->get('Remark');
        $tran_details->status = ((int) $this->request->get('Status')) ? Transaction::STATUS_SUCCESS : Transaction::STATUS_FAILED;
        $tran_details->auth_code = $this->request->get('AuthCode');
        $tran_details->description = $this->request->get('ErrDesc');
        $tran_details->ipay_transaction_id = $this->request->get('TransId');
        $tran_details->subscription_reference = $this->request->get('RefNo');

        $this->model->transactionDetails()->save($tran_details);

        return $this;
    }
}
