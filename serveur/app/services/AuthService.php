<?php

require_once('./app/lib/jwt/JWT.php');
require_once('./app/lib/jwt/BeforeValidException.php');
require_once('./app/lib/jwt/ExpiredException.php');
require_once('./app/lib/jwt/SignatureInvalidException.php');
require_once('./app/lib/jwt/Key.php');
require_once('./app/lib/jwt/JWTExceptionWithPayloadInterface.php');

use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Firebase\JWT\BeforeValidException;
use Firebase\JWT\ExpiredException;
use Firebase\JWT\SignatureInvalidException;
use Firebase\JWT\JWTExceptionWithPayloadInterface;

class AuthService {
    public static function login($id, $password) {
        // TO DO: Implement auth logic
        try {
            $jwt = JWT::encode([
                "iss" => "http://localhost:8000",
                "aud" => "http://localhost:8000",
                "iat" => time(),
                "exp" => time() + 3600,
                "sub" => 1,
                "id" => $id
            ], "testkey", 'HS256');
            return $jwt;
        } catch (Exception $e) {
            return null;
        }
    }

}

