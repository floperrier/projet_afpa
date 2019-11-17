<?php

namespace App;

use PDO;

class Connection
{
    public static function getPDO(): PDO
    {
        return new PDO("mysql:host=localhost;dbname=blog_project","root","root",[
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
        ]);
    }
}