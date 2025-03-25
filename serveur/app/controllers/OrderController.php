<?php
require_once('./app/core/AuthMiddleware.php');
require_once('./app/services/OrderService.php');

class OrderController {
    public static function validateOrder() {
        header('Content-Type: application/json');

        $body = file_get_contents('php://input');
        $data = json_decode($body, true);

        $idUser = AuthMiddleware::getUser();

        $idPayment = $data['idPayment'] ?? null;
        $idDelivery = $data['idDelivery'] ?? null;
        $deliveryAddress = $data['deliveryAddress'] ?? null;

        if (!$idPayment || !$idDelivery || !$deliveryAddress) {
            http_response_code(400);
            echo json_encode(["message" => "Tous les champs sont requis"]);
            return;
        }

        $order = OrderService::validateOrder($idUser, $idPayment, $idDelivery, $deliveryAddress);

        if ($order) {
            echo json_encode(["data" => $order->toArray()], JSON_UNESCAPED_UNICODE);
        } else {
            http_response_code(500);
            echo json_encode(["message" => "Une erreur interne est survenue. Veuillez réessayer plus tard."]);
        }

    } 
}
