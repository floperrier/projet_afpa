<?php

namespace App\Security;

use Exception;

class ForbiddenException extends Exception
{
    protected $message;
    
    public function __construct()
    {
        $this->message = "Accès interdit, connectez-vous pour accéder à cette page";
    }
}