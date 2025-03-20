<?php

class Product {
    private $id;
    private $label;
    private $description;
    private $price;
    private $urlImage;
    private $idCategory;

    public function __construct($id, $label, $description, $price, $urlImage, $idCategory) {
        $this->id = $id;
        $this->label = $label;
        $this->description = $description;
        $this->price = $price;
        $this->urlImage = $urlImage;
        $this->idCategory = $idCategory;
    }

    public function getId() {
        return $this->id;
    }

    public function getLabel() {
        return $this->label;
    }

    public function getDescription() {
        return $this->description;
    }

    public function getPrice() {
        return $this->price;
    }

    public function getUrlImage() {
        return $this->urlImage;
    }

    public function getIdCategory() {
        return $this->idCategory;
    }

    public function setId($id) {
        $this->id = $id;
    }

    public function setLabel($label) {
        $this->label = $label;
    }

    public function setDescription($description) {
        $this->description = $description;
    }

    public function setPrice($price) {
        $this->price = $price;
    }

    public function setUrlImage($urlImage) {
        $this->urlImage = $urlImage;
    }

    public function setIdCategory($idCategory) {
        $this->idCategory = $idCategory;
    }

    public function setCouleur($couleur) {
        $this->couleur = $couleur;
    }

    public function setTaille($taille) {
        $this->taille = $taille;
    }

    public function setCouleur($couleur) {
        $this->couleur = $couleur;
    }

    public function setTaille($taille) {
        $this->taille = $taille;
    }

    public function toArray() {
        return [
            "id"   => $this->getId(),
            "label"  => $this->getLabel(),
            "description"  => $this->getDescription(),
            "price"         => $this->getPrice(),
            "urlImage"    => $this->getUrlImage(),
            "idCategory" => $this->getIdCategory()
        ];
    }
}
