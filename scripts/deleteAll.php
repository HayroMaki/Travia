<?php
    require_once "include/includeClasses.php";
    global $cnx;

    // Clear every table in DB
    Ship::clear_ship_db();

    Planet::clear_planet_db();

    Trip::clear_trip_db();