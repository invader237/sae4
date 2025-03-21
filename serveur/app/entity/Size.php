<?php
class Size {
    private $id;
    private $label;
    private $discount;

    public function __construct($id, $label, $discount) {
        $this->id = $id;
        $this->label = $label;
        $this->discount = $discount;
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

    public function setId($id) {
        $this->id = $id;
    }

    public function setLabel($label) {
        $this->label = $label;
    }

    public function setDiscount($discount) {
        $this->discount = $discount;
    }

    public function toArray() {
        return array(
            'id' => $this->id,
            'label' => $this->label,
            'discount' => $this->discount
        );
    }
}
