<?php
include('../class/cart.php');
if (isset($_COOKIE['cart']) && isset($_GET['departure']) && isset($_GET['arrival']) && isset($_GET['ship'])) {
    $cart = unserialize($_COOKIE['cart']);
    $itemId = $_GET['departure'].';'.$_GET['arrival'].';'.$_GET['ship'];
    // if cart contains the same item, increment quantity
    foreach ($cart as $item) {
        if ($item->getId() == $itemId) {
            $item->addQuantity();
            setcookie('cart', serialize($cart), time() + 7200, '/');
            header('Location: ' . $_SERVER['HTTP_REFERER']);
            exit();
        }
    }
    // if cart does not contain the same item, add it
    $cart[] = new cart($itemId, $_GET['departure'], $_GET['arrival'], $_GET['ship'], 1);
    setcookie('cart', serialize($cart), time() + 7200, '/');
}
header('Location: ' . $_SERVER['HTTP_REFERER']);