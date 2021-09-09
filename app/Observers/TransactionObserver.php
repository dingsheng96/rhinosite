<?php

namespace App\Observers;

use App\Models\Transaction;

class TransactionObserver
{
    /**
     * Handle the transaction "created" event.
     *
     * @param  \App\Model\Transaction  $transaction
     * @return void
     */
    public function created(Transaction $transaction)
    {
        //
    }

    /**
     * Handle the transaction "updated" event.
     *
     * @param  \App\Model\Transaction  $transaction
     * @return void
     */
    public function updated(Transaction $transaction)
    {
        //
    }

    /**
     * Handle the transaction "deleted" event.
     *
     * @param  \App\Model\Transaction  $transaction
     * @return void
     */
    public function deleted(Transaction $transaction)
    {
        $transaction->transactionDetails()->delete();
        $transaction->userSubscription()->delete();
    }

    /**
     * Handle the transaction "restored" event.
     *
     * @param  \App\Model\Transaction  $transaction
     * @return void
     */
    public function restored(Transaction $transaction)
    {
        //
    }

    /**
     * Handle the transaction "force deleted" event.
     *
     * @param  \App\Model\Transaction  $transaction
     * @return void
     */
    public function forceDeleted(Transaction $transaction)
    {
        //
    }
}
