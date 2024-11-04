<?php

class ship
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

    public static function clear_ship_db(): void {
        global $cnx;

        $query = "TRUNCATE TABLE ship";

        $stmt = $cnx->prepare($query);
        $stmt->execute();
    }

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
}