<?php
require_once __DIR__ . '/../models/Cart.php';
require_once __DIR__ . '/../models/Order.php';
require_once __DIR__ . '/../models/Product.php';

class CheckoutController {
    private $cart;
    private $order;
    private $productModel;

    public function __construct($conn) {
        $this->cart = new Cart();
        $this->order = new Order($conn);
        $this->productModel = new Product($conn);
    }

    public function view() {
        $cart_items = $this->cart->getCart();
        include __DIR__ . '/../views/checkout/checkout.php';
    }

    public function process() {
        // later
    }
}