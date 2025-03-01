<?php
class ExempleController {
    public static function index() {
        header('Content-Type: application/json');
        echo json_encode(["message" => "Exemple de endpoint"]);
    }
}
