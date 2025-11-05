/**
 * Tab Navigation JavaScript
 * Handles tab switching and smooth scrolling
 * 
 * @package Angola_B2B
 */

(function() {
    'use strict';

    /**
     * Initialize Tab Navigation
     */
    function initTabNavigation() {
        const tabNavigation = document.querySelector('.tab-navigation');
        if (!tabNavigation) {
            return;
        }

        const tabLinks = tabNavigation.querySelectorAll('.tab-link');
        const sections = document.querySelectorAll('[id^="overview"], [id^="specifications"], [id^="certifications"], [id^="cases"], [id^="products"], [id^="advantages"]');

        if (tabLinks.length === 0) {
            return;
        }

        // Handle tab click
        tabLinks.forEach(function(tabLink) {
            tabLink.addEventListener('click', function(e) {
                e.preventDefault();
                
                const targetId = this.getAttribute('data-tab');
                const targetSection = document.getElementById(targetId);
                
                if (targetSection) {
                    // Update active tab
                    tabLinks.forEach(function(link) {
                        link.classList.remove('active');
                        link.setAttribute('aria-selected', 'false');
                    });
                    
                    this.classList.add('active');
                    this.setAttribute('aria-selected', 'true');
                    
                    // Smooth scroll to section
                    const offset = 100; // Offset for sticky header
                    const targetPosition = targetSection.getBoundingClientRect().top + window.pageYOffset - offset;
                    
                    window.scrollTo({
                        top: targetPosition,
                        behavior: 'smooth'
                    });
                    
                    // Update URL hash without triggering scroll
                    if (history.pushState) {
                        history.pushState(null, null, '#' + targetId);
                    }
                }
            });
        });

        // Update active tab on scroll (optional - for sticky navigation)
        if (tabNavigation.classList.contains('tab-navigation-sticky') && sections.length > 0) {
            let ticking = false;

            function updateActiveTab() {
                const scrollPosition = window.pageYOffset + 150; // Offset for detection

                sections.forEach(function(section) {
                    const sectionTop = section.offsetTop;
                    const sectionHeight = section.offsetHeight;
                    const sectionId = section.getAttribute('id');

                    if (scrollPosition >= sectionTop && scrollPosition < sectionTop + sectionHeight) {
                        tabLinks.forEach(function(link) {
                            const linkTab = link.getAttribute('data-tab');
                            if (linkTab === sectionId) {
                                link.classList.add('active');
                                link.setAttribute('aria-selected', 'true');
                            } else {
                                link.classList.remove('active');
                                link.setAttribute('aria-selected', 'false');
                            }
                        });
                    }
                });

                ticking = false;
            }

            window.addEventListener('scroll', function() {
                if (!ticking) {
                    window.requestAnimationFrame(updateActiveTab);
                    ticking = true;
                }
            });
        }

        // Handle initial hash in URL
        if (window.location.hash) {
            const hash = window.location.hash.substring(1);
            const targetTab = tabNavigation.querySelector('[data-tab="' + hash + '"]');
            if (targetTab) {
                targetTab.click();
            }
        }
    }

    // Initialize when DOM is ready
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initTabNavigation);
    } else {
        initTabNavigation();
    }
})();

