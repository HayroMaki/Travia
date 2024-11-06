<!DOCTYPE html>

<?php
    // To prevent the program to stop due to memory usage or execution time :
    ini_set('memory_limit', '4096M');
    ini_set('max_execution_time', 0);

    // Set up the PDO
    global $cnx;
    include("include/config.php");
    require_once "include/includeClasses.php";

    global $ship_count;
    $ship_count = 0;

    global $planet_count;
    $planet_count = 0;

    global $trip_count;
    $trip_count = 0;

    if (isset($_GET['error'])) {
        if ($_GET['error'] == "empty_fields") {
            $error_msg = "Please fill in all fields.";

        } else if ($_GET['error'] == "invalid_planets") {
            $error_msg = "Please select valid planets.";

        } else if ($_GET['error'] == "same_fields") {
            $error_msg = "Please don't select the same planet as departure and destination.";
        }
    }
?>

<html lang="fr">
    <head>
        <meta charset="UTF-8">
        <title>Travia</title>
        <link href="index.css?v=<?php echo time(); ?>" rel="stylesheet">
    </head>
    <body>
      <?php
        include("include/header.inc.php");
        include("include/admin.php");
      ?>
    </body>
</html>