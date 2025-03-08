<!DOCTYPE html>

<?php
session_start();
// Check that the user is connected :
if (!isset($_SESSION["email"])) {
    header("Location:login.php");
}

// Check or add the cart cookie :
if (!isset($_COOKIE['cart'])) {
    setcookie('cart', serialize([]), time() + 7200, '/');
}

// To prevent the program to stop due to memory usage or execution time :
ini_set('memory_limit', '4096M');
ini_set('max_execution_time', 0);
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Set up the PDO
require_once("include/setupPDO.php");
require_once("include/includeClasses.php");

global $ship_count;
$ship_count = 0;

global $planet_count;
$planet_count = 0;

global $trip_count;
$trip_count = 0;

if (isset($_GET['error'])) {
    if ($_GET['error'] == "empty_fields") {
        $error_msg = "Please fill in all fields.";

    } else if ($_GET['error'] == "invalid_planets") {
        $error_msg = "Please select valid planets.";

    } else if ($_GET['error'] == "same_fields") {
        $error_msg = "Please don't select the same planet as departure and destination.";
    }
}

$planets = json_encode(Planet::get_every_planet_for_map());

if ($planets === false) {
    echo "Erreur d'encodage JSON : " . json_last_error_msg();
    die();
}

// Include cart :
include("cart/cart.php");
include("include/fontSelector.php");
?>

<script>
    loadFont();
</script>

<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Travia</title>
    <link href="index.css?v=<?php echo time(); ?>" rel="stylesheet">
    <link rel="stylesheet" href="cart/cart.css">

    <!-- Leaflet -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"/>
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
</head>
<body>
<?php
include("include/header.inc.php");
?>

<?php

// for evey ticket in the cart

$cart = unserialize($_COOKIE['cart']);
foreach ($cart as $item) {

    echo "<div class='ticket'>";
    echo "<div class='row'>";
    echo "<div class=\"ticket-count\"> Tickets number : ".$item->getQuantity()."</div>";
    echo "<div class=\"route\">".$item->getDeparture()." => ".$item->getArrival()."</div>";
    echo "<div class=\"aboard\"> Aboard : ".$item->getShip()."</div>";
    echo "<div class=\"price\"> Price : Credits </div>";
    echo "</div>
    <hr />";
    echo "<div class=\"details\">";
    echo "<div> Distance : Billions of Km</div>";
    echo "<div> Time : Hours</div>";
    echo "<div><a href='cart/deleteCartItem.php?id=".$item->getId()."'><button>Delete</button></a></div>";
    echo "</div>";
    echo "</div>";

}
?>

<button class='buttonDelete' onclick="showConfirmation()">Delete cart</button>
<button class='buttonCheckout' onclick="showConfirmation_purchase()">Finalise purchase</button>

<div id="confirmation-popup" class="confirmation-popup">
    <div class="popup-content">
        <p>Are you sure you want to delete your cart ? </p>
        <a href="cart/deleteCart.php"><button class="confirm-button" type="submit">Confirm</button></a>
        <button class="cancel-button" type="button" onclick="closeConfirmation()">Cancel</button>
    </div>
</div>

<div id="confirmation-popup-purchase" class="confirmation-popup">
    <div class="popup-content">
        <p>Are you sure you want to confirm you tickets ? </p>
        <a href="cart/confirmCart.php"><button class="confirm-button" type="submit">Confirm</button></a>
        <button class="cancel-button" type="button" onclick="closeConfirmation_purchase()">Cancel</button>
    </div>
</div>

<br>
<br>

<script>
    function showConfirmation() {
        var popup = document.getElementById("confirmation-popup");
        popup.style.display = "block";
    }

    function closeConfirmation() {
        var popup = document.getElementById("confirmation-popup");
        popup.style.display = "none";
    }

    function showConfirmation_purchase() {
        var popup = document.getElementById("confirmation-popup-purchase");
        popup.style.display = "block";
    }

    function closeConfirmation_purchase() {
        var popup = document.getElementById("confirmation-popup-purchase");
        popup.style.display = "none";
    }
</script>

<?php
include("include/footer.inc.php");
require_once("include/background.php")
?>
</body>
</html>