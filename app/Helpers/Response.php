<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Storage;

class Response
{
    public $code;
    public $message = 'Ok';
    public $data = [];
    public $status = 'success';

    public static function instance()
    {
        return new self();
    }

    public function withStatusCode(string $status_code_prefix, string $status_code_suffix)
    {
        $this->code = $this->getCode(strtolower($status_code_prefix), strtolower($status_code_suffix));

        return $this;
    }

    public function withMessage(string $message = 'Ok', bool $flash = false)
    {
        $this->message = $message;

        if ($flash) {
            request()->session()->flash($this->status, $message);
        }

        return $this;
    }

    public function withData(array $data = [])
    {
        $this->data = $data;

        return $this;
    }

    public function withStatus(string $status = 'fail')
    {
        $this->status = ($status == 'success') ? true : false;

        return $this;
    }

    public function sendJson()
    {
        return response()->json([
            'status'    =>  $this->status,
            'code'      =>  $this->code,
            'message'   =>  $this->message,
            'data'      =>  $this->data
        ]);
    }

    protected function getCode(string $prefix_path, string $suffix_path): int
    {
        $file       =   Storage::disk('local')->get('code.json');

        $contents   =   json_decode($file, true);

        $prefix     =   strval(data_get($contents, $prefix_path));

        $suffix     =   str_pad(data_get($contents, $suffix_path), 3, '0', STR_PAD_LEFT);

        $code       =   $prefix . $suffix;

        return (int) $code;
    }
}
