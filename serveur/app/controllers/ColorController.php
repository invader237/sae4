<?php
require_once('./app/services/ColorService.php');

class ColorController {
    public static function getColorsByProductId() {
        header('Content-Type: application/json');

        $id = $_GET['id'] ?? null;

        $colors = ColorService::getColorsByProductId($id);
        if ($colors) {
            $colorsArray = array_map(fn($color) => $color->toArray(), $colors);
            echo json_encode(["data" => $colorsArray], JSON_UNESCAPED_UNICODE);
        } else {
            http_response_code(500);
            echo json_encode(["message" => "Une erreur interne est survenue. Veuillez réessayer plus tard."]);
        }
    }
}