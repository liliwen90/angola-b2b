/**
 * Mega Menu Navigation JavaScript
 * Handles hover delays, mobile interactions, and accessibility
 * 
 * @package Angola_B2B
 */

(function() {
    'use strict';

    let hoverTimeout = null;
    const HOVER_DELAY = 150; // Delay before closing menu (ms)
    const MOBILE_BREAKPOINT = 1024;

    /**
     * Initialize mega menu functionality
     */
    function initMegaMenu() {
        const megaMenuNav = document.getElementById('mega-menu-navigation');
        if (!megaMenuNav) {
            return;
        }

        const menuItems = megaMenuNav.querySelectorAll('.mega-menu-item');
        if (menuItems.length === 0) {
            return;
        }

        // Only initialize on desktop
        if (window.innerWidth < MOBILE_BREAKPOINT) {
            return;
        }

        // Setup hover handlers for each menu item
        menuItems.forEach(function(menuItem) {
            const dropdown = menuItem.querySelector('.mega-menu-dropdown');
            const link = menuItem.querySelector('.mega-menu-link');
            
            if (!dropdown || !link) {
                return;
            }

            // Mouse enter: show dropdown immediately
            menuItem.addEventListener('mouseenter', function() {
                clearTimeout(hoverTimeout);
                showDropdown(menuItem, dropdown, link);
            });

            // Mouse leave: delay hiding dropdown
            menuItem.addEventListener('mouseleave', function() {
                hoverTimeout = setTimeout(function() {
                    hideDropdown(menuItem, dropdown, link);
                }, HOVER_DELAY);
            });

            // Focus (keyboard navigation): show dropdown
            link.addEventListener('focus', function() {
                clearTimeout(hoverTimeout);
                showDropdown(menuItem, dropdown, link);
            });

            // Blur (keyboard navigation): hide dropdown
            menuItem.addEventListener('blur', function(e) {
                // Check if focus is moving to another element within the menu
                if (!menuItem.contains(e.relatedTarget)) {
                    hideDropdown(menuItem, dropdown, link);
                }
            });

            // Escape key: close dropdown
            dropdown.addEventListener('keydown', function(e) {
                if (e.key === 'Escape') {
                    hideDropdown(menuItem, dropdown, link);
                    link.focus();
                }
            });
        });

        // Close all dropdowns when clicking outside
        document.addEventListener('click', function(e) {
            if (!megaMenuNav.contains(e.target)) {
                closeAllDropdowns(menuItems);
            }
        });
    }

    /**
     * Show dropdown menu
     */
    function showDropdown(menuItem, dropdown, link) {
        menuItem.classList.add('active');
        dropdown.setAttribute('aria-hidden', 'false');
        link.setAttribute('aria-expanded', 'true');
        
        // Update ARIA attributes for accessibility
        const subcategoryLinks = dropdown.querySelectorAll('.mega-menu-subcategory-item a');
        subcategoryLinks.forEach(function(subLink) {
            subLink.setAttribute('tabindex', '0');
        });
    }

    /**
     * Hide dropdown menu
     */
    function hideDropdown(menuItem, dropdown, link) {
        menuItem.classList.remove('active');
        dropdown.setAttribute('aria-hidden', 'true');
        link.setAttribute('aria-expanded', 'false');
        
        // Update ARIA attributes for accessibility
        const subcategoryLinks = dropdown.querySelectorAll('.mega-menu-subcategory-item a');
        subcategoryLinks.forEach(function(subLink) {
            subLink.setAttribute('tabindex', '-1');
        });
    }

    /**
     * Close all dropdown menus
     */
    function closeAllDropdowns(menuItems) {
        menuItems.forEach(function(menuItem) {
            const dropdown = menuItem.querySelector('.mega-menu-dropdown');
            const link = menuItem.querySelector('.mega-menu-link');
            
            if (dropdown && link) {
                hideDropdown(menuItem, dropdown, link);
            }
        });
    }

    /**
     * Handle window resize
     */
    function handleResize() {
        if (window.innerWidth >= MOBILE_BREAKPOINT) {
            initMegaMenu();
        } else {
            // On mobile, ensure dropdowns are hidden
            const megaMenuNav = document.getElementById('mega-menu-navigation');
            if (megaMenuNav) {
                const menuItems = megaMenuNav.querySelectorAll('.mega-menu-item');
                closeAllDropdowns(menuItems);
            }
        }
    }

    // Initialize on DOM ready
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initMegaMenu);
    } else {
        initMegaMenu();
    }

    // Re-initialize on window resize (with debounce)
    let resizeTimeout;
    window.addEventListener('resize', function() {
        clearTimeout(resizeTimeout);
        resizeTimeout = setTimeout(handleResize, 250);
    });

})();

