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
}