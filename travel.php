<!DOCTYPE html>

<?php
    session_start();
    // Check that the user is connected :
    if (!isset($_SESSION["email"])) {
        header("Location:login.php");
    }

    // Check or add the cart cookie :
    if (!isset($_COOKIE['cart'])) {
        setcookie('cart', serialize([]), time() + 7200, '/');
    }

    require_once "include/setupPDO.php";
    require_once "include/includeClasses.php";

    $travel_id = $_GET['travel'];
    $selected_shipper = $ship = Ship::get_every_ship()[0]->getName();

    if (empty($travel_id)) {
        header('Location: index.php');
    }

    $travel = Travel::getTravelFromId($travel_id);
    $departure = $travel->getDeparture();
    $departure_name = Planet::get_name_from_id($departure);
    $destination = $travel->getDestination();
    $destination_name = Planet::get_name_from_id($destination);
    $distance = $travel->getDistance();
    $price = $travel->getPrice();
    $time = $travel->getTime();
    $path = $travel->getPath();
    $path_name = json_encode(Planet::planetArrayName($path));

    $selected_ship = strval($selected_shipper);

    $dep_obj = Planet::get_planet_from_id($departure);
    $dest_obj = Planet::get_planet_from_id($destination);

    list($distance_km,$distance_ly) = $dep_obj->getDistanceWith($dest_obj);

    $planets = json_encode(Planet::get_every_planet_for_map());
    $ships = Ship::get_every_ship();
    $selected_ship_obj = Ship::remove_ship_by_name($ships, $selected_ship);

    // Include cart
    include("cart/cart.php");
    include("include/fontSelector.php");
?>

<script>
    loadFont();
</script>

