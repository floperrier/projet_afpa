<?php

namespace App\Table\Exception;

use Exception;

class NotFoundException extends Exception
{
    public function __construct(?string $table = null, $value)
    {
        $this->message = "L'enregistrement correspondant à la valeur '$value' n'existe pas dans la table '$table'";
    }
}