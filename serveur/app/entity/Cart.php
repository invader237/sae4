<?php
require_once('./app/entity/Product.php');

class Cart {
    private $id;
    private $idUser;
    private array $products = []; 

    public function __construct($id, $idUser, $products = []) {
        $this->id = $id;
        $this->idUser = $idUser;
        $this->products = $products; 
    }

    public function getIdCart() {
        return $this->id;
    }

    public function getIdUser() {
        return $this->idUser;
    }

    public function getProducts(): array {
        return $this->products;
    }

    public function setIdCart(int $id): void {
        $this->id = $id;
    }

    public function setIdUser(int $idUser): void {
        $this->idUser = $idUser;
    }

    public function setProducts(array $products): void {
        $this->products = $products;
    }


    public function toArray(): array {
        $productArray = [];

        foreach ($this->products as $idProduct => $item) {
            $productArray[] = [
                'product' => $item['product']->toArray(), 
                'quantity' => $item['quantity']
            ];
        }

        return [
            "id" => $this->id,
            "idUser" => $this->idUser,
            "products" => $productArray
        ];
    }
}
?>
