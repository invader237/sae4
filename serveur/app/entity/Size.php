<?php
class Size {
    private $id_taille;
    private $libelle;

    public function __construct($id_taille, $libelle) {
        $this->id_taille = $id_taille;
        $this->libelle = $libelle;
    }

    public function getId(): int {
        return $this->id_taille;
    }

    public function getLibelle(): string {
        return $this->libelle;
    }

    public function setId_taille(int $id_taille) {
        $this->id_taille = $id_taille;
    }

    public function setLibelle(string $libelle) {
        $this->libelle = $libelle;
    }
}