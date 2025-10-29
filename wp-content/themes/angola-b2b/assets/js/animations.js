/**
 * GSAP Animations
 * Scroll-triggered animations and page transitions
 * 
 * @package Angola_B2B
 */

(function() {
    'use strict';

    /**
     * Check if GSAP is loaded
     */
    if (typeof gsap === 'undefined' || typeof ScrollTrigger === 'undefined') {
        // GSAP not loaded, exit gracefully
        return;
    }

    // Register ScrollTrigger plugin
    gsap.registerPlugin(ScrollTrigger);

    /**
     * Animation Controller Class
     */
    class AnimationController {
        constructor() {
            this.init();
        }

        init() {
            // Set GSAP defaults
            gsap.defaults({
                ease: 'power3.out',
                duration: 0.8
            });

            // Initialize animations
            this.initScrollReveal();
            this.initHeroAnimations();
            this.initNumberCounters();
            this.initProgressBars();
            this.initParallax();
            this.initStaggeredCards();

            // Refresh ScrollTrigger on window resize
            this.handleResize();
        }

        /**
         * Scroll reveal animations
         */
        initScrollReveal() {
            const reveals = document.querySelectorAll('.scroll-reveal, .scroll-reveal-left, .scroll-reveal-right, .scroll-reveal-scale');
            
            if (reveals.length === 0) return;

            reveals.forEach(element => {
                const direction = this.getRevealDirection(element);
                
                gsap.from(element, {
                    scrollTrigger: {
                        trigger: element,
                        start: 'top 80%',
                        toggleActions: 'play none none none',
                        once: true
                    },
                    ...direction,
                    opacity: 0,
                    duration: 0.8
                });
            });
        }

        /**
         * Get reveal direction based on class
         */
        getRevealDirection(element) {
            if (element.classList.contains('scroll-reveal-left')) {
                return { x: -50 };
            } else if (element.classList.contains('scroll-reveal-right')) {
                return { x: 50 };
            } else if (element.classList.contains('scroll-reveal-scale')) {
                return { scale: 0.9 };
            }
            return { y: 30 };
        }

        /**
         * Hero section animations
         */
        initHeroAnimations() {
            const hero = document.querySelector('.hero-banner');
            if (!hero) return;

            const title = hero.querySelector('.hero-title');
            const subtitle = hero.querySelector('.hero-subtitle');
            const cta = hero.querySelector('.hero-cta');

            const timeline = gsap.timeline({
                defaults: { ease: 'power3.out' }
            });

            if (title) {
                timeline.from(title, {
                    y: 50,
                    opacity: 0,
                    duration: 1
                });
            }

            if (subtitle) {
                timeline.from(subtitle, {
                    y: 30,
                    opacity: 0,
                    duration: 0.8
                }, '-=0.5');
            }

            if (cta) {
                timeline.from(cta, {
                    scale: 0.8,
                    opacity: 0,
                    duration: 0.6
                }, '-=0.3');
            }
        }

        /**
         * Animated number counters
         */
        initNumberCounters() {
            const counters = document.querySelectorAll('.animated-number');
            
            if (counters.length === 0) return;

            counters.forEach(counter => {
                const target = parseFloat(counter.dataset.target);
                const decimals = parseInt(counter.dataset.decimals) || 0;
                
                if (isNaN(target)) return;

                gsap.from(counter, {
                    scrollTrigger: {
                        trigger: counter,
                        start: 'top 80%',
                        toggleActions: 'play none none none',
                        once: true
                    },
                    textContent: 0,
                    duration: 2,
                    ease: 'power1.out',
                    snap: { textContent: decimals === 0 ? 1 : 0.1 },
                    onUpdate: function() {
                        const value = parseFloat(this.targets()[0].textContent);
                        this.targets()[0].textContent = value.toFixed(decimals);
                    }
                });
            });
        }

        /**
         * Progress bars animation
         */
        initProgressBars() {
            const progressBars = document.querySelectorAll('.spec-level-fill');
            
            if (progressBars.length === 0) return;

            progressBars.forEach(bar => {
                const level = parseInt(bar.dataset.level);
                
                if (isNaN(level)) return;

                gsap.from(bar, {
                    scrollTrigger: {
                        trigger: bar,
                        start: 'top 80%',
                        toggleActions: 'play none none none',
                        once: true
                    },
                    width: '0%',
                    duration: 1.5,
                    ease: 'power2.out',
                    onUpdate: function() {
                        const progress = this.progress();
                        bar.style.width = (level * progress) + '%';
                    }
                });
            });
        }

        /**
         * Parallax scrolling effect
         */
        initParallax() {
            const parallaxElements = document.querySelectorAll('[data-parallax]');
            
            if (parallaxElements.length === 0) return;

            parallaxElements.forEach(element => {
                const speed = parseFloat(element.dataset.parallax) || 0.5;
                
                gsap.to(element, {
                    scrollTrigger: {
                        trigger: element,
                        start: 'top bottom',
                        end: 'bottom top',
                        scrub: true
                    },
                    y: (i, target) => ScrollTrigger.maxScroll(window) * speed,
                    ease: 'none'
                });
            });
        }

        /**
         * Staggered card animations
         */
        initStaggeredCards() {
            const cardContainers = document.querySelectorAll('.products-grid, .advantages-grid');
            
            if (cardContainers.length === 0) return;

            cardContainers.forEach(container => {
                const cards = container.querySelectorAll('.product-card, .advantage-card');
                
                if (cards.length === 0) return;

                gsap.from(cards, {
                    scrollTrigger: {
                        trigger: container,
                        start: 'top 70%',
                        toggleActions: 'play none none none',
                        once: true
                    },
                    y: 50,
                    opacity: 0,
                    duration: 0.6,
                    stagger: 0.1,
                    ease: 'back.out(1.2)'
                });
            });
        }

        /**
         * Handle window resize
         */
        handleResize() {
            let resizeTimer;
            window.addEventListener('resize', () => {
                clearTimeout(resizeTimer);
                resizeTimer = setTimeout(() => {
                    ScrollTrigger.refresh();
                }, 250);
            });
        }
    }

    /**
     * Page transition effects
     */
    class PageTransitions {
        constructor() {
            this.initPageLoad();
            this.initSmoothScroll();
        }

        /**
         * Page load animation
         */
        initPageLoad() {
            const body = document.body;
            
            gsap.from(body, {
                opacity: 0,
                duration: 0.5,
                ease: 'power2.out'
            });
        }

        /**
         * Smooth scroll for anchor links
         */
        initSmoothScroll() {
            const links = document.querySelectorAll('a[href^="#"]');
            
            links.forEach(link => {
                link.addEventListener('click', (e) => {
                    const href = link.getAttribute('href');
                    
                    if (href === '#' || href === '#!') return;
                    
                    const target = document.querySelector(href);
                    
                    if (!target) return;
                    
                    e.preventDefault();
                    
                    gsap.to(window, {
                        duration: 0.8,
                        scrollTo: {
                            y: target,
                            offsetY: 100
                        },
                        ease: 'power3.inOut'
                    });
                });
            });
        }
    }

    /**
     * Back to top button
     */
    class BackToTop {
        constructor() {
            this.button = document.querySelector('.back-to-top');
            
            if (!this.button) return;
            
            this.init();
        }

        init() {
            // Show/hide button on scroll
            ScrollTrigger.create({
                start: 'top -200',
                end: 'max',
                onUpdate: (self) => {
                    if (self.direction === -1) {
                        this.button.classList.add('visible');
                    } else if (self.progress < 0.1) {
                        this.button.classList.remove('visible');
                    }
                }
            });

            // Click handler
            this.button.addEventListener('click', () => {
                gsap.to(window, {
                    duration: 1,
                    scrollTo: { y: 0 },
                    ease: 'power3.inOut'
                });
            });
        }
    }

    /**
     * Initialize all animations on DOM ready
     */
    function init() {
        new AnimationController();
        new PageTransitions();
        new BackToTop();
    }

    // Initialize
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', init);
    } else {
        init();
    }

})();

