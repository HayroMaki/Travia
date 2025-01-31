<!DOCTYPE html>

<?php
    // Set up the PDO
    require_once("include/setupPDO.php");
    require_once("include/includeClasses.php");
    include("include/fontSelector.php");

    if (isset($_POST["email"]) && isset($_POST["password"])) {
        if (Tool::verify_email_password($_POST["email"], $_POST["password"])) {
            Tool::add_login_log($_POST["email"], true, "");
            session_start();
        } else {
            $error = "Email or password is incorrect.";
            Tool::add_login_log($_POST["email"], false, $error);
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
                <label for="login-email" class="login-label">E-mail</label>
                <input type="email" name="email" class="login-input" id="login-email" placeholder="youremail@gmail.com" required/>

                <label for="login-password" class="login-label">Password</label>
                <input type="password" name="password" class="login-input" id="login-password" placeholder="your password..." required/>

                <div class="login-center">
                    <input type="submit" class="login-submit" value="Login" id="login-submit">
                    <a href="register.php" class="login-link">I don't have an account.</a>
                </div>
            </form>
        </div>
        <?php
            include("include/footer.inc.php");
            require_once("include/background.php");
        ?>
    </body>
</html>