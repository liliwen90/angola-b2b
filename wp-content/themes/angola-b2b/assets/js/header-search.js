/**
 * Header Search Overlay - MSC Style
 * @package Angola_B2B
 */

(function() {
    'use strict';
    
    // Wait for DOM to be ready
    document.addEventListener('DOMContentLoaded', function() {
        const searchToggle = document.getElementById('search-toggle');
        const searchOverlay = document.getElementById('search-overlay');
        const searchClose = document.getElementById('search-close');
        const searchInput = document.querySelector('.search-input');
        
        // Open search overlay
        if (searchToggle) {
            searchToggle.addEventListener('click', function(e) {
                e.preventDefault();
                searchOverlay.classList.add('active');
                document.body.style.overflow = 'hidden';
                
                // Focus on input after animation
                setTimeout(function() {
                    if (searchInput) {
                        searchInput.focus();
                    }
                }, 300);
            });
        }
        
        // Close search overlay
        function closeSearch() {
            searchOverlay.classList.remove('active');
            document.body.style.overflow = '';
        }
        
        if (searchClose) {
            searchClose.addEventListener('click', closeSearch);
        }
        
        // Close on overlay click (outside content)
        if (searchOverlay) {
            searchOverlay.addEventListener('click', function(e) {
                if (e.target === searchOverlay) {
                    closeSearch();
                }
            });
        }
        
        // Close on ESC key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape' && searchOverlay.classList.contains('active')) {
                closeSearch();
            }
        });
    });
})();

