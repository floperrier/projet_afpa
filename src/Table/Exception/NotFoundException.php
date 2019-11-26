<?php

namespace App\Table\Exception;

use Exception;

class NotFoundException extends Exception
{
    public function __construct(?string $table = null, int $id)
    {
        $this->message = "L'enregistrement correspondant à l'ID #$id n'existe pas dans la table '$table'";
    }
}