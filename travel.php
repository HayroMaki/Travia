<!DOCTYPE html>

<?php
    require_once "include/includeClasses.php";
    include("include/config.php");
    global $cnx;

    $departure = $_GET['Departure'];
    $destination = $_GET['Destination'];

    $dep_obj = Planet::get_planet_from_name($departure);
    $dest_obj = Planet::get_planet_from_name($destination);

    $dep_x = ($dep_obj->getX()+$dep_obj->getSubGridX());
    $dep_y = ($dep_obj->getY()+$dep_obj->getSubGridY());
    $dest_x = ($dest_obj->getX()+$dest_obj->getSubGridX());
    $dest_y = ($dest_obj->getY()+$dest_obj->getSubGridY());

    $dep_coord_str = round($dep_x,2).", ".round($dep_y,2);
    $dest_coord_str = round($dest_x,2).", ".round($dest_y,2);

    list($distance_km,$distance_ly) = $dep_obj->getDistanceWith($dest_obj);
?>

<html lang="fr">
    <head>
        <meta charset="UTF-8">
        <title><?php echo ($departure." -> ".$destination) ?></title>
        <link href="index.css?v=<?php echo time(); ?>" rel="stylesheet">
    </head>
    <body>
        <?php include("include/header.inc.php"); ?>
        <div class="main">
            <div class="genDiv">
                <p>
                    <?php
                    echo "Distance between "
                        .$departure." (".$dep_coord_str.") and "
                        .$destination." (".$dest_coord_str.") : "
                        .round($distance_km,2)." billion km / ".round($distance_ly,6)." light years."
                    ?>
            </div>
        </div>
    </body>
</html>


