<?php
class Delivery{
    private $id;
    private $label;
    private $price;

    public function __construct($id, $label, $price){
        $this->id = $id;
        $this->label = $label;
        $this->price = $price;
    }

    public function getId(){
        return $this->id;
    }

    public function getLabel(){
        return $this->label;
    }

    public function getPrice(){
        return $this->price;
    }

    public function setId($id){
        $this->id = $id;
    }

    public function setLabel($label){
        $this->label = $label;
    }

    public function setPrice($price){
        $this->price = $price;
    }

    public function toArray(){
        return array(
            "id" => $this->id,
            "label" => $this->label,
            "price" => $this->price
        );
    }

}
