/**
 * Product 360° Viewer
 * Interactive 360-degree product rotation
 * 
 * @package Angola_B2B
 */

(function() {
    'use strict';

    /**
     * Product 360 Viewer Class
     */
    class Product360Viewer {
        constructor(container) {
            this.container = container;
            this.frames = Array.from(container.querySelectorAll('.frame-360'));
            this.currentFrame = 0;
            this.totalFrames = this.frames.length;
            this.isRotating = false;
            this.startX = 0;
            this.sensitivity = 3; // pixels per frame
            this.autoRotate = false;
            this.autoRotateSpeed = 50; // ms per frame
            this.autoRotateTimer = null;
            
            if (this.totalFrames === 0) return;
            
            this.init();
        }

        init() {
            this.preloadImages();
            this.attachEventListeners();
            this.showFrame(0);
            this.addControlUI();
            
            // Start auto-rotate after a delay
            setTimeout(() => {
                this.startAutoRotate();
            }, 2000);
        }

        /**
         * Preload all 360 images
         */
        preloadImages() {
            const loadingIndicator = this.createLoadingIndicator();
            this.container.appendChild(loadingIndicator);

            let loadedCount = 0;
            const totalImages = this.totalFrames;

            this.frames.forEach((frame, index) => {
                const img = new Image();
                const src = frame.src || frame.dataset.src;
                
                img.onload = () => {
                    loadedCount++;
                    const progress = Math.round((loadedCount / totalImages) * 100);
                    this.updateLoadingProgress(loadingIndicator, progress);
                    
                    if (loadedCount === totalImages) {
                        this.onImagesLoaded(loadingIndicator);
                    }
                };
                
                img.onerror = () => {
                    loadedCount++;
                    if (loadedCount === totalImages) {
                        this.onImagesLoaded(loadingIndicator);
                    }
                };
                
                img.src = src;
            });
        }

        /**
         * Create loading indicator
         */
        createLoadingIndicator() {
            const indicator = document.createElement('div');
            indicator.className = 'viewer-360-loading';
            indicator.style.cssText = 'position:absolute;top:50%;left:50%;transform:translate(-50%,-50%);text-align:center;';
            
            const spinner = document.createElement('div');
            spinner.className = 'loading-spinner';
            
            const text = document.createElement('div');
            text.className = 'loading-text';
            text.textContent = 'Loading 0%';
            
            indicator.appendChild(spinner);
            indicator.appendChild(text);
            
            return indicator;
        }

        /**
         * Update loading progress
         */
        updateLoadingProgress(indicator, progress) {
            const text = indicator.querySelector('.loading-text');
            if (text) {
                text.textContent = `Loading ${progress}%`;
            }
        }

        /**
         * On all images loaded
         */
        onImagesLoaded(indicator) {
            if (indicator && indicator.parentNode) {
                indicator.remove();
            }
            this.container.classList.add('loaded');
        }

        /**
         * Attach event listeners
         */
        attachEventListeners() {
            // Mouse events
            this.container.addEventListener('mousedown', this.onDragStart.bind(this));
            document.addEventListener('mousemove', this.onDragMove.bind(this));
            document.addEventListener('mouseup', this.onDragEnd.bind(this));

            // Touch events
            this.container.addEventListener('touchstart', this.onTouchStart.bind(this), { passive: false });
            document.addEventListener('touchmove', this.onTouchMove.bind(this), { passive: false });
            document.addEventListener('touchend', this.onTouchEnd.bind(this));

            // Keyboard navigation
            this.container.addEventListener('keydown', this.onKeyDown.bind(this));
            
            // Make container focusable
            this.container.setAttribute('tabindex', '0');
        }

        /**
         * Mouse drag start
         */
        onDragStart(e) {
            e.preventDefault();
            this.stopAutoRotate();
            this.isRotating = true;
            this.startX = e.clientX || e.pageX;
            this.container.style.cursor = 'grabbing';
        }

        /**
         * Mouse drag move
         */
        onDragMove(e) {
            if (!this.isRotating) return;

            const currentX = e.clientX || e.pageX;
            const deltaX = currentX - this.startX;
            
            if (Math.abs(deltaX) >= this.sensitivity) {
                const framesToMove = Math.floor(Math.abs(deltaX) / this.sensitivity);
                const direction = deltaX > 0 ? 1 : -1;
                
                this.rotateBy(framesToMove * direction);
                this.startX = currentX;
            }
        }

        /**
         * Mouse drag end
         */
        onDragEnd() {
            this.isRotating = false;
            this.container.style.cursor = 'grab';
        }

        /**
         * Touch start
         */
        onTouchStart(e) {
            e.preventDefault();
            this.stopAutoRotate();
            this.isRotating = true;
            this.startX = e.touches[0].clientX;
        }

        /**
         * Touch move
         */
        onTouchMove(e) {
            if (!this.isRotating) return;
            e.preventDefault();

            const currentX = e.touches[0].clientX;
            const deltaX = currentX - this.startX;
            
            if (Math.abs(deltaX) >= this.sensitivity) {
                const framesToMove = Math.floor(Math.abs(deltaX) / this.sensitivity);
                const direction = deltaX > 0 ? 1 : -1;
                
                this.rotateBy(framesToMove * direction);
                this.startX = currentX;
            }
        }

        /**
         * Touch end
         */
        onTouchEnd() {
            this.isRotating = false;
        }

        /**
         * Keyboard navigation
         */
        onKeyDown(e) {
            if (e.key === 'ArrowLeft') {
                e.preventDefault();
                this.stopAutoRotate();
                this.rotateBy(-1);
            } else if (e.key === 'ArrowRight') {
                e.preventDefault();
                this.stopAutoRotate();
                this.rotateBy(1);
            }
        }

        /**
         * Rotate by number of frames
         */
        rotateBy(frames) {
            let newFrame = this.currentFrame + frames;
            
            // Wrap around
            if (newFrame < 0) {
                newFrame = this.totalFrames + newFrame;
            } else if (newFrame >= this.totalFrames) {
                newFrame = newFrame - this.totalFrames;
            }
            
            this.showFrame(newFrame);
        }

        /**
         * Show specific frame
         */
        showFrame(index) {
            if (index < 0 || index >= this.totalFrames) return;

            this.frames.forEach(frame => frame.classList.remove('active'));
            this.frames[index].classList.add('active');
            this.currentFrame = index;
        }

        /**
         * Start auto-rotate
         */
        startAutoRotate() {
            if (this.autoRotate) return;
            
            this.autoRotate = true;
            this.autoRotateTimer = setInterval(() => {
                this.rotateBy(1);
            }, this.autoRotateSpeed);
        }

        /**
         * Stop auto-rotate
         */
        stopAutoRotate() {
            if (!this.autoRotate) return;
            
            this.autoRotate = false;
            if (this.autoRotateTimer) {
                clearInterval(this.autoRotateTimer);
                this.autoRotateTimer = null;
            }
        }

        /**
         * Add control UI
         */
        addControlUI() {
            const controls = document.createElement('div');
            controls.className = 'viewer-360-controls';
            
            // Auto-rotate toggle
            const autoRotateBtn = document.createElement('button');
            autoRotateBtn.className = 'control-btn auto-rotate-btn';
            autoRotateBtn.setAttribute('aria-label', 'Toggle auto-rotate');
            
            const icon = document.createElement('span');
            icon.className = 'dashicons dashicons-update';
            autoRotateBtn.appendChild(icon);
            autoRotateBtn.addEventListener('click', () => {
                if (this.autoRotate) {
                    this.stopAutoRotate();
                    autoRotateBtn.classList.remove('active');
                } else {
                    this.startAutoRotate();
                    autoRotateBtn.classList.add('active');
                }
            });
            
            // Frame counter
            const frameCounter = document.createElement('div');
            frameCounter.className = 'frame-counter';
            frameCounter.textContent = `1 / ${this.totalFrames}`;
            
            // Update counter on frame change
            this.container.addEventListener('framechange', () => {
                frameCounter.textContent = `${this.currentFrame + 1} / ${this.totalFrames}`;
            });
            
            controls.appendChild(autoRotateBtn);
            controls.appendChild(frameCounter);
            this.container.appendChild(controls);
            
            // Trigger initial update
            this.container.dispatchEvent(new Event('framechange'));
        }

        /**
         * Destroy viewer
         */
        destroy() {
            this.stopAutoRotate();
            
            // Remove event listeners
            this.container.removeEventListener('mousedown', this.onDragStart);
            document.removeEventListener('mousemove', this.onDragMove);
            document.removeEventListener('mouseup', this.onDragEnd);
            this.container.removeEventListener('touchstart', this.onTouchStart);
            document.removeEventListener('touchmove', this.onTouchMove);
            document.removeEventListener('touchend', this.onTouchEnd);
            this.container.removeEventListener('keydown', this.onKeyDown);
        }
    }

    /**
     * Initialize all 360 viewers
     */
    function init() {
        const viewers = document.querySelectorAll('.viewer-360-container');
        
        viewers.forEach(viewer => {
            new Product360Viewer(viewer);
        });
    }

    // Initialize on DOM ready
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', init);
    } else {
        init();
    }

    // Export to global namespace
    window.Product360Viewer = Product360Viewer;

})();

