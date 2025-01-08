<?php
    global $departure;
    global $arrival;
?>

<script>
    function showCart() {
        var cart = document.querySelector('.cartDetail');
        if (cart.style.display === 'block') {
            cart.style.display = 'none';
        } else {
            cart.style.display = 'block';
        }
    }
</script>

<div class="cart" onclick="showCart()">
    <img src="cart/cart.png" width="40">

    <div class="cartDetail" onclick="showCart()">
        <?php
        if (isset($_COOKIE['cart'])) {
            $cart = unserialize($_COOKIE['cart']);
            echo "<div class='closeCart' onclick='showCart()'>X</div><br><br>";
            if (count($cart) == 0) {
                echo '<p><b>Your cart is empty</b></p>';
            }
            else {
                echo "<b>You have ".count($cart)." items in your cart</b><br>";
                echo "<a href='checkout.php'><b>Checkout</b></a><br>";
                foreach ($cart as $item) {
                    $ship = Ship::getShipFromName($item->getShip());
                    ?>
                    <div class="cartItem">
                        <div class="cartItemDeparture"><?php
                            echo "<b>From : ".$item->getDeparture()."</b>";
                            ?></div>
                        <div class="cartItemArrival"><?php
                            echo "<b>To : ".$item->getArrival()."</b>";
                            ?></div>
                        <div class="cartItemShip"><?php
                            // get ship name from database
                            echo "<b>With : ".$item->getShip()."</b>";
                            ?></div>
                        <div class="cartItemQuantity"><?php
                            // input type number changing quantity
                            echo "<b>Quantity : ".$item->getQuantity()."</b>";
                            ?>
                            <form action="cart/changeQuantity.php" method="get">
                                <input type="hidden" name="id" value="<?php echo $item->getId(); ?>">
                                <input type="number" name="quantity" value="<?php echo $item->getQuantity(); ?>" min="1" max="<?php echo $ship->getCapacity()?>">
                                <input type="submit" value="Change">
                            </form>
                        </div>
                        <div class="cartItemDelete">
                            <a href="cart/deleteCartItem.php?id=<?php echo $item->getId(); ?>"><b>Delete</b></a>
                        </div>
                    </div>
                    <?php
                }
                echo "<a href='cart/deleteCart.php'><b>Empty cart</b></a>";
            }
        } else {
            echo '<p><b>Your cart is empty</b></p>';
        }
        ?>
    </div>
</div>