<?php
require_once('./app/core/Connexion.php');
require_once('./app/entity/User.php');

class UserDAO {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    //1 id_utilisateur Primaire	int(11)			Non	Aucun(e)			Modifier Modifier	Supprimer Supprimer	
	//2	prenom	varchar(40)	utf8_bin		Non	Aucun(e)			Modifier Modifier	Supprimer Supprimer	
	//3	nom	varchar(40)	utf8_bin		Non	Aucun(e)			Modifier Modifier	Supprimer Supprimer	
	//4	date_naissance	date			Non	Aucun(e)			Modifier Modifier	Supprimer Supprimer	
	//5	email	varchar(40)	utf8_bin		Non	Aucun(e)			Modifier Modifier	Supprimer Supprimer	
	//6	mdp	varchar(40)	utf8_bin		Non	Aucun(e)			Modifier Modifier	Supprimer Supprimer	
	//7	id_civilite 

    public function getUserById(int $id): ?User {
        $stmt = $this->pdo->prepare('SELECT * FROM UTILISATEUR WHERE id_utilisateur = :id');
        $stmt->execute(['id' => $id]);
        $row = $stmt->fetch();
        if ($row === false) {
            return null;
        }
        return new User($row['id_utilisateur'], $row['prenom'], $row['nom'], $row['date_naissance'], $row['email'], $row['mdp'], $row['id_civilite']);
    }
}
