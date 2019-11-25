<?php

namespace App\Validator;

use App\Validator;

abstract class AbstractValidator
{
    protected $data;
    protected $validator;

    public function __construct(array $data)
    {
        Validator::lang('fr');
        $this->validator = new Validator($data);
        $this->data = $data;
    }

    public function validate(): bool
    {
        return $this->validator->validate();
    }

    public function errors()
    {
        return $this->validator->errors();
    }
}