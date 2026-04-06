<div class="flex items-center gap-2" role="group" aria-label="Accessibility controls">
    <!-- Dark Mode Toggle -->
    <button
        type="button"
        @click="toggleDarkMode()"
        @click.window="toggleDarkMode"
        class="inline-flex items-center justify-center p-2 text-sm font-medium rounded-md transition-colors hover:bg-zinc-100 dark:hover:bg-zinc-700"
        :aria-label="isDarkMode ? 'Switch to light mode' : 'Switch to dark mode'"
        :title="isDarkMode ? 'Dark mode (Alt+D)' : 'Light mode (Alt+D)'"
    >
        <svg
            x-show="!isDarkMode"
            xmlns="http://www.w3.org/2000/svg"
            viewBox="0 0 24 24"
            fill="none"
            stroke="currentColor"
            stroke-width="2"
            stroke-linecap="round"
            stroke-linejoin="round"
            class="w-5 h-5"
        >
            <circle cx="12" cy="12" r="5" />
            <line x1="12" y1="1" x2="12" y2="3" />
            <line x1="12" y1="21" x2="12" y2="23" />
            <line x1="4.22" y1="4.22" x2="5.64" y2="5.64" />
            <line x1="18.36" y1="18.36" x2="19.78" y2="19.78" />
            <line x1="1" y1="12" x2="3" y2="12" />
            <line x1="21" y1="12" x2="23" y2="12" />
            <line x1="4.22" y1="19.78" x2="5.64" y2="18.36" />
            <line x1="18.36" y1="5.64" x2="19.78" y2="4.22" />
        </svg>
        <svg
            x-show="isDarkMode"
            xmlns="http://www.w3.org/2000/svg"
            viewBox="0 0 24 24"
            fill="none"
            stroke="currentColor"
            stroke-width="2"
            stroke-linecap="round"
            stroke-linejoin="round"
            class="w-5 h-5"
        >
            <path d="M21 12.79A9 9 0 1 1 11.21 3 7 7 0 0 0 21 12.79z" />
        </svg>
    </button>

    <!-- Text Size Decrease -->
    <button
        type="button"
        @click="decreaseFontSize()"
        class="inline-flex items-center justify-center px-2 py-1 text-xs font-semibold rounded-md border border-zinc-300 dark:border-zinc-600 transition-colors hover:bg-zinc-100 dark:hover:bg-zinc-700"
        title="Decrease text size (Alt+-)"
        aria-label="Decrease text size"
    >
        A−
    </button>

    <!-- Text Size Reset -->
    <button
        type="button"
        @click="resetFontSize()"
        class="inline-flex items-center justify-center px-2 py-1 text-xs font-semibold rounded-md border border-zinc-300 dark:border-zinc-600 transition-colors hover:bg-zinc-100 dark:hover:bg-zinc-700"
        title="Reset text size (Alt+R)"
        aria-label="Reset text size"
    >
        A
    </button>

    <!-- Text Size Increase -->
    <button
        type="button"
        @click="increaseFontSize()"
        class="inline-flex items-center justify-center px-2 py-1 text-sm font-semibold rounded-md border border-zinc-300 dark:border-zinc-600 transition-colors hover:bg-zinc-100 dark:hover:bg-zinc-700"
        title="Increase text size (Alt++)"
        aria-label="Increase text size"
    >
        A+
    </button>

    <!-- High Contrast Toggle -->
    <button
        type="button"
        @click="toggleHighContrast()"
        class="inline-flex items-center justify-center p-2 text-sm font-medium rounded-md transition-colors hover:bg-zinc-100 dark:hover:bg-zinc-700"
        :title="isHighContrast ? 'High contrast mode enabled (Alt+H)' : 'Enable high contrast mode (Alt+H)'"
        :aria-label="isHighContrast ? 'Disable high contrast' : 'Enable high contrast'"
    >
        <svg
            xmlns="http://www.w3.org/2000/svg"
            viewBox="0 0 24 24"
            fill="none"
            stroke="currentColor"
            stroke-width="2"
            stroke-linecap="round"
            stroke-linejoin="round"
            class="w-5 h-5"
            :class="{ 'opacity-100': isHighContrast, 'opacity-50': !isHighContrast }"
        >
            <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm0 18c-4.41 0-8-3.59-8-8s3.59-8 8-8 8 3.59 8 8-3.59 8-8 8zm3.5-9c.83 0 1.5-.67 1.5-1.5S16.33 8 15.5 8 14 8.67 14 9.5s.67 1.5 1.5 1.5zm-7 0c.83 0 1.5-.67 1.5-1.5S9.33 8 8.5 8 7 8.67 7 9.5 7.67 11 8.5 11zm3.5 6.5c2.33 0 4.31-1.46 5.11-3.5H6.89c.8 2.04 2.78 3.5 5.11 3.5z" />
        </svg>
    </button>

    <!-- Help -->
    <button
        type="button"
        @click="showAccessibilityHelp()"
        class="inline-flex items-center justify-center p-2 text-sm font-medium rounded-md transition-colors hover:bg-zinc-100 dark:hover:bg-zinc-700"
        title="Keyboard shortcuts help (Alt+?)"
        aria-label="Show accessibility help"
    >
        <svg
            xmlns="http://www.w3.org/2000/svg"
            viewBox="0 0 24 24"
            fill="none"
            stroke="currentColor"
            stroke-width="2"
            stroke-linecap="round"
            stroke-linejoin="round"
            class="w-5 h-5"
        >
            <circle cx="12" cy="12" r="10" />
            <path d="M12 16v-4M12 8h.01" />
        </svg>
    </button>
</div>

<script>
    function setupAccessibilityControls() {
        return {
            isDarkMode: false,
            fontScale: 1,
            isHighContrast: false,

            init() {
                this.updateState();

                // Listen for accessibility changes
                window.addEventListener('accessibility:theme-changed', () => this.updateState());
                window.addEventListener('accessibility:font-scale-changed', () => this.updateState());
                window.addEventListener('accessibility:high-contrast-changed', () => this.updateState());
            },

            updateState() {
                this.isDarkMode = document.documentElement.classList.contains('dark');
                this.fontScale = window.accessibility?.getFontScale?.() || 1;
                this.isHighContrast = window.accessibility?.isHighContrastEnabled?.() || false;
            },

            toggleDarkMode() {
                window.accessibility?.toggleDarkMode?.();
                this.updateState();
            },

            increaseFontSize() {
                window.accessibility?.increaseFontSize?.();
                this.updateState();
            },

            decreaseFontSize() {
                window.accessibility?.decreaseFontSize?.();
                this.updateState();
            },

            resetFontSize() {
                window.accessibility?.resetFontSize?.();
                this.updateState();
            },

            toggleHighContrast() {
                window.accessibility?.toggleHighContrast?.();
                this.updateState();
            },

            showAccessibilityHelp() {
                // Could be expanded to show a help modal
                alert('Keyboard Shortcuts:\n\nAlt+D: Toggle dark mode\nAlt++: Increase text size\nAlt+-: Decrease text size\nAlt+R: Reset text size\nAlt+H: Toggle high contrast\nAlt+?: Show this help');
            }
        };
    }
</script>
