/**
 * Mobile Menu
 * Responsive navigation for mobile devices
 * 
 * @package Angola_B2B
 */

(function($) {
    'use strict';

    /**
     * Mobile Menu Class
     */
    class MobileMenu {
        constructor() {
            this.menuToggle = document.querySelector('.mobile-menu-toggle');
            this.navigation = document.querySelector('.main-navigation');
            this.body = document.body;
            this.isOpen = false;
            
            if (this.menuToggle && this.navigation) {
                this.init();
            }
        }

        init() {
            // Toggle button click
            this.menuToggle.addEventListener('click', (e) => {
                e.preventDefault();
                this.toggle();
            });

            // Close menu on escape key
            document.addEventListener('keydown', (e) => {
                if (e.key === 'Escape' && this.isOpen) {
                    this.close();
                }
            });

            // Close menu on outside click
            document.addEventListener('click', (e) => {
                if (this.isOpen && 
                    !this.navigation.contains(e.target) && 
                    !this.menuToggle.contains(e.target)) {
                    this.close();
                }
            });

            // Handle submenu toggles on mobile
            this.setupSubmenuToggles();

            // Close menu on window resize if desktop
            let resizeTimer;
            window.addEventListener('resize', () => {
                clearTimeout(resizeTimer);
                resizeTimer = setTimeout(() => {
                    if (window.innerWidth >= 1024 && this.isOpen) {
                        this.close();
                    }
                }, 250);
            });

            // Handle language switcher in mobile menu
            this.setupLanguageSwitcher();
        }

        toggle() {
            if (this.isOpen) {
                this.close();
            } else {
                this.open();
            }
        }

        open() {
            this.navigation.classList.add('active');
            this.menuToggle.setAttribute('aria-expanded', 'true');
            this.isOpen = true;
            
            // Lock body scroll
            if (window.AngolaB2B && window.AngolaB2B.lockBodyScroll) {
                window.AngolaB2B.lockBodyScroll();
            }

            // Trap focus within menu
            this.trapFocus();
        }

        close() {
            this.navigation.classList.remove('active');
            this.menuToggle.setAttribute('aria-expanded', 'false');
            this.isOpen = false;
            
            // Unlock body scroll
            if (window.AngolaB2B && window.AngolaB2B.unlockBodyScroll) {
                window.AngolaB2B.unlockBodyScroll();
            }

            // Remove focus trap
            this.removeFocusTrap();

            // Close all submenus
            const openSubmenus = this.navigation.querySelectorAll('.menu-item-has-children.active');
            openSubmenus.forEach(item => {
                item.classList.remove('active');
            });
        }

        setupSubmenuToggles() {
            const menuItems = this.navigation.querySelectorAll('.menu-item-has-children');
            
            menuItems.forEach(item => {
                const link = item.querySelector('a');
                
                // Create toggle button
                const toggle = document.createElement('button');
                toggle.className = 'submenu-toggle';
                toggle.setAttribute('aria-expanded', 'false');
                toggle.setAttribute('aria-label', 'Toggle submenu');
                
                const icon = document.createElement('span');
                icon.className = 'dashicons dashicons-arrow-down-alt2';
                toggle.appendChild(icon);
                
                // Insert toggle after link
                if (link && link.parentNode) {
                    link.parentNode.insertBefore(toggle, link.nextSibling);
                }
                
                // Toggle submenu on button click
                toggle.addEventListener('click', (e) => {
                    e.preventDefault();
                    e.stopPropagation();
                    
                    const isActive = item.classList.contains('active');
                    
                    // Close other submenus at same level
                    const siblings = Array.from(item.parentNode.children);
                    siblings.forEach(sibling => {
                        if (sibling !== item && sibling.classList.contains('menu-item-has-children')) {
                            sibling.classList.remove('active');
                            const siblingToggle = sibling.querySelector('.submenu-toggle');
                            if (siblingToggle) {
                                siblingToggle.setAttribute('aria-expanded', 'false');
                            }
                        }
                    });
                    
                    // Toggle current submenu
                    if (isActive) {
                        item.classList.remove('active');
                        toggle.setAttribute('aria-expanded', 'false');
                    } else {
                        item.classList.add('active');
                        toggle.setAttribute('aria-expanded', 'true');
                    }
                });
            });
        }

        setupLanguageSwitcher() {
            const languageSelect = this.navigation.querySelector('#language-select');
            
            if (languageSelect) {
                languageSelect.addEventListener('change', function() {
                    if (this.value) {
                        window.location.href = this.value;
                    }
                });
            }
        }

        trapFocus() {
            const focusableElements = this.navigation.querySelectorAll(
                'a[href], button:not([disabled]), input:not([disabled]), select:not([disabled]), textarea:not([disabled]), [tabindex]:not([tabindex="-1"])'
            );
            
            if (focusableElements.length === 0) return;
            
            const firstElement = focusableElements[0];
            const lastElement = focusableElements[focusableElements.length - 1];
            
            this.focusTrapHandler = (e) => {
                const isTabPressed = e.key === 'Tab';
                
                if (!isTabPressed) return;
                
                if (e.shiftKey) {
                    if (document.activeElement === firstElement) {
                        lastElement.focus();
                        e.preventDefault();
                    }
                } else {
                    if (document.activeElement === lastElement) {
                        firstElement.focus();
                        e.preventDefault();
                    }
                }
            };
            
            document.addEventListener('keydown', this.focusTrapHandler);
            
            // Focus first element
            setTimeout(() => {
                firstElement.focus();
            }, 100);
        }

        removeFocusTrap() {
            if (this.focusTrapHandler) {
                document.removeEventListener('keydown', this.focusTrapHandler);
                this.focusTrapHandler = null;
            }
            
            // Return focus to toggle button
            this.menuToggle.focus();
        }
    }

    /**
     * Initialize on DOM ready
     */
    function init() {
        new MobileMenu();
    }

    // Initialize
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', init);
    } else {
        init();
    }

})(jQuery);

