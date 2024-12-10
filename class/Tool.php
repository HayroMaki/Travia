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
}