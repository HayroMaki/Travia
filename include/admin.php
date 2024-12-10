<?php
/*
 * This part is only for Admin users, allowing him to generate/delete the database's data and check the logs.
 * Only include if the user has the rights for those actions.
 */

// Check if there is an order from the user :
if (isset($_GET)) {
    if (isset($_GET['order'])) {
        $order = $_GET['order'];

        // If the order is to generate the data's from the json file :
        if ($order == "generateData") {
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

        // If the order is to generate the tables from the sql file :
        } else if ($order == "generateTables") {
            include("scripts/createTables.php");

        // If the order is to delete the data's :
        } else if ($order == "delete") {

            // Clear the DB
            include("scripts/deleteAll.php");
        }

        // Go back to page without GET after the creation is done.
        echo '<script type="text/javascript"> window.location="'.Tool::get_URL_wo_GET().'";</script>';
    }
} ?>

<!--
Create 4 buttons that allows the admin user to :
 - Generate the tables in the database using the sql file (doing so will erase every data).
 - Generate the datas inside the tables using the json files.
 - Clear every data from the database.
 - Check the logs from the database.
 -->
<div id="genDiv">
    <a href="?order=generateTables">
        <div class="GenButton">Generate<br>Tables</div>
    </a>

    <a href="?order=generateData">
        <div class="GenButton">Generate<br>Database</div>
    </a>

    <a href="?order=delete">
        <div class="GenButton">Clear<br>Database</div>
    </a>

    <a href="./log.php">
        <div class="GenButton">Check<br>Logs</div>
    </a>
</div>
