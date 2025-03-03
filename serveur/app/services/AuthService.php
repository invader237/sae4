<?php
require_once('./app/lib/jwt/JWT.php');
require_once('./app/lib/jwt/Key.php');

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

require_once('./app/DAO/UserDAO.php');
require_once('./app/entity/User.php');
require_once('./app/core/Connexion.php');

class AuthService {

    public static function login($id, $password) {
        $db = Database::getConnection();
        $userDAO = new UserDAO($db);
        $user = $userDAO->getUserById($id);

        if ($user === null) {
            return null;
        }

        $hashedPassword = hash('sha256', $password);

        if ($hashedPassword !== $user->getMdp()) {
            return null; 
        }

        try {
            $jwt = JWT::encode([
                "iss" => "http://localhost:8000",
                "aud" => "http://localhost:8000",
                "iat" => time(),
                "exp" => time() + 3600,
                "id" => $id
            ], "testkey", 'HS256');
            return $jwt;
        } catch (Exception $e) {
            return null;
        }
    }
}
