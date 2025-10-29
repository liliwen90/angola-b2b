/**
 * Utility Functions
 * Reusable helper functions
 * 
 * @package Angola_B2B
 */

(function() {
    'use strict';

    // Create global namespace
    window.AngolaB2B = window.AngolaB2B || {};

    /**
     * Debounce function
     * Limits the rate at which a function can fire
     */
    window.AngolaB2B.debounce = function(func, wait, immediate) {
        let timeout;
        return function() {
            const context = this;
            const args = arguments;
            const later = function() {
                timeout = null;
                if (!immediate) func.apply(context, args);
            };
            const callNow = immediate && !timeout;
            clearTimeout(timeout);
            timeout = setTimeout(later, wait);
            if (callNow) func.apply(context, args);
        };
    };

    /**
     * Throttle function
     * Ensures a function is called at most once in a specified time period
     */
    window.AngolaB2B.throttle = function(func, limit) {
        let inThrottle;
        return function() {
            const args = arguments;
            const context = this;
            if (!inThrottle) {
                func.apply(context, args);
                inThrottle = true;
                setTimeout(() => inThrottle = false, limit);
            }
        };
    };

    /**
     * Check if element is in viewport
     */
    window.AngolaB2B.isInViewport = function(element) {
        if (!element) return false;
        
        const rect = element.getBoundingClientRect();
        return (
            rect.top >= 0 &&
            rect.left >= 0 &&
            rect.bottom <= (window.innerHeight || document.documentElement.clientHeight) &&
            rect.right <= (window.innerWidth || document.documentElement.clientWidth)
        );
    };

    /**
     * Check if element is partially in viewport
     */
    window.AngolaB2B.isPartiallyInViewport = function(element, offset) {
        if (!element) return false;
        
        offset = offset || 0;
        const rect = element.getBoundingClientRect();
        const windowHeight = window.innerHeight || document.documentElement.clientHeight;
        
        return (
            rect.top <= windowHeight - offset &&
            rect.bottom >= offset
        );
    };

    /**
     * Get scroll position
     */
    window.AngolaB2B.getScrollPosition = function() {
        return window.pageYOffset || document.documentElement.scrollTop || document.body.scrollTop || 0;
    };

    /**
     * Smooth scroll to element
     */
    window.AngolaB2B.scrollToElement = function(element, offset, duration) {
        if (!element) return;
        
        offset = offset || 0;
        duration = duration || 500;
        
        const targetPosition = element.getBoundingClientRect().top + window.pageYOffset - offset;
        const startPosition = window.pageYOffset;
        const distance = targetPosition - startPosition;
        let startTime = null;

        function animation(currentTime) {
            if (startTime === null) startTime = currentTime;
            const timeElapsed = currentTime - startTime;
            const run = ease(timeElapsed, startPosition, distance, duration);
            window.scrollTo(0, run);
            if (timeElapsed < duration) requestAnimationFrame(animation);
        }

        function ease(t, b, c, d) {
            t /= d / 2;
            if (t < 1) return c / 2 * t * t + b;
            t--;
            return -c / 2 * (t * (t - 2) - 1) + b;
        }

        requestAnimationFrame(animation);
    };

    /**
     * Lock body scroll
     */
    window.AngolaB2B.lockBodyScroll = function() {
        const scrollbarWidth = window.innerWidth - document.documentElement.clientWidth;
        document.body.style.overflow = 'hidden';
        document.body.style.paddingRight = scrollbarWidth + 'px';
    };

    /**
     * Unlock body scroll
     */
    window.AngolaB2B.unlockBodyScroll = function() {
        document.body.style.overflow = '';
        document.body.style.paddingRight = '';
    };

    /**
     * Get query parameter from URL
     */
    window.AngolaB2B.getQueryParam = function(param) {
        const urlParams = new URLSearchParams(window.location.search);
        return urlParams.get(param);
    };

    /**
     * Set query parameter in URL without reload
     */
    window.AngolaB2B.setQueryParam = function(param, value) {
        const url = new URL(window.location);
        if (value) {
            url.searchParams.set(param, value);
        } else {
            url.searchParams.delete(param);
        }
        window.history.pushState({}, '', url);
    };

    /**
     * Cookie helpers
     */
    window.AngolaB2B.setCookie = function(name, value, days) {
        let expires = '';
        if (days) {
            const date = new Date();
            date.setTime(date.getTime() + (days * 24 * 60 * 60 * 1000));
            expires = '; expires=' + date.toUTCString();
        }
        document.cookie = name + '=' + (value || '') + expires + '; path=/';
    };

    window.AngolaB2B.getCookie = function(name) {
        const nameEQ = name + '=';
        const ca = document.cookie.split(';');
        for (let i = 0; i < ca.length; i++) {
            let c = ca[i];
            while (c.charAt(0) === ' ') c = c.substring(1, c.length);
            if (c.indexOf(nameEQ) === 0) return c.substring(nameEQ.length, c.length);
        }
        return null;
    };

    window.AngolaB2B.deleteCookie = function(name) {
        document.cookie = name + '=; Path=/; Expires=Thu, 01 Jan 1970 00:00:01 GMT;';
    };

    /**
     * Format number with animation-friendly output
     */
    window.AngolaB2B.formatNumber = function(num, decimals) {
        decimals = decimals || 0;
        return num.toFixed(decimals).replace(/\B(?=(\d{3})+(?!\d))/g, ',');
    };

    /**
     * Animate number counter
     */
    window.AngolaB2B.animateNumber = function(element, target, duration) {
        if (!element) return;
        
        target = parseFloat(target);
        duration = duration || 2000;
        
        const decimals = (target.toString().split('.')[1] || '').length;
        const start = 0;
        const increment = target / (duration / 16);
        let current = start;

        const timer = setInterval(function() {
            current += increment;
            if (current >= target) {
                current = target;
                clearInterval(timer);
            }
            element.textContent = window.AngolaB2B.formatNumber(current, decimals);
        }, 16);
    };

    /**
     * Lazy load images
     */
    window.AngolaB2B.lazyLoadImages = function() {
        const images = document.querySelectorAll('img[loading="lazy"]');
        
        if ('IntersectionObserver' in window) {
            const imageObserver = new IntersectionObserver(function(entries, observer) {
                entries.forEach(function(entry) {
                    if (entry.isIntersecting) {
                        const img = entry.target;
                        if (img.dataset.src) {
                            img.src = img.dataset.src;
                            img.removeAttribute('data-src');
                        }
                        imageObserver.unobserve(img);
                    }
                });
            });

            images.forEach(function(img) {
                imageObserver.observe(img);
            });
        } else {
            // Fallback for browsers without IntersectionObserver
            images.forEach(function(img) {
                if (img.dataset.src) {
                    img.src = img.dataset.src;
                    img.removeAttribute('data-src');
                }
            });
        }
    };

    /**
     * Detect mobile device
     */
    window.AngolaB2B.isMobile = function() {
        return /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent);
    };

    /**
     * Detect touch device
     */
    window.AngolaB2B.isTouchDevice = function() {
        return 'ontouchstart' in window || navigator.maxTouchPoints > 0 || navigator.msMaxTouchPoints > 0;
    };

    /**
     * Get breakpoint name
     */
    window.AngolaB2B.getBreakpoint = function() {
        const width = window.innerWidth;
        if (width < 640) return 'mobile';
        if (width < 768) return 'sm';
        if (width < 1024) return 'md';
        if (width < 1280) return 'lg';
        if (width < 1536) return 'xl';
        return '2xl';
    };

    /**
     * Show notification
     */
    window.AngolaB2B.showNotification = function(message, type, duration) {
        type = type || 'info';
        duration = duration || 3000;

        const notification = document.createElement('div');
        notification.className = 'notification notification-' + type;
        notification.textContent = message;
        notification.style.cssText = 'position:fixed;top:20px;right:20px;padding:16px 24px;background:#fff;border-radius:8px;box-shadow:0 10px 15px -3px rgba(0,0,0,0.1);z-index:9999;animation:slideInRight 0.3s ease-out;';

        document.body.appendChild(notification);

        setTimeout(function() {
            notification.style.animation = 'fadeOut 0.3s ease-out';
            setTimeout(function() {
                if (notification.parentNode) {
                    notification.parentNode.removeChild(notification);
                }
            }, 300);
        }, duration);
    };

    /**
     * Copy to clipboard
     */
    window.AngolaB2B.copyToClipboard = function(text) {
        if (navigator.clipboard && window.isSecureContext) {
            return navigator.clipboard.writeText(text);
        } else {
            // Fallback for older browsers
            const textArea = document.createElement('textarea');
            textArea.value = text;
            textArea.style.position = 'fixed';
            textArea.style.left = '-999999px';
            document.body.appendChild(textArea);
            textArea.focus();
            textArea.select();
            try {
                document.execCommand('copy');
                textArea.remove();
                return Promise.resolve();
            } catch (error) {
                textArea.remove();
                return Promise.reject(error);
            }
        }
    };

    /**
     * Initialize utilities on DOM ready
     */
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', function() {
            window.AngolaB2B.lazyLoadImages();
        });
    } else {
        window.AngolaB2B.lazyLoadImages();
    }

})();

