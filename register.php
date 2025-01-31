<!DOCTYPE html>

<?php
// Set up the PDO
require_once("include/setupPDO.php");
require_once("include/includeClasses.php");

include("include/fontSelector.php");

if (isset($_POST["first-name"]) && isset($_POST["last-name"]) && isset($_POST["email"]) && isset($_POST["password"]) && isset($_POST["verify-password"])) {
    // Check if email is already used :
    if (Tool::email_present($_POST["email"])) {
        $error = "Email is already in use.";
        Tool::add_register_log($_POST["email"], $_POST["first-name"], $_POST["last-name"], false, $error);
    }

    // Check that both password are the same :
    if ($_POST["password"] != $_POST["verify-password"]) {
        $error = "Passwords do not match.";
        Tool::add_register_log($_POST["email"], $_POST["first-name"], $_POST["last-name"], false, $error);
    }


}
?>

<script>
    loadFont();
</script>

<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Travia</title>
    <link href="index.css?v=<?php echo time(); ?>" rel="stylesheet">
    <link rel="stylesheet" href="cart/cart.css">
</head>
<body>
<?php
    include("include/header.inc.php");
?>
<div class="login-container">
    <div class="login-error"><?php if (isset($error)) echo $error ?></div>
    <form action="login.php" method="post" class="login-form" id="login-form">

        <div class="login-little-inputs">
            <div>
                <label for="first-name" class="login-label">First name</label>
                <input type="text" name="first-name" class="login-input" id="first-name" required/>
            </div>

            <div>
                <label for="last-name" class="login-label">Last name</label>
                <input type="text" name="last-name" class="login-input" id="last-name" required/>
            </div>
        </div>

        <label for="login-email" class="login-label">E-mail</label>
        <input type="email" name="email" class="login-input" id="login-email" placeholder="youremail@gmail.com" required/>

        <label for="login-password" class="login-label">Password</label>
        <input type="password" name="password" class="login-input" id="login-password" placeholder="your password..." required/>

        <label for="login-verify-password" class="login-label">Confirm password</label>
        <input type="password" name="verify-password" class="login-input" id="login-verify-password" placeholder="your password..." required/>

        <div class="login-center">
            <input type="submit" class="login-submit" value="Register" id="login-submit">
            <a href="login.php" class="login-link">I already have an account.</a>
        </div>
    </form>
</div>
<?php
    include("include/footer.inc.php");
    require_once("include/background.php");
?>
</body>
</html>