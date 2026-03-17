<?php

class Order {
    private $conn;

    public function __construct($conn) {
        $this->conn = $conn;
    }

    public function create($user_id, $total, $cart) {
        $status = 'pending';

        $stmt = $this->conn->prepare("INSERT INTO orders (user_id, total_amount, payment_status) VALUES (?, ?, ?)");
        $stmt->bind_param("ids", $user_id, $total, $status);
        $stmt->execute();

        $order_id = $stmt->insert_id;

        foreach ($cart as $id => $qty) {
            $product = $this->conn->query("SELECT * FROM products WHERE id = $id")->fetch_assoc();
            $price = $product['price'];

            $stmt2 = $this->conn->prepare("INSERT INTO order_items (order_id, product_id, quantity, price) VALUES (?, ?, ?, ?)");
            $stmt2->bind_param("iiid", $order_id, $id, $qty, $price);
            $stmt2->execute();
        }

        return $order_id;
    }

    public function updatePaymentStatus($order_id, $status, $reference = '') {
        $stmt = $this->conn->prepare("UPDATE orders SET payment_status = ?, payment_reference = ? WHERE id = ?");
        $stmt->bind_param("ssi", $status, $reference, $order_id);
        $stmt->execute();
    }
}