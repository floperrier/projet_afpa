<?php

namespace App\Table;

use PDO;
use App\Table\Exception\NotFoundException;

abstract class Table
{
    /**
     * @var PDO
     */
    protected $pdo;

    protected $table = null;
    protected $class = null;

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    public function find(int $id)
    {
        $query = $this->pdo->prepare("SELECT * FROM $this->table WHERE id = :id");
        $query->execute(['id' => $id]);
        $query->setFetchMode(PDO::FETCH_CLASS,$this->class);
        $result = $query->fetch();
        if ($result === false) {
            throw new NotFoundException($this->table,$id);
        }
        return $result;
    }
}