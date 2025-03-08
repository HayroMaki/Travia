<!DOCTYPE html>

<?php
session_start();
// Check that the user is connected :
if (!isset($_SESSION["email"])) {
    header("Location:login.php");
}
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
        <div class="box"><img class="jg" src="data/images/jules.png">Jules Renaud--Grange<br><div class="role">Scrum Master</div></div>
    </a>
    <a href="https://www.linkedin.com/in/guillaume-augeraud/" target="_blank">
        <div class="box"><img class="jg" src="data/images/guillaume.png">Guillaume Augeraud<br><div class="role">Technical Leader</div></div>
    </a>
    <a href="https://www.linkedin.com/in/a-achirecesei/" target="_blank">
        <div class="box"><img class="jg" src="data/images/andrei.png">Andrei Achirecesei<br><div class="role">Developper</div></div>
    </a>
    <a href="https://www.linkedin.com/in/rissot/" target="_blank">
        <div class="box"><img class="jg" src="data/images/benjamin.png">Benjamin Rissot<br><div class="role">Developper</div></div>
    </a>
</div>

<p>
    Welcome on Travia ! A Star-Wars themed university project to find the best intergalactic trips.<br>
    We are four second year students in computer science at University Gustave Eiffel.<br>
    This site was built using various languages such as PHP, Javascript, Java, C and exploiting algorithms like A-star.<br>
    <br>
    We use libraries : leaflet for the map and cJSON for reading data's in C.<br>
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