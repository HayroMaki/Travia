<!DOCTYPE html>

<?php
// Set up the PDO
require_once("include/setupPDO.php");
require_once("include/includeClasses.php");
?>

<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Travia</title>
    <link href="index.css?v=<?php echo time(); ?>" rel="stylesheet">
    <link rel="stylesheet" href="cart/cart.css">
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
<form onsubmit="onSubmit()" class="verif">
    <h3>Enter the verification code you received by email :</h3>
    <div class="verif-container">
        <input type="number" name="code" class="verif-number" required/>
        <input type="number" name="code" class="verif-number" required/>
        <input type="number" name="code" class="verif-number" required/>
        <input type="number" name="code" class="verif-number" required/>
        <input type="number" name="code" class="verif-number" required/>
        <input type="number" name="code" class="verif-number" required/>
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

    // mini example on how to pull the data on submit of the form
    function onSubmit(e) {
        e.preventDefault()
        const code = inputElements.map(({value})=>value).join('')
        console.log(code)
    }
</script>
<?php
include("include/footer.inc.php");
require_once("include/background.php");
?>
</body>
</html>
