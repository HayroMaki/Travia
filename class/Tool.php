<?php
use PHPMailer\PHPMailer\PHPMailer;

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
     * Create a new login log in the log table of the database,
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
     * Create a new registration log in the log table of the database,
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
        $stmt->execute();
    }

    /**
     *  Create a new validation email log in the log table of the database,
     *  using the provided email, the success state,
     *  and, if needed, the reason of failure.
     *  Doesn't work if the $cnx isn't setup.
     *
     * @param string $email the user's email.
     * @param bool $success the success state.
     * @param string|null $reason the reason of failure, empty if the registration was successful.
     * @return void
     */
    public static function add_verification_log(string $email, bool $success, string $reason) {
        global $cnx;
        date_default_timezone_set('Europe/Moscow');

        if ($success) {
            $success_msg = "Email successfully sent.";
        } else {
            $success_msg = "Email could not be sent : Reason : ".$reason.".";
        }

        $msg = "Verification procedure, sent verification code to ".$email." > ".$success_msg;
        $log_date = date("Y-m-d H:i:s");

        $query = "INSERT INTO log (date,trace) VALUES ('".$log_date."','".$msg."')";
        $stmt = $cnx->prepare($query);
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

    /**
     *
     *
     * @param string $email the user's email.
     * @param string $enc_password the user's ENCRYPTED password.
     * @param string $first_name the user's first name.
     * @param string $last_name the user's last name.
     * @param int|null $home_planet_id the user's home planet (can be null).
     * @param int|null $work_planet_id the user's work planet (can be null).
     * @return bool true if the procedure went well, false if not.
     */
    public static function send_verification_email_registration(string $email, string $enc_password,
                                                   string $first_name, string $last_name,
                                                   ?int $home_planet_id, ?int $work_planet_id): bool {
        try {
            require_once('include/sendLinkMail.php');
            $code = self::create_verification_code_in_db_register($email, $enc_password, $first_name, $last_name, $home_planet_id, $work_planet_id, 10);// CHANGE LINK :
            $link = "http://localhost/Travia/LinkVerification?verify=" . $code;
            $mail = new PHPMailer(true);
            return sendLinkMail($mail, $email, $link);
        } catch (Exception $e) {
            return false;
        }
    }

    /**
     *  Create a new verification code in the database linked to an email and return it.
     *  In the case this email is already in the database, delete the row and create a new one.
     *  Doesn't work if the $cnx isn't setup.
     *
     * @param string $email the user's email.
     * @param string $enc_password the user's ENCRYPTED password.
     * @param string $first_name the user's first name.
     * @param string $last_name the user's last name.
     * @param int|null $home_planet_id the user's home planet (can be null).
     * @param int|null $work_planet_id the user's work planet (can be null).
     * @return string the verification code (6 random digits).
     */
    private static function create_verification_code_in_db_register(string $email, string $enc_password,
                                                                    string $first_name, string $last_name,
                                                                    ?int   $home_planet_id, ?int $work_planet_id, int $length = 6): string {
        global $cnx;
        if (self::email_present($email)) {
            $rm_query = $cnx->prepare("DELETE FROM register_verify WHERE email = :email");
            $rm_query->bindParam(":email", $email, PDO::PARAM_STR);
            $rm_query->execute();
        }
        $code = self::random_string($length);
        $datetime = date("Y-m-d H:i:s");

        // Add the newly created code :
        $add_query = $cnx->prepare("
            INSERT INTO register_verify (email, code, date, `first-name`, `last-name`, password, `home-planet`, `work-planet`) 
            VALUES (:email, :code, :date, :first_name, :last_name, :password, :home_planet, :work_planet)");
        $add_query->bindParam(":email", $email, PDO::PARAM_STR);
        $add_query->bindParam(":code", $code, PDO::PARAM_STR);
        $add_query->bindParam(":date", $datetime, PDO::PARAM_STR);
        $add_query->bindParam(":first_name", $first_name, PDO::PARAM_STR);
        $add_query->bindParam(":last_name", $last_name, PDO::PARAM_STR);
        $add_query->bindParam(":password", $enc_password, PDO::PARAM_STR);
        $add_query->bindParam(":home_planet", $home_planet_id, PDO::PARAM_INT);
        $add_query->bindParam(":work_planet", $work_planet_id, PDO::PARAM_INT);
        $add_query->execute();

        return $code;
    }

    /**
     * Generate a random string of characters using random_int.
     *
     * @param int $length How many characters do we want (6 by default).
     * @param string $chars A string of all possible characters to select from ([0-9] by default).
     * @return string the generated string of characters.
     */
    private static function random_string(int $length = 6, string $chars = '0123456789'): string {
        if ($length < 1) {
            throw new \RangeException("Length must");
        }
        $code = [];
        $max = mb_strlen($chars, '8bit') - 1;
        for ($i = 0; $i < $length; ++$i) {
            $code []= $chars[random_int(0, $max)];
        }
        return implode('', $code);
    }

    /**
     *
     *
     * @param string $email the user's email.
     * @return bool true if the verification code is still valid, false if not.
     */
    public static function check_expiration_registration(string $email): bool {
        global $cnx;
        $stmt = $cnx->prepare("SELECT date FROM register_verify WHERE email = :email");
        $stmt->bindParam(':email', $email, PDO::PARAM_STR);
        $stmt->execute();
        $result = $stmt->fetch();

        if ($result != null) {
            $code_date = new DateTime($result['date']);
            $current_date = new DateTime();
            $code_date->add(new DateInterval('P10M'));

            if ($code_date < $current_date) {
                $rm_code = $cnx->prepare("DELETE FROM register_verify WHERE email = :email");
                $rm_code->bindParam(":email", $email, PDO::PARAM_STR);
                $rm_code->execute();
                return false;
            } else {
                return true;
            }
        } return false;
    }

    /**
     *
     *
     * @param string $email the user's email.
     * @param string $code
     * @return bool
     */
    public static function check_code_registration(string $email, string $code): bool {
        global $cnx;
        $stmt = $cnx->prepare("SELECT code FROM register_verify WHERE email = :email");
        $stmt->bindParam(":email", $email, PDO::PARAM_STR);
        $stmt->execute();
        $result = $stmt->fetch();

        $code_db = strval($result["code"]);
        return strcmp($code, $code_db);
    }

    /**
     *
     *
     * @param string $email the user's email.
     * @return bool true if the procedure went well, false if not.
     */
    public static function send_verification_email_login(string $email): bool {

        try {
            require_once('include/sendCodeMail.php');
            $code = self::create_verification_code_in_db_login($email);
            $mail = new PHPMailer(true);
            return sendCodeMail($mail, $email, $code);
        } catch (Exception $e) {
            return false;
        }
    }

    /**
     *  Create a new verification code in the database linked to an email and return it.
     *  In the case this email is already in the database, delete the row and create a new one.
     *  Doesn't work if the $cnx isn't setup.
     *
     * @param string $email the user's email.
     * @return string the verification code (6 random digits).
     */
    private static function create_verification_code_in_db_login(string $email, int $length = 6): string {
        global $cnx;
        if (self::email_present($email)) {
            $rm_query = $cnx->prepare("DELETE FROM login_verify WHERE email = :email");
            $rm_query->bindParam(":email", $email, PDO::PARAM_STR);
            $rm_query->execute();
        }
        $code = self::random_string($length);
        $datetime = date("Y-m-d H:i:s");

        // Add the newly created code :
        $add_query = $cnx->prepare("
            INSERT INTO login_verify (email, code, date) VALUES (:email, :code, :date)");
        $add_query->bindParam(":email", $email, PDO::PARAM_STR);
        $add_query->bindParam(":code", $code, PDO::PARAM_STR);
        $add_query->bindParam(":date", $datetime, PDO::PARAM_STR);
        $add_query->execute();

        return $code;
    }

    /**
     *
     *
     * @param string $email the user's email.
     * @return bool true if the verification code is still valid, false if not.
     */
    public static function check_expiration_login(string $email): bool {
        global $cnx;
        $stmt = $cnx->prepare("SELECT date FROM login_verify WHERE email = :email");
        $stmt->bindParam(':email', $email, PDO::PARAM_STR);
        $stmt->execute();
        $result = $stmt->fetch();

        if ($result != null) {
            $code_date = new DateTime($result['date']);
            $current_date = new DateTime();
            $code_date->add(new DateInterval('P10M'));

            if ($code_date < $current_date) {
                $rm_code = $cnx->prepare("DELETE FROM login_verify WHERE email = :email");
                $rm_code->bindParam(":email", $email, PDO::PARAM_STR);
                $rm_code->execute();
                return false;
            } else {
                return true;
            }
        } return false;
    }

    /**
     *
     *
     * @param string $email the user's email.
     * @param string $code
     * @return bool
     */
    public static function check_code_login(string $email, string $code): bool {
        global $cnx;
        $stmt = $cnx->prepare("SELECT code FROM login_verify WHERE email = :email");
        $stmt->bindParam(":email", $email, PDO::PARAM_STR);
        $stmt->execute();
        $result = $stmt->fetch();

        $code_db = strval($result["code"]);
        return strcmp($code, $code_db);
    }

    public static function delete_login_verify(string $email): bool {
        global $cnx;
        $stmt = $cnx->prepare("DELETE FROM login_verify WHERE email = :email");
        $stmt->bindParam(":email", $email, PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->rowCount() > 0;
    }

    /**
     *
     *
     * @param string $email the user's email.
     * @return bool true if the procedure went well, false if not.
     */
    public static function send_verification_email_change(string $email, string $password): bool {
        try {
            require_once('include/sendChangeMail.php');
            $code = self::create_verification_code_in_db_change($email, $password);
            $link = "http://localhost/Travia/recover?verify=" . $code . "&email=" . $email;
            $mail = new PHPMailer(true);
            return sendChangeMail($mail, $email, $link);
        } catch (Exception $e) {
            return false;
        }
    }

    /**
     *  Create a new verification code in the database linked to an email and return it.
     *  In the case this email is already in the database, delete the row and create a new one.
     *  Doesn't work if the $cnx isn't setup.
     *
     * @param string $email the user's email.
     * @return string the verification code (6 random digits).
     */
    private static function create_verification_code_in_db_change(string $email, string $password, int $length = 10): string {
        global $cnx;
        if (self::email_present($email)) {
            $rm_query = $cnx->prepare("DELETE FROM change_verify WHERE email = :email");
            $rm_query->bindParam(":email", $email, PDO::PARAM_STR);
            $rm_query->execute();
        }
        $code = self::random_string($length);
        $datetime = date("Y-m-d H:i:s");

        // Add the newly created code :
        $add_query = $cnx->prepare("
            INSERT INTO change_verify (email, code, date, password) VALUES (:email, :code, :date, :password)");
        $add_query->bindParam(":email", $email, PDO::PARAM_STR);
        $add_query->bindParam(":code", $code, PDO::PARAM_STR);
        $add_query->bindParam(":date", $datetime, PDO::PARAM_STR);
        $add_query->bindParam(":password", $password, PDO::PARAM_STR);
        $add_query->execute();

        return $code;
    }

    /**
     *
     *
     * @param string $email the user's email.
     * @return bool true if the verification code is still valid, false if not.
     */
    public static function check_expiration_change(string $email): bool {
        global $cnx;
        $stmt = $cnx->prepare("SELECT date FROM change_verify WHERE email = :email");
        $stmt->bindParam(':email', $email, PDO::PARAM_STR);
        $stmt->execute();
        $result = $stmt->fetch();

        if ($result != null) {
            $code_date = new DateTime($result['date']);
            $current_date = new DateTime();
            $code_date->add(new DateInterval('P10M'));

            if ($code_date < $current_date) {
                $rm_code = $cnx->prepare("DELETE FROM change_verify WHERE email = :email");
                $rm_code->bindParam(":email", $email, PDO::PARAM_STR);
                $rm_code->execute();
                return false;
            } else {
                return true;
            }
        } return false;
    }

    /**
     *
     *
     * @param string $email the user's email.
     * @param string $code
     * @return bool
     */
    public static function check_code_change(string $email, string $code): bool {
        global $cnx;
        $stmt = $cnx->prepare("SELECT code FROM change_verify WHERE email = :email");
        $stmt->bindParam(":email", $email, PDO::PARAM_STR);
        $stmt->execute();
        $result = $stmt->fetch();

        $code_db = strval($result["code"]);
        return strcmp($code, $code_db);
    }

    /**
     *
     *
     * @param string $email
     * @return string
     */
    public static function get_new_password_change(string $email): string {
        global $cnx;
        $stmt = $cnx->prepare("SELECT password FROM change_verify WHERE email = :email");
        $stmt->bindParam(":email", $email, PDO::PARAM_STR);
        $stmt->execute();
        $result = $stmt->fetch();
        return strval($result["password"]);
    }

    /**
     *
     *
     * @param string $email
     * @return bool
     */
    public static function delete_change_verify(string $email): bool {
        global $cnx;
        $stmt = $cnx->prepare("DELETE FROM change_verify WHERE email = :email");
        $stmt->bindParam(":email", $email, PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->rowCount() > 0;
    }

    /**
     *
     *
     * @param string $email the user's email.
     * @return bool
     */
    public static function register(string $email): bool {
        global $cnx;
        $stmt = $cnx->prepare("SELECT * FROM register_verify WHERE email = :email");
        $stmt->bindParam(":email", $email, PDO::PARAM_STR);
        $stmt->execute();
        $result = $stmt->fetch();
        if ($result != null) {
            $email = $result["email"];
            $password = $result["password"];
            $first_name = $result["first-name"];
            $last_name = $result["last-name"];
            $home_planet_id = $result["home-planet"];
            $work_planet_id = $result["work-planet"];

            $reg_stmt = $cnx->prepare("
                INSERT INTO account (email, `first-name`, `last-name`, password, `home-planet`, `work-planet`) 
                VALUES (:email, :first_name, :last_name, :password, :home_planet_id, :work_planet_id)");
            $reg_stmt->bindParam(":email", $email, PDO::PARAM_STR);
            $reg_stmt->bindParam(":first_name", $first_name, PDO::PARAM_STR);
            $reg_stmt->bindParam(":last_name", $last_name, PDO::PARAM_STR);
            $reg_stmt->bindParam(":password", $password, PDO::PARAM_STR);
            $reg_stmt->bindParam(":home_planet_id", $home_planet_id, PDO::PARAM_INT);
            $reg_stmt->bindParam(":work_planet_id", $work_planet_id, PDO::PARAM_INT);
            $reg_stmt->execute();
            if ($reg_stmt->rowCount() > 0) {
                $verify_stmt = $cnx->prepare("DELETE FROM register_verify WHERE email = :email");
                $verify_stmt->bindParam(":email", $email, PDO::PARAM_STR);
                $verify_stmt->execute();
                return true;
            } else return false;
        } else return false;
    }

    /**
     * Load a .env file as environment variables.
     *
     * @param string $file the file path.
     * @return void
     */
    public static function load_env_file(string $file) {
        if (file_exists($file)) {
            $lines = file($file, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
            foreach ($lines as $line) {
                if (strpos($line, '#') === 0 || empty($line)) continue;
                list($key, $value) = explode('=', $line, 2);
                putenv("$key=$value");
            }
        }
    }

    /**
     * Generate an url-safe token using the concatenated string of email and hashed password,
     * by default, the token's length is 16.
     *
     * @param string $email the account's email address.
     * @param string $password the account's password.
     * @param int $length the token's length (default = 16);
     * @return false|string the generated url-safe token as a string or false on failure.
     */
    public static function generate_token(string $email, string $password, int $length = 16) {
        $string = $email . $password;
        // Create a raw binary sha256 hash and base64 encode it :
        $hash_base64 = base64_encode(hash('sha256', $string, true));
        // Replace non-url-safe chars to make the string url-safe :
        $hash_url_safe = strtr($hash_base64, '+/', '-_');
        // Trim base64 padding characters from the end :
        $hash_url_safe = rtrim($hash_url_safe, '=');
        // return a shortened string :
        return substr($hash_url_safe, 0, $length);
    }
}