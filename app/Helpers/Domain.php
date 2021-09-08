<?php

namespace App\Helpers;

class Domain
{
    protected $config_file = "domain";

    public function getConfig(string $type = null)
    {
        if (!empty($type)) {

            $this->config_file .= ".{$type}";
        }

        return config($this->config_file);
    }
}
