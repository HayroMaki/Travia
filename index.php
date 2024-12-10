<!DOCTYPE html>

<?php
    // To prevent the program to stop due to memory usage or execution time :
    ini_set('memory_limit', '4096M');
    ini_set('max_execution_time', 0);
    ini_set('display_errors', 1);
    error_reporting(E_ALL);

    // Set up the PDO
    require_once("include/setupPDO.php");
    require_once("include/includeClasses.php");

    global $ship_count;
    $ship_count = 0;

    global $planet_count;
    $planet_count = 0;

    global $trip_count;
    $trip_count = 0;

    if (isset($_GET['error'])) {
        if ($_GET['error'] == "empty_fields") {
            $error_msg = "Please fill in all fields.";

        } else if ($_GET['error'] == "invalid_planets") {
            $error_msg = "Please select valid planets.";

        } else if ($_GET['error'] == "same_fields") {
            $error_msg = "Please don't select the same planet as departure and destination.";
        }
    }

    $planets = json_encode(Planet::get_every_planet_for_map());

if ($planets === false) {
    echo "Erreur d'encodage JSON : " . json_last_error_msg();
    die();
}
?>

<html lang="fr">
    <head>
        <meta charset="UTF-8">
        <title>Travia</title>
        <link href="index.css?v=<?php echo time(); ?>" rel="stylesheet">

        <!-- Leaflet -->
        <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"/>
        <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    </head>
    <body>
        <?php
            include("include/header.inc.php");
            include("include/searchForm.php");
        ?>
        <div id="genDiv">
            <div id="map"></div>
        </div>
        <?php
            include("include/admin.php");
        ?>

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
            const planetss = <?php echo $planets ?>;

            console.log(<?php echo $planets; ?>);

            // Put every planet on the map :
            planetss.forEach(function(planet) {
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
                    `<div class='lightbox-planet-name'>${planet.name}</div>`
                );

                // Add animation when hovering (circle gets bigger for easier interaction) :
                circle.on('mouseover', function () {
                    animateCircle(this, this.options.radius, 7, 200);
                    this.bringToFront();
                    // Sometimes the circle stays big, so after 5sec, make it shrink :
                    setTimeout(() => {
                        animateCircle(this, this.options.radius, 3, 500);
                    }, 5000);
                });

                // When not hovering anymore, shrink it back :
                circle.on('mouseout', function () {
                    animateCircle(this, this.options.radius, 3, 200);
                });
            });
        </script>
    </body>
</html>