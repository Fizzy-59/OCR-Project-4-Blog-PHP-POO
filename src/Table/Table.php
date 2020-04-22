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

}