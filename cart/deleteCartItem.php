<?php
include('../class/cart.php');

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    if (isset($_COOKIE['cart'])) {
        $cart = unserialize($_COOKIE['cart']);
        foreach ($cart as $item) {
            if ($item->getId() == $id) {
                unset($cart[array_search($item, $cart)]);
                setcookie('cart', serialize($cart), time() + 7200, '/');
                header('Location: ' . $_SERVER['HTTP_REFERER']);
                exit();
            }
        }
    }
}