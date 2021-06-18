<?php

namespace App\Helpers;

class Message
{
    public static function instance()
    {
        return new self();
    }

    public function format(string $action, string $module, string $status = 'fail'): string
    {
        return __('messages.' . $action . '.' . $status, ['module' => $module]);
    }
}
