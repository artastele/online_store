<h1>Products</h1>

<?php foreach($products as $p): ?>
<div>
    <h3><?= $p['name'] ?></h3>
    <p><?= $p['description'] ?></p>
    <p>₱<?= $p['price'] ?></p>

    <form action="/online_store_mvc/public/cart/add" method="POST">
        <input type="hidden" name="product_id" value="<?= $p['id'] ?>">
        <input type="number" name="quantity" value="1" min="1">
        <button type="submit">Add to Cart</button>
    </form>
</div>
<?php endforeach; ?>

<a href="/online_store_mvc/public/cart">View Cart</a>