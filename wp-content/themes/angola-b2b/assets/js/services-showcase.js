/**
 * Services Showcase Tab Switcher
 * Handle tab switching in services showcase section
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
        initServiceTabs();
    }

    function initServiceTabs() {
        const tabs = document.querySelectorAll('.service-tab');
        const panels = document.querySelectorAll('.service-panel');

        if (tabs.length === 0 || panels.length === 0) return;

        tabs.forEach(tab => {
            tab.addEventListener('click', function() {
                const tabId = this.dataset.tab;

                // Remove active class from all tabs and panels
                tabs.forEach(t => t.classList.remove('active'));
                panels.forEach(p => p.classList.remove('active'));

                // Add active class to clicked tab
                this.classList.add('active');

                // Show corresponding panel
                const activePanel = document.querySelector(`[data-panel="${tabId}"]`);
                if (activePanel) {
                    activePanel.classList.add('active');
                }
            });

            // Keyboard navigation
            tab.addEventListener('keydown', function(e) {
                if (e.key === 'Enter' || e.key === ' ') {
                    e.preventDefault();
                    this.click();
                }

                // Arrow key navigation
                if (e.key === 'ArrowRight' || e.key === 'ArrowLeft') {
                    e.preventDefault();
                    const currentIndex = Array.from(tabs).indexOf(this);
                    let nextIndex;

                    if (e.key === 'ArrowRight') {
                        nextIndex = (currentIndex + 1) % tabs.length;
                    } else {
                        nextIndex = (currentIndex - 1 + tabs.length) % tabs.length;
                    }

                    tabs[nextIndex].focus();
                    tabs[nextIndex].click();
                }
            });
        });

        // Auto-rotate tabs (optional, can be disabled)
        let autoRotateInterval;
        const autoRotateDelay = 8000; // 8 seconds

        function startAutoRotate() {
            autoRotateInterval = setInterval(() => {
                const currentActiveTab = document.querySelector('.service-tab.active');
                const currentIndex = Array.from(tabs).indexOf(currentActiveTab);
                const nextIndex = (currentIndex + 1) % tabs.length;
                
                tabs[nextIndex].click();
            }, autoRotateDelay);
        }

        function stopAutoRotate() {
            if (autoRotateInterval) {
                clearInterval(autoRotateInterval);
            }
        }

        // Start auto-rotate on page load
        // startAutoRotate();

        // Stop auto-rotate when user interacts
        tabs.forEach(tab => {
            tab.addEventListener('click', stopAutoRotate);
            tab.addEventListener('mouseenter', stopAutoRotate);
        });
    }

})();

