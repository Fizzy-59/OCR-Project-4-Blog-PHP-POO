<?php


namespace App\Table;

use App\PaginatedQuery;
use App\Model\Post;
use Exception;

final class PostTable extends Table
{

    protected $table = "post";
    protected $class = Post::class;

    public function update(Post $post): void
    {
        $query = $this->pdo->prepare("UPDATE {$this->table} SET name = :name WHERE id = :id");
        $ok = $query->execute(
            [
                'id' => $post->getId(),
                'name' => $post->getName()
            ]);
        if ($ok === false)
        {
            throw new Exception("Impossible de supprimer l'enrengistrement $id dans la table {$this->table}");
        };
    }

    public function delete(int $id): void
    {
        $query = $this->pdo->prepare("DELETE FROM {$this->table} WHERE id = ?");
        $ok = $query->execute([$id]);
        if ($ok === false)
        {
            throw new Exception("Impossible de supprimer l'enrengistrement $id dans la table {$this->table}");
        };
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