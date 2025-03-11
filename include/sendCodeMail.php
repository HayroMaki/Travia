<?php
//Imported from example on the PHPMailer github page :
// https://github.com/PHPMailer/PHPMailer

//Import PHPMailer classes into the global namespace
//These must be at the top of your script, not inside a function
require 'library/PHPMailer/src/Exception.php';
require 'library/PHPMailer/src/PHPMailer.php';
require 'library/PHPMailer/src/SMTP.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

function sendCodeMail($mail,$to,$content): bool {
    Tool::load_env_file('data' . '/.env');
    $host = getenv('SMTP_HOST');
    $user = getenv("SMTP_USER");
    $pass = str_replace('\'','',getenv("SMTP_PASS"));
    $port = getenv("SMTP_PORT");
    try {
        //Server settings
        $mail->SMTPDebug = 0;//Enable verbose debug output
        $mail->isSMTP();//Send using SMTP
        $mail->Host = $host;//Set the SMTP server to send through
        $mail->SMTPAuth = true;//Enable SMTP authentication
        $mail->Username = $user;//SMTP username
        $mail->Password = $pass;//SMTP password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;//Enable implicit TLS encryption
        $mail->Port = $port;//TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`
        //Recipients
        $mail->setFrom($user, 'Travia Mailer');
        $mail->addAddress($to, 'Travia User');//Add a recipient
        //Content
        $mail->isHTML(true);//Set email format to HTML
        $mail->Subject = 'Verification code for Travia';
        $mail->Body = '
            <!DOCTYPE html>
            <html lang="en">
            <head>
                <meta charset="UTF-8">
                <meta name="viewport" content="width=device-width, initial-scale=1.0">
                <title>Travia Account Validation</title>
                <style>
                    body {
                        font-family: Arial, Helvetica, sans-serif;
                        margin: 0;
                        padding: 0;
                    }
                
                    .container {
                        margin: 0 auto;
                        padding: 40px;
                        max-width: 600px;
                        text-align: center;
                    }
                    
                    .code {
                        width: 50%;
                        margin: 3rem auto;
                        padding: 40px;
                        border-radius: 5rem;
                        background-color: #4e4e4e;
                        color: white;
                        font-size: 40px;
                        font-weight: bold;
                        letter-spacing: 20px;
                    }
                    
                    .footer {
                        margin-top: 20px;
                        font-size: 14px;
                        color: black;
                    }
                </style>
            </head>
            <body>
                <div class="container">
                    <h1>Here is your validation code :</h1>
                    <div class="code">' . $content . '</div>
                    <p>This code will be valid for the next 10 minutes.</p>
                    <div class="footer">We wish you great experience on our website.</div>
                </div>
            </body>
        ';
        $mail->AltBody = '
            Here is your validation code : ' . $content . '.
            This code will be valid for the next 10 minutes.
            We wish you great experience on our website.';
        $mail->send();
        return true;
    } catch (\Exception $e) {
        return false;
    }
}
