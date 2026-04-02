<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Roulette-Style Carousel</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/5.3.0/css/bootstrap.min.css">
    <style>
        #rouletteCarousel {
            position: relative;
            overflow: hidden;
        }

        .carousel-inner {
            display: flex;
            transition: transform 0.5s ease-in-out;
        }

        .carousel-item {
            min-width: 100%;
        }

        .carousel-control {
            position: absolute;
            top: 0;
            bottom: 0;
            width: 10%;
            font-size: 2rem;
            color: #fff;
            text-align: center;
            background: rgba(0, 0, 0, 0.5);
            cursor: pointer;
            opacity: 0;
            transition: opacity 0.3s ease-in-out;
        }

        .carousel-control:hover {
            opacity: 1;
        }

        .carousel-control-prev {
            left: 0;
        }

        .carousel-control-next {
            right: 0;
        }
    </style>
</head>
<body>

<div class="container mt-3">
    <div id="rouletteCarousel" class="carousel">
        <div class="carousel-inner">
            <?php
            // Example PHP loop to generate carousel items
            for ($i = 1; $i <= 8; $i++) {
                $activeClass = ($i === 1) ? 'active' : '';
                echo '<div class="carousel-item ' . $activeClass . '">
                        <button class="btn btn-primary" type="button">Item ' . $i . '</button>
                      </div>';
            }
            ?>
        </div>
        <div class="carousel-control-prev" onclick="changeDirection('prev')">&lt;</div>
        <div class="carousel-control-next" onclick="changeDirection('next')">&gt;</div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/5.3.0/js/bootstrap.min.js"></script>
<script>
    function changeDirection(direction) {
        const carouselInner = document.querySelector('.carousel-inner');
        const currentTransform = window.getComputedStyle(carouselInner).getPropertyValue('transform');
        const currentPosition = parseInt(currentTransform.split(',')[4]);

        if (direction === 'next') {
            carouselInner.style.transform = `translateX(${currentPosition - 100}%)`;
        } else if (direction === 'prev') {
            carouselInner.style.transform = `translateX(${currentPosition + 100}%)`;
        }
    }

    const rouletteCarousel = document.getElementById('rouletteCarousel');
    rouletteCarousel.addEventListener('mouseenter', function () {
        this.classList.add('hovered');
    });

    rouletteCarousel.addEventListener('mouseleave', function () {
        this.classList.remove('hovered');
    });
</script>

</body>
</html>
