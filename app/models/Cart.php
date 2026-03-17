<?php

class Cart {

    public function add($product_id, $qty) {
        if (!isset($_SESSION['cart'])) {
            $_SESSION['cart'] = [];
        }

        if (isset($_SESSION['cart'][$product_id])) {
            $_SESSION['cart'][$product_id] += $qty;
        } else {
            $_SESSION['cart'][$product_id] = $qty;
        }
    }

    public function remove($product_id) {
        if (isset($_SESSION['cart'][$product_id])) {
            unset($_SESSION['cart'][$product_id]);
        }
    }

    public function getCart() {
        return $_SESSION['cart'] ?? [];
    }

    public function clear() {
        unset($_SESSION['cart']);
    }
}