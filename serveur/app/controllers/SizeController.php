<?php
require_once('./app/services/SizeService.php');

class SizeController {
    public static function getSizesByProductId() {
        header('Content-Type: application/json');

        $id = $_GET['id'] ?? null;

        $sizes = SizeService::getSizesByProductId($id);
        if ($sizes) {
            $sizesArray = array_map(fn($size) => $size->toArray(), $sizes);
            echo json_encode(["data" => $sizesArray], JSON_UNESCAPED_UNICODE);
        } else {
            http_response_code(500);
            echo json_encode(["message" => "Une erreur interne est survenue. Veuillez réessayer plus tard."]);
        }
    }
}