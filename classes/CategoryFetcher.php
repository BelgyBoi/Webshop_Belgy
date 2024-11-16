<?php

namespace WebshopBelgy;

use PDO;

class CategoryFetcher
{
    private $conn;

    public function __construct(PDO $conn)
    {
        $this->conn = $conn;
    }

    public function getCategories()
    {
        $statement = $this->conn->prepare('SELECT * FROM categories');
        $statement->execute();
        return $statement->fetchAll(PDO::FETCH_ASSOC);
    }
}

