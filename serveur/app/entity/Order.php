<?php
class Order {
    private $id;
    private $idUser;
    private $idPayment;
    private $idDelivery;
    private $deliveryAddress;
    private $date;
    private array $products; 

    function __construct($id, $idUser, $idPayment, $idDelivery, $deliveryAddress, $date, $products = []) {
        $this->id = $id;
        $this->idUser = $idUser;
        $this->idPayment = $idPayment;
        $this->idDelivery = $idDelivery;
        $this->deliveryAddress = $deliveryAddress;
        $this->date = $date;
        $this->products = $products; 
    }

    public function getIdOrder() {
        return $this->id;
    }

    public function getIdUser() {
        return $this->idUser;
    }

    public function getIdPayment() {
        return $this->idPayment;
    }

    public function getIdDelivery() {
        return $this->idDelivery;
    }

    public function getDeliveryAddress() {
        return $this->deliveryAddress;
    }

    public function getDate() {
        return $this->date;
    }

    public function getProducts(): array {
        return $this->products;
    }

    public function setIdOrder(int $id): void {
        $this->id = $id;
    }

    public function setIdUser(int $idUser): void {
        $this->idUser = $idUser;
    }

    public function setIdPayment(int $idPayment): void {
        $this->idPayment = $idPayment;
    }

    public function setIdDelivery(int $idDelivery): void {
        $this->idDelivery = $idDelivery;
    }

    public function setDeliveryAddress(string $deliveryAddress): void {
        $this->deliveryAddress = $deliveryAddress;
    }

    public function setDate(string $date): void {
        $this->date = $date;
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
            "idPayment" => $this->idPayment,
            "idDelivery" => $this->idDelivery,
            "deliveryAddress" => $this->deliveryAddress,
            "date" => $this->date,
            "products" => $productArray
        ];
    }

}
