<?php
require_once __DIR__ . '/../models/Order.php';
require_once __DIR__ . '/../models/Cart.php';

class OrderController {
    private $order;
    private $cart;

    public function __construct($conn) {
        $this->order = new Order($conn);
        $this->cart = new Cart();
    }

    public function success($order_id = null) {
        if ($order_id) {
            // Update status for the specific order
            $this->order->updatePaymentStatus($order_id, 'paid');
            unset($_SESSION['pending_order']);
            unset($_SESSION['paying_order_id']);
            $_SESSION['success'] = 'Order placed successfully!';
            $_SESSION['order_id'] = $order_id;
            if (!isset($_SESSION['paying_order_id'])) {
                $this->cart->clear();
            }
            unset($_SESSION['paying_order_id']);
        } elseif (isset($_SESSION['pending_order'])) {
            $order_data = $_SESSION['pending_order'];
            if (isset($_SESSION['paying_order_id'])) {
                $order_id = $_SESSION['paying_order_id'];
                $this->order->updatePaymentStatus($order_id, 'paid', $_GET['payment_intent_id'] ?? $_GET['reference_number'] ?? '');
                unset($_SESSION['paying_order_id']);
            } else {
                $order_id = $this->order->create($order_data['total'], $order_data['cart']);
                $this->order->updatePaymentStatus($order_id, 'paid', $_GET['payment_intent_id'] ?? $_GET['reference_number'] ?? '');
            }
            unset($_SESSION['pending_order']);
            $_SESSION['success'] = 'Order placed successfully!';
            $_SESSION['order_id'] = $order_id;
            if (!isset($_SESSION['paying_order_id'])) {
                $this->cart->clear();
            }
            unset($_SESSION['paying_order_id']);
        }
        include __DIR__ . '/../views/orders/success.php';
    }

    public function failed($order_id = null) {
        include __DIR__ . '/../views/orders/failed.php';
    }

    public function webhook() {
        $input = file_get_contents('php://input');
        $event = json_decode($input, true);

        if ($event['data']['type'] == 'payment_intent.succeeded') {
            $payment_intent_id = $event['data']['id'];
            $order = $this->order->getOrderByPaymentReference($payment_intent_id);
            if ($order) {
                $this->order->updatePaymentStatus($order['id'], 'paid', $payment_intent_id);
            }
        }
        // Respond with 200
        http_response_code(200);
    }

    public function view() {
        $id = intval($_GET['id'] ?? 0);
        if ($id <= 0) {
            echo "Invalid order ID";
            return;
        }
        $order = $this->order->getOrderById($id);
        if (!$order) {
            echo "Order not found";
            return;
        }
        $items = $this->order->getOrderItems($id);
        include __DIR__ . '/../views/orders/view.php';
    }

    public function history() {
        $status = $_GET['status'] ?? '';
        $orders = $this->order->getAllOrders($status);
        include __DIR__ . '/../views/orders/history.php';
    }
}   