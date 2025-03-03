<?php
class User {
    private $id_utilisateur;
    private $prenom;
    private $nom;
    private $date_naissance;
    private $email;
    private $mdp;
    private $id_civilite;

    public function __construct($id_utilisateur, $prenom, $nom, $date_naissance, $email, $mdp, $id_civilite) {
        $this->id_utilisateur = $id_utilisateur;
        $this->prenom = $prenom;
        $this->nom = $nom;
        $this->date_naissance = $date_naissance;
        $this->email = $email;
        $this->mdp = $mdp;
        $this->id_civilite = $id_civilite;
    }

    public function getId_utilisateur() {
        return $this->id_utilisateur;
    }

    public function getPrenom() {
        return $this->prenom;
    }

    public function getNom() {
        return $this->nom;
    }

    public function getDate_naissance() {
        return $this->date_naissance;
    }

    public function getEmail() {
        return $this->email;
    }

    public function getMdp() {
        return $this->mdp;
    }

    public function getId_civilite() {
        return $this->id_civilite;
    }

    public function setId_utilisateur($id_utilisateur) {
        $this->id_utilisateur = $id_utilisateur;
    }

    public function setPrenom($prenom) {
        $this->prenom = $prenom;
    }

    public function setNom($nom) {
        $this->nom = $nom;
    }

    public function setDate_naissance($date_naissance) {
        $this->date_naissance = $date_naissance;
    }

    public function setEmail($email) {
        $this->email = $email;
    }

    public function setMdp($mdp) {
        $this->mdp = $mdp;
    }

    public function setId_civilite($id_civilite) {
        $this->id_civilite = $id_civilite;
    }

}

