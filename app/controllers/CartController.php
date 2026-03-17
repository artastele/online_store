<?php
require_once __DIR__ . '/../models/Cart.php';
require_once __DIR__ . '/../models/Product.php';

class CartController {
    private $cart;
    private $productModel;

    public function __construct($conn) {
        $this->cart = new Cart();
        $this->productModel = new Product($conn);
    }

    public function add() {
        $this->cart->add($_POST['product_id'], $_POST['quantity']);
        header("Location: /online_store_MVC/public/cart");
        exit;
    }

    public function remove() {
        $this->cart->remove($_GET['product_id']);
        header("Location: /online_store_MVC/public/cart");
        exit;
    }

    public function view() {
        $cart_items = $this->cart->getCart();
        include __DIR__ . '/../views/cart/cart.php';
    }
}