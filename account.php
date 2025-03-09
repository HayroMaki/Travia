<?php
    require_once "include/setupPDO.php";
    require_once "include/includeClasses.php";

    session_start();
    if (!isset($_SESSION['email'])) {
        header("Location:login.php");
    } else {
        $email = $_SESSION['email'];
        $account = Account::get_account_from_mail($email);
        if ($account == null) {
            header("Location:login.php");
        }
?>
<html lang="fr">
    <head>
        <meta charset="UTF-8">
        <title>Travia</title>
        <link href="index.css?v=<?php echo time(); ?>" rel="stylesheet">
    </head>
    <body>
    <?php include("include/header.inc.php");
        if (isset($_GET["delete"])) {
            if ($_GET["delete"] == 1) { ?>
            <h1 style="text-align: center">Are you sure you want to delete your account ?<br>This action is irreversible.</h1>
        <div class="account-actions">
            <a href="?delete=2" class="account-link"><div class="account-button">Yes<br>I'm sure</div></a>
            <a href="account.php" class="account-link"><div class="account-button">No<br>Go back</div></a>
        </div>
        <?php } else if ($_GET["delete"] == 2) {
                if ($account->delete()) {
                    header("Location:login.php");
                } else { ?>
                    <h1 style="text-align: center">Something went wrong, we couldn't delete your account, please try again later.</h1>
                <?php }}} else { ?>
            <h1 style="text-align: center">Hello <?= $account->get_first_name() . " " . $account->get_last_name() ?></h1>
            <?php if ($account->is_admin()) { ?>
                <h1 style="text-align: center">Admin account.</h1>
            <?php } ?>
            <div class="account-actions">
                <a href="login.php" class="account-link"><div class="account-button">Log<br>Out</div></a>
                <a href="?delete=1" class="account-link"><div class="account-button">Delete<br>Account</div></a>
            </div>
        <?php } ?>
    </body>
    <?php }
    include("include/footer.inc.php");
    require_once("include/background.php");
    ?>
</html>


