<?php
    require_once("include/setupPDO.php");
    require_once "./include/includeClasses.php";

    if (is_null($_GET["Departure"]) || is_null($_GET["Destination"])) {
        Tool::add_search_log("Palpatine","", "",false,"empty_fields");
        header('location: ./index.php?error=empty_fields');
    } else {
        $departure = $_GET['Departure'];
        $destination = $_GET['Destination'];

        if (!Planet::check_if_present($departure) || !Planet::check_if_present($destination)) {
            Tool::add_search_log("Palpatine",$departure, $destination,false,"invalid_planets");
            header('location: ./index.php?error=invalid_planets');
        }

        else if ($departure == $destination) {
            Tool::add_search_log("Palpatine",$departure, $destination,false,"same_fields");
            header('location: ./index.php?error=same_fields');
        }

        else {
            Tool::add_search_log("Palpatine",$departure, $destination,true,"");
            header('location: ./travel.php?Departure='.$departure.'&Destination='.$destination);
        }
    }
