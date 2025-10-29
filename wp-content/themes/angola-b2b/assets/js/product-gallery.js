/**
 * Product Gallery
 * PhotoSwipe lightbox and Swiper carousel integration
 * 
 * @package Angola_B2B
 */

(function($) {
    'use strict';

    /**
     * Product Gallery Class
     */
    class ProductGallery {
        constructor(element) {
            this.gallery = element;
            this.mainViewer = this.gallery.querySelector('.product-gallery-viewer');
            this.thumbnails = this.gallery.querySelector('.gallery-thumbnails');
            this.viewSwitcher = this.gallery.querySelector('.view-type-switcher');
            this.currentView = 'gallery';
            
            if (!this.mainViewer) return;
            
            this.init();
        }

        init() {
            this.initLightbox();
            this.initThumbnailCarousel();
            this.initViewSwitcher();
            this.initImageHotspots();
            this.initComparisonSlider();
            this.setupKeyboardNavigation();
        }

        /**
         * Initialize PhotoSwipe lightbox
         */
        initLightbox() {
            if (typeof PhotoSwipeLightbox === 'undefined') {
                return;
            }

            const lightbox = new PhotoSwipeLightbox({
                gallery: this.mainViewer,
                children: 'a',
                pswpModule: () => import('https://cdn.jsdelivr.net/npm/photoswipe@5/dist/photoswipe.umd.min.js')
            });

            lightbox.init();

            // Zoom button
            const zoomBtn = this.gallery.querySelector('.gallery-zoom');
            if (zoomBtn) {
                zoomBtn.addEventListener('click', () => {
                    const activeImage = this.mainViewer.querySelector('.gallery-image.active');
                    if (activeImage) {
                        const link = activeImage.querySelector('a');
                        if (link) {
                            link.click();
                        }
                    }
                });
            }

            // Fullscreen button
            const fullscreenBtn = this.gallery.querySelector('.gallery-fullscreen');
            if (fullscreenBtn) {
                fullscreenBtn.addEventListener('click', () => {
                    this.toggleFullscreen();
                });
            }
        }

        /**
         * Initialize thumbnail carousel
         */
        initThumbnailCarousel() {
            if (!this.thumbnails || typeof Swiper === 'undefined') {
                return;
            }

            const thumbnailSwiper = new Swiper(this.thumbnails, {
                slidesPerView: 'auto',
                spaceBetween: 10,
                freeMode: true,
                watchSlidesProgress: true,
                navigation: {
                    nextEl: '.swiper-button-next',
                    prevEl: '.swiper-button-prev',
                },
                breakpoints: {
                    320: {
                        slidesPerView: 3,
                    },
                    640: {
                        slidesPerView: 4,
                    },
                    1024: {
                        slidesPerView: 5,
                    }
                }
            });

            // Thumbnail click handler
            const thumbnailButtons = this.thumbnails.querySelectorAll('.gallery-thumbnail');
            thumbnailButtons.forEach((thumb, index) => {
                thumb.addEventListener('click', () => {
                    this.switchImage(index);
                    
                    // Update active state
                    thumbnailButtons.forEach(t => t.classList.remove('active'));
                    thumb.classList.add('active');
                });
            });
        }

        /**
         * Switch displayed image
         */
        switchImage(index) {
            const images = this.mainViewer.querySelectorAll('.gallery-image');
            
            if (!images[index]) return;

            images.forEach(img => img.classList.remove('active'));
            images[index].classList.add('active');
        }

        /**
         * Initialize view type switcher
         */
        initViewSwitcher() {
            if (!this.viewSwitcher) return;

            const buttons = this.viewSwitcher.querySelectorAll('.view-type-btn');
            
            buttons.forEach(button => {
                button.addEventListener('click', () => {
                    const viewType = button.dataset.view;
                    if (!viewType) return;

                    this.switchView(viewType);
                    
                    // Update active button
                    buttons.forEach(btn => btn.classList.remove('active'));
                    button.classList.add('active');
                });
            });
        }

        /**
         * Switch between different view types
         */
        switchView(viewType) {
            const viewers = {
                'gallery': this.gallery.querySelector('.product-gallery-viewer'),
                '360': this.gallery.querySelector('.product-360-viewer'),
                'video': this.gallery.querySelector('.product-video-viewer'),
                'comparison': this.gallery.querySelector('.product-comparison-viewer')
            };

            // Hide all viewers
            Object.values(viewers).forEach(viewer => {
                if (viewer) {
                    viewer.style.display = 'none';
                }
            });

            // Show selected viewer
            if (viewers[viewType]) {
                viewers[viewType].style.display = 'block';
                this.currentView = viewType;

                // Trigger specific initializations
                if (viewType === 'video') {
                    this.initVideo(viewers[viewType]);
                }
            }
        }

        /**
         * Initialize video viewer
         */
        initVideo(videoViewer) {
            const video = videoViewer.querySelector('video');
            if (!video) return;

            // Auto-play when switching to video view
            video.play().catch(() => {
                // Auto-play failed, user needs to interact first
            });
        }

        /**
         * Initialize image hotspots
         */
        initImageHotspots() {
            const hotspots = this.gallery.querySelectorAll('.image-hotspot');
            
            if (hotspots.length === 0) return;

            hotspots.forEach(hotspot => {
                hotspot.addEventListener('click', () => {
                    this.showHotspotInfo(hotspot);
                });

                // Keyboard support
                hotspot.addEventListener('keydown', (e) => {
                    if (e.key === 'Enter' || e.key === ' ') {
                        e.preventDefault();
                        this.showHotspotInfo(hotspot);
                    }
                });
            });
        }

        /**
         * Show hotspot information
         */
        showHotspotInfo(hotspot) {
            const title = hotspot.dataset.title;
            const description = hotspot.dataset.description;
            
            if (!title) return;

            // Create tooltip
            const tooltip = document.createElement('div');
            tooltip.className = 'hotspot-tooltip active';
            
            const closeBtn = document.createElement('button');
            closeBtn.className = 'hotspot-close';
            closeBtn.setAttribute('aria-label', 'Close');
            closeBtn.textContent = '×';
            
            const titleEl = document.createElement('h4');
            titleEl.textContent = title;
            
            const descEl = document.createElement('p');
            descEl.textContent = description || '';
            
            tooltip.appendChild(closeBtn);
            tooltip.appendChild(titleEl);
            tooltip.appendChild(descEl);

            // Position tooltip
            const rect = hotspot.getBoundingClientRect();
            tooltip.style.position = 'absolute';
            tooltip.style.top = rect.bottom + 10 + 'px';
            tooltip.style.left = rect.left + 'px';

            document.body.appendChild(tooltip);

            // Close button
            const closeBtn = tooltip.querySelector('.hotspot-close');
            closeBtn.addEventListener('click', () => {
                tooltip.remove();
            });

            // Close on outside click
            setTimeout(() => {
                document.addEventListener('click', function closeTooltip(e) {
                    if (!tooltip.contains(e.target) && e.target !== hotspot) {
                        tooltip.remove();
                        document.removeEventListener('click', closeTooltip);
                    }
                });
            }, 100);
        }

        /**
         * Initialize comparison slider
         */
        initComparisonSlider() {
            const slider = this.gallery.querySelector('#comparison-slider');
            const divider = this.gallery.querySelector('.comparison-divider');
            const afterImage = this.gallery.querySelector('.comparison-after');
            
            if (!slider || !divider || !afterImage) return;

            slider.addEventListener('input', (e) => {
                const value = e.target.value;
                divider.style.left = value + '%';
                afterImage.style.clipPath = `inset(0 ${100 - value}% 0 0)`;
            });
        }

        /**
         * Toggle fullscreen
         */
        toggleFullscreen() {
            if (!document.fullscreenElement) {
                this.gallery.requestFullscreen().catch(() => {
                    // Fullscreen not supported or denied
                });
            } else {
                document.exitFullscreen();
            }
        }

        /**
         * Setup keyboard navigation
         */
        setupKeyboardNavigation() {
            document.addEventListener('keydown', (e) => {
                if (this.currentView !== 'gallery') return;

                const images = this.mainViewer.querySelectorAll('.gallery-image');
                const activeIndex = Array.from(images).findIndex(img => img.classList.contains('active'));

                if (e.key === 'ArrowLeft' && activeIndex > 0) {
                    this.switchImage(activeIndex - 1);
                } else if (e.key === 'ArrowRight' && activeIndex < images.length - 1) {
                    this.switchImage(activeIndex + 1);
                }
            });
        }

    }

    /**
     * Initialize all product galleries
     */
    function init() {
        const galleries = document.querySelectorAll('.product-gallery');
        
        galleries.forEach(gallery => {
            new ProductGallery(gallery);
        });
    }

    // Initialize on DOM ready
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', init);
    } else {
        init();
    }

})(jQuery);

