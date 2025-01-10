<?php
include "../class/Ship.php";
include "../class/Planet.php";
include "../class/Trip.php";
include "../class/Tool.php";
include "../class/cart.php";
include "../class/Travel.php";
global $cnx;
require_once("../include/config_local.php");

//Get the data for the programme
if (isset($_GET['dep']) && isset($_GET['dest']) && isset($_GET['option'])) {
    $id_dep = $_GET['dep'];
    $opt = $_GET['option'];
    $id_end = $_GET['dest'];
} else {
    header("location: ../index.php?error=invalid_planets");
}

//Create an array for the filters (and check if there's a filter)
$filter1 = $_GET['filter1'] ?? null;
$filter2 = $_GET['filter2'] ?? null;
$filter3 = $_GET['filter3'] ?? null;
if ($filter1 == null && $filter2 == null && $filter3 == null) header("location ../index.php?error=option");
$filters = array();
if ($filter1 != null) $array_filters[] = $filter1;
if ($filter2 != null) $array_filters[] = $filter2;
if ($filter3 != null) $array_filters[] = $filter3;
$len = count($array_filters);

// Chemin vers le fichier .jar
$jarPath = 'Travia.jar';

//Create the command to execute the jar, the txt name, and an string representing an array for the database
if ($len == 1) {
    $command = "java -jar " . $jarPath . " localhost travia \"Implius\" \"Pepette8;\" " . $id_dep . " " . $id_end . " " . $opt . " " . $array_filters[0];
    $filters="[$filter1]";
    $txt="./cache_".$id_dep."-".$id_end."_".$opt."_".$array_filters[0].".txt";
}
if ($len == 2) {
    $command = "java -jar " . $jarPath . " localhost travia \"Implius\" \"Pepette8;\" " . $id_dep . " " . $id_end . " " . $opt . " " . $array_filters[0] . "," . $array_filters[1];
    $filters="[$filter1,$filter2]";
    $txt="./cache_".$id_dep."-".$id_end."_".$opt."_".$array_filters[0].",".$array_filters[1].".txt";
}
if ($len == 3) {
    $command = "java -jar " . $jarPath . " localhost travia \"Implius\" \"Pepette8;\" " . $id_dep . " " . $id_end . " " . $opt . " " . $array_filters[0] . "," . $array_filters[1] . "," . $array_filters[2];
    $filters="[$filter1,$filter2]";
    $txt="./cache_".$id_dep."-".$id_end."_".$opt."_".$array_filters[0].",".$array_filters[1].",".$array_filters[2].".txt";
}

// Exécuter la commande et récupérer la sortie
$output = [];
$returnCode = 0;
exec($command, $output, $returnCode);

// Afficher le résultat ou gérer les erreurs
if ($returnCode === 0) {

} else {
    Tool::add_search_log("Palpatine",Planet::get_name_from_id($id_dep), Planet::get_name_from_id($id_end),false,"Program failed");
    header("location: ../index.php?error=prog");
}

//Get the data from the txt create by the programme
//Open the file and get the string
fopen($txt,'r');
$string=file($txt)[0];
if ($string == "[]") {
    Tool::add_search_log("Palpatine",Planet::get_name_from_id($id_dep), Planet::get_name_from_id($id_end),false,"No travel found");
    header("location: ../index.php?error=travel");
} else {

    //Convert the string into an int table
    $modifiedString = trim($string, "[]");
    $values = explode(",", $modifiedString);
    $idTable = array_map('intval', $values); //The table with all the id
    $lenTable = count($idTable);

    //Get all data from the trips
    $distance = 0;
    $price = 0;
    $time = [0,0];
    for ($i = 0; $i < $lenTable-1; $i++) {
        $id1 = $idTable[$i];
        $id2 = $idTable[$i+1];
        $planet1 = Planet::get_planet_from_id($id1);
        $planet2 = Planet::get_planet_from_id($id2);
        $trip = Planet::getTripWith($id1, $id2);
        $distance += $trip->getDistance();
        $price += $trip->get_price();
        //hour
        $time[0] += $trip->get_time()[0];
        //minute
        if ($time[1] + $trip->get_time()[1] > 60){
            $rest = $time[1] + $trip->get_time()[1] - 60;
            $time[1] = 0 + $rest;
        } else {
            $time[1] += $trip->get_time()[1];
        }
    }

    //Transform time into a string
    $time_string = "[".$time[0].",".$time[1]."]";
    
    //Insert into the table travel
    Travel::addTravel($time_string, $price, $distance, $id_dep, $id_end, $opt, $filters, $string);
    $id = Tool::get_last_ai_id();
    Tool::add_search_log("Palpatine",Planet::get_name_from_id($id_dep), Planet::get_name_from_id($id_end),true,"");
    header("location: ../travel.php?travel=$id");
}



?>
