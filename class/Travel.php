<?php

class Travel {
    private int $id;
    private float $time;
    private float $price;
    private float $distance;
    private int $departure;
    private int $destination;
    private String $cost;
    private String $filters;
    private array $path;

    public function __construct(int $id, float $time, float $price, float $distance, int $departure, int $destination, int $cost, string $filters, string $txt) {
        $this->id = $id;
        $this->time = $time;
        $this->price = $price;
        $this->distance = $distance;
        $this->departure = $departure;
        $this->destination = $destination;
        $this->cost = $cost;
        $this->filters = $filters;
        $this->path = self::toPath($txt);
    }

    public function getId(): int {
        return $this->id;
    }
    public function getTime(): float {
        return $this->time;
    }
    public function getPrice(): float {
        return $this->price;
    }
    public function getDistance(): float {
        return $this->distance;
    }
    public function getDeparture(): float {
        return $this->departure;
    }
    public function getDestination(): float {
        return $this->destination;
    }
    public function getPath(): array {
        return $this->path;
    }

    /**
     * Transform a String into a path array.
     *
     * @return array the array of the path's planets ids.
     */
    public static function toPath(String $path): array {
        return explode(",", str_replace("[","",str_replace("]","",$path)));
    }

    /**
     * Get the id in the DB of the searched travel based on the departure and destination planets ids,
     * the selected type of cost and the filters, or null if not present.
     * Doesn't work if the $cnx isn't setup.
     *
     * @param int $dep the departure planet id.
     * @param int $dest the destination planet id.
     * @param String $cost the cost type (either "Distance","Price" or "Speed").
     * @param String $filters the filters separated with commas (ex: "Empire,Rebelles");
     * @return int|null the travel id in the DB or null if not found.
     */
    public static function check_travel(int $dep, int $dest, String $cost, String $filters): ?int {
        global $cnx;

        $query = "SELECT * FROM travel WHERE departure = :dep AND destination = :dest AND cost = :cost AND filters = :filters";
        $stmt = $cnx->prepare($query);

        $stmt->bindParam(':dep', $dep, PDO::PARAM_STR);
        $stmt->bindParam(':dest', $dest, PDO::PARAM_STR);
        $stmt->bindParam(':cost', $cost, PDO::PARAM_STR);
        $stmt->bindParam(':filters', $filters, PDO::PARAM_STR);

        $stmt->execute();
        $result = $stmt->fetch();

        if (empty($result)) {
            return -1;
        }
        return $result;
    }

    /**
     * Get a travel object from the DB using its id, or null if not present.
     * Doesn't work if the $cnx isn't setup.
     *
     * @param int $id the travel's id.
     * @return Travel|null the travel object or null if not found.
     */
    public static function getTravelFromId(int $id): ?Travel {
        global $cnx;

        $query = "SELECT * FROM travel WHERE id = :id";
        $stmt = $cnx->prepare($query);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        $f = $stmt->fetch();
        $f = $f[0];

        if (empty($f)) {
            return null;
        }
        return new Travel($f["id"],
            $f["time"],$f["price"],$f["distance"],
            $f["departure"],$f["destination"],
            $f["cost"],$f["filters"],$f["txt"]);
    }

    public static function addTravel(string $time, float $price, float $distance, int $departure, int $destination, string $cost, string $filters, string $txt): void {
        global $cnx;

        $insert = "INSERT INTO travel VALUES(0,:time,:price,:distance,:departure,:destination,:cost,:filters,:txt)";

        $stmt = $cnx->prepare($insert);

        $stmt->bindParam(':time', $time, PDO::PARAM_STR);
        $stmt->bindParam(':price', $price);
        $stmt->bindParam(':distance', $distance);
        $stmt->bindParam(':departure', $departure, PDO::PARAM_INT);
        $stmt->bindParam(':destination', $destination, PDO::PARAM_INT);
        $stmt->bindParam(':cost', $cost, PDO::PARAM_STR);
        $stmt->bindParam(':filters', $filters, PDO::PARAM_STR);
        $stmt->bindParam(':txt', $txt,PDO::PARAM_STR);

        $stmt->execute();


    }

}