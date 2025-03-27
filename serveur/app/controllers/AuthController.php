<?php
require_once('./app/services/AuthService.php');
require_once('./app/core/AuthMiddleware.php');

class AuthController {
    public static function login() {
        header('Content-Type: application/json');

        $body = file_get_contents('php://input');
        $data = json_decode($body, true); 

        $email = $data['email'] ?? null;
        $password = $data['password'] ?? null;

        if (!$email || !$password) {
            http_response_code(400);
            echo json_encode(["message" => "ID et mot de passe requis"], status: 400);
            return;
        }

        $jwt = AuthService::login($email, $password);

        if ($jwt) {
            echo json_encode(["token" => $jwt]);
        } else {
            http_response_code(401);
            echo json_encode(["message" => "Email ou mot de passe incorrect"]);
        }
    }

    public static function register() {
        header('Content-Type: application/json');

        $body = file_get_contents('php://input');
        $data = json_decode($body, true); 

        $prenom = $data['prenom'] ?? null;
        $nom = $data['nom'] ?? null;
        $date_naissance = $data['date_naissance'] ?? null;
        $email = $data['email'] ?? null;
        $mdp = $data['mdp'] ?? null;
        $id_civilite = $data['id_civilite'] ?? null;

        if (!$prenom || !$nom || !$date_naissance || !$email || !$mdp || !$id_civilite) {
            http_response_code(400);
            echo json_encode(["message" => "Tous les champs sont requis"]);
            return;
        }

        AuthService::register($prenom, $nom, $date_naissance, $email, $mdp, $id_civilite);

        echo json_encode(["message" => "Utilisateur créé"]);

    }

    public static function changePassword() {
        header('Content-Type: application/json');

        $body = file_get_contents('php://input');
        $data = json_decode($body, true); 

        $oldPassword = $data['oldPassword'] ?? null;
        $newPassword = $data['newPassword'] ?? null;

        $idUser=AuthMiddleware::getUser();

        if (!$idUser || !$oldPassword || !$newPassword) {
            http_response_code(400);
            echo json_encode(["message" => "Ancien mot de passe et nouveau mot de passe requis"]);
            return;
        }

        $jwt = AuthService::changePassword($idUser, $oldPassword, $newPassword);

        if ($jwt) {
            echo json_encode(["token" => $jwt]);
        } else {
            http_response_code(401);
            echo json_encode(["message" => "Email ou mot de passe incorrect"]);
        }
    }


}
