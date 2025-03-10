<?php
    header('Content-Type: application/json');
    require_once("include/setupPDO.php");
    require_once("include/includeClasses.php");
    global $cnx;

    // Default JSON to return :
    $json = array("connexion" => false);

    // Check case where we have a connection using email and password :
    if (isset($_GET["email"]) && isset($_GET["password"])) {
        $email = $_GET["email"];
        $password = $_GET["password"];

        // Verify that they are a valid account :
        if (Tool::verify_email_password($email, $password)) {
            // Generate a token (hash of email and hashed password) :
            $token = Tool::generate_token($email, $password);

            // Check if there is already a token linked to the account :
            $stmt = $cnx->prepare("SELECT token FROM account WHERE email = :email");
            $stmt->bindParam(":email", $email, PDO::PARAM_STR);
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);

            // Check if the token is still valid :
            if ($result && $result["token"] == $token) {
                $json = array(
                    "connexion" => true,
                    "token" => $token,
                );
            } else {
                // Add the token in the account in DB :
                $stmt = $cnx->prepare("UPDATE account SET token = :token WHERE email = :email");
                $stmt->bindParam(":token", $token, PDO::PARAM_STR);
                $stmt->bindParam(":email", $email, PDO::PARAM_STR);
                $stmt->execute();

                if ($stmt->rowCount() > 0) {
                    // Return the account's token :
                    $json = array(
                        "connexion" => true,
                        "token" => $token,
                    );
                }
            }
        }
    // Check case where we have a connection using a token :
    } else if (isset($_GET["token"])) {
        $token = $_GET["token"];
        $stmt = $cnx->prepare("SELECT * FROM account WHERE token = :token");
        $stmt->bindParam(":token", $token, PDO::PARAM_STR);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($row) {
            $json = array(
                "connexion" => true,
                "user_id" => $row["id"],
                "login" => $row["email"],
                "first-name" => $row["first-name"],
                "last-name" => $row["last-name"],
                "token" => $token,
            );
        }
    // If there is no GET or invalid GET :
    } else {
        $json = array(
            "connexion" => false,
            "error" => "please provide email and password or token.",
        );
    }

    echo json_encode($json);
    $cnx = null;
?>