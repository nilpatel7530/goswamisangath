// Language Switcher - AJAX implementation
// Wrap in IIFE to prevent redeclaration errors when script is loaded multiple times
(function() {
    'use strict';
    
    // Check if already initialized
    if (window.languageSwitcherInitialized) {
        console.log('Language switcher already initialized, skipping re-init');
        return; // Already initialized
    }
    window.languageSwitcherInitialized = true;
    
let translations = {};

// Initialize translations from server
async function initTranslations() {
    try {
        // Get current locale from session or HTML lang attribute
        const currentLocale = document.documentElement.lang || 'en';
        const languageDisplay = document.querySelector('.language-display');
        const localeFromDisplay = languageDisplay ? languageDisplay.textContent.trim().toLowerCase() : null;
        
        // Determine which locale to fetch
        let localeToFetch = currentLocale;
        if (localeFromDisplay && ['gu', 'hi'].includes(localeFromDisplay)) {
            localeToFetch = localeFromDisplay;
        }
        
        const response = await fetch(`/translations?locale=${localeToFetch}`);
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        translations = await response.json();
        
        console.log('Translations loaded:', Object.keys(translations).length, 'keys');
        if (Object.keys(translations).length < 10) {
            console.warn('Warning: Very few translations loaded. Expected 300+. Check translations route.');
        }
        
        // Reuse variables from above (currentLocale, languageDisplay, localeFromDisplay already declared)
        console.log('Current locale:', currentLocale, 'Display locale:', localeFromDisplay);
        
        // If display shows Gujarati but locale is English, update locale
        if (localeFromDisplay === 'gu' && currentLocale === 'en') {
            document.documentElement.lang = 'gu';
        } else if (localeFromDisplay === 'hi' && currentLocale === 'en') {
            document.documentElement.lang = 'hi';
        }
        
        // Always apply translations if locale is not English or if we have translations
        const needsTranslation = currentLocale !== 'en' || localeFromDisplay === 'gu' || localeFromDisplay === 'hi';
        
        if (Object.keys(translations).length > 0) {
            // Wait for DOM to be fully ready
            const applyTranslations = () => {
                // Run immediately if possible, then one fallback to catch dynamic elements
                requestAnimationFrame(() => {
                    updatePageTranslations();
                });
                setTimeout(() => {
                    updatePageTranslations();
                }, 300);
            };
            
            if (document.readyState === 'loading') {
                document.addEventListener('DOMContentLoaded', applyTranslations);
            } else {
                applyTranslations();
            }
        }
    } catch (error) {
        console.error('Failed to load translations:', error);
    }
}

// Switch language via AJAX
async function switchLanguage(locale) {
    console.log('=== switchLanguage called ===');
    console.log('Locale:', locale);
    console.log('Function context:', this);
    
    // Prevent default if called from button (event might not be available)
    try {
        if (typeof event !== 'undefined' && event) {
            event.preventDefault();
            event.stopPropagation();
        }
    } catch (e) {
        // Event not available, that's okay
    }
    
    try {
        // Get CSRF token from meta tag or from Laravel's default location
        let csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
        
        // Fallback: try to get from _token input if meta tag doesn't exist
        if (!csrfToken) {
            const tokenInput = document.querySelector('input[name="_token"]');
            csrfToken = tokenInput ? tokenInput.value : null;
        }
        
        console.log('CSRF Token found:', !!csrfToken);
        
        if (!csrfToken) {
            console.warn('CSRF token not found, falling back to page reload');
            window.location.href = `/language/${locale}`;
            return;
        }
        
        console.log('Sending POST request to /language/' + locale + '/switch');
        
        const response = await fetch(`/language/${locale}/switch`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            },
            credentials: 'same-origin'
        });

        console.log('Response status:', response.status, response.statusText);

        if (!response.ok) {
            const errorText = await response.text();
            console.error('Response error:', errorText);
            let errorData;
            try {
                errorData = JSON.parse(errorText);
            } catch (e) {
                errorData = { message: errorText };
            }
            throw new Error(errorData.message || 'Failed to switch language');
        }

        const data = await response.json();
        console.log('Language switch response:', data);
        
        if (data.success) {
            // Reload translations with new locale
            console.log('Fetching translations for locale:', locale);
            const translationResponse = await fetch(`/translations?locale=${locale}`);
            if (translationResponse.ok) {
                translations = await translationResponse.json();
                console.log('Translations reloaded:', Object.keys(translations).length, 'keys');
            } else {
                console.warn('Failed to fetch translations, using response data');
                translations = data.translations || {};
            }
            
            // Update HTML lang attribute
            document.documentElement.lang = locale;
            console.log('Updated HTML lang to:', locale);
            
            // Update language display
            const displayElements = document.querySelectorAll('.language-display');
            console.log('Found', displayElements.length, 'language-display elements');
            displayElements.forEach(el => {
                el.textContent = locale.toUpperCase();
            });
            
            // Update all translations on the page
            console.log('Updating page translations...');
            updatePageTranslations();
            
            // Close dropdown if using Alpine.js
            setTimeout(() => {
                // Try to close Alpine dropdown
                const dropdowns = document.querySelectorAll('[x-data*="open"]');
                dropdowns.forEach(dropdown => {
                    if (dropdown.__x) {
                        dropdown.__x.$data.open = false;
                    }
                });
            }, 100);
            
            console.log('=== Language switch completed ===');
        } else {
            throw new Error('Language switch failed: ' + (data.message || 'Unknown error'));
        }
    } catch (error) {
        console.error('=== Error switching language ===');
        console.error('Error details:', error);
        console.error('Error stack:', error.stack);
        // Fallback to page reload
        console.log('Falling back to page reload');
        window.location.href = `/language/${locale}`;
    }
}

