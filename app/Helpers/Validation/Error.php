<?php

namespace App\Helpers\Validation;

class Error
{
    private $errors = [];

    public function __construct($var = null) {}

    public function get()
    {
        return $this->errors;
    }
    public function add($key, $value)
    {
        if (!array_key_exists($key, $this->errors)) {
            $this->errors[$key] = [];
        }
        $this->errors[$key][] = $value;
    }
    public function hasErrors()
    {
        return count($this->errors) > 0;
    }
}
