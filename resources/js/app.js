import { initializeAccessibility } from './accessibility.js';
import { initializeHeaderDrawer } from './header-drawer.js';

function bootUi() {
    initializeAccessibility();
    initializeHeaderDrawer();
}

if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', bootUi, { once: true });
} else {
    bootUi();
}

document.addEventListener('livewire:navigated', bootUi);
