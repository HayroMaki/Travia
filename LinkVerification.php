<!DOCTYPE html>

<?php
session_start();
// Set up the PDO
require_once("include/setupPDO.php");
require_once("include/includeClasses.php");

if (!isset($_SESSION["email"])) {
    header("Location:register.php");
} else {
    $email = $_SESSION["email"];
}

if (!isset($_GET["verify"]) || strlen($_GET["verify"]) != 10) {
    header("Location:register.php");
} else {
    $verify = $_GET["verify"];
}

if (isset($email) && isset($verify)) {
    if (!Tool::check_expiration_registration($email)) {
        $expired = true;
    } else if (Tool::check_code_registration($email, $verify)) {
        $invalid = true;
    } else {
        if (!Tool::register($email)) {
            $registered = false;
        } else {
            $registered = true;
        }
    }
}
?>

<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Travia</title>
    <link href="index.css?v=<?php echo time(); ?>" rel="stylesheet">
    <link rel="stylesheet" href="cart/cart.css">
    <style>
        h1 {
            text-align: center;
        }
    </style>
</head>
<body>
<?php
include("include/header.inc.php");
if (isset($expired) && $expired) { ?>
    <h1>This link is expired, please restart the registration process.</h1>
<?php } else if (isset($invalid) && $invalid) { ?>
    <h1>Either your session or the link is invalid.</h1>
<?php } else if (!isset($registered) || !$registered) { ?>
    <h1>Something went wrong, please try again later.</h1>
<?php } else { ?>
    <h1>Your account has been successfully created ! Welcome to Travia !</h1>
<?php }
include("include/footer.inc.php");
require_once("include/background.php");
?>
</body>
</html>

