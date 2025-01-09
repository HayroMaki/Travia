global$cnx; <!--
    This is not an include but not a real page either,
    It is a kind of transition page that checks the validity of the entered planets,
    And returns an error back to the search page or the travel.
-->
<?php
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
    $sql_cache = "SELECT * FROM travel WHERE departure = :departure AND destination = :destination;";
    $req = $cnx->prepare($sql_cache);
    $req->bindParam(":departure", $departure, PDO::PARAM_STR);
    $req->bindParam(":destination", $destination, PDO::PARAM_STR);
    $req->execute();

    // Check that the fields aren't null,
    // if not, head back to index with the empty_fields error :
    if (is_null($_GET["Departure"]) || is_null($_GET["Destination"])) {
        // Add a failed search log :
        Tool::add_search_log("Palpatine","", "",false,"empty_fields");
        //header('location: ./index.php?error=empty_fields');
    } else if (!isset($_GET['filter1']) && !isset($_GET['filter2']) && !isset($_GET['filter3'])) {
        //header("location: ./index.php?error=option");
    } else {
        // Check that the fields are valid (in the database),
        // if not, head back to index with the invalid_planets error :
        if (!Planet::check_if_present($departure) || !Planet::check_if_present($destination)) {
            // Add a failed search log :
            Tool::add_search_log("Palpatine",$departure, $destination,false,"invalid_planets");
            //header('location: ./index.php?error=invalid_planets');
        }

        // Check that the planets are not the same,
        // if they are, head back to index with the same_fields error :
        else if ($departure == $destination) {
            // Add a failed search log :
            Tool::add_search_log("Palpatine",$departure, $destination,false,"same_fields");
            //header('location: ./index.php?error=same_fields');
        }

        //No error but no travel
        else if ($req->fetchAll() == array()) {
            print_r("Launch");
            header("location: ./travel-search/launch.php?filter1=".$filter1."&filter2=".$filter2."&filter3=".$filter3."&option=".$option."&dep=".$id_dep."&dest=".$id_end);
        }

        // Every test cleared, head to the travel page with the valid departure and destination :
        else {
            // Add a successful search log :
            Tool::add_search_log("Palpatine",$departure, $destination,true,"");
            $ship = Ship::get_every_ship()[0]->getName();
            //header('location: ./travel.php?Departure='.$departure.'&Destination='.$destination.'&Ship='.$ship);
        }



    }
