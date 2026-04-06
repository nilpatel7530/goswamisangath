/**
 * Global Theme Manager
 * Handles dark/light theme switching and persistence
 */

(function() {
    'use strict';

    const ThemeManager = {
        // Default theme
        defaultTheme: 'light',
        
        // Storage key
        storageKey: 'trueunion_theme',
        
        /**
         * Initialize theme on page load
         */
        init: function() {
            // Get saved theme or use default (always default to light)
            const savedTheme = localStorage.getItem(this.storageKey);
            const theme = (savedTheme === 'dark' || savedTheme === 'light') ? savedTheme : this.defaultTheme;
            
            // Apply theme
            this.setTheme(theme);
            
            // Update toggle switch state
            this.updateToggleState(theme);
            
            // Listen for toggle changes
            this.bindToggleEvents();
        },
        
        /**
         * Set theme on document
         */
        setTheme: function(theme) {
            if (theme === 'dark' || theme === 'light') {
                // Use Tailwind's 'dark' class approach
                if (theme === 'dark') {
                    document.documentElement.classList.add('dark');
                } else {
                    document.documentElement.classList.remove('dark');
                }
                localStorage.setItem(this.storageKey, theme);
                
                // Dispatch custom event for other scripts
                const event = new CustomEvent('themeChanged', { detail: { theme: theme } });
                document.dispatchEvent(event);
            }
        },
        
        /**
         * Get current theme
         */
        getTheme: function() {
            return document.documentElement.classList.contains('dark') ? 'dark' : 'light';
        },
        
        /**
         * Toggle between light and dark
         */
        toggle: function() {
            const currentTheme = this.getTheme();
            const newTheme = currentTheme === 'dark' ? 'light' : 'dark';
            this.setTheme(newTheme);
            // Update toggle state after a brief delay to ensure DOM is updated
            setTimeout(() => {
                this.updateToggleState(newTheme);
            }, 50);
        },
        
        /**
         * Update toggle switch visual state
         */
        updateToggleState: function(theme) {
            const toggles = document.querySelectorAll('.theme-toggle input[type="checkbox"]');
            toggles.forEach(function(toggle) {
                toggle.checked = theme === 'dark';
            });
        },
        
        /**
         * Bind events to toggle switches
         */
        bindToggleEvents: function() {
            const self = this;
            
            // Remove any existing event listeners by using a flag
            if (this._eventsBound) {
                return; // Already bound
            }
            this._eventsBound = true;
            
            // Simple event delegation - listen for change events on checkboxes
            document.addEventListener('change', function(e) {
                const target = e.target;
                // Check if this is a theme toggle checkbox
                if (target.type === 'checkbox' && 
                    (target.classList.contains('theme-toggle-input') || target.closest('.theme-toggle'))) {
                    // Get the current checked state
                    const isChecked = target.checked;
                    const currentTheme = self.getTheme();
                    
                    // If checkbox is checked, theme should be dark
                    // If checkbox is unchecked, theme should be light
                    const desiredTheme = isChecked ? 'dark' : 'light';
                    
                    // Only change theme if it doesn't match
                    if (currentTheme !== desiredTheme) {
                        self.setTheme(desiredTheme);
                        self.updateToggleState(desiredTheme);
                    }
                }
            });
        }
    };
    
    // Initialize when DOM is ready
    function initializeTheme() {
        ThemeManager.init();
    }
    
    // Initialize immediately if DOM is ready, otherwise wait
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initializeTheme);
    } else {
        // DOM already loaded, initialize immediately
        initializeTheme();
    }
    
    // Also initialize on window load as fallback
    window.addEventListener('load', function() {
        if (!ThemeManager._eventsBound) {
            ThemeManager.bindToggleEvents();
        }
    });
    
    // Expose ThemeManager globally for manual control if needed
    window.ThemeManager = ThemeManager;
})();

/**
 * Auto-dismiss notifications after 5 seconds
 * Only targets explicit notification elements (exact ids or id ending with "-notification")
 * to avoid hiding sections like #basic-info, #success-stories, etc.
 */
(function() {
    'use strict';
    
    const processedNotifications = new Set();
    
    function autoDismissNotifications() {
        const idSelectors = [
            '#success-notification',
            '#error-notification',
            '[id$="-notification"]'
        ];
        
        let notifications = [];
        
        idSelectors.forEach(selector => {
            try {
                document.querySelectorAll(selector).forEach(el => {
                    if (!processedNotifications.has(el) && el.textContent.trim()) {
                        notifications.push(el);
                        processedNotifications.add(el);
                    }
                });
            } catch (e) {}
        });
        
        // Also find session messages by class patterns (success/error messages)
        const classSelectors = [
            'div.bg-green-500\\/20',
            'div.bg-red-500\\/20',
            'div.bg-blue-500\\/20',
            'div.bg-yellow-500\\/20',
            'div.bg-green-500\\/10'
        ];
        
        classSelectors.forEach(selector => {
            try {
                document.querySelectorAll(selector).forEach(el => {
                    // Check if it looks like a notification (has text, is visible, not already processed)
                    if (!processedNotifications.has(el) && 
                        el.textContent.trim() && 
                        el.offsetParent !== null &&
                        (el.textContent.includes('Welcome') ||
                         el.textContent.includes('success') ||
                         el.textContent.includes('error') ||
                         el.textContent.includes('Success') ||
                         el.textContent.includes('Error'))) {
                        notifications.push(el);
                        processedNotifications.add(el);
                    }
                });
            } catch (e) {
                // Invalid selector, skip
            }
        });
        
        // Auto-dismiss each notification after 5 seconds
        notifications.forEach(notification => {
            // Add transition classes if not already present
            if (!notification.classList.contains('transition-opacity')) {
                notification.classList.add('transition-opacity', 'duration-300');
            }
            
            setTimeout(() => {
                if (notification && notification.parentNode) {
                    notification.style.opacity = '0';
                    setTimeout(() => {
                        if (notification && notification.parentNode) {
                            notification.style.display = 'none';
                        }
                    }, 300);
                }
            }, 5000);
        });
    }
    
    // Run on DOM ready
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', autoDismissNotifications);
    } else {
        autoDismissNotifications();
    }
    
    // Also run after a short delay to catch any notifications added after initial load
    setTimeout(autoDismissNotifications, 100);
})();
