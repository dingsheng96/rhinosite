<?php

namespace App\Helpers;

use App\Models\User;
use App\Models\Order;
use App\Models\Package;
use App\Models\Product;
use App\Models\Project;
use App\Models\UserDetail;
use App\Models\Transaction;

class Status
{
    public static function instance()
    {
        return new self();
    }

    public function activeStatus(): array
    {
        return [
            'active' => __('labels.active'),
            'inactive'  => __('labels.inactive')
        ];
    }

    public function transactionStatus(): array
    {
        return [
            Transaction::STATUS_PENDING => __('labels.pending'),
            Transaction::STATUS_SUCCESS => __('labels.success'),
            Transaction::STATUS_FAILED => __('labels.failed'),
        ];
    }

    public function orderStatus(): array
    {
        return [
            Order::STATUS_PENDING => __('labels.pending'),
            Order::STATUS_PAID => __('labels.paid'),
            Order::STATUS_CANCELLED => __('labels.cancelled'),
        ];
    }

    public function verificationStatus(): array
    {
        return [
            UserDetail::STATUS_PENDING => 'Pending',
            UserDetail::STATUS_APPROVED => 'Approved',
            UserDetail::STATUS_REJECTED => 'Rejected'
        ];
    }

    public function projectStatus(): array
    {
        return [
            Project::STATUS_PUBLISHED => __('labels.published'),
            Project::STATUS_DRAFT => __('labels.draft'),
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
            'paid' => [
                'text' => __('labels.paid'),
                'class' => 'badge badge-pill badge-lg badge-success'
            ],
            'rejected' => [
                'text' => __('labels.rejected'),
                'class' => 'badge badge-pill badge-lg badge-danger'
            ],
            'cancelled' => [
                'text' => __('labels.cancelled'),
                'class' => 'badge badge-pill badge-lg badge-danger'
            ],
            'approved' => [
                'text' => __('labels.approved'),
                'class' => 'badge badge-pill badge-lg badge-success'
            ],
            'published' => [
                'text' => __('labels.published'),
                'class' => 'badge badge-pill badge-lg badge-success'
            ],
            'draft' => [
                'text' => __('labels.draft'),
                'class' => 'badge badge-pill badge-lg badge-primay'
            ],
            'expired' => [
                'text' => __('labels.expired'),
                'class' => 'badge badge-pill badge-lg badge-danger'
            ],
            'boosting' => [
                'text' => __('labels.boosting'),
                'class' => 'badge badge-pill badge-lg badge-success'
            ],
            'incoming' => [
                'text' => __('labels.incoming'),
                'class' => 'badge badge-pill badge-lg badge-warning'
            ]
        ];

        return $labels[$status];
    }
}
