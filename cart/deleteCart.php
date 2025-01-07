<?php
if (isset($_COOKIE['cart'])) {
    setcookie('cart', serialize([]), time() + 7200, '/');
}
header('Location: ' . $_SERVER['HTTP_REFERER']);