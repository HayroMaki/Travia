<!DOCTYPE html>

<?php
    require_once "include/includeClasses.php";
    include("include/config.php");
    global $cnx;
?>

<html lang="fr">
    <head>
        <meta charset="UTF-8">
        <title>Travia</title>
        <link href="index.css?v=<?php echo time(); ?>" rel="stylesheet">
    </head>
    <body>
        <?php include("include/header.inc.php") ?>
        <table>
            <?php
            foreach (Tool::get_log() as $log) { ?>
                <tr>
                    <td>log n°<?php echo $log['id']; ?> :</td>
                    <td><?php echo $log['trace']; ?></td>
                </tr>
            <?php } ?>
        </table>
    </body>
</html>