import { initializeAccessibility } from './accessibility.js';

// Initialize accessibility features on page load
document.addEventListener('DOMContentLoaded', () => {
    initializeAccessibility();
});

// Also initialize immediately in case DOM is already ready
if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', () => {
        initializeAccessibility();
    });
} else {
    initializeAccessibility();
}