// Update all translations on the page
function updatePageTranslations() {
    if (!translations || Object.keys(translations).length === 0) {
        console.warn('No translations available');
        return;
    }
    
    let updatedCount = 0;
    let missingKeys = [];
    
    // Update elements with data-translate attribute
    document.querySelectorAll('[data-translate]').forEach(element => {
        const key = element.getAttribute('data-translate');
        if (translations[key]) {
            const translatedText = translations[key];
            
            // Always update if we have a translation (even if same as key for English)
            // This ensures consistency and handles all languages properly
            try {
                // Check if element has child elements
                if (element.children.length > 0) {
                    // For elements with children, check if they're just spans or other inline elements
                    const hasOnlyInlineChildren = Array.from(element.children).every(child => {
                        const inlineTags = ['SPAN', 'BR', 'STRONG', 'EM', 'B', 'I', 'U', 'SMALL', 'MARK'];
                        return inlineTags.includes(child.tagName);
                    });
                    
                    if (hasOnlyInlineChildren) {
                        // Replace entire text content including inline children
                        element.textContent = translatedText;
                        updatedCount++;
                    } else {
                        // Complex structure - try to update first text node
                        const textNodes = [];
                        const walker = document.createTreeWalker(
                            element,
                            NodeFilter.SHOW_TEXT,
                            null,
                            false
                        );
                        let node;
                        while (node = walker.nextNode()) {
                            if (node.textContent.trim()) {
                                textNodes.push(node);
                            }
                        }
                        
                        if (textNodes.length > 0) {
                            // Update first text node with translation
                            textNodes[0].textContent = translatedText;
                            // Clear other text nodes
                            for (let i = 1; i < textNodes.length; i++) {
                                textNodes[i].textContent = '';
                            }
                            updatedCount++;
                        } else {
                            // No text nodes found, replace textContent
                            element.textContent = translatedText;
                            updatedCount++;
                        }
                    }
                } else {
                    // Simple case - no children, just update text
                    element.textContent = translatedText;
                    updatedCount++;
                }
            } catch (error) {
                console.warn('Error updating element:', key, error);
                // Fallback: just replace textContent
                try {
                    element.textContent = translatedText;
                    updatedCount++;
                } catch (e) {
                    console.error('Failed to update element:', key, e);
                }
            }
        } else {
            missingKeys.push(key);
        }
    });
    
    console.log(`Updated ${updatedCount} translation elements`);
    if (missingKeys.length > 0) {
        console.warn('Missing translation keys:', missingKeys.slice(0, 10));
    }
    
    // Update select options with data-translate attribute
    document.querySelectorAll('select option[data-translate]').forEach(option => {
        const key = option.getAttribute('data-translate');
        if (translations[key]) {
            option.textContent = translations[key];
        }
    });
    
    // Update elements with data-translate-placeholder attribute
    document.querySelectorAll('[data-translate-placeholder]').forEach(element => {
        const key = element.getAttribute('data-translate-placeholder');
        if (translations[key]) {
            element.placeholder = translations[key];
        }
    });
    
    // Update elements with data-translate-title attribute
    document.querySelectorAll('[data-translate-title]').forEach(element => {
        const key = element.getAttribute('data-translate-title');
        if (translations[key]) {
            element.title = translations[key];
        }
    });
    
    // Update elements with data-translate-html attribute (for HTML content)
    document.querySelectorAll('[data-translate-html]').forEach(element => {
        const key = element.getAttribute('data-translate-html');
        if (translations[key]) {
            element.innerHTML = translations[key];
        }
    });
    
    // Update page title if translation exists
    const titleKey = 'Matrimony - Find the Love You Deserve';
    if (translations[titleKey] && translations[titleKey] !== titleKey) {
        document.title = translations[titleKey];
    }
    
    // Auto-update elements containing translation keys (fallback for pages without data-translate)
    // This scans for common translation patterns
    Object.keys(translations).forEach(key => {
        // Find elements that contain the exact English translation
        const englishText = translations[key]; // This would be the English version
        // Only update if we have a different locale
        if (document.documentElement.lang !== 'en') {
            document.querySelectorAll('*').forEach(el => {
                if (el.textContent && el.textContent.trim() === key && !el.hasAttribute('data-translate')) {
                    // Check if this looks like a translation key
                    if (translations[key] && translations[key] !== key) {
                        el.textContent = translations[key];
                    }
                }
            });
        }
    });
}

