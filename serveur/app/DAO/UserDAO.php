<?php
require_once('./app/core/Connexion.php');
require_once('./app/entity/User.php');

class UserDAO {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
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

<<<<<<< HEAD
    public function getUserByEmail(string $email): ?User {
        $stmt = $this->pdo->prepare('SELECT * FROM UTILISATEUR WHERE email = :email');
        $stmt->execute(['email' => $email]);
        $row = $stmt->fetch();
        if ($row === false) {
            return null;
        }
        return new User($row['id_utilisateur'], $row['prenom'], $row['nom'], $row['date_naissance'], $row['email'], $row['mdp'], $row['id_civilite']);
    }

=======
>>>>>>> 94ab9e9 (:construction:(Auth): Start implementing registration feature)
    public function createUser(User $user): void {
        $stmt = $this->pdo->prepare('INSERT INTO UTILISATEUR (prenom, nom, date_naissance, email, mdp, id_civilite) VALUES (:prenom, :nom, :date_naissance, :email, :mdp, :id_civilite)');
        $stmt->execute([
            'prenom' => $user->getPrenom(),
            'nom' => $user->getNom(),
            'date_naissance' => $user->getDateNaissance(),
            'email' => $user->getEmail(),
            'mdp' => $user->getMdp(),
            'id_civilite' => $user->getIdCivilite()
        ]);
    }
}
