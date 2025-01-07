<?php
    include '../class/cart.php';
    if (isset($_COOKIE['cart']) && isset($_GET['id']) && isset($_GET['quantity'])) {
        $cart = unserialize($_COOKIE['cart']);
        foreach ($cart as $item) {
            if ($item->getId() == $_GET['id']) {
                $item->setQuantity($_GET['quantity']);
                setcookie('cart', serialize($cart), time() + 7200, '/');
                header('Location: ' . $_SERVER['HTTP_REFERER']);
                exit();
            }
        }
    }