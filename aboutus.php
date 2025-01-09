<!DOCTYPE html>

<?php

// To prevent the program to stop due to memory usage or execution time :
ini_set('memory_limit', '4096M');
ini_set('max_execution_time', 0);
ini_set('display_errors', 1);
error_reporting(E_ALL);
?>

<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>About us</title>
    <link href="index.css?v=<?php echo time(); ?>" rel="stylesheet">
    <link rel="stylesheet" href="aboutus.css">

    <!-- Leaflet -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"/>
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
</head>
<body>
<?php
include("include/header.inc.php");
?>

<h1>Meet our team !</h1>
<p>Click on us to learn more</p>

<div class="photos">
    <a href="https://www.linkedin.com/in/julesrenaudgrange/" target="_blank">
        <div class="box"><img class="jg" src="images/jules.png">Jules Renaud--Grange<br><div class="role">Scrum Master</div></div>
    </a>
    <a href="https://www.linkedin.com/in/guillaume-augeraud/" target="_blank">
        <div class="box"><img class="jg" src="images/guillaume.png">Guillaume Augeraud<br><div class="role">Technical Leader</div></div>
    </a>
    <a href="https://www.linkedin.com/in/a-achirecesei/" target="_blank">
        <div class="box"><img src="images/andrei.png">Andrei Achirecesei<br><div class="role">Developer</div></div>
    </a>
    <a href="https://www.linkedin.com/in/rissot/" target="_blank">
        <div class="box"><img class="b" src="images/benjamin.jpg">Benjamin Rissot<br><div class="role">Developer</div></div>
    </a>
</div>

<p>
    Welcome on Travia ! A Star-Wars themed university project to find the best intergalactic trips.<br>
    We are four second year students in computer science at IUT de Marne-la-Vallée, Université Gustave Eiffel.<br>
    This site was built using various languages such as PHP, Javascript, Java, C and exploiting algorithms like A-star.<br>
</p>

<p>
    @ 2025 Travia - IUT de Marne-la-Vallée
</p>

<?php
include("include/footer.inc.php");
require_once("include/background.php")
?>
</body>
</html>