// Initialize on page load
if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', function() {
        initTranslations().then(() => {
            // Always check and update translations on initial load
            const currentLocale = document.documentElement.lang || 'en';
            const languageDisplay = document.querySelector('.language-display');
            const displayLocale = languageDisplay ? languageDisplay.textContent.trim().toLowerCase() : null;
            
            // Update if locale is not English
            if (displayLocale === 'gu' || displayLocale === 'hi' || currentLocale === 'gu' || currentLocale === 'hi') {
                // Multiple attempts to ensure all elements are updated
                setTimeout(() => updatePageTranslations(), 100);
                setTimeout(() => updatePageTranslations(), 500);
                setTimeout(() => updatePageTranslations(), 1000);
            }
        });
    });
} else {
    initTranslations().then(() => {
        const currentLocale = document.documentElement.lang || 'en';
        const languageDisplay = document.querySelector('.language-display');
        const displayLocale = languageDisplay ? languageDisplay.textContent.trim().toLowerCase() : null;
        
        // Update if locale is not English
        if (displayLocale === 'gu' || displayLocale === 'hi' || currentLocale === 'gu' || currentLocale === 'hi') {
            // Multiple attempts to ensure all elements are updated
            setTimeout(() => updatePageTranslations(), 100);
            setTimeout(() => updatePageTranslations(), 500);
            setTimeout(() => updatePageTranslations(), 1000);
        }
    });
}

// Make function globally available BEFORE IIFE ends
window.switchLanguage = switchLanguage;
window.updatePageTranslations = updatePageTranslations;

// Also expose it immediately for debugging
console.log('Language switcher initialized. switchLanguage function available:', typeof window.switchLanguage);
console.log('Function test:', typeof window.switchLanguage === 'function' ? 'SUCCESS' : 'FAILED');

// Ensure it's available immediately
if (typeof window.switchLanguage !== 'function') {
    console.error('CRITICAL: switchLanguage not assigned to window!');
}

})(); // End IIFE

// Double-check after IIFE completes
console.log('After IIFE - switchLanguage type:', typeof window.switchLanguage);

// Ensure function is available even after IIFE - this runs after IIFE completes
setTimeout(function() {
    if (typeof window.switchLanguage === 'undefined') {
        console.error('ERROR: switchLanguage function not available globally!');
    } else {
        console.log('✓ switchLanguage is globally available');
    }
}, 100);
