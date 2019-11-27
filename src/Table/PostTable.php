<?php
namespace App\Table;

use App\PaginatedQuery;
use App\Model\Post;
use Exception;

final class PostTable extends Table
{

    protected $table = "post";
    protected $class = Post::class;

    public function updatePost(Post $post)
    {
        $this->update([
            "name" => $post->getName(),
            "slug" => $post->getSlug(),
            "content" => $post->getContent(),
            "created_at" => $post->getCreatedAt()->format("Y-m-d H:i:s")
        ],$post->getId());
        /* $this->pdo->exec("DELETE FROM post_category WHERE post_id = {$post->getId()}");
        $query = $this->pdo->prepare("INSERT INTO post_category SET category_id = ?, post_id = ?");
        foreach ($categories as $category) {
            $query->execute([$category,$post->getId()]);
        }
        $this->pdo->commit(); */
    }

    public function createPost(Post $post)
    {
        $id = $this->create([
            "name" => $post->getName(),
            "slug" => $post->getSlug(),
            "content" => $post->getContent(),
            "created_at" => $post->getCreatedAt()->format("Y-m-d H:i:s")
        ]);
        $post->setId($id);
        /* $this->pdo->exec("DELETE FROM post_category WHERE post_id = {$post->getId()}");
        $query = $this->pdo->prepare("INSERT INTO post_category SET category_id = ?, post_id = ?");
        foreach ($categories as $category) {
            $query->execute([$category,$post->getId()]);
        } */
    }

    public function attachCategories(int $id, array $categories)
    {
        $this->pdo->exec("DELETE FROM post_category WHERE post_id = {$id}");
        $query = $this->pdo->prepare("INSERT INTO post_category SET category_id = ?, post_id = ?");
        foreach ($categories as $category) {
            $query->execute([$category,$id]);
        }
    }

    public function findPaginated()
    {
        $pagination = new PaginatedQuery(
            "SELECT count(id) FROM {$this->table}",
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
            "SELECT post.* FROM {$this->table}
            INNER JOIN post_category pc ON pc.post_id = post.id
            WHERE pc.category_id = {$categoryId}",
            $this->class
        );
        $posts = $pagination->getItems();
        (new CategoryTable($this->pdo))->hydratePosts($posts);
        return [$posts,$pagination];
    }
}