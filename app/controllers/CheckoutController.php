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
        if (isset($_GET['order_id'])) {
            $order_id = intval($_GET['order_id']);
            $order = $this->order->getOrderById($order_id);
            if ($order && $order['payment_status'] == 'pending') {
                $items = $this->order->getOrderItems($order_id);
                $cart_items = [];
                foreach ($items as $item) {
                    $cart_items[$item['product_id']] = $item['quantity'];
                }
                $_SESSION['cart'] = $cart_items; // Override cart with order items
                $_SESSION['paying_order_id'] = $order_id;
            }
        }
        $cart_items = $this->cart->getCart();
        include __DIR__ . '/../views/checkout/checkout.php';
    }

    public function process() {
        if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
            die('CSRF token mismatch');
        }
        // Validate inputs
        $name = trim($_POST['name'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $phone = trim($_POST['phone'] ?? '');
        $address = trim($_POST['address'] ?? '');

        if (empty($name) || empty($email) || empty($phone) || empty($address) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $_SESSION['error'] = 'Please fill all fields correctly.';
            header("Location: /online_store_mvc/public/checkout");
            exit;
        }

        $cart_items = $this->cart->getCart();
        if (!$cart_items) {
            $_SESSION['error'] = 'Cart is empty.';
            header("Location: /online_store_mvc/public/cart");
            exit;
        }
        $total = 0;
        foreach ($cart_items as $id => $qty) {
            if ($qty <= 0) {
                $_SESSION['error'] = 'Invalid quantity.';
                header("Location: /online_store_mvc/public/cart");
                exit;
            }
            $product = $this->productModel->getProductById($id);
            $total += $product['price'] * $qty;
        }

        // Create order first
        if (isset($_SESSION['paying_order_id'])) {
            $order_id = $_SESSION['paying_order_id'];
        } else {
            $order_id = $this->order->create($total, $cart_items);
        }

        // Save order data in session
        $_SESSION['pending_order'] = [
            'cart' => $cart_items,
            'total' => $total,
            'name' => htmlspecialchars($name),
            'email' => htmlspecialchars($email),
            'phone' => htmlspecialchars($phone),
            'address' => htmlspecialchars($address)
        ];

        // Create PayMongo Payment Link
        $amount = intval($total * 100); // in centavos
        $description = 'Order Payment';
        $baseUrl = $this->getBaseUrl();
        $success_url = "$baseUrl/online_store_mvc/public/orders/success/$order_id";
        $failed_url = "$baseUrl/online_store_mvc/public/orders/failed/$order_id";

        $data = [
            'data' => [
                'attributes' => [
                    'amount' => $amount,
                    'currency' => 'PHP',
                    'description' => $description,
                    'redirect' => [
                        'success' => $success_url,
                        'failed' => $failed_url
                    ]
                ]
            ]
        ];

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'https://api.paymongo.com/v1/links');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json',
            'Authorization: Basic ' . base64_encode(PAYMONGO_SECRET . ':')
        ]);
        $response = curl_exec($ch);
        curl_close($ch);

        $result = json_decode($response, true);
        if (isset($result['data']['attributes']['checkout_url'])) {
            header("Location: " . $result['data']['attributes']['checkout_url']);
            exit;
        } else {
            // Log the error for debugging
            $error_log = __DIR__ . '/../../logs/payment_errors.log';
            $error_message = date('Y-m-d H:i:s') . " - Payment link creation failed. Response: " . $response . "\n";
            file_put_contents($error_log, $error_message, FILE_APPEND);

            // Check for specific errors
            if (isset($result['errors'])) {
                $error_details = implode(', ', array_column($result['errors'], 'detail'));
                $_SESSION['error'] = 'Payment link creation failed: ' . $error_details;
            } else {
                $_SESSION['error'] = 'Payment link creation failed. Please check your configuration.';
            }
            header("Location: /online_store_mvc/public/checkout");
            exit;
        }
    }

    private function getBaseUrl() {
        $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
        $host = $_SERVER['HTTP_HOST'];
        return "$protocol://$host";
    }
}