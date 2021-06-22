<?php

namespace App\Helpers;

use App\Models\User;
use App\Models\Registration;

class Status
{
    public static function instance()
    {
        return new self();
    }

    public function accountStatus(): array
    {
        return [
            User::STATUS_ACTIVE => 'Active',
            User::STATUS_INACTIVE  => 'Inactive'
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

    public function registrationStatus(): array
    {
        return [
            Registration::STATUS_PENDING => 'Pending',
            Registration::STATUS_APPROVED => 'Approved',
            Registration::STATUS_REJECTED => 'Rejected'
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
            'approved' => [
                'text' => __('labels.approved'),
                'class' => 'badge badge-pill badge-lg badge-success'
            ],
            'publish' => [
                '1' => [
                    'text' => __('labels.publishing'),
                    'class' => 'badge badge-pill badge-lg badge-success'
                ]
            ]
        ];

        return $labels[$status];
    }
}
