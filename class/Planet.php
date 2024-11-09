<?php

class Planet
{
    private string $name;
    private ?string $image;
    private ?string $coord;
    private float $x;
    private float $y;
    private ?string $sun_name;
    private ?string $sub_grid_coord;
    private float $sub_grid_x;
    private float $sub_grid_y;
    private string $region;
    private string $sector;
    private int $suns;
    private int $moons;
    private int $position;
    private float $distance;
    private float $length_day;
    private float $length_year;
    private float $diameter;
    private float $gravity;

    public function __construct(string $name, ?string $image,
                                ?string $coord, float $x, float $y,
                                ?string $sun_name,
                                ?string $sub_grid_coord, float $sub_grid_x, float $sub_grid_y,
                                string $region, string $sector, int $suns, int $moons,
                                int $position, float $distance, float $length_day, float $length_year,
                                float $diameter, float $gravity) {
        $this->name = $name;
        $this->image = $image;
        $this->coord = $coord;
        $this->x = $x;
        $this->y = $y;
        $this->sun_name = $sun_name;
        $this->sub_grid_coord = $sub_grid_coord;
        $this->sub_grid_x = $sub_grid_x;
        $this->sub_grid_y = $sub_grid_y;
        $this->region = $region;
        $this->sector = $sector;
        $this->suns = $suns;
        $this->moons = $moons;
        $this->position = $position;
        $this->distance = $distance;
        $this->length_day = $length_day;
        $this->length_year = $length_year;
        $this->diameter = $diameter;
        $this->gravity = $gravity;
    }
    public function getName(): string
    {
        return $this->name;
    }

    public function getImage(): string
    {
        return $this->image;
    }

