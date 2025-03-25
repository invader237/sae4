<?php
require_once('./app/services/CartService.php');
require_once('./app/DAO/OrderDAO.php');

class OrderService {
    public static function validateOrder($idUser, $idPayment, $idDelivery, $deliveryAddress) {
        $db = Database::getConnection();
        $orderDAO = new OrderDAO($db);

        $currentDate = date('Y-m-d H:i:s');
        $cart = CartService::getCart($idUser);
        $cartProducts = $cart->getProducts();

        foreach ($cartProducts as &$productEntry) {
            $unitPrice = self::computeUnitPrice($productEntry);
            $productEntry['product']->getProduct()->setPrice($unitPrice);
        }

        $order = new Order( -1, $idUser, $idPayment, $idDelivery, $deliveryAddress, $currentDate, $cartProducts);

        $order = $orderDAO->createOrder($order);

        CartService::removeAllProduct($idUser);

        return $order;
    }

    public static function computeUnitPrice($product) {
        $price = $product['product']->getProduct()->getPrice();

        $colorDiscount = $product['product']->getColor()->getDiscount();
        $sizeDiscount = $product['product']->getSize()->getDiscount();

        return $price - $colorDiscount - $sizeDiscount;
    }
}
