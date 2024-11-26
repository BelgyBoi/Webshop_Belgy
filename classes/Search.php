<?php
namespace WebshopBelgy;

require '../vendor/autoload.php';

use WebshopBelgy\Database;

class ProductSearch {
    private $conn;

    public function __construct($conn) {
        $this->conn = $conn;
    }

    public function searchProducts($query) {
        $statement = $this->conn->prepare(
            'SELECT p.id, p.name, p.main_image_url, p.price 
            FROM products p 
            JOIN categories c ON p.category_id = c.id
            WHERE p.name LIKE :query OR p.description LIKE :query OR c.name LIKE :query
            LIMIT 10'
        );
        $statement->bindValue(':query', '%' . $query . '%');
        $statement->execute();
        return $statement->fetchAll(\PDO::FETCH_ASSOC);
    }
}

if (isset($_GET['query'])) {
    $query = $_GET['query'];
    $conn = Database::getConnection();
    $productSearch = new ProductSearch($conn);
    $results = $productSearch->searchProducts($query);
    echo json_encode($results);
}
?>
