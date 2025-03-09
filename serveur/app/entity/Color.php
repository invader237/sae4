<?php
class Color {
    private $id_couleur;
    private $libelle;

    public function __construct($id_couleur, $libelle) {
        $this->id_couleur = $id_couleur;
        $this->libelle = $libelle;
    }

    public function getId_couleur(): int {
        return $this->id_couleur;
    }

    public function getLibelle(): string {
        return $this->libelle;
    }

    public function setId_couleur(int $id_couleur) {
        $this->id_couleur = $id_couleur;
    }

    public function setLibelle(string $libelle) {
        $this->libelle = $libelle;
    }

    public function toArray() {
        return [
            "id_couleur"   => $this->getId_couleur(),
            "libelle"  => $this->getLibelle()
        ];
    }
}