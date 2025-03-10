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

use PHPMailer\PHPMailer\PHPMailer;

require_once("include/setupPDO.php");
require_once("include/includeClasses.php");

$first_name = filter_input(INPUT_POST, "first-name");
$last_name = filter_input(INPUT_POST, "last-name");
$email = filter_input(INPUT_POST, "email", FILTER_SANITIZE_EMAIL);
$password = filter_input(INPUT_POST, "password");
$verify_password = filter_input(INPUT_POST, "verify-password");

if (isset($first_name) && isset($last_name) && isset($email) && isset($password) && isset($verify_password)) {
    // Check if email is already used :
    // This poses a problem, a user should never know if the email he entered is used or not, so we don't tell him.
    if (Tool::email_present($email)) {
        $msg = "Email is already in use.";
        Tool::add_register_log($email, $first_name, $last_name, false, $msg);
    }

    // Check that both passwords are the same :
    else if ($password != $verify_password) {
        $error = "Passwords do not match.";
        Tool::add_register_log($email, $first_name, $last_name, false, $error);
    }

    // Send the verification email with a newly generated random verification code :
    else {
        if (Tool::send_verification_email_registration($email, password_hash($password,PASSWORD_BCRYPT),
            $first_name, $last_name, null, null)) {
            Tool::add_verification_log($email, true, "");
            $_SESSION["email"] = $email;
            $error = "If your email address is not already used, a verification link has been sent to your email address.";
        } else {
            Tool::add_verification_log($email, false, "Mailer Error.");
            $error = "Could not send verification email, try again later.";
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
</head>
<body>
<?php
    include("include/header.inc.php");
?>
<div class="login-container">
    <div class="login-error"><?php if (isset($error)) echo $error ?></div>
    <form action="#" method="POST" class="login-form" id="login-form">

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

<script>
    document.getElementById("login-form").addEventListener("submit", function (event) {
        const password = document.getElementById("login-password").value;
        const errorContainer = document.querySelector(".login-error");

        const passwordRegex = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[\W_]).{12,32}$/;

        if (!passwordRegex.test(password)) {
            errorContainer.textContent = "Password must be between 12 and 32 characters long, with at least one lowercase letter, one capital letter, one number and one special character (* _ ? ...)";
            event.preventDefault();
        }
    });
</script>
<?php
    include("include/footer.inc.php");
    require_once("include/background.php");
?>
</body>
</html>