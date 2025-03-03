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
}
