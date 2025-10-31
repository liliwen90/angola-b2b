/**
 * Language Switcher
 * Handle language switching functionality
 * 
 * @package Angola_B2B
 */

(function($) {
    'use strict';

    /**
     * Language Switcher Class
     */
    class LanguageSwitcher {
        constructor() {
            this.switchers = document.querySelectorAll('.language-select-dropdown');
            
            if (this.switchers.length > 0) {
                this.init();
            }
        }

        init() {
            this.switchers.forEach(switcher => {
                // Handle change event with loading state
                switcher.addEventListener('change', (e) => {
                    const url = e.target.value;
                    if (url) {
                        // Add loading state
                        e.target.disabled = true;
                        e.target.style.opacity = '0.6';
                        
                        // Switch language
                        this.switchLanguage(url);
                    }
                });
            });

            // Sync all language switchers
            this.syncSwitchers();
        }

        switchLanguage(url) {
            // Save language preference
            const langCode = this.extractLangCode(url);
            if (langCode && window.AngolaB2B && window.AngolaB2B.setCookie) {
                window.AngolaB2B.setCookie('angola_b2b_lang', langCode, 365);
            }

            // Navigate to new URL
            window.location.href = url;
        }

        extractLangCode(url) {
            // Try to extract language code from URL
            const patterns = [
                /\/([a-z]{2})\//,           // /en/, /pt/, /zh/
                /lang=([a-z]{2})/,          // ?lang=en
                /\/([a-z]{2}-[A-Z]{2})\//   // /zh-CN/, /pt-PT/
            ];

            for (let pattern of patterns) {
                const match = url.match(pattern);
                if (match) {
                    return match[1];
                }
            }

            return null;
        }

        syncSwitchers() {
            // If one switcher changes, sync others
            this.switchers.forEach((switcher, index) => {
                switcher.addEventListener('change', (e) => {
                    const selectedValue = e.target.value;
                    
                    this.switchers.forEach((otherSwitcher, otherIndex) => {
                        if (index !== otherIndex) {
                            otherSwitcher.value = selectedValue;
                        }
                    });
                });
            });
        }

        getCurrentLanguage() {
            // Get current language from body class or cookie
            const bodyClasses = document.body.className;
            const langMatch = bodyClasses.match(/lang-([a-z]{2}(?:-[a-z]{2})?)/i);
            
            if (langMatch) {
                return langMatch[1];
            }

            // Fallback to cookie
            if (window.AngolaB2B && window.AngolaB2B.getCookie) {
                return window.AngolaB2B.getCookie('angola_b2b_lang');
            }

            return null;
        }
    }

    /**
     * Handle language-specific content loading
     */
    class LanguageContent {
        constructor() {
            this.currentLang = this.detectLanguage();
            this.init();
        }

        init() {
            // Load language-specific assets
            this.loadLanguageAssets();

            // Handle RTL languages if needed
            this.handleRTL();

            // Update document lang attribute
            this.updateDocumentLang();
        }

        detectLanguage() {
            // Check body class
            const bodyClasses = document.body.className;
            const langMatch = bodyClasses.match(/lang-([a-z]{2}(?:-[a-z]{2})?)/i);
            
            if (langMatch) {
                return langMatch[1];
            }

            // Check HTML lang attribute
            const htmlLang = document.documentElement.lang;
            if (htmlLang) {
                return htmlLang;
            }

            // Default to English
            return 'en';
        }

        loadLanguageAssets() {
            // Load language-specific CSS if exists
            const langCSS = document.querySelector('link[data-lang-css]');
            if (langCSS) {
                const href = langCSS.getAttribute('href');
                const newHref = href.replace(/lang-[a-z]{2}/, 'lang-' + this.currentLang);
                langCSS.setAttribute('href', newHref);
            }
        }

        handleRTL() {
            // RTL languages: Arabic, Hebrew, Persian, Urdu
            const rtlLangs = ['ar', 'he', 'fa', 'ur'];
            
            if (rtlLangs.includes(this.currentLang)) {
                document.documentElement.setAttribute('dir', 'rtl');
            } else {
                document.documentElement.setAttribute('dir', 'ltr');
            }
        }

        updateDocumentLang() {
            document.documentElement.lang = this.currentLang;
        }
    }

    /**
     * Initialize on DOM ready
     */
    function init() {
        new LanguageSwitcher();
        new LanguageContent();

        // Add keyboard navigation for language switcher
        const switchers = document.querySelectorAll('.language-select-dropdown');
        switchers.forEach(switcher => {
            switcher.addEventListener('keydown', (e) => {
                if (e.key === 'Enter' || e.key === ' ') {
                    e.preventDefault();
                    switcher.click();
                }
            });
        });
    }

    // Initialize
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', init);
    } else {
        init();
    }

})(jQuery);

