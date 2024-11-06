<?php

class Trip
{
    private int $departure_planet_id;
    private int $destination_planet_id;
    private string $departure_day;
    private string $departure_time;
    private int $ship_id;

    public function __construct(int $departure_planet_id, int $destination_planet_id, string $departure_day, string $departure_time, int $ship_id)
    {
        $this->departure_planet_id = $departure_planet_id;
        $this->destination_planet_id = $destination_planet_id;
        $this->departure_day = $departure_day;
        $this->departure_time = $departure_time;
        $this->ship_id = $ship_id;
    }

    public function getDeparturePlanetId(): int
    {
        return $this->departure_planet_id;
    }

    public function getDestinationPlanetId(): int
    {
        return $this->destination_planet_id;
    }

    public function getDepartureDay(): string
    {
        return $this->departure_day;
    }

    public function getDepartureTime(): string
    {
        return $this->departure_time;
    }

    public function getShipId(): int
    {
        return $this->ship_id;
    }
    public function toString(): string {
        return "depPlanet Id:".$this->departure_planet_id .
            ", destPlanet Id:".$this->destination_planet_id .
            ", depDay:".$this->departure_day .
            ", depTime:".$this->departure_time .
            ", ship Id:".$this->ship_id ;
    }
    public static function clear_trip_db(): void {
        global $cnx;

        $query = "TRUNCATE TABLE trip";

        $stmt = $cnx->prepare($query);
        $stmt->execute();
    }
    public function add_trip_to_db() : void
    {
        global $cnx;

        $query = "INSERT INTO trip (departurePlanetId, destinationPlanetId, departureDay, departureTime, shipId) 
                VALUES (:departurePlanetId, :destinationPlanetId, :departureDay, :departureTime, :shipId)";

        $stmt = $cnx->prepare($query);

        $stmt->bindParam(':departurePlanetId', $this->departure_planet_id, PDO::PARAM_INT);
        $stmt->bindParam(':destinationPlanetId', $this->destination_planet_id, PDO::PARAM_INT);
        $stmt->bindParam(':departureDay', $this->departure_day, PDO::PARAM_STR);
        $stmt->bindParam(':departureTime', $this->departure_time, PDO::PARAM_STR);
        $stmt->bindParam(':shipId', $this->ship_id, PDO::PARAM_INT);

        $result = $stmt->execute();

        if (!$result) {
            echo "Something went wrong when trying to add new trip.\n";
        }
    }
}