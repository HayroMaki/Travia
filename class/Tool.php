<?php

/**
 * A completely static class that has some QOL functions.
 * Used for logs and other things.
 */
class Tool
{
    /**
     * Get the last auto-incremented value in the database, used during the creation of the data's.
     * Doesn't work if the $cnx isn't setup.
     *
     * @return int the last auto-incremented id.
     */
    public static function get_last_ai_id(): int
    {
        global $cnx;

        $stmt = $cnx->prepare("SELECT LAST_INSERT_ID()");
        $stmt->execute();

        $result = $stmt->fetch();

        if ($result === null) {
            die('Error getting the last ai id.');
        }

        return $result[0];
    }

    /**
     * Returns the url of the actual page, removing the GET part (?x=... &y=... ).
     *
     * @return string the page's url without the GET part.
     */
    public static function get_URL_wo_GET(): string {
        $protocol = ((!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off') || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";

        return $protocol . $_SERVER['HTTP_HOST'] . parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
    }

    /**
     * Create a new search log in the log table of the database,
     * using the account name, the planets of departure and destination, the success state,
     * and, if needed, the reason of failure.
     * Doesn't work if the $cnx isn't setup.
     *
     * @param string $user the user's name.
     * @param string $departure the planet of departure's name.
     * @param string $destination the planet of destination's name.
     * @param bool $success the success state.
     * @param string $reason the reason of failure, empty if the search was successful.
     * @return void
     */
    public static function add_search_log(string $user, string $departure, string $destination, bool $success, string $reason) {
        global $cnx;
        date_default_timezone_set('Europe/Moscow');

        if ($success) {
            $success_msg = "Search successful.";
        } else {
            $success_msg = "Search failed : Reason : ".$reason.".";
        }

        $msg = "user:".addslashes($user)." searched a path from ".addslashes($departure)." to ".addslashes($destination)." > ".$success_msg;
        $log_date = date("Y-m-d H:i:s");

        $query = "INSERT INTO log (date,trace) VALUES ('".$log_date."','".$msg."')";
        $stmt = $cnx->prepare($query);
        echo $query;
        $stmt->execute();
    }

    /**
     * Create a new search log in the log table of the database,
     * using the provided email, the success state,
     * and, if needed, the reason of failure.
     * Doesn't work if the $cnx isn't setup.
     *
     * @param string $email the user's email.
     * @param bool $success the success state.
     * @param string|null $reason the reason of failure, empty if the connection was successful.
     * @return void
     */
    public static function add_login_log(string $email, bool $success, string $reason) {
        global $cnx;
        date_default_timezone_set('Europe/Moscow');

        if ($success) {
            $success_msg = "Connection successful.";
        } else {
            $success_msg = "Connection failed : Reason : ".$reason.".";
        }

        $msg = "Connection attempt using ".$email." > ".$success_msg;
        $log_date = date("Y-m-d H:i:s");

        $query = "INSERT INTO log (date,trace) VALUES ('".$log_date."','".$msg."')";
        $stmt = $cnx->prepare($query);
        echo $query;
        $stmt->execute();
    }

    /**
     * Create a new search log in the log table of the database,
     * using the provided email, first and last name, the success state,
     * and, if needed, the reason of failure.
     * Doesn't work if the $cnx isn't setup.
     *
     * @param string $email the user's email.
     * @param string $first_name the user's first name.
     * @param string $last_name the user's last name.
     * @param bool $success the success state.
     * @param string|null $reason the reason of failure, empty if the registration was successful.
     * @return void
     */
    public static function add_register_log(string $email, string $first_name, string $last_name, bool $success, string $reason) {
        global $cnx;
        date_default_timezone_set('Europe/Moscow');

        if ($success) {
            $success_msg = "Registration successful.";
        } else {
            $success_msg = "Registration failed : Reason : ".$reason.".";
        }

        $msg = "Registration attempt using ".$email." as ".$first_name." ".$last_name." > ".$success_msg;
        $log_date = date("Y-m-d H:i:s");

        $query = "INSERT INTO log (date,trace) VALUES ('".$log_date."','".$msg."')";
        $stmt = $cnx->prepare($query);
        echo $query;
        $stmt->execute();
    }

    /**
     * Get an array of every log in the log table of the database.
     * Doesn't work if the $cnx isn't setup.
     *
     * @return array an array of logs that each contains 3 elements : 'id', 'date' and 'trace'.
     */
    public static function get_log(): array {
        global $cnx;

        $query = "SELECT * FROM log";
        $stmt = $cnx->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    /**
     * Verify the existence of an email in the account table of the database.
     * Doesn't work if the $cnx isn't setup.
     *
     * @param string $email the user's email.
     * @return bool true if present, false if not.
     */
    public static function email_present(string $email): bool {
        global $cnx;
        $stmt = $cnx->prepare("SELECT id FROM account WHERE email = :email");
        $stmt->bindParam(":email", $email, PDO::PARAM_STR);
        $stmt->execute();

        $result = $stmt->fetch();
        return $result != null;
    }

    /**
     * Verify an email/password couple in the account table of the database.
     * Doesn't work if the $cnx isn't setup.
     *
     * @param string $email the user's email.
     * @param string $password the user's password.
     * @return bool true if present AND password is correct,
     * false if email not present OR email is present but password is not correct.
     */
    public static function verify_email_password(string $email, string $password): bool {
        global $cnx;

        $stmt = $cnx->prepare("SELECT password FROM account WHERE email = :email");
        $stmt->bindParam(':email', $email, PDO::PARAM_STR);
        $stmt->execute();

        $result = $stmt->fetch();
        if ($result != null) {
            return password_verify($password, $result['password']);
        } else return false;
    }
}