<?php
require_once('./app/services/ColorService.php');

class ColorController {
    public static function getAllColors() {
        header('Content-Type: application/json');

        $colors = ColorService::getAllColors();

        if ($colors) {
            $colorsArray = array_map(fn($color) => $color->toArray(), $colors);
            echo json_encode(["data" => $colorsArray], JSON_UNESCAPED_UNICODE);
        } else {
            http_response_code(500);
            echo json_encode(["message" => "Une erreur interne est survenue. Veuillez réessayer plus tard."]);
        }
    }

    public static function getColorById() {
        header('Content-Type: application/json');

        $body = file_get_contents('php://input');
        $data = json_decode($body, true); 

        $id = $data['id'] ?? null;

        $color = ColorService::getColorById($id);
        if ($color) {
            echo json_encode(["data" => $color->toArray()], JSON_UNESCAPED_UNICODE);
        } else {
            http_response_code(404);
            echo json_encode(["message" => "Couleur non trouvée."]);
        }
    }
}