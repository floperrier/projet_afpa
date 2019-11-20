<?php

namespace App;

use PDO;

class PaginatedQuery
{
    private $queryCount;
    private $query;
    private $classMapping;
    private $pdo;
    private $perPage;
    private $count;
    private $items;

    public function __construct(string $queryCount, string $query, string $classMapping, ?PDO $pdo = null, int $perPage = 12)
    {
        $this->queryCount = $queryCount;
        $this->query = $query;
        $this->classMapping = $classMapping;
        $this->pdo = $pdo ?: Connection::getPDO();
        $this->perPage = $perPage;
    }

    public function getItems(): array
    {
        if ($this->items) {
            return $this->items;
        }
        $currentPage = $this->getCurrentPage();
        $pages = $this->getPages();
        if ($currentPage > $pages) {
            throw new \Exception("Cette page n'existe pas");
        }
        // On récupère les articles à afficher
        $offset = $this->perPage * ($currentPage - 1);
        return $this->items = $this->pdo->query($this->query . " LIMIT {$this->perPage} OFFSET $offset")->fetchAll(PDO::FETCH_CLASS,$this->classMapping);
    }

    private function getCurrentPage(): int
    {
        return URL::getPositiveInt('page',1);
    }

    private function getPages(): int
    {
        if ($this->count === null) {
            $count = (int)$this->pdo->query($this->queryCount)->fetch(PDO::FETCH_NUM)[0];
        }
        return (int)ceil($count / $this->perPage);
    }

    public function previousLink(string $link): ?string
    {
        $currentPage = $this->getCurrentPage();
        if ($currentPage <= 1) return null;
        if ($currentPage > 2) $link .= "?page=" . ($currentPage - 1);
        return <<<HTML
        <a class="btn btn-primary" href="$link">Page précédente</a>
HTML;
    }

    public function nextLink(string $link): ?string
    {
        $currentPage = $this->getCurrentPage();
        $pages = $this->getPages();
        if ($currentPage >= $pages) return null;
        $link .= "?page=" . ($currentPage + 1);
        return <<<HTML
        <a class="btn btn-primary ml-auto" href="$link">Page suivante</a>
HTML;
    }
}