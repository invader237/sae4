<?php
require_once('./app/lib/jwt/JWT.php');
require_once('./app/lib/jwt/Key.php');
require_once('./app/lib/jwt/SignatureInvalidException.php');
require_once('./app/lib/jwt/BeforeValidException.php');
require_once('./app/lib/jwt/ExpiredException.php');

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class AuthMiddleware {
    private static  $secretKey = "security_salt"; 

    public static function getUser() {
        $headers = getallheaders();

        if (!isset($headers['Authorization'])) {
            http_response_code(401);
            echo json_encode([
                "message" => "Token manquant"
            ]);
            exit();
        }

        $token = str_replace('Bearer ', '', $headers['Authorization']); 

        try {
            $decoded = JWT::decode($token, new Key(self::$secretKey, 'HS256'));
            return $decoded;
        } catch (Exception $e) {
            http_response_code(401);
            echo json_encode([
                "message" => "Token invalide"
            ]);
            exit();
        }
    }
}
?>
