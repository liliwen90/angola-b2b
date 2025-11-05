/**
 * Statistics Counter Animation
 * Animated number counting on scroll
 * 
 * @package Angola_B2B
 */

(function() {
    'use strict';

    // Initialize when DOM is ready
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', init);
    } else {
        init();
    }

    function init() {
        initObserver();
    }

    function initObserver() {
        const statItems = document.querySelectorAll('.stat-item');
        
        if (statItems.length === 0) return;

        // Intersection Observer for triggering animations
        const observerOptions = {
            root: null,
            rootMargin: '0px',
            threshold: 0.3
        };

        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    const item = entry.target;
                    const delay = parseInt(item.dataset.delay) || 0;
                    
                    setTimeout(() => {
                        item.classList.add('animate-in');
                        animateNumbers(item);
                    }, delay);
                    
                    observer.unobserve(item);
                }
            });
        }, observerOptions);

        statItems.forEach(item => {
            observer.observe(item);
        });
    }

    function animateNumbers(item) {
        const numberElement = item.querySelector('.stat-number');
        
        if (!numberElement) return;

        const target = parseInt(numberElement.dataset.target) || 0;
        const suffix = numberElement.dataset.suffix || '';
        const duration = 2000; // 2 seconds
        const frameRate = 1000 / 60; // 60 FPS
        const totalFrames = Math.round(duration / frameRate);
        let frame = 0;

        const counter = setInterval(() => {
            frame++;
            const progress = easeOutQuart(frame / totalFrames);
            const currentNumber = Math.round(progress * target);
            
            numberElement.textContent = formatNumber(currentNumber) + suffix;
            numberElement.classList.add('counting');
            
            if (frame === totalFrames) {
                clearInterval(counter);
                numberElement.classList.remove('counting');
            }
        }, frameRate);
    }

    function formatNumber(num) {
        // Add thousand separators for numbers >= 1000
        if (num >= 1000) {
            return num.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ',');
        }
        return num.toString();
    }

    // Easing function for smooth animation
    function easeOutQuart(t) {
        return 1 - Math.pow(1 - t, 4);
    }

})();

