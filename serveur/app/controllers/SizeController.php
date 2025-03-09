<?php
require_once('./app/services/SizeService.php');

class SizeController {
    public static function getAllSizes() {
        header('Content-Type: application/json');

        $sizes = SizeService::getAllSizes();

        if ($sizes) {
            $sizesArray = array_map(fn($size) => $size->toArray(), $sizes);
            echo json_encode(["data" => $sizesArray], JSON_UNESCAPED_UNICODE);
        } else {
            http_response_code(500);
            echo json_encode(["message" => "Une erreur interne est survenue. Veuillez réessayer plus tard."]);
        }
    }

    public static function getSizeById($id) {
        header('Content-Type: application/json');

        $size = SizeService::getSizeById($id);

        if ($size) {
            echo json_encode(["data" => $size->toArray()], JSON_UNESCAPED_UNICODE);
        } else {
            http_response_code(404);
            echo json_encode(["message" => "Taille non trouvée."]);
        }
    }
}