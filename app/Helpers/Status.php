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

    public function statusLabel(string $status): array
    {
        $labels = [
            'active' => [
                'text' => __('labels.active'),
                'class' => 'badge badge-pill badge-lg badge-success'
            ],
            'inactive' => [
                'text' => __('labels.inactive'),
                'class' => 'badge badge-pill badge-lg badge-danger'
            ],
            'success' => [
                'text' => __('labels.success'),
                'class' => 'badge badge-pill badge-lg badge-success'
            ],
            'pending' => [
                'text' => __('labels.pending'),
                'class' => 'badge badge-pill badge-lg badge-warning'
            ],
            'failed' => [
                'text' => __('labels.failed'),
                'class' => 'badge badge-pill badge-lg badge-danger'
            ],
            'confirmed' => [
                'text' => __('labels.confirmed'),
                'class' => 'badge badge-pill badge-lg badge-primary'
            ],
            'rejected' => [
                'text' => __('labels.rejected'),
                'class' => 'badge badge-pill badge-lg badge-danger'
            ],
        ];

        return $labels[$status];
    }
}
