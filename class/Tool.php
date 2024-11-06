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
}