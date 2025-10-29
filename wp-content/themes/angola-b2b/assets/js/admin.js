/**
 * Admin JavaScript
 * WordPress admin panel enhancements
 * 
 * @package Angola_B2B
 */

(function($) {
    'use strict';

    /**
     * Admin Enhancements Class
     */
    class AdminEnhancements {
        constructor() {
            this.init();
        }

        init() {
            this.initProductFeaturedToggle();
            this.initMediaUploadHelper();
            this.initACFEnhancements();
            this.initQuickEditWarning();
        }

        /**
         * Featured product toggle
         */
        initProductFeaturedToggle() {
            const screen = document.querySelector('body.post-type-product');
            if (!screen) return;

            // Add quick toggle in product list
            const featuredCells = document.querySelectorAll('.column-product_featured');
            
            featuredCells.forEach(cell => {
                const icon = cell.querySelector('.dashicons-star-filled');
                if (icon) {
                    icon.style.cursor = 'pointer';
                    icon.setAttribute('title', 'Toggle featured status');
                }
            });
        }

        /**
         * Media upload helper
         */
        initMediaUploadHelper() {
            // Add helper text for product images
            const featuredImageDiv = document.getElementById('postimagediv');
            if (featuredImageDiv) {
                const helper = document.createElement('p');
                helper.className = 'description';
                helper.textContent = 'Recommended size: 1200x1200px for best quality.';
                helper.style.cssText = 'margin-top:10px;font-style:italic;color:#666;';
                
                const inside = featuredImageDiv.querySelector('.inside');
                if (inside) {
                    inside.appendChild(helper);
                }
            }
        }

        /**
         * ACF enhancements
         */
        initACFEnhancements() {
            if (typeof acf === 'undefined') return;

            // Add tooltips to ACF fields
            const acfFields = document.querySelectorAll('.acf-field');
            
            acfFields.forEach(field => {
                const label = field.querySelector('.acf-label label');
                const instructions = field.querySelector('.acf-input .description');
                
                if (label && instructions) {
                    const helpIcon = document.createElement('span');
                    helpIcon.className = 'dashicons dashicons-info';
                    helpIcon.style.cssText = 'font-size:16px;margin-left:5px;cursor:help;color:#2271b1;';
                    helpIcon.setAttribute('title', instructions.textContent);
                    
                    label.appendChild(helpIcon);
                }
            });

            // Gallery field enhancements
            const galleryFields = document.querySelectorAll('.acf-gallery');
            
            galleryFields.forEach(gallery => {
                const addButton = gallery.querySelector('.acf-gallery-add');
                if (addButton) {
                    const hint = document.createElement('p');
                    hint.className = 'description';
                    hint.textContent = 'Tip: You can drag and drop images to reorder them.';
                    hint.style.cssText = 'margin-top:10px;font-style:italic;';
                    
                    gallery.appendChild(hint);
                }
            });
        }

        /**
         * Quick edit warning
         */
        initQuickEditWarning() {
            const quickEditLinks = document.querySelectorAll('.editinline');
            
            quickEditLinks.forEach(link => {
                link.addEventListener('click', () => {
                    setTimeout(() => {
                        const quickEdit = document.querySelector('.inline-edit-row');
                        if (quickEdit) {
                            const warning = document.createElement('div');
                            warning.className = 'notice notice-warning inline';
                            warning.style.cssText = 'margin:10px 0;padding:10px;';
                            
                            const warningText = document.createElement('p');
                            warningText.textContent = 'Note: Quick Edit does not update ACF custom fields. Use the full edit screen for complete control.';
                            
                            warning.appendChild(warningText);
                            
                            const fieldset = quickEdit.querySelector('fieldset');
                            if (fieldset) {
                                fieldset.insertBefore(warning, fieldset.firstChild);
                            }
                        }
                    }, 100);
                });
            });
        }
    }

    /**
     * Product Admin Helper
     */
    class ProductAdminHelper {
        constructor() {
            const screen = document.querySelector('body.post-type-product');
            if (!screen) return;
            
            this.init();
        }

        init() {
            this.addBulkActions();
            this.addColumnSorting();
        }

        /**
         * Add custom bulk actions
         */
        addBulkActions() {
            const bulkSelect = document.querySelector('#bulk-action-selector-top');
            if (!bulkSelect) return;

            // Add "Mark as Featured" option
            const featuredOption = document.createElement('option');
            featuredOption.value = 'mark_featured';
            featuredOption.textContent = 'Mark as Featured';
            bulkSelect.appendChild(featuredOption);

            // Add "Remove Featured" option
            const unfeaturedOption = document.createElement('option');
            unfeaturedOption.value = 'remove_featured';
            unfeaturedOption.textContent = 'Remove Featured';
            bulkSelect.appendChild(unfeaturedOption);
        }

        /**
         * Enhance column sorting
         */
        addColumnSorting() {
            // Visual feedback for sortable columns
            const sortableHeaders = document.querySelectorAll('.manage-column.sortable');
            
            sortableHeaders.forEach(header => {
                header.style.cursor = 'pointer';
                
                header.addEventListener('mouseenter', () => {
                    header.style.backgroundColor = '#f0f0f1';
                });
                
                header.addEventListener('mouseleave', () => {
                    header.style.backgroundColor = '';
                });
            });
        }
    }

    /**
     * ACF Field Validation
     */
    class ACFValidation {
        constructor() {
            if (typeof acf === 'undefined') return;
            
            this.init();
        }

        init() {
            // Validate gallery has at least one image
            this.validateGallery();
            
            // Validate 360 images count
            this.validate360Images();
        }

        /**
         * Validate gallery field
         */
        validateGallery() {
            const publishButton = document.querySelector('#publish');
            if (!publishButton) return;

            publishButton.addEventListener('click', (e) => {
                const galleryField = document.querySelector('.acf-field[data-name="product_gallery"]');
                if (!galleryField) return;

                const images = galleryField.querySelectorAll('.acf-gallery-attachment');
                
                if (images.length === 0) {
                    const confirmed = confirm('No product images added. Continue publishing?');
                    if (!confirmed) {
                        e.preventDefault();
                    }
                }
            });
        }

        /**
         * Validate 360 images
         */
        validate360Images() {
            const images360Field = document.querySelector('.acf-field[data-name="product_360_images"]');
            if (!images360Field) return;

            const gallery = images360Field.querySelector('.acf-gallery');
            if (!gallery) return;

            const observer = new MutationObserver(() => {
                const count = gallery.querySelectorAll('.acf-gallery-attachment').length;
                
                if (count > 0 && count < 12) {
                    this.showWarning(images360Field, 'Recommended: 12-36 images for smooth 360° rotation.');
                } else if (count >= 12) {
                    this.hideWarning(images360Field);
                }
            });

            observer.observe(gallery, { childList: true, subtree: true });
        }

        /**
         * Show validation warning
         */
        showWarning(field, message) {
            let warning = field.querySelector('.validation-warning');
            
            if (!warning) {
                warning = document.createElement('div');
                warning.className = 'validation-warning';
                warning.style.cssText = 'margin-top:10px;padding:8px;background:#fff3cd;border-left:3px solid:#ffc107;font-size:13px;';
                
                const icon = document.createElement('span');
                icon.className = 'dashicons dashicons-warning';
                icon.style.cssText = 'color:#ffc107;margin-right:5px;';
                
                const text = document.createElement('span');
                text.textContent = message;
                
                warning.appendChild(icon);
                warning.appendChild(text);
                
                const input = field.querySelector('.acf-input');
                if (input) {
                    input.appendChild(warning);
                }
            } else {
                const text = warning.querySelector('span:last-child');
                if (text) {
                    text.textContent = message;
                }
            }
        }

        /**
         * Hide validation warning
         */
        hideWarning(field) {
            const warning = field.querySelector('.validation-warning');
            if (warning) {
                warning.remove();
            }
        }
    }

    /**
     * Initialize on DOM ready
     */
    function init() {
        new AdminEnhancements();
        new ProductAdminHelper();
        new ACFValidation();
    }

    // Initialize
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', init);
    } else {
        init();
    }

})(jQuery);

