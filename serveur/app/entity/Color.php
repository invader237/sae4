<?php
class Color {
    private $id;
    private $label;
    private $discount;
    private $urlImage;

    public function __construct($id, $label, $discount, $urlImage) {
        $this->id = $id;
        $this->label = $label;
        $this->discount = $discount;
        $this->urlImage = $urlImage;
    }

    public function getId() {
        return $this->id;
    }

    public function getLabel() {
        return $this->label;
    }

    public function getDiscount() {
        return $this->discount;
    }

    public function getUrlImage() {
        return $this->urlImage;
    }

    public function setId($id) {
        $this->id = $id;
    }

    public function setLabel($label) {
        $this->label = $label;
    }

    public function setDiscount($discount) {
        $this->discount = $discount;
    }

    public function setUrlImage($urlImage) {
        $this->urlImage = $urlImage;
    }

    public function toArray() {
        return array(
            'id' => $this->id,
            'label' => $this->label,
            'discount' => $this->discount,
            'urlImage' => $this->urlImage
        );
    }

}
