<?php


namespace App\Table;

use App\PaginatedQuery;
use App\Model\Post;
use Exception;

final class PostTable extends Table
{

    protected $table = "post";
    protected $class = Post::class;

    public function updatePost(Post $post): void
    {
        $this->update(
            [
                'id'      => $post->getId(),
                'slug'    => $post->getSlug(),
                'name'    => $post->getName(),
                'content' => $post->getContent(),
                'created_at' => $post->getCreatedAt()->format('Y-m-d H:i:s')
            ], $post->getId())
        ;
    }

    public function createPost(Post $post): void
    {
        $id = $this->create(
            [
                'name'    => $post->getName(),
                'slug'    => $post->getSlug(),
                'content' => $post->getContent(),
                'created_at' => $post->getCreatedAt()->format('Y-m-d H:i:s'),
            ])
        ;

        $post->setId($id);
    }


    public function findPaginated()
    {
        $paginatedQuery = new PaginatedQuery
        (
            "SELECT * FROM {$this->table} ORDER BY created_at DESC",
            "SELECT COUNT(id) FROM post",
            $this->pdo

        );

        $posts = $paginatedQuery->getItems(Post::class);

        (new CategoryTable($this->pdo))->hydratePost($posts);

        return [$posts, $paginatedQuery];

    }

    public function findPaginatedForCategory(int $categoryId)
    {
        $paginatedQuery = new PaginatedQuery
        (
            "SELECT p.*
            FROM {$this->table} p
            JOIN post_category pc on pc.post_id = p.id
            WHERE pc.category_id = {$categoryId}
            ORDER BY created_at DESC",
            "SELECT COUNT(category_id) FROM post_category WHERE category_id = {$categoryId}"
        );

        $posts = $paginatedQuery->getItems(Post::class);

        (new CategoryTable($this->pdo))->hydratePost($posts);

        return [$posts, $paginatedQuery];

    }

}