<html lang="fr">
    <head>
        <meta charset="UTF-8">
        <title><?php echo "Travia" ?></title>
        <link rel="stylesheet" href="index.css?v=<?php echo time(); ?>">
        <link rel="stylesheet" href="cart/cart.css">

        <!-- Leaflet -->
        <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"/>
        <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    </head>
    <body>
        <?php include("include/header.inc.php"); ?>

        <div class="main">
            <div class="selected-travel">
                <div id="info-container">
                    <div class="planet-info">
                        <div class="planet-image">
                            <img class="circle" src="<?php echo $dep_obj->getImageUrl()?>" alt="<?php echo $dep_obj->getName(); ?> image">
                        </div>
                        <h2>Departure</h2>
                        <h1><?php echo $dep_obj->getName() ?></h1>
                        <h2>At <span id="important">hh:mm</span></h2>
                    </div>
                    <div class="travel-info">
                        <h2>Time</h2>
                        <h2><span id="important"><?= $time[0] ?>:<?= $time[1] ?></span></h2>
                        <h2>Distance</h2>
                        <h2><span id="important"><?php echo round($distance,2) ?> billion km</span></h2>
                        <br>
                        <img class="travel-arrow" src="data/icons/travel_arrow.png" alt="arrow">
                        <br>
                        <h2>Aboard the <span id="important"><?= $selected_ship_obj->getName() ?></span></h2>
                        <h2>Ticket price : <span id="important"><?= $selected_ship_obj->get_price($distance_km) ?> cred.</span></h2>
                        <h3>Remaining tickets : <?= $selected_ship_obj->getCapacity() ?></h3>

                        <a class="addToCartButton" href="cart/addToCart.php?departure=<?php echo $departure; ?>&arrival=<?php echo $destination; ?>&ship=<?php echo $selected_shipper; ?>">
                            <b>Add to cart</b>
                        </a>
                    </div>
                    <div class="planet-info">
                        <div class="planet-image">
                            <img class="circle" src="<?php echo $dest_obj->getImageUrl()?>" alt="<?php echo $dest_obj->getName(); ?> image">
                        </div>
                        <h2>Destination</h2>
                        <h1><?php echo $dest_obj->getName() ?></h1>
                        <h2>At <span id="important">hh:mm</span></h2>
                    </div>
                </div>
                <div id="map-container">
                    <div id="map-spacer"></div>
                    <div id="map"></div>
                    <div id="ticket-selector-container">
                        <form action="" method="post"></form>
                    </div>
                </div>
            </div>
        </div>

        <!--
        <div><h1 id="more-travels-title">More Interesting Travels</h1></div>
        <hr id="white-line">
        -->


        <?php /* foreach ($ships as $ship) {
            $s_time = $ship->get_time($distance_km);
            ?>
            <a href="travel.php?Departure=<?= $departure ?>&Destination=<?= $destination ?>&Ship=<?= $ship->getName() ?>" class="non-selected-travel-link">
                <button class="non-selected-travel">
                    <span><?= $departure ?> -> <?= $destination ?></span>
                    <span>|</span>
                    <span><?= $s_time[0] ?>:<?= $s_time[1] ?></span>
                    <span>|</span>
                    <span>aboard the <?= $ship->getName() ?></span>
                    <span>|</span>
                    <span>ticket price: <?= $ship->get_price($distance_km) ?> cred.</span>
                </button>
            </a>
        <?php } */?>
        </form>

        <script>
            const yx = L.latLng;
            const xy = function (x, y) {
                if (Array.isArray(x)) {    // When doing xy([x, y]);
                    return yx(x[1], x[0]);
                }
                return yx(y, x);  // When doing xy(x, y);
            };

            // Take a color and give back a slighly darker version (for the circles borders) :
            function darkenColor(hex, percent = 10) {
                hex = hex.replace('#', '');

                const r = parseInt(hex.substring(0, 2), 16);
                const g = parseInt(hex.substring(2, 4), 16);
                const b = parseInt(hex.substring(4, 6), 16);

                const darken = (component) => Math.max(0, Math.min(255, component - (component * percent) / 100));

                const rDarker = Math.round(darken(r));
                const gDarker = Math.round(darken(g));
                const bDarker = Math.round(darken(b));

                const toHex = (value) => value.toString(16).padStart(2, '0');
                return `#${toHex(rDarker)}${toHex(gDarker)}${toHex(bDarker)}`;
            }

            // Animate the growing/shrinking of the given circle in the map :
            function animateCircle(circle, startRadius, endRadius, duration) {
                const frames = 30;
                const step = (endRadius - startRadius) / frames;
                const interval = duration / frames;

                let currentRadius = startRadius;
                let currentFrame = 0;

                const grow = setInterval(() => {
                    currentFrame++;
                    currentRadius += step;

                    circle.setStyle({ radius: currentRadius });

                    if (currentFrame >= frames) {
                        clearInterval(grow);
                    }
                }, interval);
            }

            // Create the map :
            const map = L.map('map', {
                crs: L.CRS.Simple,
                zoom: 1.45,
                minZoom: 1.45,
                maxZoom: 5
            });

            // Create the canvas (so that the points show up) :
            const renderer = L.canvas();

            // Set up the bounds and background :
            const bounds = [[-31, -31], [155,155]];
            var image = L.tileLayer('data/images/no_image.png', bounds, {
                pane: "mapPane",
            }).addTo(map);
            map.fitBounds(bounds);
            map.setMaxBounds(bounds);

            // Add the points :
            const planets = <?php echo $planets ?>;

            const dep_name = <?php echo json_encode($departure_name) ?>;
            const dest_name = <?php echo json_encode($destination_name) ?>;
            const path = <?php echo $path_name ?>;
            const path_circle = {};

            let dep_planet_circle;
            let dest_planet_circle;
            let dep_planet_xy;
            let dest_planet_xy;

            // Put every planet on the map :
            planets.forEach(function(planet) {
                const add = xy(planet.x, planet.y);
                const circle = L.circleMarker(add, {
                    radius: 3, // size of circle
                    weight: 1, // size of border
                    renderer: renderer,
                    color: darkenColor(planet.color, 50), // darker on the outside
                    fillColor: planet.color,
                    fillOpacity: 1,
                }).addTo(map).bindPopup(
                    // Add the popup :
                    `<div class="planet-image lightbox-planet-image" style="width: 100px; height: 100px"><img class="circle" src="${planet.image}" alt="${planet.name} image"></div>` +
                    `<div class='lightbox-planet-name'>${planet.name}</div>` +
                    `<div class='lightbox-planet-text'>${planet.region}</div>` +
                    `<div class='lightbox-planet-text lightbox-get-up'>${planet.sector}</div>`
                );

                if (path.includes(planet.name)){
                    path_circle[planet.name] = add;
                    circle.setStyle({
                        color: "#FFFFFF"
                    });
                }

                // Store the departure and destination planets :
                if (planet.name === dep_name) {
                    dep_planet_circle = circle;
                    dep_planet_xy = add;

                } else if (planet.name === dest_name) {
                    dest_planet_circle = circle;
                    dest_planet_xy = add;

                } else {
                    // Animations only on non-selected planets :
                    // Add animation when hovering (circle gets bigger for easier interaction) :
                    circle.on('mouseover', function () {
                        animateCircle(this, this.options.radius, 7, 200);
                        this.bringToFront();
                        // Sometimes the circle stays big, so after 5sec, make it shrink :
                        setTimeout(() => {
                            animateCircle(this, this.options.radius, 3, 500);
                            // Just in case, bring selected back to front :
                            dep_planet_circle.bringToFront();
                            dest_planet_circle.bringToFront();
                        }, 5000);
                    });

                    // When not hovering anymore, shrink it back :
                    circle.on('mouseout', function () {
                        animateCircle(this, this.options.radius, 3, 200);
                        // Just in case, bring selected back to front :
                        dep_planet_circle.bringToFront();
                        dest_planet_circle.bringToFront();
                    });
                }
            });

            // For the departure and destination planets, put them in white, and bigger :
            animateCircle(dep_planet_circle,dep_planet_circle.options.radius,6,0);
            dep_planet_circle.options.color = darkenColor("#ffffff",50);
            dep_planet_circle.options.fillColor = "#ffffff";

            animateCircle(dest_planet_circle,dest_planet_circle.options.radius,6,0);
            dest_planet_circle.options.color = darkenColor("#ffffff",50);
            dest_planet_circle.options.fillColor = "#ffffff";

            // Draw a direct line between the two planets :
            /* const line = L.polyline([dep_planet_xy,dest_planet_xy],{
                weight: 4,
                color: "#ffffff",
            }).addTo(map); */
           path.forEach(function(id) {
               console.log(id === dest_name);
                if (!(id === dest_name)) {
                    console.log(id);
                    const start = path_circle[id];
                    const end = path_circle[path[path.indexOf(id) + 1]];
                    const line = L.polyline([start, end], {
                        weight: 4,
                        color: "#FFFFFF",
                    }).addTo(map);
                }
            });

            // Bring to front after creating the line so that the click isn't buggy :
            dep_planet_circle.bringToFront();
            dest_planet_circle.bringToFront();
        </script>

        <?php
            include("include/footer.inc.php");
            require_once("include/background.php");
        ?>
    </body>
</html>


