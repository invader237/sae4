<?php
require_once('./app/services/CategoryService.php');

class CategoryController {
    public static function getAll() {
        header('Content-Type: application/json');

        $categories = CategoryService::getAll();
        if ($categories) {
            $categoriesArray = array_map(fn($category) => $category->toArray(), $categories);
            echo json_encode(["data" => $categoriesArray], JSON_UNESCAPED_UNICODE);
        } else {
            http_response_code(500);
            echo json_encode(["message" => "Une erreur interne est survenue. Veuillez réessayer plus tard."]);
        }
    }
}
