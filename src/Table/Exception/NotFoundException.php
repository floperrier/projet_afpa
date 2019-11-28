<?php

namespace App\Table\Exception;

use Exception;

class NotFoundException extends Exception
{
    public function __construct(?string $table = null, $value, string $field)
    {
        switch($field) {
            case "id":
                $this->message = "L'enregistrement correspondant à l'ID #$value n'existe pas dans la table '$table'";
            break;
            case "username":
                $this->message = "L'enregistrement correspondant à l'utilisateur '$value' n'existe pas dans la table '$table'";
            break;
        }
    }
}