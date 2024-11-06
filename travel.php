<!DOCTYPE html>

<?php
    require_once "include/includeClasses.php";
    include("include/config.php");
    global $cnx;

    $departure = $_GET['Departure'];
    $destination = $_GET['Destination'];
?>

<html lang="fr">
    <head>
        <meta charset="UTF-8">
        <title><?php echo ($departure." -> ".$destination) ?></title>
        <link href="index.css?v=<?php echo time(); ?>" rel="stylesheet">
    </head>
    <body>
        <?php include("include/header.inc.php") ?>

    </body>
</html>


