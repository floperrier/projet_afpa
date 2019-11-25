<?php

namespace App\Validator;

use App\Table\PostTable;

class PostValidator extends AbstractValidator
{
    protected $data;
    protected $validator;

    public function __construct(array $data, PostTable $postTable, ?int $postId = null)
    {
        parent::__construct($data);
        $this->validator->rule('required',['name', 'slug']);
        $this->validator->rule('lengthBetween',['name', 'slug'],3,200);
        $this->validator->rule('slug','slug');
        $this->validator->rule(function($field, $value) use($postTable, $postId) {
            return !$postTable->exists($field, $value, $postId);
        }, ['slug','name'])->message('Cette valeur existe déjà pour ce champ');
    }
}