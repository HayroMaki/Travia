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

    /**
     * @var array|string[]
     * an array of strings where keys are name of regions and values are colors.
     * For each region associates a different color to show on the map.
     */
    private static array $region_color = [
        "Colonies" => "#fa777a",
        "Core" => "#e48700",
        "Deep Core" => "#b79a08",
        "Expansion Region" => "#bc9c03",
        "Extragalactic" => "#20be03",
        "Hutt Space" => "#0fb879",
        "Inner Rim Territories" => "#00c2a6",
        "Mid Rim Territories" => "#00badc",
        "Outer Rim Territories" => "#06a7fc",
        "Talcene Sector" => "#8f8ffc",
        "The Centrality" => "#ce70fc",
        "Tingel Arm" => "#f060dc",
        "Wild Space" => "#fe60ad"
    ];

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

    /**
     * Adds the planet to the database.
     * Doesn't work if the $cnx isn't setup.
     *
     * @return void
     */
    public function add_planet_to_db() : void {
        global $cnx;

        $query = "INSERT INTO planet (name,image,coord,x,y,sunName,subGridCoord,subGridX,subGridY,region,sector,suns,moons,position,distance,lengthDay,lengthYear,diameter,gravity) 
                VALUES (:name, :images, :coord, :x, :y, :sun_name, :sub_grid_coord, :sub_grid_x, :sub_grid_y, :region, :sector, :suns, :moons, :position, :distance, :length_day, :length_year, :diameter, :gravity)
                ";

        $stmt = $cnx->prepare($query);

        $stmt->bindParam(':name', $this->name, PDO::PARAM_STR);
        $stmt->bindParam(':images', $this->image, PDO::PARAM_STR);
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

    /**
     * Calculate the simple distance between two planets using their x and y coordinates.
     *
     * @param Planet $planet the planet to get the distance to.
     *
     * @return array an array of two elements :
     * the distance in billion kilometers and in light years.
     */
    public function getDistanceWith(Planet $planet): array {
        $p1_x = ($this->getX() + $this->getSubGridX()) * 6;
        $p1_y = ($this->getY() + $this->getSubGridY()) * 6;

        $p2_x = ($planet->getX() + $planet->getSubGridX()) * 6;
        $p2_y = ($planet->getY() + $planet->getSubGridY()) * 6;

        $const_billion_km_to_light_year = 9460.7379375591;

        $result_in_billion_km = sqrt((pow(($p1_x - $p2_x),2)) + (pow(($p1_y - $p2_y),2)));
        $result_in_light_year = $result_in_billion_km/$const_billion_km_to_light_year;

        return array($result_in_billion_km,$result_in_light_year);
    }

    /**
     * Get the url for the image of the planet from the site Wookipedia.
     * If there is no image, it will use the no_image.png in the data/images file.
     *
     * @return string the url of the image.
     */
    public function getImageUrl(): string {
        global $cnx;

        if ($this->image == "no_image.png") return "data/images/no_image.png";

        $image = str_replace(' ','_',$this->image);

        $md5 = md5($image);
        $first_md5 = substr($md5, 0, 1);
        $second_md5 = substr($md5, 0, 2);

        $url = "https://static.wikia.nocookie.net/starwars/images/$first_md5/$second_md5/$image";

        return $url;
    }

    /**
     * Completely clear the datas from the planet table of the database.
     * Doesn't work if the $cnx isn't setup.
     *
     * @return void
     */
    public static function clear_planet_db(): void {
        global $cnx;

        $query = "TRUNCATE TABLE planet";

        $stmt = $cnx->prepare($query);
        $stmt->execute();
    }

    /**
     * Create a Planet object from the given name using the datas of the database.
     * Doesn't work if the $cnx isn't setup.
     *
     * @param string $name the planet's name.
     *
     * @return Planet|null if the planet is not in the database, return null.
     */
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

    /**
     * Create an array containing the names of every planet in the database.
     * Doesn't work if the $cnx isn't setup.
     *
     * @return array array of planet names as strings.
     */
    public static function get_every_planet_name(): array {
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
            $result[] = strval($row['name']);
        }

        return $result;
    }

    /**
     * Create an array containing information for every planet in the db used to generate the map,
     * such as their name, calculated x/y coordinates, image url, and color based on their region.
     * Doesn't work if the $cnx isn't setup.
     *
     * @return array an array containing 'name', 'x', 'y', 'image' and 'color' keys.
     */
    public static function get_every_planet_for_map(): array {
        $result = array();
        $planets = Planet::get_every_planet_name();

        foreach ($planets as $planet) {
            $planet_obj = Planet::get_planet_from_name($planet);

            $result[] = [
                'name' => $planet_obj->getName(),
                'x' => ($planet_obj->getX()+$planet_obj->getSubGridX()) * 6,
                'y' => ($planet_obj->getY()+$planet_obj->getSubGridY()) * 6,
                'color' => Planet::$region_color[$planet_obj->getRegion()],
                'image' => $planet_obj->getImageUrl(),
            ];
        }

        return $result;
    }

    /**
     * Check if a planet is in the database using its name.
     * Doesn't work if the $cnx isn't setup.
     *
     * @param string $name the planet's name.
     *
     * @return bool true if exists in the database, if not : false.
     */
    public static function check_if_present(string $name): bool {
        global $cnx;

        $query = "SELECT name FROM planet WHERE name = ?";

        $stmt = $cnx->prepare($query);
        $stmt->bindParam(1, $name);
        $stmt->execute();
        $fetch = $stmt->fetchAll();

        return !empty($fetch);
    }
}