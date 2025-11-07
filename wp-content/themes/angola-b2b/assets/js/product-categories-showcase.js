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
            // Set initial active category (last one - logistics)
            const lastCard = this.$cards.last();
            this.activeCategory = lastCard.data('category');
            
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
            
            // Mouse leave event - revert to last category (logistics)
            this.$wrapper.on('mouseleave', function() {
                const lastCategoryId = self.$cards.last().data('category');
                self.switchBackground(lastCategoryId);
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

