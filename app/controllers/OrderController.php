<?php
require_once __DIR__ . '/../models/Order.php';

class OrderController {
    private $order;

    public function __construct($conn) {
        $this->order = new Order($conn);
    }

    public function success() {
        include __DIR__ . '/../views/orders/success.php';
    }
}   