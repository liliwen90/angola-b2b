/**
 * Main JavaScript
 * Core functionality and initialization
 * 
 * @package Angola_B2B
 */

(function($) {
    'use strict';

    /**
     * Main App Class
     */
    class AngolaB2BApp {
        constructor() {
            this.init();
        }

        init() {
            this.initHeaderScroll();
            this.initBackToTop();
            this.initSocialShare();
            this.initPrintButton();
            this.initTabsSystem();
            this.initAccordions();
            this.initTooltips();
            this.initImageLightbox();
        }

        /**
         * MSC-style header scroll effect
         * - 默认状态：完全透明背景，白色文字和图标
         * - 滚动后：白色背景出现，黑色文字和图标
         */
        initHeaderScroll() {
            const header = document.querySelector('.site-header');
            if (!header) return;

            let ticking = false;
            const scrollThreshold = 50; // 滚动阈值降低，更快响应

            const updateHeader = () => {
                const currentScroll = window.pageYOffset || document.documentElement.scrollTop;
                
                if (currentScroll > scrollThreshold) {
                    // 滚动后：添加scrolled类，白色背景出现
                    header.classList.add('scrolled');
                } else {
                    // 页面顶部：移除scrolled类，保持透明
                    header.classList.remove('scrolled');
                }

                ticking = false;
            };

            // Throttle scroll events for better performance
            window.addEventListener('scroll', () => {
                if (!ticking) {
                    window.requestAnimationFrame(updateHeader);
                    ticking = true;
                }
            }, { passive: true });

            // Initialize on load
            updateHeader();
            
            // Update on resize (header height might change)
            window.addEventListener('resize', () => {
                if (!ticking) {
                    window.requestAnimationFrame(updateHeader);
                    ticking = true;
                }
            }, { passive: true });
        }

        /**
         * Back to top button functionality
         */
        initBackToTop() {
            const backToTopBtn = document.querySelector('.back-to-top');
            if (!backToTopBtn) return;

            // Show/hide on scroll
            let scrollTimer;
            window.addEventListener('scroll', () => {
                clearTimeout(scrollTimer);
                scrollTimer = setTimeout(() => {
                    if (window.pageYOffset > 300) {
                        backToTopBtn.classList.add('visible');
                    } else {
                        backToTopBtn.classList.remove('visible');
                    }
                }, 100);
            });

            // Click handler
            backToTopBtn.addEventListener('click', () => {
                window.scrollTo({
                    top: 0,
                    behavior: 'smooth'
                });
            });
        }

        /**
         * Social share functionality
         */
        initSocialShare() {
            const shareButtons = document.querySelectorAll('.share-product, .share-button');
            
            shareButtons.forEach(button => {
                button.addEventListener('click', (e) => {
                    e.preventDefault();
                    
                    const sharePopup = document.querySelector('.share-popup');
                    if (sharePopup) {
                        sharePopup.style.display = 'block';
                    }
                });
            });

            // Close share popup
            const closeButtons = document.querySelectorAll('.close-share-popup');
            closeButtons.forEach(button => {
                button.addEventListener('click', () => {
                    const sharePopup = document.querySelector('.share-popup');
                    if (sharePopup) {
                        sharePopup.style.display = 'none';
                    }
                });
            });

            // Social share links
            const socialLinks = document.querySelectorAll('.social-share-buttons a');
            socialLinks.forEach(link => {
                link.addEventListener('click', (e) => {
                    e.preventDefault();
                    
                    const url = link.getAttribute('href');
                    if (!url) return;

                    window.open(url, 'share', 'width=600,height=400');
                });
            });
        }

        /**
         * Print button functionality
         */
        initPrintButton() {
            const printButtons = document.querySelectorAll('.print-product');
            
            printButtons.forEach(button => {
                button.addEventListener('click', () => {
                    window.print();
                });
            });
        }

        /**
         * Tabs system
         */
        initTabsSystem() {
            const tabButtons = document.querySelectorAll('.tab-button');
            
            if (tabButtons.length === 0) return;

            tabButtons.forEach(button => {
                button.addEventListener('click', () => {
                    const tabId = button.dataset.tab;
                    if (!tabId) return;

                    // Get parent tabs container
                    const tabsContainer = button.closest('.product-tabs');
                    if (!tabsContainer) return;

                    // Remove active class from all buttons
                    const allButtons = tabsContainer.querySelectorAll('.tab-button');
                    allButtons.forEach(btn => btn.classList.remove('active'));

                    // Add active class to clicked button
                    button.classList.add('active');

                    // Hide all tab panes
                    const allPanes = tabsContainer.querySelectorAll('.tab-pane');
                    allPanes.forEach(pane => pane.classList.remove('active'));

                    // Show selected tab pane
                    const targetPane = tabsContainer.querySelector('#tab-' + tabId);
                    if (targetPane) {
                        targetPane.classList.add('active');
                    }
                });
            });
        }

        /**
         * Accordions
         */
        initAccordions() {
            const accordions = document.querySelectorAll('.accordion-item');
            
            accordions.forEach(item => {
                const header = item.querySelector('.accordion-header');
                if (!header) return;

                header.addEventListener('click', () => {
                    const isOpen = item.classList.contains('active');
                    
                    // Close all accordions in same group
                    const parent = item.parentElement;
                    if (parent) {
                        const siblings = parent.querySelectorAll('.accordion-item');
                        siblings.forEach(sibling => {
                            sibling.classList.remove('active');
                        });
                    }
                    
                    // Toggle current accordion
                    if (!isOpen) {
                        item.classList.add('active');
                    }
                });
            });
        }

        /**
         * Tooltips
         */
        initTooltips() {
            const tooltips = document.querySelectorAll('[data-tooltip]');
            
            tooltips.forEach(element => {
                const tooltipText = element.dataset.tooltip;
                if (!tooltipText) return;

                let tooltip = null;

                const showTooltip = () => {
                    // Remove existing tooltip if any
                    if (tooltip && tooltip.parentNode) {
                        tooltip.parentNode.removeChild(tooltip);
                    }

                    tooltip = document.createElement('div');
                    tooltip.className = 'tooltip-popup';
                    tooltip.textContent = tooltipText;
                    tooltip.style.cssText = 'position:fixed;z-index:9999;padding:8px 12px;background:#1f2937;color:#fff;border-radius:4px;font-size:14px;white-space:nowrap;pointer-events:none;';
                    
                    const rect = element.getBoundingClientRect();
                    tooltip.style.top = (rect.top - 40) + 'px';
                    tooltip.style.left = (rect.left + rect.width / 2) + 'px';
                    tooltip.style.transform = 'translateX(-50%)';
                    
                    document.body.appendChild(tooltip);
                };

                const hideTooltip = () => {
                    if (tooltip && tooltip.parentNode) {
                        tooltip.parentNode.removeChild(tooltip);
                        tooltip = null;
                    }
                };

                element.addEventListener('mouseenter', showTooltip);
                element.addEventListener('mouseleave', hideTooltip);
                element.addEventListener('focus', showTooltip);
                element.addEventListener('blur', hideTooltip);
            });
        }

        /**
         * Image lightbox for simple images
         */
        initImageLightbox() {
            const images = document.querySelectorAll('.wp-block-image img, .entry-content img');
            
            images.forEach(img => {
                // Skip if already in a lightbox
                if (img.closest('a')) return;
                
                // Skip if too small
                if (img.naturalWidth < 600) return;

                img.style.cursor = 'zoom-in';
                
                img.addEventListener('click', () => {
                    this.openImageLightbox(img.src, img.alt);
                });
            });
        }

        /**
         * Open image lightbox
         */
        openImageLightbox(src, alt) {
            const overlay = document.createElement('div');
            overlay.className = 'image-lightbox-overlay';
            overlay.style.cssText = 'position:fixed;top:0;left:0;width:100%;height:100%;background:rgba(0,0,0,0.9);z-index:99999;display:flex;align-items:center;justify-content:center;cursor:zoom-out;';
            
            const img = document.createElement('img');
            img.src = src;
            img.alt = alt || '';
            img.style.cssText = 'max-width:90%;max-height:90%;object-fit:contain;';
            
            overlay.appendChild(img);
            document.body.appendChild(overlay);
            
            // Lock body scroll
            if (window.AngolaB2B && window.AngolaB2B.lockBodyScroll) {
                window.AngolaB2B.lockBodyScroll();
            }
            
            // Close on click
            overlay.addEventListener('click', () => {
                overlay.remove();
                if (window.AngolaB2B && window.AngolaB2B.unlockBodyScroll) {
                    window.AngolaB2B.unlockBodyScroll();
                }
            });
            
            // Close on escape
            const escapeHandler = (e) => {
                if (e.key === 'Escape') {
                    overlay.remove();
                    if (window.AngolaB2B && window.AngolaB2B.unlockBodyScroll) {
                        window.AngolaB2B.unlockBodyScroll();
                    }
                    document.removeEventListener('keydown', escapeHandler);
                }
            };
            document.addEventListener('keydown', escapeHandler);
        }
    }

    /**
     * Initialize theme
     */
    function init() {
        new AngolaB2BApp();
    }

    // Initialize on DOM ready
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', init);
    } else {
        init();
    }

})(jQuery);

