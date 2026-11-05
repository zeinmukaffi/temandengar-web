import './bootstrap';
import '../css/app.css';

import 'bootstrap';

document.addEventListener('DOMContentLoaded', () => {

    // Scroll reveal animation
    const revealElements = document.querySelectorAll('[data-reveal]');

    if (!('IntersectionObserver' in window)) {
        revealElements.forEach(element => {
            element.classList.add('is-visible');
        });

        return;
    }

    document.documentElement.classList.add('js-reveal');

    const observer = new IntersectionObserver((entries, currentObserver) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('is-visible');
                currentObserver.unobserve(entry.target);
            }
        });
    }, {
        threshold: 0.12
    });

    revealElements.forEach(element => observer.observe(element));

});