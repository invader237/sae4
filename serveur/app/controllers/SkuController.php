<?php
require_once('./app/services/SkuService.php');

class SkuController {
    public static function getSkusByColorAndSize() {
        header('Content-Type: application/json');

        $id = $_GET['id'] ?? null;  
        $idColor = $_GET['idColor'] ?? null;
        $idSize = $_GET['idSize'] ?? null;

        $sku = SkuService::getSkusByColorAndSize($id, $idColor, $idSize);
        if ($sku) {
            echo json_encode(["data" => $sku->toArray()], JSON_UNESCAPED_UNICODE);
        } else {
            http_response_code(404);
            echo json_encode(["message" => "SKU non trouvé."]);
        }
    }
}
