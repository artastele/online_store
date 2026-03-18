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
    <h1>Order History</h1>
    <form method="GET" class="checkout-form" style="max-width: 300px;">
    <div class="form-group">
        <label>Filter by Status:</label>
        <select name="status">
        <option value="">All</option>
        <option value="pending">Pending</option>
        <option value="paid">Paid</option>
        <option value="failed">Failed</option>
        <option value="cancelled">Cancelled</option>
        </select>
    </div>
    <button type="submit" class="btn">Filter</button>
    </form>

    <div class="orders-grid">
    <?php foreach ($orders as $o): ?>
    <div class="order-card">
        <div class="order-header">
            <h3>Order #<?php echo htmlspecialchars($o['id']); ?></h3>
            <span class="status status-<?php echo htmlspecialchars($o['payment_status']); ?>"><?php echo htmlspecialchars($o['payment_status']); ?></span>
        </div>
        <div class="order-details">
            <p><strong>Total:</strong> ₱<?php echo htmlspecialchars($o['total_amount']); ?></p>
        </div>
        <div class="order-actions">
            <a href="view?id=<?php echo $o['id']; ?>" class="btn">View Details</a>
            <?php if ($o['payment_status'] == 'pending'): ?>
            <a href="../checkout?order_id=<?php echo $o['id']; ?>" class="btn btn-primary">Pay Now</a>
            <?php endif; ?>
        </div>
    </div>
    <?php endforeach; ?>
    </div>

    <a href="../" class="btn btn-secondary">Back to Products</a>
</main>

<footer>
    <div class="container">
        <p>&copy; 2026 Online Store. All rights reserved.</p>
    </div>
</footer>