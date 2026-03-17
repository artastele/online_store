<h1>Your Cart</h1>

<?php if($cart_items): $total = 0; ?>
    <?php foreach($cart_items as $id => $qty):
        $product = $GLOBALS['conn']->query("SELECT * FROM products WHERE id=$id")->fetch_assoc();
        $subtotal = $product['price'] * $qty;
        $total += $subtotal;
    ?>
    <div>
        <h3><?= $product['name'] ?></h3>
        <p>₱<?= $product['price'] ?> x <?= $qty ?> = ₱<?= $subtotal ?></p>
        <a href="/online_store_mvc/public/cart/remove?product_id=<?= $id ?>">Remove</a>
    </div>
    <?php endforeach; ?>

    <h3>Total: ₱<?= $total ?></h3>

    <form action="/online_store_mvc/public/checkout/process" method="POST">
        <button type="submit">Checkout</button>
    </form>
<?php else: ?>
    <p>Cart is empty</p>
<?php endif; ?>