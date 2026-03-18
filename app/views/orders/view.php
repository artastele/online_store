<link rel="stylesheet" href="/online_store_mvc/public/assets/css/style.css">
<header>
    <nav class="container">
        <h1>Online Store</h1>
        <div>
            <a href="/online_store_mvc/public/">Home</a>
            <a href="/online_store_mvc/public/cart">Cart</a>
            <a href="/online_store_mvc/public/orders/history">Orders</a>
        </div>
    </nav>
</header>

<main class="container">
    <h1>Order Details</h1>
    <div class="checkout-form">
        <p><strong>Order Number:</strong> <?php echo htmlspecialchars($order['id']); ?></p>
        <p><strong>Total Amount:</strong> ₱<?php echo htmlspecialchars($order['total_amount']); ?></p>
        <p><strong>Payment Status:</strong> <?php echo htmlspecialchars($order['payment_status']); ?></p>
        <p><strong>Payment Reference:</strong> <?php echo htmlspecialchars($order['payment_reference'] ?: 'N/A'); ?></p>

        <h2>Items Bought</h2>
        <ul>
        <?php foreach ($items as $item): ?>
        <li><?php echo htmlspecialchars($item['name']); ?> - ₱<?php echo htmlspecialchars($item['price']); ?> x <?php echo htmlspecialchars($item['quantity']); ?> = ₱<?php echo htmlspecialchars($item['price'] * $item['quantity']); ?></li>
        <?php endforeach; ?>
        </ul>
    </div>

    <a href="history" class="btn btn-secondary">Back to Order History</a>
</main>

<footer>
    <div class="container">
        <p>&copy; 2026 Online Store. All rights reserved.</p>
    </div>
</footer>