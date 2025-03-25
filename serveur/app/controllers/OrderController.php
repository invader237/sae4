<?php
require_once('./app/core/AuthMiddleware.php');
require_once('./app/services/OrderService.php');

class OrderController {
    public static function getAll() {
        header('Content-Type: application/json');

        $idUser = AuthMiddleware::getUser();
        $orders = OrderService::getAll($idUser);

        if ($orders) {
            $orders = array_map(fn($entry) => array_merge(
                $entry['order']->toArray(),
                ['total' => $entry['total']]
            ), $orders);

            echo json_encode(["data" => $orders], JSON_UNESCAPED_UNICODE);
        } else {
            http_response_code(500);
            echo json_encode(["message" => "Une erreur interne est survenue. Veuillez réessayer plus tard."]);
        }
    }

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
