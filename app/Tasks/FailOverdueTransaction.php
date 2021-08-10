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

        $today = today()->startOfDay();
        $last_two_days = $today->copy()->subDays(2)->startOfDay();

        // cancelling pending two days transactions
        $transactions = Transaction::with(['sourceable'])->pending()
            ->whereBetween('created_at', [$last_two_days, $today])->get();

        try {

            foreach ($transactions as $transaction) {
                $transaction =  TransactionFacade::setModel($transaction)->setTransactionStatus(Transaction::STATUS_FAILED)->getModel();
                $order = OrderFacade::setModel($transaction->sourceable)->ssetOrderStatus(Order::STATUS_CANCELLED)->getModel();
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
