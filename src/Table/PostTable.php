<?php


namespace App\Table;

use App\PaginatedQuery;
use App\Model\Post;

final class PostTable extends Table
{

    protected $table = "post";
    protected $class = Post::class;

    public function findPaginatedForCategory(int $categoryId)
    {
        $paginatedQuery = new PaginatedQuery
        (
            "SELECT p.*
            FROM post p
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