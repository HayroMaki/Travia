<!DOCTYPE html>

<?php
session_start();
// Set up the PDO
require_once("include/setupPDO.php");
require_once("include/includeClasses.php");

$code_1 = filter_input(INPUT_POST, "code1", FILTER_SANITIZE_NUMBER_INT);
$code_2 = filter_input(INPUT_POST, "code2", FILTER_SANITIZE_NUMBER_INT);
$code_3 = filter_input(INPUT_POST, "code3", FILTER_SANITIZE_NUMBER_INT);
$code_4 = filter_input(INPUT_POST, "code4", FILTER_SANITIZE_NUMBER_INT);
$code_5 = filter_input(INPUT_POST, "code5", FILTER_SANITIZE_NUMBER_INT);
$code_6 = filter_input(INPUT_POST, "code6", FILTER_SANITIZE_NUMBER_INT);

if (!isset($_SESSION["email"])) {
    header("Location:register.php");
} else {
    $email = $_SESSION["email"];
}

if (isset($email) && isset($code_1) && isset($code_2) && isset($code_3) && isset($code_4) && isset($code_5) && isset($code_6)) {
    $code = $code_1 . $code_2 . $code_3 . $code_4 . $code_5 . $code_6;
    if (!Tool::check_expiration($email)) {
        $expired = true;
    } else if (Tool::check_code($email, $code)) {
        $invalid = true;
    } else {
        if (!Tool::register($email)) {
            $registered = false;
        } else {
            header("Location:login.php");
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
        <!-- This prevents the input type=number to show the arrows -->
        <style>
            input::-webkit-outer-spin-button,
            input::-webkit-inner-spin-button {
                -webkit-appearance: none;
                margin: 0;
            }
            /* Firefox */
            input[type=number] {
                -moz-appearance: textfield;
            }
        </style>
    </head>
<body>
<?php
include("include/header.inc.php");
?>
<form action="#" method="POST" class="verif">
    <h3>Enter the verification code you received by email :</h3>
    <div class="login-error">
        <?php
        if (isset($expired) && $expired) {
            echo "Your code expired, please start over the registration process.";
        } else if (isset($invalid) && $invalid) {
            echo "Your code is invalid, please try again.";
        } else if (isset($registered) && !$registered) {
            echo "Could not register, please try again.";
        }
        ?>
    </div>
    <div class="verif-container">
        <input type="number" name="code1" class="verif-number" required/>
        <input type="number" name="code2" class="verif-number" required/>
        <input type="number" name="code3" class="verif-number" required/>
        <input type="number" name="code4" class="verif-number" required/>
        <input type="number" name="code5" class="verif-number" required/>
        <input type="number" name="code6" class="verif-number" required/>
    </div>
    <input type="submit" class="verif-submit" value="Validate" id="verif-submit">
</form>
<script>
    // Code by Robert Aron : Multi / Separated Input Verification Code
    // on codepen : https://codepen.io/RobertAron/pen/gOLLXLo

    const inputElements = [...document.querySelectorAll('input.verif-number')]

    inputElements.forEach((ele,index)=>{
        ele.addEventListener('keydown',(e)=>{
            // if the keycode is backspace & the current field is empty
            // focus the input before the current. Then the event happens
            // which will clear the "before" input box.
            if(e.keyCode === 8 && e.target.value==='') inputElements[Math.max(0,index-1)].focus()
        })
        ele.addEventListener('input',(e)=>{
            // take the first character of the input
            const [first,...rest] = e.target.value
            e.target.value = first ?? '' // first will be undefined when backspace was entered, so set the input to ""
            const lastInputBox = index===inputElements.length-1
            const didInsertContent = first!==undefined
            if(didInsertContent && !lastInputBox) {
                // continue to input the rest of the string
                inputElements[index+1].focus()
                inputElements[index+1].value = rest.join('')
                inputElements[index+1].dispatchEvent(new Event('input'))
            }
        })
    })
</script>
<?php
include("include/footer.inc.php");
require_once("include/background.php");
?>
</body>
</html>
