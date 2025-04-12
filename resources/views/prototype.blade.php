<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cube 3D Rotatif</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        /* Conteneur de la scène 3D */
        .scene {
            width: 300px;
            height: 300px;
            margin: auto;
            perspective: 1000px; /* Pour donner l'effet 3D */
        }

        /* Cube 3D */
        .cube {
            width: 100%;
            height: 100%;
            position: relative;
            transform-style: preserve-3d;
            animation: rotateCube 10s infinite linear;
        }

        /* Faces du cube */
        .cube-face {
            position: absolute;
            width: 300px;
            height: 300px;
            background-color: #f3f3f3;
            border: 1px solid #ccc;
        }

        .cube-face img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        /* Positionnement des différentes faces */
        .cube-face-front { transform: rotateY(0deg) translateZ(150px); }
        .cube-face-back { transform: rotateY(180deg) translateZ(150px); }
        .cube-face-left { transform: rotateY(-90deg) translateZ(150px); }
        .cube-face-right { transform: rotateY(90deg) translateZ(150px); }
        .cube-face-top { transform: rotateX(90deg) translateZ(150px); }
        .cube-face-bottom { transform: rotateX(-90deg) translateZ(150px); }

        /* Animation de rotation du cube */
        @keyframes rotateCube {
            from {
                transform: rotateX(0deg) rotateY(0deg);
            }
            to {
                transform: rotateX(360deg) rotateY(360deg);
            }
        }
    </style>
</head>
<body>
    <div class="container text-center my-5">
        <h2>Cube 3D Rotatif</h2>
        <div class="scene">
            <div class="cube">
                <div class="cube-face cube-face-front">
                    <img src="https://via.placeholder.com/300" alt="Image 1">
                </div>
                <div class="cube-face cube-face-back">
                    <img src="https://via.placeholder.com/300" alt="Image 2">
                </div>
                <div class="cube-face cube-face-left">
                    <img src="https://via.placeholder.com/300" alt="Image 3">
                </div>
                <div class="cube-face cube-face-right">
                    <img src="https://via.placeholder.com/300" alt="Image 4">
                </div>
                <div class="cube-face cube-face-top">
                    <img src="https://via.placeholder.com/300" alt="Image 5">
                </div>
                <div class="cube-face cube-face-bottom">
                    <img src="https://via.placeholder.com/300" alt="Image 6">
                </div>
            </div>
        </div>
    </div>

    <!-- Script Bootstrap -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