    public function getCoord(): string
    {
        return $this->coord;
    }
    public function getX(): float
    {
        return $this->x;
    }
    public function getY(): float
    {
        return $this->y;
    }
    public function getSunName(): string
    {
        return $this->sun_name;
    }
    public function getSubGridCoord(): string
    {
        return $this->sub_grid_coord;
    }
    public function getSubGridX(): float
    {
        return $this->sub_grid_x;
    }
    public function getSubGridY(): float
    {
        return $this->sub_grid_y;
    }
    public function getRegion(): string
    {
        return $this->region;
    }
    public function getSector(): string
    {
        return $this->sector;
    }
    public function getSuns(): int
    {
        return $this->suns;
    }
    public function getMoons(): int
    {
        return $this->moons;
    }
    public function getPosition(): int
    {
        return $this->position;
    }
    public function getDistance(): float
    {
        return $this->distance;
    }
    public function getLengthDay(): float
    {
        return $this->length_day;
    }
    public function getLengthYear(): float
    {
        return $this->length_year;
    }
    public function getDiameter(): float
    {
        return $this->diameter;
    }
    public function getGravity(): float
    {
        return $this->gravity;
    }
    public static function clear_planet_db(): void {
        global $cnx;

        $query = "TRUNCATE TABLE planet";

        $stmt = $cnx->prepare($query);
        $stmt->execute();
    }
    public static function get_every_planet(): array {
        global $cnx;

        $query = "SELECT name FROM planet";

        $stmt = $cnx->prepare($query);
        $stmt->execute();
        $fetch = $stmt->fetchAll();

        if (empty($fetch)) {
            return [];
        }
        $result = array();
        foreach ($fetch as $row) {
            $result[] = $row['name'];
        }
        return $result;
    }
    public static function check_if_present(string $name): bool {
        global $cnx;

        $query = "SELECT name FROM planet WHERE name = ?";

        $stmt = $cnx->prepare($query);
        $stmt->bindParam(1, $name);
        $stmt->execute();
        $fetch = $stmt->fetchAll();

        return !empty($fetch);
    }
    public function add_planet_to_db() : void {
        global $cnx;

        $query = "INSERT INTO planet (name,image,coord,x,y,sunName,subGridCoord,subGridX,subGridY,region,sector,suns,moons,position,distance,lengthDay,lengthYear,diameter,gravity) 
                VALUES (:name, :image, :coord, :x, :y, :sun_name, :sub_grid_coord, :sub_grid_x, :sub_grid_y, :region, :sector, :suns, :moons, :position, :distance, :length_day, :length_year, :diameter, :gravity)
                ";

        $stmt = $cnx->prepare($query);

        $stmt->bindParam(':name', $this->name, PDO::PARAM_STR);
        $stmt->bindParam(':image', $this->image, PDO::PARAM_STR);
        $stmt->bindParam(':coord', $this->coord, PDO::PARAM_STR);
        $stmt->bindParam(':x', $this->x, PDO::PARAM_STR);
        $stmt->bindParam(':y', $this->y, PDO::PARAM_STR);
        $stmt->bindParam(':sun_name', $this->sun_name, PDO::PARAM_STR);
        $stmt->bindParam(':sub_grid_coord', $this->sub_grid_coord, PDO::PARAM_STR);
        $stmt->bindParam(':sub_grid_x', $this->sub_grid_x, PDO::PARAM_STR);
        $stmt->bindParam(':sub_grid_y', $this->sub_grid_y, PDO::PARAM_STR);
        $stmt->bindParam(':region', $this->region, PDO::PARAM_STR);
        $stmt->bindParam(':sector', $this->sector, PDO::PARAM_STR);
        $stmt->bindParam(':suns', $this->suns, PDO::PARAM_INT);
        $stmt->bindParam(':moons', $this->moons, PDO::PARAM_INT);
        $stmt->bindParam(':position', $this->position, PDO::PARAM_INT);
        $stmt->bindParam(':distance', $this->distance, PDO::PARAM_STR);
        $stmt->bindParam(':length_day', $this->length_day, PDO::PARAM_STR);
        $stmt->bindParam(':length_year', $this->length_year, PDO::PARAM_STR);
        $stmt->bindParam(':diameter', $this->diameter, PDO::PARAM_STR);
        $stmt->bindParam(':gravity', $this->gravity, PDO::PARAM_STR);

        $stmt->execute();
    }
    public static function getGalacticCenter() : Planet {
        return new Planet(
            "Core",null,
            null,0,0,
            null,
            null,0.0,0.0,
            "Core","Core",0,0,
            0,0,0,0,0,0);
    }
    public static function get_planet_from_name(string $name): ?Planet {
        global $cnx;

        $query = "SELECT * FROM planet WHERE name = ?";
        $stmt = $cnx->prepare($query);

        $stmt->bindParam(1, $name, PDO::PARAM_STR);

        $stmt->execute();
        $f = $stmt->fetchAll();

        $f = $f[0];

        if (empty($f)) {
            return null;
        }
        return new Planet(
            $f['name'],$f['image'],
            $f['coord'],$f['x'],$f['y'],
            $f['sunName'],
            $f['subGridCoord'],$f['subGridX'],$f['subGridY'],
            $f['region'],$f['sector'],$f['suns'],$f['moons'],
            $f['position'],$f['distance'],$f['lengthDay'],$f['lengthYear'],
            $f['diameter'],$f['gravity']);
    }
    public function getDistanceWith(Planet $planet): array {
        $p1_x = ($this->getX()+$this->getSubGridX());
        $p1_y = ($this->getY()+$this->getSubGridY());

        $p2_x = ($planet->getX()-$planet->getSubGridX());
        $p2_y = ($planet->getY()-$planet->getSubGridY());

        $const_billion_km_to_light_year = 9460.7379375591;

        $result_in_billion_km = sqrt((pow(2,$p1_x - $p2_x)) + (pow(2,$p1_y - $p2_y)));
        $result_in_light_year = $result_in_billion_km/$const_billion_km_to_light_year;

        return array($result_in_billion_km,$result_in_light_year);
    }
}