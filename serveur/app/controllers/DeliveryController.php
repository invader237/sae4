<?php
require_once('./app/services/DeliveryService.php');

class DeliveryController {
    public static function getAll() {
        header('Content-Type: application/json');

        $deliverys = DeliveryService::getAll();

        if ($deliverys) {
            $deliverysArray = array_map(fn($delivery) => $delivery->toArray(), $deliverys);
            echo json_encode(["data" => $deliverysArray], JSON_UNESCAPED_UNICODE);
        } else {
            http_response_code(500);
            echo json_encode(["message" => "Une erreur interne est survenue. Veuillez réessayer plus tard."]);
        }
    }
}
