<?php
require_once('./app/entity/Product.php');
require_once('./app/entity/Color.php');
require_once('./app/entity/Size.php');

class ProductDetail {
    private Product $product;
    private Color $color;
    private Size $size;

    public function __construct(Product $product, Color $color, Size $size) {
        $this->product = $product;
        $this->color = $color;
        $this->size = $size;
    }

    public function getProduct() {
        return $this->product;
    }

    public function getColor() {
        return $this->color;
    }

    public function getSize() {
        return $this->size;
    }

    public function setProduct(Product $product) {
        $this->product = $product;
    }

    public function setColor(Color $color) {
        $this->color = $color;
    }

    public function setSize(Size $size) {
        $this->size = $size;
    }

    public function toArray() {
        return [
            'product' => $this->product->toArray(),
            'color' => $this->color->toArray(),
            'size' => $this->size->toArray(),
        ];
    }

}
