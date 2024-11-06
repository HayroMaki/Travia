<?php
    require_once "include/includeClasses.php";
    global $cnx;

    global $planet_count;
    global $trip_count;

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
    Planet::clear_planet_db();

    // Clear the trip table
    Trip::clear_trip_db();

    // Create all planets as an object
    foreach ($json_data as $planet) {
        if (isset($planet['Image'])) $image = $planet['Image']; else $image = "no_image.png";
        if (isset($planet["Coord"])) $coord = $planet["Coord"]; else $coord = null;
        if (isset($planet["SunName"])) $sun_name = $planet["SunName"]; else $sun_name = null;
        if (isset($planet["SubGridCoord"])) $sub_grid_coord = $planet["SubGridCoord"]; else $sub_grid_coord = null;

        $planet_obj = new Planet(
            $planet["Name"], $image,
            $coord, $planet["X"], $planet["Y"],
            $sun_name,
            $sub_grid_coord, $planet["SubGridX"], $planet["SubGridY"],
            $planet["Region"], $planet["Sector"], $planet["Suns"], $planet["Moons"],
            $planet["Position"], $planet["Distance"], $planet["LengthDay"], $planet["LengthYear"],
            $planet["Diameter"], $planet["Gravity"]);

        // Add the planet to the DB
        $planet_obj->add_planet_to_db();

        // Display the number of done planets as well as the percentage of BDD completion
        $planet_count++;
        echo "<script type='text/javascript'> document.getElementById('planet_counter').innerHTML = '".$planet_count."'; </script>";
        echo "<script type='text/javascript'> document.getElementById('percentage').innerHTML = '".ceil(($planet_count/5444)*100)."'; </script>";

        // Get the planet id from the last auto increment
        $planet_id = Tool::get_last_ai_id();

        // Create all trips coming from the planet
        $trips_dict = $planet["trips"];

        if (!($trips_dict === null)) {
            $days = ["Primeday","Centaxday","Taungsday","Zhellday","Benduday"];

            // Check every day
            foreach ($days as $day) {
                if (isset($trips_dict[$day])) {

                    // Check every trip that day
                    foreach ($trips_dict[$day] as $trip) {

                        // Create the trip as an object
                        $trip_obj = new Trip($planet_id, $trip["destination_planet_id"][0], $day, $trip["departure_time"][0], $trip["ship_id"][0]);

                        // Add the trip to DB
                        $trip_obj->add_trip_to_db();

                        // Display the number of done trips
                        $trip_count++;
                        echo "<script type='text/javascript'> document.getElementById('trip_counter').innerHTML = '".$trip_count."'; </script>";
                    }
                }
            }
        }
    }

