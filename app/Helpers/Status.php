<?php

namespace App\Helpers;

class Status
{
    public static function instance()
    {
        return new self();
    }

    public function activationStatus(): array
    {
        return [
            'active' => 'Active',
            'inactive'  => 'Inactive'
        ];
    }

    public function transactionStatus(): array
    {
        return [
            'pending' => 'Pending',
            'failed' => 'Failed',
            'success' => 'Success'
        ];
    }

    public function orderStatus(): array
    {
        return [
            'pending' => 'Pending',
            'failed' => 'Failed',
            'success' => 'Success',
            'confirmed' => 'Confirmed',
            'rejected' => 'Rejected'
        ];
    }
}
