<?php
class Product {
    private $id_produit;
    private $designation;
    private $description;
    private $prix;
    private $url_image;
    private $id_categorie;
    private $sizes = [];
    private $couleurs = [];

    public function __construct($id_produit, $designation, $description, $prix, $url_image, $id_categorie) {
        $this->id_produit = $id_produit;
        $this->designation = $designation;
        $this->description = $description;
        $this->prix = $prix;
        $this->url_image = $url_image;
        $this->id_categorie = $id_categorie;
    }

    public function getId_produit() {
        return $this->id_produit;
    }

    public function getDesignation() {
        return $this->designation;
    }

    public function getDescription() {
        return $this->description;
    }

    public function getPrix() {
        return $this->prix;
    }

    public function getUrl_image() {
        return $this->url_image;
    }

    public function getId_categorie() {
        return $this->id_categorie;
    }

    public function getCouleurs(): array {
        return $this->couleurs;
    }

    public function setId_produit($id_produit) {
        $this->id_produit = $id_produit;
    }

    public function setDesignation($designation) {
        $this->designation = $designation;
    }

    public function setDescription($description) {
        $this->description = $description;
    }

    public function setPrix($prix) {
        $this->prix = $prix;
    }

    public function setUrl_image($url_image) {
        $this->url_image = $url_image;
    }

    public function setId_categorie($id_categorie) {
        $this->id_categorie = $id_categorie;
    }

    public function getSizes(): array {
        return $this->sizes;
    }

    public function setSizes(array $sizes): void {
        $this->sizes = $sizes;
    }

    public function setCouleurs(array $couleurs): void {
        $this->couleurs = $couleurs;
    }

    public function toArray() {
        $sizesArray = array_map(function($size) {
            return [
                "id_taille" => $size->getId(),
                "libelle" => $size->getLibelle()
            ];
        }, $this->getSizes());
        $colorsArray = [];
        foreach ($this->getCouleurs() as $couleur) {
            $colorsArray[] = $couleur->toArray();
        }

        return [
            "id_produit"   => $this->getId_produit(),
            "designation"  => $this->getDesignation(),
            "description"  => $this->getDescription(),
            "prix"         => $this->getPrix(),
            "url_image"    => $this->getUrl_image(),
            "id_categorie" => $this->getId_categorie(),
            "sizes"        => $sizesArray,
            "couleurs"     => $colorsArray
        ];
    }
}
