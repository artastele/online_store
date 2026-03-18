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
    <h1>Products</h1>

    <div class="products-grid">
    <?php foreach($products as $p): ?>
    <div class="product">
        <img src="assets/images/<?= htmlspecialchars($p['image'] ?: 'default.jpg') ?>" alt="<?= htmlspecialchars($p['name']) ?>">
        <div class="product-content">
            <h3><?= htmlspecialchars($p['name']) ?></h3>
            <p><?= htmlspecialchars($p['description']) ?></p>
            <p class="price">₱<?= htmlspecialchars($p['price']) ?></p>

            <form action="/online_store_mvc/public/cart/add" method="POST">
                <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
                <input type="hidden" name="product_id" value="<?= $p['id'] ?>">
                <input type="number" name="quantity" value="1" min="1">
                <button type="submit" class="btn">Add to Cart</button>
            </form>
        </div>
    </div>
    <?php endforeach; ?>
    </div>
</main>

<footer>
    <div class="container">
        <p>&copy; 2026 Online Store. All rights reserved.</p>
    </div>
</footer>