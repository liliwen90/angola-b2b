/**
 * Header Search Box - MSC Style
 * @package Angola_B2B
 */

(function() {
    'use strict';
    
    // Wait for DOM to be ready
    document.addEventListener('DOMContentLoaded', function() {
        const searchToggle = document.getElementById('search-toggle');
        const searchWrapper = document.getElementById('search-box-wrapper');
        const searchOverlay = document.getElementById('search-box-overlay');
        const searchInput = document.getElementById('search-box-input');
        const searchBox = document.getElementById('search-box');
        
        // Open search box
        if (searchToggle) {
            searchToggle.addEventListener('click', function(e) {
                e.preventDefault();
                e.stopPropagation();
                searchWrapper.classList.add('active');
                
                // Focus on input after animation
                setTimeout(function() {
                    if (searchInput) {
                        searchInput.focus();
                    }
                }, 100);
            });
        }
        
        // Close search box
        function closeSearch() {
            searchWrapper.classList.remove('active');
            if (searchInput) {
                searchInput.value = '';
            }
        }
        
        // Close when clicking overlay (outside search box)
        if (searchOverlay) {
            searchOverlay.addEventListener('click', function(e) {
                e.stopPropagation();
                closeSearch();
            });
        }
        
        // Prevent closing when clicking inside search box
        if (searchBox) {
            searchBox.addEventListener('click', function(e) {
                e.stopPropagation();
            });
        }
        
        // Close on ESC key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape' && searchWrapper.classList.contains('active')) {
                closeSearch();
            }
        });
        
        // Close when clicking anywhere on page (except search elements)
        document.addEventListener('click', function(e) {
            if (searchWrapper.classList.contains('active')) {
                const isSearchToggle = e.target.closest('#search-toggle');
                const isSearchBox = e.target.closest('#search-box');
                
                if (!isSearchToggle && !isSearchBox) {
                    closeSearch();
                }
            }
        });
    });
})();

