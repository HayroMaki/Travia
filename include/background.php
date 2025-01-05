<!-- this background was made entirely by my classmate Guillaume Augeraud -->

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Background étoilé</title>
    <style>

        .canvas-bg {
            display: block;
            position: absolute;
            top: 0;
            left: 0;
            pointer-events: none; /* Désactive l'interaction utilisateur sur le canvas */
        }
    </style>
</head>
<body>
<canvas id="starry-background" class="canvas-bg"></canvas>
<script>
    // Initialisation du canvas
    const canvas = document.getElementById('starry-background');
    const ctx = canvas.getContext('2d');

    function resizeCanvas() {
        canvas.width = window.innerWidth-20;
        canvas.height = window.innerHeight+130;

        // Réinitialiser les étoiles pour qu'elles soient réparties correctement
        stars.forEach(star => {
            star.x = Math.random() * canvas.width;
            star.y = Math.random() * canvas.height;
        });

        // Réinitialiser les météores pour éviter qu'ils soient hors cadre
        meteors.forEach(meteor => {
            meteor.x = Math.random() * canvas.width;
            meteor.y = Math.random() * canvas.height * 0.5;
        });
    }
    // Variables
    const stars = [];
    const meteors = [];
    const numStars = 700;

    const meteorLineLenght = Math.random() * 15 + 1;
    const meteorOpacityDecrease = 0.01;
    const meteorSpeedMultiplicator = 10;

        // Classe pour les étoiles
        class Star {
            constructor() {
                this.x = Math.random() * canvas.width;
                this.y = Math.random() * canvas.height;
                this.size = Math.random() * 2;
                this.speed = Math.random() * 0.5;
            }

            draw() {
                ctx.beginPath();
                ctx.arc(this.x, this.y, this.size, 0, Math.PI * 2);
                ctx.fillStyle = 'white';
                ctx.fill();
            }

            update() {
                this.y += this.speed;
                if (this.y > canvas.height) {
                    this.y = 0;
                    this.x = Math.random() * canvas.width;
                }
            }
        }

    // Classe pour les météorites
    class Meteor {
        constructor() {
            this.x = Math.random() * canvas.width;
            this.y = Math.random() * canvas.height; // Multiplie par 0.5 pour ne faire apparaitre que sur la partie haute du site
            this.size = Math.random() * 3 + 1;
            this.speedX = Math.random() * meteorSpeedMultiplicator + 2;
            this.speedY = Math.random() * 2 + 1;
            this.opacity = 1;
        }

        draw() {
            ctx.beginPath();
            ctx.moveTo(this.x, this.y);
            ctx.lineTo(this.x - this.speedX * meteorLineLenght, this.y - this.speedY * meteorLineLenght);
            ctx.strokeStyle = `rgba(255, 255, 255, ${this.opacity})`;
            ctx.lineWidth = this.size;
            ctx.stroke();
        }

        update() {
            this.x += this.speedX;
            this.y += this.speedY;
            this.opacity -= meteorOpacityDecrease;
            if (this.opacity <= 0) {
                this.reset();
            }
        }

        reset() {
            this.x = Math.random() * canvas.width;
            this.y = Math.random() * canvas.height * 0.5;
            this.size = Math.random() * 3 + 1;
            this.speedX = Math.random() * meteorSpeedMultiplicator + 2;
            this.speedY = Math.random() * 2 + 1;
            this.opacity = 1;
        }
    }

    // Création des étoiles
    for (let i = 0; i < numStars; i++) {
        stars.push(new Star());
    }

    // Fonction pour générer des météorites aléatoirement
    function generateMeteor() {
        meteors.push(new Meteor());
        if (meteors.length > 10) {
            meteors.shift(); // Limiter le nombre de météores
        }
    }

    // Animation
    function animate() {
        ctx.clearRect(0, 0, canvas.width, canvas.height);

        // Met à jour et dessine les étoiles
        stars.forEach(star => {
            star.update();
            star.draw();
        });

        // Met à jour et dessine les météorites
        meteors.forEach(meteor => {
            meteor.update();
            meteor.draw();
        });

        requestAnimationFrame(animate);
    }
    setInterval(generateMeteor, 1500);
    window.addEventListener('resize', resizeCanvas);

    // Met le canvas à la taille initiale de la fenêtre et démarre l'animation
    resizeCanvas();
    animate();
</script>
</body>
</html>
