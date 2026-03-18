<link rel="stylesheet" href="assets/css/style.css">
<header>
    <nav class="container">
        <h1>Online Store</h1>
        <div>
            <a href="./">Home</a>
            <a href="cart">Cart</a>
            <a href="orders/history">Orders</a>
        </div>
    </nav>
</header>

<main class="container">
    <?php if (isset($_SESSION['error'])): ?>
    <div class="alert error"><?php echo htmlspecialchars($_SESSION['error']); unset($_SESSION['error']); ?></div>
    <?php endif; ?>

    <h1>Checkout</h1>

    <?php if($cart_items): $total=0; ?>
    <div class="cart-section">
        <div class="cart-items">
            <?php foreach($cart_items as $id=>$qty):
            $product = $GLOBALS['conn']->query("SELECT * FROM products WHERE id=$id")->fetch_assoc();
            $subtotal = $product['price'] * $qty;
            $total += $subtotal;
            ?>
            <div class="cart-item">
                <img src="assets/images/<?= htmlspecialchars($product['image'] ?: 'default.jpg') ?>" alt="<?= htmlspecialchars($product['name']) ?>">
                <div class="cart-item-details">
                    <h3><?= htmlspecialchars($product['name']) ?></h3>
                    <p>₱<?= htmlspecialchars($product['price']) ?> x <?= htmlspecialchars($qty) ?> = ₱<?= htmlspecialchars($subtotal) ?></p>
                </div>
            </div>
            <?php endforeach; ?>
        </div>

        <div class="checkout-form">
            <h3>Shipping Information</h3>
            <form action="/online_store_mvc/public/checkout/process" method="POST">
            <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
            <div class="form-group">
                <label for="name">Name</label>
                <input type="text" id="name" name="name" required>
            </div>
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" required>
            </div>
            <div class="form-group">
                <label for="phone">Phone</label>
                <input type="tel" id="phone" name="phone" required>
            </div>
            <div class="form-group">
                <label for="address">Address</label>
                <input type="text" id="address" name="address" required>
            </div>
            <h3>Total: ₱<?= htmlspecialchars($total) ?></h3>
            <button type="submit" class="btn">Proceed to Payment</button>
            </form>
        </div>
    </div>
    <?php else: ?>
    <p>Cart is empty</p>
    <?php endif; ?>

    <a href="cart" class="btn btn-secondary">Back to Cart</a>
</main>

<footer>
    <div class="container">
        <p>&copy; 2026 Online Store. All rights reserved.</p>
    </div>
</footer>
