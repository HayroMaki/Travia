<?php
    require_once("include/setupPDO.php");
    require_once "./include/includeClasses.php";

    // Check that the fields aren't null,
    // if not, head back to index with the empty_fields error :
    if (is_null($_GET["Departure"]) || is_null($_GET["Destination"])) {
        // Add a failed search log :
        Tool::add_search_log("Palpatine","", "",false,"empty_fields");
        header('location: ./index.php?error=empty_fields');
    } else {
        $departure = $_GET['Departure'];
        $destination = $_GET['Destination'];

        // Check that the fields are valid (in the database),
        // if not, head back to index with the invalid_planets error :
        if (!Planet::check_if_present($departure) || !Planet::check_if_present($destination)) {
            // Add a failed search log :
            Tool::add_search_log("Palpatine",$departure, $destination,false,"invalid_planets");
            header('location: ./index.php?error=invalid_planets');
        }

        // Check that the planets are not the same,
        // if they are, head back to index with the same_fields error :
        else if ($departure == $destination) {
            // Add a failed search log :
            Tool::add_search_log("Palpatine",$departure, $destination,false,"same_fields");
            header('location: ./index.php?error=same_fields');
        }

        // Every test cleared, head to the travel page with the valid departure and destination :
        else {
            // Add a successful search log :
            Tool::add_search_log("Palpatine",$departure, $destination,true,"");
            header('location: ./travel.php?Departure='.$departure.'&Destination='.$destination);
        }
    }
