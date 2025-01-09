<?php

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
if ($filter1 != null) $filters[] = $filter1;
if ($filter2 != null) $filters[] = $filter2;
if ($filter3 != null) $filters[] = $filter3;
$len = count($filters);

// Chemin vers le fichier .jar
$jarPath = 'Travia.jar';

//Create the command to execute the jar
if ($len == 1) $command = "java -jar ".$jarPath." localhost travia \"Implius\" \"Pepette8;\" ".$id_dep." ".$id_end." ".$opt." ".$filters[0];
if ($len == 2) $command = "java -jar ".$jarPath." localhost travia \"Implius\" \"Pepette8;\" ".$id_dep." ".$id_end." ".$opt." ".$filters[0]." ".$filters[1];
if ($len == 3) $command = "java -jar ".$jarPath." localhost travia \"Implius\" \"Pepette8;\" ".$id_dep." ".$id_end." ".$opt." ".$filters[0]." ".$filters[1]." ".$filters[2];

// Exécuter la commande et récupérer la sortie
$output = [];
$returnCode = 0;
exec($command, $output, $returnCode);

// Afficher le résultat ou gérer les erreurs
if ($returnCode === 0) {

} else {
    header("location: ../index.php?error=prog");
}

//Get the data from the txt create by the programme
//Get the name of the txt
if ($len == 1) $txt="./cache_".$id_dep."-".$id_end."_".$opt."_".$filters[0].".txt";
if ($len == 2) $txt="./cache_".$id_dep."-".$id_end."_".$opt."_".$filters[0]."-".$filters[1].".txt";
if ($len == 3) $txt="./cache_".$id_dep."-".$id_end."_".$opt."_".$filters[0]."-".$filters[1]."-".$filters[2].".txt";

//Open the file and get the string
fopen($txt,'r');
$string=file($txt)[0];
if ($string == "[]") {
    print_r("Not found");
    //header("location: ../index.php?error=travel");
} else {
    print_r("Found");
//Convert the string into an int table
    $modifiedString = trim($string, "[]");
    $values = explode(",", $modifiedString);
    $idTable = array_map('intval', $values); //The table with all the id
    $lenTable = count($idTable);
    print_r($idTable);
}



?>
