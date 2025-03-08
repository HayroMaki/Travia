<?php
session_start();
// Check that the user is connected :
if (!isset($_SESSION["email"])) {
    header("Location:login.php");
}

// Chemin vers le fichier .jar
$jarPath = 'travel-search/Travia.jar';

// Commande pour exécuter le fichier .jar
$command = "java -jar " . escapeshellarg($jarPath) . " localhost travia 'Implius' 'Pepette8;' 12 2369 Distance Empire";

// Exécuter la commande et récupérer la sortie
$output = [];
$returnCode = 0;
exec($command, $output, $returnCode);

// Afficher le résultat ou gérer les erreurs
if ($returnCode === 0) {
    echo "Commande exécutée avec succès :<br>";
    echo implode("<br>", $output);
} else {
    echo "Erreur lors de l'exécution de la commande. Code de retour : $returnCode";
}
?>
