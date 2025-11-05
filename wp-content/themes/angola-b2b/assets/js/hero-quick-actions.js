/**
 * Hero Quick Action Tabs
 * Handle tab switching in hero section
 * 
 * @package Angola_B2B
 */

(function() {
    'use strict';

    // Wait for DOM to be ready
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initQuickActions);
    } else {
        initQuickActions();
    }

    function initQuickActions() {
        const tabs = document.querySelectorAll('.quick-action-tab');
        const panels = document.querySelectorAll('.action-panel');

        if (tabs.length === 0 || panels.length === 0) return;

        tabs.forEach(tab => {
            tab.addEventListener('click', function() {
                const tabName = this.dataset.tab;

                // Remove active class from all tabs
                tabs.forEach(t => t.classList.remove('active'));
                
                // Add active class to clicked tab
                this.classList.add('active');

                // Hide all panels
                panels.forEach(panel => panel.classList.remove('active'));
                
                // Show corresponding panel
                const activePanel = document.querySelector(`[data-panel="${tabName}"]`);
                if (activePanel) {
                    activePanel.classList.add('active');
                }
            });

            // Add keyboard navigation
            tab.addEventListener('keydown', function(e) {
                if (e.key === 'Enter' || e.key === ' ') {
                    e.preventDefault();
                    this.click();
                }
            });
        });

        // Form validation and enhancement
        const trackingForm = document.querySelector('.tracking-form');
        if (trackingForm) {
            trackingForm.addEventListener('submit', function(e) {
                const input = this.querySelector('input[name="tracking_number"]');
                if (!input.value.trim()) {
                    e.preventDefault();
                    input.focus();
                }
            });
        }

        const quoteForm = document.querySelector('.quote-form');
        if (quoteForm) {
            quoteForm.addEventListener('submit', function(e) {
                const input = this.querySelector('input[name="product_name"]');
                if (!input.value.trim()) {
                    e.preventDefault();
                    input.focus();
                }
            });
        }
    }

    // Add subtle parallax effect to hero background if enabled
    function initParallax() {
        const heroes = document.querySelectorAll('.hero-section[data-parallax="true"]');
        
        heroes.forEach(hero => {
            const background = hero.querySelector('.hero-background');
            if (!background) return;

            let ticking = false;

            window.addEventListener('scroll', function() {
                if (!ticking) {
                    window.requestAnimationFrame(function() {
                        const scrolled = window.pageYOffset;
                        const rate = scrolled * 0.5;
                        
                        if (hero.getBoundingClientRect().top + hero.offsetHeight > 0) {
                            background.style.transform = `translate3d(0, ${rate}px, 0)`;
                        }
                        
                        ticking = false;
                    });

                    ticking = true;
                }
            });
        });
    }

    // Initialize parallax
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initParallax);
    } else {
        initParallax();
    }
})();

