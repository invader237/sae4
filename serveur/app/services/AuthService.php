<?php
require_once('./app/lib/jwt/JWT.php');
require_once('./app/lib/jwt/Key.php');

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

require_once('./app/core/VarEnv.php');

require_once('./app/DAO/UserDAO.php');
require_once('./app/entity/User.php');
require_once('./app/core/Connexion.php');

class AuthService {

    public static function login($email, $pwd) {
        $db = Database::getConnection();
        $userDAO = new UserDAO($db);
        $user = $userDAO->getUserByEmail($email);

        if ($user === null) {
            return null;
        }

        $hashedPwd = hash('sha256', $pwd);

        if ($hashedPwd !== $user->getPwd()) {
            return null; 
        }

        try {
            $jwt = JWT::encode([
                "iat" => time(),
                "exp" => time() + 3600,
                "id" => $user->getId(),
            ], SECURITY_SALT, 'HS256');
            return $jwt;
        } catch (Exception $e) {
            return null;
        }
    }

    public static function register($firstName, $name, $birthDate, $email, $pwd, $idTitle) {
        $db = Database::getConnection();
        $userDAO = new UserDAO($db);
        $user = new User(0, $firstName, $name, $birthDate, $email, hash('sha256', $pwd), $idTitle);
        $userDAO->createUser($user);
    }
}
