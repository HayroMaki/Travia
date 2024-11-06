<?php

class Tool
{
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
    public static function get_URL_wo_GET(): string {
        $protocol = ((!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off') || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";

        return $protocol . $_SERVER['HTTP_HOST'] . parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
    }
    public static function add_search_log(string $user, string $departure, string $destination, bool $success, string $reason) {
        global $cnx;

        if ($success) {
            $success_msg = "Search successful.";
        } else {
            $success_msg = "Search failed : Reason : ".$reason.".";
        }

        $msg = "user:".addslashes($user)." searched a path from ".addslashes($departure)." to ".addslashes($destination)." > ".$success_msg;

        $query = "INSERT INTO log (trace) VALUES ('".$msg."')";
        $stmt = $cnx->prepare($query);
        $stmt->execute();
    }
    public static function get_log(): array {
        global $cnx;

        $query = "SELECT * FROM log";
        $stmt = $cnx->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll();
    }
}