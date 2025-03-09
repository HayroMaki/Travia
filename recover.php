<?php
    session_start();
    // Set up the PDO
    require_once("include/setupPDO.php");
    require_once("include/includeClasses.php");
?>
<html lang="fr">
    <head>
        <meta charset="UTF-8">
        <title>Travia</title>
        <link href="index.css?v=<?php echo time(); ?>" rel="stylesheet">
    </head>
    <body>
    <?php
    include("include/header.inc.php");

    if (isset($_GET["email"]) && isset($_GET['verify']) && strlen($_GET["verify"]) == 10) {
        $email = $_GET["email"];
        $verify = $_GET["verify"];
        if (!Tool::check_expiration_change($email)) {
            $expired = true;
        } else if (Tool::check_code_change($email, $verify)) {
            $invalid = true;
        } else {
            if (Account::change_password($email,Tool::get_new_password_change($email))) {
                Tool::delete_change_verify($email);
                ?>
                <h1 style="text-align: center">Your password has been changed successfully.</h1>
            <?php } else { ?>
                <h1 style="text-align: center">Something went wrong, please try again later.</h1>
    <?php }}} else if (isset($_POST["email"]) && isset($_POST["password"])) {
        $email = $_POST["email"];
        $password = $_POST["password"];
        if (Tool::email_present($email)) {
            Tool::send_verification_email_change($email, password_hash($password,PASSWORD_BCRYPT));
        }
        ?>
        <h1 style="text-align: center">If your email address is valid, you will receive an email to validate your password change.</h1>
    <?php } else { ?>
        <div class="login-container">
            <p class="login-error">Enter your account's email and your new password :</p>
            <form action="" method="post" class="login-form" id="login-form">
                <label for="login-email" class="login-label">E-mail</label>
                <input type="email" name="email" class="login-input" id="login-email" placeholder="youremail@gmail.com" required/>

                <label for="login-password" class="login-label">New password</label>
                <input type="password" name="password" class="login-input" id="login-password" placeholder="your new password..." required/>

                <div class="login-center">
                    <input type="submit" class="login-submit" value="Send " id="login-submit">
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
    <?php }
    include("include/footer.inc.php");
    require_once("include/background.php");
    ?>
    </body>
</html>
