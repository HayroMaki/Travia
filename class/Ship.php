<?php

class Ship
{
    private int $id;
    private string $name;
    private string $camp;
    private float $speed_kmh;
    private int $capacity;
    public function __construct(int $id, string $name, string $camp, float $speed_kmh, int $capacity) {
        $this->id = $id;
        $this->name = $name;
        $this->camp = $camp;
        $this->speed_kmh = $speed_kmh;
        $this->capacity = $capacity;
    }
    public function getId(): int {
        return $this->id;
    }
    public function getName(): string {
        return $this->name;
    }
    public function getCamp(): string {
        return $this->camp;
    }
    public function getSpeedKmh(): float {
        return $this->speed_kmh;
    }
    public function getCapacity(): int {
        return $this->capacity;
    }
    public function toString(): string {
        return "id:".$this->id . ", name:".$this->name . ", camp:".$this->camp . ", speed:".$this->speed_kmh . ", capacity:".$this->capacity;
    }

    /**
     * Adds the ship to the database.
     * Doesn't work if the $cnx isn't setup.
     *
     * @return void
     */
    public function add_ship_to_db() : void {
        global $cnx;

        $query = "INSERT INTO ship VALUES (:id, :name, :camp, :speed_kmh, :capacity)";

        $stmt = $cnx->prepare($query);

        $stmt->bindParam(":id", $this->id, PDO::PARAM_INT);
        $stmt->bindParam(":name", $this->name, PDO::PARAM_STR);
        $stmt->bindParam(":camp", $this->camp, PDO::PARAM_STR);
        $stmt->bindParam(":speed_kmh", $this->speed_kmh, PDO::PARAM_STR);
        $stmt->bindParam(":capacity", $this->capacity, PDO::PARAM_INT);

        $stmt->execute();
    }

    /**
     * Completely clear the datas from the ship table in the database.
     * Doesn't work if the $cnx isn't setup.
     *
     * @return void
     */
    public static function clear_ship_db(): void {
        global $cnx;

        $query = "TRUNCATE TABLE ship";

        $stmt = $cnx->prepare($query);
        $stmt->execute();
    }

    /**
     * Return an array of Ship object from the database.
     * Doesn't work if the $cnx isn't setup.
     *
     * @return array an array of ship object.
     */
    public static function get_every_ship(): array {
        global $cnx;

        $query = "SELECT * FROM ship ORDER BY speed_kmh";

        $stmt = $cnx->prepare($query);
        $stmt->execute();

        $fetch = $stmt->fetchAll();

        if (empty($fetch)) {
            return [];
        }

        $result = array();
        foreach ($fetch as $row) {
            $result[] = new Ship($row['id'], $row['name'], $row['camp'], $row['speed_kmh'], $row['capacity']);
        }

        return $result;
    }

    /**
     *
     *
     * @param array $ships
     * @param string $name
     *
     * @return Ship|null
     */
    public static function remove_ship_by_name(array &$ships, string $name): ?Ship {
        foreach ($ships as $key => $ship) {
            if ($ship->getName() === $name) {
                // Store the removed ship :
                $removedShip = $ship;
                unset($ships[$key]);
                // Re-index the array to maintain sequential keys :
                $ships = array_values($ships);
                return $removedShip;
            }
        }
        return null;
    }

    /**
     *
     *
     * @return void
     */
    public function get_time(float $distance): array {
        // The $distance is in billion of km, so we need to change that into km :
        $distance_km = $distance * pow(10,9);

        $time = $distance_km/$this->getSpeedKmh();

        $hour = floor($time);
        $fraction = $time - $hour;

        $minute = round($fraction * 60);
        if ($minute < 10) {
            $minute = "0" . $minute;
        }

        return [$hour, $minute];
    }

    public function get_price(float $distance): float {
        $price = 100 * $distance;

        $speed = $this->getSpeedKmh()/pow(10,9);

        if ($speed > 1.08) {
            $speed_modifier = $speed/1.08;
            $price = $price + ($speed_modifier * $price);
        } else {
            $price = 2 * $price;
        }

        return round($price);
    }
}