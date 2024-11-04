<?php
    include "class/planet.php";
    global $cnx;
    include "./config.php";

    // Read the JSON file
    $json = file_get_contents("./data/planets_details.json");

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

    // Clear the planet table
    planet::clear_planet_db();

    // Create all planets as an object
    foreach ($json_data as $planet) {
        if (isset($planet['Image'])) $image = $planet['Image']; else $image = "no_image.png";
        if (isset($planet["Coord"])) $coord = $planet["Coord"]; else $coord = null;
        if (isset($planet["SunName"])) $sun_name = $planet["SunName"]; else $sun_name = null;
        if (isset($planet["SubGridCoord"])) $sub_grid_coord = $planet["SubGridCoord"]; else $sub_grid_coord = null;

        $planet_obj = new planet(
            $planet["Name"], $image,
            $coord, $planet["X"], $planet["Y"],
            $sun_name,
            $sub_grid_coord, $planet["SubGridX"], $planet["SubGridY"],
            $planet["Region"], $planet["Sector"], $planet["Suns"], $planet["Moons"],
            $planet["Position"], $planet["Distance"], $planet["LengthDay"], $planet["LengthYear"],
            $planet["Diameter"], $planet["Gravity"]);

        $planet_obj->add_planet_to_db();
    }

