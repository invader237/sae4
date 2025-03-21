<?php
class User {
    private $id;
    private $firstName;
    private $name;
    private $birthDate;
    private $email;
    private $pwd;
    private $idTitle;

    public function __construct($id, $firstName, $name, $birthDate, $email, $pwd, $idTitle) {
        $this->id = $id;
        $this->firstName = $firstName;
        $this->name = $name;
        $this->birthDate = $birthDate;
        $this->email = $email;
        $this->pwd = $pwd;
        $this->idTitle = $idTitle;
    }

    public function getId() {
        return $this->id;
    }

    public function getFirstName() {
        return $this->firstName;
    }

    public function getName() {
        return $this->name;
    }

    public function getBirth() {
        return $this->birthDate;
    }

    public function getEmail() {
        return $this->email;
    }

    public function getPwd() {
        return $this->pwd;
    }

    public function getTitle() {
        return $this->idTitle;
    }

    public function setId($id) {
        $this->id = $id;
    }

    public function setFirstName($firstName) {
        $this->firstName = $firstName;
    }

    public function setName($name) {
        $this->name = $name;
    }

    public function setBirth($birthDate) {
        $this->birthDate = $birthDate;
    }

    public function setEmail($email) {
        $this->email = $email;
    }

    public function setPwd($pwd) {
        $this->pwd = $pwd;
    }

    public function setTitle($idTitle) {
        $this->idTitle = $idTitle;
    }

}

