<?php

class Account {
    private string $email;
    private string $first_name;
    private string $last_name;
    private ?int $home_planet;
    private ?int $work_planet;

    public function __construct(string $email, string $first_name, string $last_name, ?int $home_planet, ?int $work_planet) {
        $this->email = $email;
        $this->first_name = $first_name;
        $this->last_name = $last_name;
        $this->home_planet = $home_planet;
        $this->work_planet = $work_planet;
    }

    public function get_email(): string {
        return $this->email;
    }
    public function get_first_name(): string {
        return $this->first_name;
    }
    public function get_last_name(): string {
        return $this->last_name;
    }
    public function get_home_planet(): ?int {
        return $this->home_planet;
    }
    public function get_work_planet(): ?int {
        return $this->work_planet;
    }

    /**
     *
     *
     * @param string $email
     * @return Account|null
     */
    public static function get_account_from_mail(string $email): ?Account {
        global $cnx;

        $stmt = $cnx->prepare("SELECT * FROM account WHERE email = ?");
        $stmt->bindParam(1, $email, PDO::PARAM_STR);
        $stmt->execute();
        $f = $stmt->fetch();

        if (empty($f)) {
            return null;
        }

        return new Account($f['email'],$f['first-name'],$f['last-name'], $f['home-planet'], $f['work-planet']);
    }

    public function delete(): bool {
        global $cnx;
        $stmt = $cnx->prepare("DELETE FROM account WHERE email = ?");
        $stmt->bindParam(1, $this->email, PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->rowCount() > 0;
    }

    public static function change_password(string $email, string $password): bool {
        global $cnx;
        $stmt = $cnx->prepare("UPDATE account SET password = ? WHERE email = ?");
        $stmt->bindParam(1, $password, PDO::PARAM_STR);
        $stmt->bindParam(2, $email, PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->rowCount() > 0;
    }
}