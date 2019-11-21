<?php
namespace App\Table;

use App\PaginatedQuery;
use App\Model\Post;

final class PostTable extends Table
{

    protected $table = "post";
    protected $class = Post::class;

    public function findPaginated()
    {
        $pagination = new PaginatedQuery(
            "SELECT count(id) FROM post",
            "SELECT * FROM post ORDER BY created_at DESC",
            Post::class
        );
        $posts = $pagination->getItems();
        (new CategoryTable($this->pdo))->hydratePosts($posts);
        return [$posts,$pagination];
    }

    public function findPaginatedForCategory(int $categoryId)
    {
        $pagination = new PaginatedQuery(
            "SELECT count(category_id) FROM post_category pc
            WHERE pc.category_id = {$categoryId}",
            "SELECT post.* FROM post
            INNER JOIN post_category pc ON pc.post_id = post.id
            WHERE pc.category_id = {$categoryId}",
            $this->class
        );
        $posts = $pagination->getItems();
        (new CategoryTable($this->pdo))->hydratePosts($posts);
        return [$posts,$pagination];
    }
}