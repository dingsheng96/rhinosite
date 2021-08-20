<?php

namespace App\Tasks;

use App\Models\Order;
use App\Models\Transaction;
use Illuminate\Support\Facades\DB;
use App\Support\Facades\OrderFacade;
use App\Support\Facades\TransactionFacade;

class FailOverdueTransaction
{
    public function __invoke()
    {
        DB::beginTransaction();

        // cancelling previouse 3 days pending transactions
        $transactions = Transaction::with(['sourceable'])
            ->pending()
            ->whereDate('created_at', '<=', today()->subDays(3)->toDateString())
            ->get();

        try {

            foreach ($transactions as $transaction) {

                $transaction =  TransactionFacade::setModel($transaction)->setTransactionStatus(Transaction::STATUS_FAILED)->getModel();

                if ($transaction->sourceable) {
                    $order = OrderFacade::setModel($transaction->sourceable)->setOrderStatus(Order::STATUS_CANCELLED)->getModel();
                }
            }

            activity()->useLog('task_cancelled_overdue_transactions')
                ->performedOn(new Transaction())
                ->withProperties(['target_id' => $transactions->pluck('id')->toArray()])
                ->log('Successfully Processed Tasks: ' . $transactions->count());

            DB::commit();
        } catch (\Error | \Exception $ex) {

            DB::rollBack();

            activity()->useLog('task_cancelled_overdue_transactions')
                ->performedOn(new Transaction())
                ->withProperties(['target_id' => $transactions->pluck('id')->toArray()])
                ->log('Processed Tasks Revert. Error found: ' . $ex->getMessage());
        }
    }
}
