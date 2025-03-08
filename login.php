<!DOCTYPE html>

<?php
    session_start();
    if (isset($_SESSION["email"])) {
        unset($_SESSION["email"]);
    }

    if (isset($_SESSION["connected"])) {
        unset($_SESSION["connected"]);
    }

    // Set up the PDO
    require_once("include/setupPDO.php");
    require_once("include/includeClasses.php");
    include("include/fontSelector.php");

    if (isset($_POST["email"]) && isset($_POST["password"])) {
        $email = $_POST["email"];

        // Check the email/password couple from DB :
        if (Tool::verify_email_password($email, $_POST["password"])) {

            // Send a verification code :
            if (Tool::send_verification_email_login($email)) {
                Tool::add_verification_log($email, true, "");
                $_SESSION["email"] = $email;
                header('Location:codeVerification.php');
            } else {
                Tool::add_verification_log($email, false, "Mailer Error.");
                $error = "Could not send verification email, try again later.";
            }
        } else {
            $error = "Email or password is incorrect.";
            Tool::add_login_log($email, false, $error);
        }
    }
?>

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