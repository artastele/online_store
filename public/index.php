<?php

require_once '../config/config.php';

/* LOAD MODELS FIRST */
require_once '../app/models/Product.php';
require_once '../app/models/Cart.php';
require_once '../app/models/Order.php';

/* LOAD CONTROLLERS */
require_once '../app/controllers/ProductController.php';
require_once '../app/controllers/CartController.php';
require_once '../app/controllers/CheckoutController.php';
require_once '../app/controllers/OrderController.php';


$request = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

$base = '/online_store_MVC/public';
$request = str_replace($base, '', $request);

if ($request === '' || $request === '/') {

    $controller = new ProductController($conn);
    $controller->index();

}
elseif ($request === '/cart') {

    $controller = new CartController($conn);
    $controller->view();

}
elseif ($request === '/cart/add') {

    $controller = new CartController($conn);
    $controller->add();

}
elseif ($request === '/cart/remove') {

    $controller = new CartController($conn);
    $controller->remove();

}
elseif ($request === '/checkout') {

    $controller = new CheckoutController($conn);
    $controller->view();

}
elseif ($request === '/checkout/process') {

    $controller = new CheckoutController($conn);
    $controller->process();

}
elseif ($request === '/orders/success') {

    $controller = new OrderController($conn);
    $controller->success();

}
else {

    echo "404 Page Not Found";

}