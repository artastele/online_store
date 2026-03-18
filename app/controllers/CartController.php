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
        if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
            die('CSRF token mismatch');
        }
        $product_id = intval($_POST['product_id'] ?? 0);
        $qty = intval($_POST['quantity'] ?? 0);
        if ($product_id <= 0 || $qty <= 0) {
            $_SESSION['error'] = 'Invalid product or quantity.';
            header("Location: /online_store_mvc/public/");
            exit;
        }
        $this->cart->add($product_id, $qty);
        $_SESSION['success'] = 'Added to cart!';
        header("Location: /online_store_mvc/public/cart");
        exit;
    }

    public function remove() {
        $product_id = intval($_GET['product_id'] ?? 0);
        if ($product_id <= 0) {
            $_SESSION['error'] = 'Invalid product.';
            header("Location: /online_store_mvc/public/cart");
            exit;
        }
        $this->cart->remove($product_id);
        $_SESSION['success'] = 'Item removed!';
        header("Location: /online_store_mvc/public/cart");
        exit;
    }

    public function update() {
        if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
            die('CSRF token mismatch');
        }
        $product_id = intval($_POST['product_id'] ?? 0);
        $qty = intval($_POST['quantity'] ?? 0);
        if ($product_id <= 0 || $qty < 0) {
            $_SESSION['error'] = 'Invalid product or quantity.';
            header("Location: /online_store_mvc/public/cart");
            exit;
        }
        if ($qty == 0) {
            $this->cart->remove($product_id);
            $_SESSION['success'] = 'Item removed!';
        } else {
            $_SESSION['cart'][$product_id] = $qty;
            $_SESSION['success'] = 'Quantity updated!';
        }
        header("Location: /online_store_mvc/public/cart");
        exit;
    }

    public function view() {
        $cart_items = $this->cart->getCart();
        include __DIR__ . '/../views/cart/cart.php';
    }
}