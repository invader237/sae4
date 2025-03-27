<?php
require_once('./app/core/Connexion.php');
require_once('./app/entity/User.php');

class UserDAO {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function getUser(int $id): ?User {
        $stmt = $this->pdo->prepare('SELECT * FROM UTILISATEUR, CIVILITE WHERE id_utilisateur = :id AND UTILISATEUR.id_civilite = CIVILITE.id_civilite');
        $stmt->execute(['id' => $id]);
        $row = $stmt->fetch();
        if ($row === false) {
            return null;
        }
        return new User($row['id_utilisateur'], $row['prenom'], $row['nom'], $row['date_naissance'], $row['email'], "", $row['libelle']);
    }

    public function getUserByEmail(string $email): ?User {
        $stmt = $this->pdo->prepare('SELECT * FROM UTILISATEUR WHERE email = :email');
        $stmt->execute(['email' => $email]);
        $row = $stmt->fetch();
        if ($row === false) {
            return null;
        }
        return new User($row['id_utilisateur'], $row['prenom'], $row['nom'], $row['date_naissance'], $row['email'], $row['mdp'], $row['id_civilite']);
    }

    public function getUserById(int $id): ?User {
        $stmt = $this->pdo->prepare('SELECT * FROM UTILISATEUR WHERE id_utilisateur = :id');
        $stmt->execute(['id' => $id]);
        $row = $stmt->fetch();
        if ($row === false) {
            return null;
        }
        return new User($row['id_utilisateur'], $row['prenom'], $row['nom'], $row['date_naissance'], $row['email'], $row['mdp'], $row['id_civilite']);
    }

    public function createUser(User $user): void {
        $stmt = $this->pdo->prepare('INSERT INTO UTILISATEUR (prenom, nom, date_naissance, email, mdp, id_civilite) VALUES (:prenom, :nom, :date_naissance, :email, :mdp, :id_civilite)');
        $stmt->execute([
            'prenom' => $user->getFirstName(),
            'nom' => $user->getName(),
            'date_naissance' => $user->getBirthDate(),
            'email' => $user->getEmail(),
            'mdp' => $user->getPwd(),
            'id_civilite' => $user->getIdTitle()
        ]);
    }

    public function updatePassword(User $user): void {
        $stmt = $this->pdo->prepare('UPDATE UTILISATEUR SET mdp = :mdp WHERE id_utilisateur = :id');
        $stmt->execute([
            'mdp' => $user->getPwd(),
            'id' => $user->getId()
        ]);
    }
}
