<?php

namespace App\Support\Services;

class TransactionService extends BaseService
{
    public function __construct()
    {
        parent::__construct(Transaction::class);
    }

    public function storeData()
    {
        $this->storeTransaction();

        return $this;
    }

    public function storeTransaction()
    {
        //
    }
}
