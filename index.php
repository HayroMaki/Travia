<!DOCTYPE html>

<?php
    // To prevent the program to stop due to memory usage or execution time :
    ini_set('memory_limit', '4096M');
    ini_set('max_execution_time', 0);

    // Set up the PDO
    global $cnx;
    include ("config.php");
    require_once "include/includeClasses.php";

    global $ship_count;
    $ship_count = 0;

    global $planet_count;
    $planet_count = 0;

    global $trip_count;
    $trip_count = 0;
?>

<html lang="fr">
    <head>
        <meta charset="UTF-8">
        <title>Travia</title>
        <link href="index.css?v=<?php echo time(); ?>" rel="stylesheet">
    </head>
    <body>
      <!-- Header bar -->
      <header id="header">
        <p id="mainTitle">Travia</p>
        <a href="">
          <img src="icons/utilisateur.png" id="userImage" alt="user icon">
        </a>
      </header>

      <?php
      // Check if the user's order
      if (isset($_GET)) {
          if (isset($_GET['order'])) {
              $order = $_GET['order'];

              // If the order is to generate :
              if ($order == "generate") {
                  // First, clear the DB
                  include ("scripts/deleteAll.php");

                  // Display the completion of the generation
                  ?>
                    <div style="text-align:center">
                        <p>Remplissage de la BDD : [<b id="percentage">0</b>%]</p>
                        <p>Remplissage de la table 'ship' : [<b id="ship_counter">0</b>/10]</p>
                        <p>Remplissage de la table 'planet' : [<b id="planet_counter">0</b>/5444]</p>
                        <p>Remplissage de la table 'trip' : [<b id="trip_counter">0</b>/127047]</p>
                    </div>
                  <?php

                  // Then generate ships, planets and their trips
                  include("scripts/decodeShip.php");
                  include("scripts/decodePlanet.php");

              // If the order is to delete :
              } else if ($order == "delete") {

                  // Clear the DB
                  include("scripts/deleteAll.php");
              }

              // Go back to page without GET
              echo '<script type="text/javascript"> window.location="'.Tool::get_URL_wo_GET().'";</script>';
          }
      } ?>

      <div id="genDiv">
          <?php include("include/searchForm.php");?>
      </div>

      <div id="genDiv">
        <a href="index.php?order=generate">
            <div class="GenButton"><p>Generate Database</p></div>
        </a>

        <a href="index.php?order=delete">
            <div class="GenButton"><p>Delete Database</p></div>
        </a>
      </div>
    </body>
</html>