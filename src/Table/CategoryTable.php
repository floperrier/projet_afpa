<?php

namespace App\Table;

use PDO;
use App\Model\Category;
use App\PaginatedQuery;

final class CategoryTable extends Table
{
    protected $table = "category";
    protected $class = Category::class;

    public function hydratePosts(array $posts): void
    {
        $postsById = [];
        foreach ($posts as $post) {
            $postsById[$post->getId()] = $post;
        }
        $categories = $this->pdo
            ->query("SELECT c.*, pc.post_id FROM post_category pc
                    JOIN category c ON pc.category_id = c.id
                    WHERE pc.post_id IN (" . implode(',',array_keys($postsById)) . ")")
            ->fetchAll(PDO::FETCH_CLASS,$this->class);
        
        foreach($categories as $category) {
            $postsById[$category->getPostId()]->addCategory($category);
        }
    }

    public function create(Category $category)
    {
        $query = $this->pdo->prepare("INSERT INTO {$this->table} (name,slug) VALUES (:name,:slug)");
        $ok = $query->execute([
            "name" => $category->getName(),
            "slug" => $category->getSlug()
        ]);
        if ($ok === false) {
            throw new Exception("La création de la catégorie a échoué");
        }
        $category->setId((int)$this->pdo->lastInsertId());
    }

    public function update(Category $category)
    {
        $query = $this->pdo->prepare("UPDATE {$this->table} SET name = :name, slug = :slug WHERE id = :id");
        $ok = $query->execute([
            "id" => $category->getId(),
            "name" => $category->getName(),
            "slug" => $category->getSlug()
        ]);
        if ($ok === false) {
            throw new Exception("La modification de la catégorie {$category->getId()} a échoué");
        }
    }

    public function delete(int $id): void
    {
        $query = $this->pdo->prepare("DELETE FROM {$this->table} WHERE id = ?");
        $ok = $query->execute([$id]);
        if ($ok === false) {
            throw new Exception("La suppression de la catégorie $id a échoué");
        }
    }

    public function findPaginated()
    {
        $pagination = new PaginatedQuery(
            "SELECT count(id) FROM {$this->table}",
            "SELECT * FROM {$this->table}",
            Category::class
        );
        $categories = $pagination->getItems();
        return [$categories,$pagination];
    }
}