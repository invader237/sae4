<?php
require_once('./app/DAO/CategoryDAO.php');

class CategoryService {
    public static function getAll() {
        $db = Database::getConnection();
        $categoryDAO = new CategoryDAO($db);
        $categories = $categoryDAO->getAll();
        return $categories;
    }
}
