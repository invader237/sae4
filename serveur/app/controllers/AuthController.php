<?php
require_once('./app/services/AuthService.php');

class AuthController {
    public static function login() {
        header('Content-Type: application/json');

        $id = $_POST['id'] ?? null;
        $password = $_POST['password'] ?? null;

        if (!$id || !$password) {
            http_response_code(400);
            echo json_encode(["message" => "ID et mot de passe requis"]);
            return;
        }

        $jwt = AuthService::login($id, $password);

        if ($jwt) {
            echo json_encode(["token" => $jwt]);
        } else {
            http_response_code(401);
            echo json_encode(["message" => "Email ou mot de passe incorrect"]);
        }
    }

    public static function register() {
        header('Content-Type: application/json');

        $prenom = $_POST['prenom'] ?? null;
        $nom = $_POST['nom'] ?? null;
        $date_naissance = $_POST['date_naissance'] ?? null;
        $email = $_POST['email'] ?? null;
        $mdp = $_POST['mdp'] ?? null;
        $id_civilite = $_POST['id_civilite'] ?? null;

        if (!$prenom || !$nom || !$date_naissance || !$email || !$mdp || !$id_civilite) {
            http_response_code(400);
            echo json_encode(["message" => "Tous les champs sont requis"]);
            return;
        }

        AuthService::register($prenom, $nom, $date_naissance, $email, $mdp, $id_civilite);

        echo json_encode(["message" => "Utilisateur créé"]);

    }


}
