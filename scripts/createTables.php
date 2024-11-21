<?php
    require_once "include/includeClasses.php";
    global $cnx;

    // Create Database tables using the SQL file.
    $query = file_get_contents("./data/tables.sql");

    $stmt = $cnx->prepare($query);
    $stmt->execute();
