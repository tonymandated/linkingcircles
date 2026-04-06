/**
 * Accessibility Module
 * Manages: dark mode, font scaling, high contrast mode, and keyboard shortcuts
 */

const STORAGE_KEY_THEME = 'lca-theme';
const STORAGE_KEY_FONT_SCALE = 'lca-font-scale';
const STORAGE_KEY_HIGH_CONTRAST = 'lca-high-contrast';

export function initializeAccessibility() {
    // Initialize theme
    initializeTheme();

    // Initialize font scale
    initializeFontScale();

    // Initialize high contrast mode
    initializeHighContrast();

    // Setup keyboard shortcuts
    setupKeyboardShortcuts();
}

/**
 * Initialize dark mode from localStorage or system preference
 */
function initializeTheme() {
    const html = document.documentElement;
    const savedTheme = localStorage.getItem(STORAGE_KEY_THEME);

    let theme = savedTheme;
    if (!theme) {
        // Check system preference
        theme = window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light';
    }

    applyTheme(theme);

    // Listen for system theme changes
    window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', (e) => {
        if (!localStorage.getItem(STORAGE_KEY_THEME)) {
            applyTheme(e.matches ? 'dark' : 'light');
        }
    });
}

/**
 * Apply theme and store preference
 */
function applyTheme(theme) {
    const html = document.documentElement;

    if (theme === 'dark') {
        html.classList.add('dark');
        html.setAttribute('data-theme', 'dark');
    } else {
        html.classList.remove('dark');
        html.setAttribute('data-theme', 'light');
    }

    localStorage.setItem(STORAGE_KEY_THEME, theme);

    // Dispatch event for components to react to theme change
    window.dispatchEvent(new CustomEvent('accessibility:theme-changed', { detail: { theme } }));
}

/**
 * Toggle between light and dark mode
 */
export function toggleDarkMode() {
    const html = document.documentElement;
    const isDark = html.classList.contains('dark');
    applyTheme(isDark ? 'light' : 'dark');
}

/**
 * Get current theme
 */
export function getCurrentTheme() {
    return document.documentElement.getAttribute('data-theme') || 'light';
}

/**
 * Initialize font scaling from localStorage
 */
function initializeFontScale() {
    const saved = localStorage.getItem(STORAGE_KEY_FONT_SCALE);
    const scale = saved ? parseFloat(saved) : 1;
    applyFontScale(scale);
}

/**
 * Apply font scale to document
 */
function applyFontScale(scale) {
    const clampedScale = Math.max(0.75, Math.min(1.5, scale));
    document.documentElement.style.setProperty('--font-scale', clampedScale);
    localStorage.setItem(STORAGE_KEY_FONT_SCALE, clampedScale.toString());

    // Dispatch event for components to react to font scale change
    window.dispatchEvent(new CustomEvent('accessibility:font-scale-changed', { detail: { scale: clampedScale } }));
}

/**
 * Increase font size
 */
export function increaseFontSize() {
    const current = parseFloat(localStorage.getItem(STORAGE_KEY_FONT_SCALE) || '1');
    applyFontScale(current + 0.1);
}

/**
 * Decrease font size
 */
export function decreaseFontSize() {
    const current = parseFloat(localStorage.getItem(STORAGE_KEY_FONT_SCALE) || '1');
    applyFontScale(current - 0.1);
}

/**
 * Reset font size to default
 */
export function resetFontSize() {
    applyFontScale(1);
}

/**
 * Get current font scale
 */
export function getFontScale() {
    return parseFloat(localStorage.getItem(STORAGE_KEY_FONT_SCALE) || '1');
}

/**
 * Initialize high contrast mode from localStorage
 */
function initializeHighContrast() {
    const saved = localStorage.getItem(STORAGE_KEY_HIGH_CONTRAST);
    const isEnabled = saved === 'true';

    if (isEnabled) {
        enableHighContrast();
    }
}

/**
 * Toggle high contrast mode
 */
export function toggleHighContrast() {
    const isEnabled = document.documentElement.getAttribute('data-theme') === 'high-contrast';
    if (isEnabled) {
        disableHighContrast();
    } else {
        enableHighContrast();
    }
}

/**
 * Enable high contrast mode
 */
function enableHighContrast() {
    const html = document.documentElement;
    html.setAttribute('data-theme', 'high-contrast');
    html.classList.add('high-contrast');
    localStorage.setItem(STORAGE_KEY_HIGH_CONTRAST, 'true');

    // Dispatch event
    window.dispatchEvent(new CustomEvent('accessibility:high-contrast-changed', { detail: { enabled: true } }));
}

/**
 * Disable high contrast mode
 */
function disableHighContrast() {
    const html = document.documentElement;
    const theme = localStorage.getItem(STORAGE_KEY_THEME) || 'light';
    html.setAttribute('data-theme', theme);
    html.classList.remove('high-contrast');
    localStorage.setItem(STORAGE_KEY_HIGH_CONTRAST, 'false');

    // Dispatch event
    window.dispatchEvent(new CustomEvent('accessibility:high-contrast-changed', { detail: { enabled: false } }));
}

/**
 * Get high contrast status
 */
export function isHighContrastEnabled() {
    return document.documentElement.getAttribute('data-theme') === 'high-contrast';
}

/**
 * Setup keyboard shortcuts for accessibility
 */
function setupKeyboardShortcuts() {
    document.addEventListener('keydown', (e) => {
        // Alt + D: Toggle dark mode
        if (e.altKey && e.key === 'd') {
            e.preventDefault();
            toggleDarkMode();
        }

        // Alt + Plus: Increase font size
        if (e.altKey && (e.key === '+' || e.key === '=')) {
            e.preventDefault();
            increaseFontSize();
        }

        // Alt + Minus: Decrease font size
        if (e.altKey && e.key === '-') {
            e.preventDefault();
            decreaseFontSize();
        }

        // Alt + R: Reset font size
        if (e.altKey && e.key === 'r') {
            e.preventDefault();
            resetFontSize();
        }

        // Alt + H: Toggle high contrast
        if (e.altKey && e.key === 'h') {
            e.preventDefault();
            toggleHighContrast();
        }

        // Alt + ?: Show keyboard shortcuts help
        if (e.altKey && (e.key === '?' || e.shiftKey && e.key === '/')) {
            e.preventDefault();
            showAccessibilityHelp();
        }
    });
}

/**
 * Show accessibility help overlay
 */
function showAccessibilityHelp() {
    // Dispatch event for Livewire component or JS handler
    window.dispatchEvent(new CustomEvent('accessibility:show-help'));
}

// Make functions available globally
window.accessibility = {
    initialize: initializeAccessibility,
    toggleDarkMode,
    getCurrentTheme,
    increaseFontSize,
    decreaseFontSize,
    resetFontSize,
    getFontScale,
    toggleHighContrast,
    isHighContrastEnabled,
};
