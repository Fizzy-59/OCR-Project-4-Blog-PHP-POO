<?php


namespace App\Table;

use App\Table\Exception\NotFoundException;
use Exception;
use PDO;

abstract class Table
{

    protected $pdo;
    protected $table = null;
    protected $class = null;

    public function __construct(PDO $pdo)
    {
        if ($this->table === null)
        {
            throw new Exception("La class " . get_class($this) . " n'a pas de propriété \$table.");
        }
        if ($this->class === null)
        {
            throw new Exception("La class " . get_class($this) . " n'a pas de propriété \$class.");
        }
        $this->pdo = $pdo;
    }


    /**
     * Find a registration against the primary key
     *
     * @param  int $id
     * @return mixed
     * @throws NotFoundException
     */
    public function find (int $id)
    {
        $query = $this->pdo->prepare('SELECT * FROM ' . $this->table . ' WHERE id = :id');
        $query->execute(['id' => $id]);
        $query->setFetchMode(PDO::FETCH_CLASS, $this->class);
        $result = $query->fetch();
        if ($result === false)
        {
            throw new NotFoundException($this->table, $id);
        }
        return $result;
    }


    /**
     * Check if a enrollment already exists at the table
     *
     * @param  string $field
     * @param  $value
     * @param  int|null $except
     * @return bool
     */
    public function exists (string $field, $value, ?int $except = null): bool
    {
        $sql = "SELECT COUNT(id) FROM {$this->table} WHERE $field = ?";
        $params = [$value];

        // Check all except current id
        if ($except !== null) {
            $sql .= " AND id != ?";
            $params[] = $except;
        }
        $query = $this->pdo->prepare($sql);
        $query->execute($params);
        return (int)$query->fetch(PDO::FETCH_NUM)[0] > 0;
    }


    /**
     * Find all recorded in table
     */
    public function all(): array
    {
        $sql = "SELECT * FROM {$this->table}";
        return $this->pdo->query($sql, PDO::FETCH_CLASS, $this->class)->fetchAll();
    }

    /**
     * @param  int $id
     * @throws Exception
     */
    public function delete(int $id)
    {
        $query = $this->pdo->prepare("DELETE FROM {$this->table} WHERE id = ?");
        $ok = $query->execute([$id]);
        if ($ok === false)
        {
            throw new Exception("Impossible de supprimer l'enrengistrement $id dans la table {$this->table}");
        };
    }

    /**
     * @param  array $data
     * @return int
     * @throws Exception
     */
    public function create(array $data): int
    {
        $sqlFields = [];

        // Dynamic generation for query sql params
        foreach ($data as $key => $value)
        {
            $sqlFields[] = "$key = :$key";
        }

        $query = $this->pdo->prepare(
            "INSERT INTO {$this->table} SET " . implode(', ', $sqlFields));

        $ok = $query->execute($data);

        if ($ok === false)
        {
            throw new Exception("Impossible de créer l'enrengistrement dans la table {$this->table}");
        };

        return (int) $this->pdo->lastInsertId();
    }

    /**
     * @param  array $data
     * @param  int $id
     * @throws Exception
     */
    public function update(array $data, int $id)
    {
        $sqlFields = [];

        foreach ($data as $key => $value)
        {
            $sqlFields[] = "$key = :$key";
        }

        $query = $this->pdo->prepare(
            "UPDATE {$this->table} SET " . implode(', ', $sqlFields) . " WHERE id = :id")
        ;

        $ok = $query->execute(array_merge($data, ['id' => $id]));

        if ($ok === false)
        {
            throw new Exception("Impossible de modifier l'enrengistrement dans la table {$this->table}");
        };

    }


    /**
     * @param  string $sql
     * @return array
     */
    public function queryAndFetchAll (string $sql): array
    {
        return $this->pdo->query($sql, PDO::FETCH_CLASS, $this->class)->fetchAll();
    }
}
