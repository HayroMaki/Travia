global$cnx; <!--
    This is not an include but not a real page either,
    It is a kind of transition page that checks the validity of the entered planets,
    And returns an error back to the search page or the travel.
-->
<?php
session_start();
    // Check that the user is connected :
    if (!isset($_SESSION["email"])) {
        header("Location:login.php");
    }

    global $cnx;
    require_once("include/setupPDO.php");
    require_once "./include/includeClasses.php";
    //Get the id of the planets
    $departure = $_GET['Departure'];
    $destination = $_GET['Destination'];
    $id_dep = Planet::get_id_from_name($departure);
    $id_end = Planet::get_id_from_name($destination);

    //We will check if the travel is in the cache
    $option = $_GET['option'] ?? "";
    $filter1 = $_GET['filter1'] ?? null;
    $filter2 = $_GET['filter2'] ?? null;
    $filter3 = $_GET['filter3'] ?? null;
    $array = array();
    if (!$filter1 == null){
        array_push($array, $filter1);
    }
    if (!$filter2 == null){
        array_push($array, $filter2);
    }
    if (!$filter3 == null){
        array_push($array, $filter3);
    }
    $len = count($array);
    if ($len == 1) $filters="[$filter1]";
    if ($len == 2) $filters="[$filter1,$filter2]";
    if ($len == 3) $filters="[$filter1,$filter2,$filter3]";

    $check = Travel::check_travel($id_dep,$id_end,$option,$filters);
    print_r($check);

    // Check that the fields aren't null,
    // if not, head back to index with the empty_fields error :
    if (is_null($_GET["Departure"]) || is_null($_GET["Destination"])) {
        // Add a failed search log :
        Tool::add_search_log("Palpatine","", "",false,"empty_fields");
        header('location: ./index.php?error=empty_fields');

    } else if (!isset($_GET['filter1']) && !isset($_GET['filter2']) && !isset($_GET['filter3'])) {
        Tool::add_search_log("Palpatine","", "",false,"empty_options");
        header("location: ./index.php?error=option");

    } else {
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

        //No error but no travel
        else if ($check == -1) {
            header("location: ./travel-search/launch.php?filter1=".$filter1."&filter2=".$filter2."&filter3=".$filter3."&option=".$option."&dep=".$id_dep."&dest=".$id_end);
        }

        // No error but already a travel
        else {
            // Add a successful search log :
            Tool::add_search_log("Palpatine",$departure, $destination,true,"");
            header("location: ./travel.php?travel=$check");
        }



    }
