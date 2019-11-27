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
            $post->setCategories([]);
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

    public function all(): array
    {
        return $this->queryAndFetchAll("SELECT * FROM {$this->table} ORDER BY id DESC");
    }

    public function list(): array
    {
        $categories = $this->queryAndFetchAll("SELECT * FROM {$this->table} ORDER BY name ASC");
        $results = [];
        foreach ($categories as $category) {
            $results[$category->getId()] = $category->getName();
        }
        return $results;
    }
}