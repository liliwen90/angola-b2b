/**
 * AJAX Product Filters
 * Dynamic product filtering without page reload
 * 
 * @package Angola_B2B
 */

(function($) {
    'use strict';

    /**
     * AJAX Filters Class
     */
    class AjaxFilters {
        constructor() {
            this.container = document.getElementById('products-container');
            this.loadingIndicator = document.getElementById('loading-indicator');
            this.searchInput = document.getElementById('product-search-input');
            this.categoryFilter = document.getElementById('product-category-filter');
            this.sortFilter = document.getElementById('product-sort');
            this.currentPage = 1;
            this.isLoading = false;
            
            if (!this.container) return;
            
            this.init();
        }

        init() {
            this.attachEventListeners();
            this.initInfiniteScroll();
        }

        /**
         * Attach event listeners
         */
        attachEventListeners() {
            // Search input with debounce
            if (this.searchInput) {
                let searchTimer;
                this.searchInput.addEventListener('input', (e) => {
                    clearTimeout(searchTimer);
                    searchTimer = setTimeout(() => {
                        this.currentPage = 1;
                        this.filterProducts();
                    }, 500);
                });
            }

            // Category filter
            if (this.categoryFilter) {
                this.categoryFilter.addEventListener('change', () => {
                    this.currentPage = 1;
                    this.filterProducts();
                });
            }

            // Sort filter
            if (this.sortFilter) {
                this.sortFilter.addEventListener('change', () => {
                    this.currentPage = 1;
                    this.filterProducts();
                });
            }
        }

        /**
         * Filter products via AJAX
         */
        filterProducts() {
            if (this.isLoading) return;

            this.isLoading = true;
            this.showLoading();

            const data = {
                action: 'angola_b2b_filter_products',
                nonce: window.angolaB2B ? window.angolaB2B.nonce : '',
                search: this.searchInput ? this.searchInput.value : '',
                category: this.categoryFilter ? this.categoryFilter.value : '',
                sort: this.sortFilter ? this.sortFilter.value : 'date-desc',
                paged: this.currentPage
            };

            fetch(window.angolaB2B ? window.angolaB2B.ajaxUrl : '/wp-admin/admin-ajax.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: new URLSearchParams(data)
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.json();
            })
            .then(result => {
                this.hideLoading();
                this.isLoading = false;

                if (result.success && result.data) {
                    this.updateProducts(result.data);
                    this.updateURL(data);
                } else {
                    this.showError(result.data ? result.data.message : 'Error loading products');
                }
            })
            .catch(error => {
                this.hideLoading();
                this.isLoading = false;
                this.showError('Failed to load products. Please try again.');
            });
        }

        /**
         * Update products container
         */
        updateProducts(data) {
            if (!this.container) return;

            // Clear container content safely
            while (this.container.firstChild) {
                this.container.removeChild(this.container.firstChild);
            }
            
            if (data.html) {
                // Use DOMParser for safe HTML parsing
                const parser = new DOMParser();
                const doc = parser.parseFromString(data.html, 'text/html');
                const elements = doc.body.children;
                
                while (elements.length > 0) {
                    this.container.appendChild(elements[0]);
                }
            }

            // Trigger custom event for other scripts
            const event = new CustomEvent('productsUpdated', {
                detail: { data: data }
            });
            document.dispatchEvent(event);

            // Scroll to top of results
            if (window.AngolaB2B && window.AngolaB2B.scrollToElement) {
                window.AngolaB2B.scrollToElement(this.container, 100, 500);
            }
        }

        /**
         * Update URL without reload
         */
        updateURL(data) {
            const url = new URL(window.location);
            
            if (data.search) {
                url.searchParams.set('s', data.search);
            } else {
                url.searchParams.delete('s');
            }
            
            if (data.category) {
                url.searchParams.set('category', data.category);
            } else {
                url.searchParams.delete('category');
            }
            
            if (data.sort && data.sort !== 'date-desc') {
                url.searchParams.set('sort', data.sort);
            } else {
                url.searchParams.delete('sort');
            }
            
            if (data.paged > 1) {
                url.searchParams.set('paged', data.paged);
            } else {
                url.searchParams.delete('paged');
            }
            
            window.history.pushState({}, '', url);
        }

        /**
         * Initialize infinite scroll
         */
        initInfiniteScroll() {
            let scrollTimer;
            window.addEventListener('scroll', () => {
                clearTimeout(scrollTimer);
                scrollTimer = setTimeout(() => {
                    this.checkInfiniteScroll();
                }, 200);
            });
        }

        /**
         * Check if we need to load more
         */
        checkInfiniteScroll() {
            if (this.isLoading) return;

            const scrollPosition = window.innerHeight + window.pageYOffset;
            const threshold = document.body.offsetHeight - 500;

            if (scrollPosition >= threshold) {
                this.loadMore();
            }
        }

        /**
         * Load more products
         */
        loadMore() {
            if (this.isLoading) return;

            this.currentPage++;
            this.isLoading = true;
            this.showLoading();

            const data = {
                action: 'angola_b2b_load_more_products',
                nonce: window.angolaB2B ? window.angolaB2B.nonce : '',
                category: this.categoryFilter ? this.categoryFilter.value : '',
                paged: this.currentPage
            };

            fetch(window.angolaB2B ? window.angolaB2B.ajaxUrl : '/wp-admin/admin-ajax.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: new URLSearchParams(data)
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.json();
            })
            .then(result => {
                this.hideLoading();
                this.isLoading = false;

                if (result.success && result.data && result.data.html) {
                    this.appendProducts(result.data.html);
                }
            })
            .catch(error => {
                this.hideLoading();
                this.isLoading = false;
                this.currentPage--;
            });
        }

        /**
         * Append products to container
         */
        appendProducts(html) {
            if (!this.container || !html) return;

            // Use DOMParser for safe HTML parsing
            const parser = new DOMParser();
            const doc = parser.parseFromString(html, 'text/html');
            const elements = doc.body.children;
            
            while (elements.length > 0) {
                this.container.appendChild(elements[0]);
            }
        }

        /**
         * Show loading indicator
         */
        showLoading() {
            if (this.loadingIndicator) {
                this.loadingIndicator.style.display = 'flex';
            }
        }

        /**
         * Hide loading indicator
         */
        hideLoading() {
            if (this.loadingIndicator) {
                this.loadingIndicator.style.display = 'none';
            }
        }

        /**
         * Show error message
         */
        showError(message) {
            if (!this.container) return;

            const errorDiv = document.createElement('div');
            errorDiv.className = 'ajax-error';
            errorDiv.textContent = message;
            errorDiv.style.cssText = 'padding:20px;text-align:center;color:#ef4444;';
            
            // Clear container content safely
            while (this.container.firstChild) {
                this.container.removeChild(this.container.firstChild);
            }
            
            this.container.appendChild(errorDiv);
        }
    }

    /**
     * Quick Inquiry Form Handler
     */
    class QuickInquiry {
        constructor() {
            this.forms = document.querySelectorAll('#product-inquiry-form');
            
            if (this.forms.length === 0) return;
            
            this.init();
        }

        init() {
            this.forms.forEach(form => {
                form.addEventListener('submit', (e) => {
                    e.preventDefault();
                    this.submitForm(form);
                });
            });
        }

        /**
         * Submit inquiry form
         */
        submitForm(form) {
            const formData = new FormData(form);
            const messageDiv = form.querySelector('.form-message');
            const submitBtn = form.querySelector('button[type="submit"]');
            
            // Get data attributes
            const productId = form.dataset.productId;
            const nonce = form.dataset.nonce;
            
            // Add AJAX action and nonce
            formData.append('action', 'angola_b2b_submit_inquiry');
            formData.append('nonce', nonce || '');
            formData.append('product_id', productId || '');
            
            // Disable submit button
            if (submitBtn) {
                submitBtn.disabled = true;
                submitBtn.textContent = 'Submitting...';
            }

            fetch(window.angolaB2B ? window.angolaB2B.ajaxUrl : '/wp-admin/admin-ajax.php', {
                method: 'POST',
                body: formData
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.json();
            })
            .then(result => {
                if (messageDiv) {
                    messageDiv.style.display = 'block';
                    messageDiv.className = 'form-message ' + (result.success ? 'success' : 'error');
                    messageDiv.textContent = result.data ? result.data.message : 'Unknown error';
                }
                
                if (result.success) {
                    form.reset();
                }
                
                // Re-enable submit button
                if (submitBtn) {
                    submitBtn.disabled = false;
                    submitBtn.textContent = form.dataset.submitText || 'Submit';
                }
            })
            .catch(error => {
                if (messageDiv) {
                    messageDiv.style.display = 'block';
                    messageDiv.className = 'form-message error';
                    messageDiv.textContent = 'Submission failed. Please try again.';
                }
                
                // Re-enable submit button
                if (submitBtn) {
                    submitBtn.disabled = false;
                    submitBtn.textContent = form.dataset.submitText || 'Submit';
                }
            });
        }
    }

    /**
     * Initialize on DOM ready
     */
    function init() {
        new AjaxFilters();
        new QuickInquiry();
    }

    // Initialize
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', init);
    } else {
        init();
    }

})(jQuery);

