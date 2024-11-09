<?php
// Check if the user's order
if (isset($_GET)) {
    if (isset($_GET['order'])) {
        $order = $_GET['order'];

        // If the order is to generate :
        if ($order == "generate") {
            // First, clear the DB
            include("scripts/deleteAll.php");

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
    <a href="./index.php?order=generate">
        <div class="GenButton">Generate Database</div>
    </a>

    <a href="./index.php?order=delete">
        <div class="GenButton">Delete Database</div>
    </a>

    <a href="./log.php">
        <div class="GenButton">Check Logs</div>
    </a>
</div>
