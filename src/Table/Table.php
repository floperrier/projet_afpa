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

    public function __construct(PDO $pdo)
    {
        if ($this->table === null) {
            throw new \Exception("La classe " . get_class($this) . " n'a pas de propriété \$table");
        }
        if ($this->table === null) {
            throw new \Exception("La classe " . get_class($this) . " n'a pas de propriété \$class");
        }
        $this->pdo = $pdo;
    }

    public function find(int $id)
    {
        $query = $this->pdo->prepare("SELECT * FROM {$this->table} WHERE id = :id");
        $query->execute(['id' => $id]);
        $query->setFetchMode(PDO::FETCH_CLASS,$this->class);
        $result = $query->fetch();
        if ($result === false) {
            throw new NotFoundException($this->table,$id);
        }
        return $result;
    }

    public function exists(string $field, string $value, ?int $except = null): bool
    {
        $sql = "SELECT count(id) FROM {$this->table} WHERE $field = ?";
        $params = [$value];
        if ($except != null) {
            $sql .= " AND id != ?";
            $params[] = $except;
        }
        $query = $this->pdo->prepare($sql);
        $query->execute($params);
        return $query->fetch(PDO::FETCH_NUM)[0] > 0;
    }
}