/**
 * Product Categories Showcase
 * Handle background image transitions on hover
 */

(function($) {
    'use strict';

    class ProductCategoriesShowcase {
        constructor() {
            this.$wrapper = $('.product-categories-wrapper');
            this.$cards = $('.product-category-card');
            this.$bgImages = $('.category-bg-image');
            this.activeCategory = null;
            
            if (this.$cards.length === 0) {
                return;
            }
            
            this.init();
        }

        init() {
            // Set initial active category (first one)
            const firstCard = this.$cards.first();
            this.activeCategory = firstCard.data('category');
            
            // Bind hover events
            this.bindEvents();
        }

        bindEvents() {
            const self = this;
            
            // Mouse enter event
            this.$cards.on('mouseenter', function() {
                const categoryId = $(this).data('category');
                self.switchBackground(categoryId);
            });
            
            // Mouse leave event - revert to first category
            this.$wrapper.on('mouseleave', function() {
                const firstCategoryId = self.$cards.first().data('category');
                self.switchBackground(firstCategoryId);
            });
        }

        switchBackground(categoryId) {
            if (categoryId === this.activeCategory) {
                return;
            }
            
            // Remove active class from all backgrounds
            this.$bgImages.removeClass('active');
            
            // Add active class to target background
            this.$bgImages.filter('[data-category="' + categoryId + '"]').addClass('active');
            
            // Update active category
            this.activeCategory = categoryId;
        }
    }

    // Initialize on document ready
    $(document).ready(function() {
        new ProductCategoriesShowcase();
    });

})(jQuery);

