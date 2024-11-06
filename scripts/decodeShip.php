
<?php
    require_once "include/includeClasses.php";
    global $cnx;

    global $ship_count;

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
    Ship::clear_ship_db();

    // Create all ships as an object
    foreach ($json_data as $ship_dict) {
        $ship_obj = new Ship($ship_dict["id"], $ship_dict["name"], $ship_dict["camp"], $ship_dict["speed_kmh"], $ship_dict["capacity"]);

        // Add the ship to the DB
        $ship_obj->add_ship_to_db();

        // Display the number of done ships
        $ship_count++;
        echo "<script type='text/javascript'> document.getElementById('ship_counter').innerHTML = '".$ship_count."'; </script>";
    }


