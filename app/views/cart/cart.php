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
    <?php if (isset($_SESSION['success'])): ?>
    <div class="alert success"><?php echo htmlspecialchars($_SESSION['success']); unset($_SESSION['success']); ?></div>
    <?php endif; ?>
    <?php if (isset($_SESSION['error'])): ?>
    <div class="alert error"><?php echo htmlspecialchars($_SESSION['error']); unset($_SESSION['error']); ?></div>
    <?php endif; ?>

    <h1>Your Cart</h1>

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
                    <p>₱<?= htmlspecialchars($product['price']) ?> each</p>
                    <div class="quantity-controls">
                        <form action="/online_store_mvc/public/cart/update" method="POST">
                        <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
                        <input type="hidden" name="product_id" value="<?= $id ?>">
                        <label>Qty:</label>
                        <input type="number" name="quantity" value="<?= $qty ?>" min="0">
                        <button type="submit" class="btn btn-small">Update</button>
                        </form>
                    </div>
                    <p>Subtotal: ₱<?= htmlspecialchars($subtotal) ?></p>
                </div>
            </div>
            <?php endforeach; ?>
        </div>

        <div class="cart-summary">
            <h3>Order Summary</h3>
            <p>Total: ₱<?= htmlspecialchars($total) ?></p>
            <form action="/online_store_mvc/public/checkout/process" method="POST">
            <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
            <button type="submit" class="btn">Proceed to Checkout</button>
            </form>
        </div>
    </div>
    <?php else: ?>
    <p>Your cart is empty.</p>
    <?php endif; ?>

    <a href="./" class="btn btn-secondary">Continue Shopping</a>
</main>

<footer>
    <div class="container">
        <p>&copy; 2026 Online Store. All rights reserved.</p>
    </div>
</footer>