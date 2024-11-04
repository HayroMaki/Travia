
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

    //Clear the ship table
    ship::clear_ship_db();

    // Create all ships as an object
    foreach ($json_data as $ship) {
        $ship_obj = new ship($ship["id"], $ship["name"], $ship["camp"], $ship["speed_kmh"], $ship["capacity"]);

        // Add the ship to the DB
        $ship_obj->add_ship_to_db();
    }


