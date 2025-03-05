<?php
class User {
<<<<<<< HEAD
<<<<<<< HEAD
=======
>>>>>>> f152694 (:recycle:(User): rename user properties for consistency)
    private $idUtilisateur;
    private $prenom;
    private $nom;
    private $dateNaissance;
    private $email;
    private $mdp;
    private $idCivilite;

    public function __construct($idUtilisateur, $prenom, $nom, $dateNaissance, $email, $mdp, $idCivilite) {
        $this->idUtilisateur = $idUtilisateur;
        $this->prenom = $prenom;
        $this->nom = $nom;
        $this->dateNaissance = $dateNaissance;
        $this->email = $email;
        $this->mdp = $mdp;
        $this->idCivilite = $idCivilite;
    }

    public function getIdUtilisateur() {
        return $this->idUtilisateur;
    }

    public function getPrenom() {
        return $this->prenom;
    }

    public function getNom() {
        return $this->nom;
    }

    public function getDateNaissance() {
        return $this->dateNaissance;
    }

    public function getEmail() {
        return $this->email;
    }

    public function getMdp() {
        return $this->mdp;
    }

    public function getIdCivilite() {
        return $this->idCivilite;
    }

    public function setIdUtilisateur($idUtilisateur) {
        $this->idUtilisateur = $idUtilisateur;
    }

    public function setPrenom($prenom) {
        $this->prenom = $prenom;
    }

    public function setNom($nom) {
        $this->nom = $nom;
    }

    public function setDateNaissance($dateNaissance) {
        $this->dateNaissance = $dateNaissance;
    }

    public function setEmail($email) {
        $this->email = $email;
    }

    public function setMdp($mdp) {
        $this->mdp = $mdp;
    }

    public function setIdCivilite($idCivilite) {
        $this->idCivilite = $idCivilite;
    }

}

