<?php
require_once('./app/services/UserService.php');

class UserController {
    public static function getUser() {
        header('Content-Type: application/json');

        $idUser=AuthMiddleware::getUser();

        $user = UserService::getUser($idUser);

        if ($user) {
            echo json_encode(["data" => $user->toArray()], JSON_UNESCAPED_UNICODE);
        } else {
            http_response_code(404);
            echo json_encode(["message" => "Utilisateur introuvable"]);
        }
    }


}
