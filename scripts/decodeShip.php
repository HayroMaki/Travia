
<?php
    include "class/ship.php";
    global $cnx;
    include "./config.php";

    // Read the JSON file
    $json = file_get_contents("./data/ships.json");

    // Check if the file was read successfully
    if ($json === FALSE) {
        die('Error reading the JSON file.');
    }

    // Decode the JSON file
    $json_data = json_decode($json, true);

    // Check if the JSON was decoded successfully
    if ($json_data === null) {
        die('Error decoding the JSON file.');
    }

    $ship_list = array();

    // Create the ship
    foreach ($json_data as $ship) {
        $ship_list[] = new ship($ship["id"], $ship["name"], $ship["camp"], $ship["speed_kmh"], $ship["capacity"]);
    }

