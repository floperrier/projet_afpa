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
            throw new NotFoundException($this->table, $id, "id");
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

    public function all()
    {
        return $this->queryAndFetchAll("SELECT * FROM {$this->class} ORDER BY id DESC");
    }

    public function queryAndFetchAll(string $sql)
    {
        return $this->pdo->query($sql)->fetchAll(PDO::FETCH_CLASS,$this->class);
    }

    public function create(array $data): int
    {
        $sqlFields = [];
        foreach ($data as $key => $value) {
            $sqlFields[] = "$key = :$key";
        }
        $query = $this->pdo->prepare("INSERT INTO {$this->table} SET " . implode(", ", $sqlFields));
        $ok = $query->execute($data);
        if ($ok === false) {
            throw new Exception("La création a échoué");
        }
        return (int)$this->pdo->lastInsertId();
    }

    public function update(array $data, int $id)
    {
        $sqlFields = [];
        foreach ($data as $key => $value) {
            $sqlFields[] = "$key = :$key";
        }
        $query = $this->pdo->prepare("UPDATE {$this->table} SET " . implode(", ", $sqlFields) . " WHERE id = :id");
        $ok = $query->execute(array_merge($data,['id' => $id]));
        if ($ok === false) {
            throw new Exception("La modification du champs $id a échoué");
        }
    }

    public function delete(int $id): void
    {
        $query = $this->pdo->prepare("DELETE FROM {$this->table} WHERE id = ?");
        $ok = $query->execute([$id]);
        if ($ok === false) {
            throw new Exception("La suppression du champs $id a échoué");
        }
    }
}