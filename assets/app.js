/*
 * Welcome to your app's main JavaScript file!
 *
 * This file will be included onto the page via the importmap() Twig function,
 * which should already be in your base.html.twig.
 */
import './styles/app.scss';

const burger = document.querySelector('.burger');
const nav = document.querySelector('.nav-links');

burger.addEventListener('click', () => {
    nav.classList.toggle('nav-active');
    burger.classList.toggle('toggle');
});


document.addEventListener("DOMContentLoaded", function () {
    const carousels = document.querySelectorAll('.portfolio-carousel');

    carousels.forEach(carousel => {
        const images = carousel.querySelectorAll('.carousel img');
        const indicators = carousel.querySelectorAll('.indicator');
        let currentIndex = 0;

        function updateCarousel(index) {
            images.forEach((img, i) => {
                img.style.display = i === index ? 'block' : 'none';
            });

            indicators.forEach((ind, i) => {
                if (i === index) {
                    ind.classList.add('active');
                } else {
                    ind.classList.remove('active');
                }
            });
        }

        // Set initial state
        updateCarousel(currentIndex);

        indicators.forEach((indicator, i) => {
            indicator.addEventListener('click', () => {
                currentIndex = i;
                updateCarousel(currentIndex);
            });
        });
    });
});

