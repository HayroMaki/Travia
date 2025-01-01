<!DOCTYPE html>

<?php
    require_once("include/setupPDO.php");
    require_once "include/includeClasses.php";
?>

<html lang="fr">
    <head>
        <meta charset="UTF-8">
        <title>Travia admin</title>
        <link href="index.css?v=<?php echo time(); ?>" rel="stylesheet">
    </head>
    <body>
        <?php
            include("include/header.inc.php");
            include("include/admin.php");
            include("include/footer.inc.php");
        ?>
    </body>
</html>
