<?php
require_once('./app/DAO/UserDAO.php');
require_once('./app/core/Connexion.php');

class UserService {
    public static function getUser($idUser) {
        $db = Database::getConnection();
        $userDAO = new UserDAO($db);
        $user = $userDAO->getUser($idUser);
        return $user;
    }
}
