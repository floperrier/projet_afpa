<?php

namespace App;

class ObjectHelper
{
    public static function hydrate($objet, array $data, array $properties)
    {
        foreach ($properties as $property) {
            $method = 'set' . str_replace(' ','',ucwords(str_replace('_',' ',$property)));
            $objet->$method($data[$property]);
        }
    }
}