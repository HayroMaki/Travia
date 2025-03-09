<!-- Header bar -->
<header id="header">
    <img src="data/icons/travia_logo.png" id="travia-logo" alt="travia logo">

    <a href="./index.php" id="mainTitleLink">
        <p id="mainTitle">Travia</p>
    </a>

    <?php if (isset($_SESSION["connected"]) && $_SESSION["connected"]) { ?>
        <a href="./account.php">
            <img src="data/icons/user_Palpatine.png" id="user-image" alt="user icon">
        </a>
    <?php } ?>
</header>
<hr id="white-line">
