<div class="relative max-w-full" data-accessibility-controls data-accessibility-controls-root>
    <div class="flex max-w-full flex-wrap items-center justify-end gap-2" role="group" aria-label="Accessibility controls">
        <button
            type="button"
            data-accessibility-action="toggle-dark-mode"
            class="inline-flex items-center justify-center p-2 text-sm font-medium rounded-md transition-colors hover:bg-zinc-100 dark:hover:bg-zinc-700"
            aria-label="Switch to dark mode"
            title="Dark mode (Alt+D)"
        >
            <svg
                data-accessibility-icon="sun"
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
                data-accessibility-icon="moon"
                xmlns="http://www.w3.org/2000/svg"
                viewBox="0 0 24 24"
                fill="none"
                stroke="currentColor"
                stroke-width="2"
                stroke-linecap="round"
                stroke-linejoin="round"
                class="hidden w-5 h-5"
            >
                <path d="M21 12.79A9 9 0 1 1 11.21 3 7 7 0 0 0 21 12.79z" />
            </svg>
        </button>

        <button
            type="button"
            data-accessibility-action="decrease-font-size"
            class="inline-flex items-center justify-center px-2 py-1 text-xs font-semibold rounded-md border border-zinc-300 dark:border-zinc-600 transition-colors hover:bg-zinc-100 dark:hover:bg-zinc-700"
            title="Decrease text size (Alt+-)"
            aria-label="Decrease text size"
        >A-</button>

        <button
            type="button"
            data-accessibility-action="reset-font-size"
            class="inline-flex items-center justify-center px-2 py-1 text-xs font-semibold rounded-md border border-zinc-300 dark:border-zinc-600 transition-colors hover:bg-zinc-100 dark:hover:bg-zinc-700"
            title="Reset text size (Alt+R)"
            aria-label="Reset text size"
        >A</button>

        <button
            type="button"
            data-accessibility-action="increase-font-size"
            class="inline-flex items-center justify-center px-2 py-1 text-sm font-semibold rounded-md border border-zinc-300 dark:border-zinc-600 transition-colors hover:bg-zinc-100 dark:hover:bg-zinc-700"
            title="Increase text size (Alt++)"
            aria-label="Increase text size"
        >A+</button>

        <button
            type="button"
            data-accessibility-action="toggle-high-contrast"
            class="inline-flex items-center justify-center p-2 text-sm font-medium rounded-md transition-colors hover:bg-zinc-100 dark:hover:bg-zinc-700"
            title="Enable high contrast (Alt+H)"
            aria-label="Enable high contrast"
        >
            <svg
                xmlns="http://www.w3.org/2000/svg"
                viewBox="0 0 24 24"
                fill="none"
                stroke="currentColor"
                stroke-width="2"
                stroke-linecap="round"
                stroke-linejoin="round"
                data-accessibility-icon="contrast"
                class="w-5 h-5 opacity-50"
            >
                <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm0 18c-4.41 0-8-3.59-8-8s3.59-8 8-8 8 3.59 8 8-3.59 8-8 8z" />
            </svg>
        </button>

        <button
            type="button"
            data-accessibility-action="toggle-read-aloud"
            class="inline-flex items-center justify-center p-2 text-sm font-medium rounded-md transition-colors hover:bg-zinc-100 dark:hover:bg-zinc-700"
            title="Start reading page"
            aria-label="Start reading page"
            aria-pressed="false"
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
                <path d="M11 5 6 9H3v6h3l5 4V5Z" />
                <path d="M15.54 8.46a5 5 0 0 1 0 7.07" />
                <path d="M19.07 4.93a10 10 0 0 1 0 14.14" />
            </svg>
        </button>

        <button
            type="button"
            data-accessibility-action="toggle-settings-panel"
            class="inline-flex items-center justify-center p-2 text-sm font-medium rounded-md transition-colors hover:bg-zinc-100 dark:hover:bg-zinc-700"
            title="Open accessibility settings"
            aria-label="Open accessibility settings"
            aria-expanded="false"
        >
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-5 h-5">
                <circle cx="12" cy="12" r="3" />
                <path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1 0 2.83 2 2 0 0 1-2.83 0l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-4 0v-.09a1.65 1.65 0 0 0-1-1.51 1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83 0 2 2 0 0 1 0-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82 1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1 0-4h.09a1.65 1.65 0 0 0 1.51-1 1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 0-2.83 2 2 0 0 1 2.83 0l.06.06a1.65 1.65 0 0 0 1.82.33h.09A1.65 1.65 0 0 0 10 3.09V3a2 2 0 0 1 4 0v.09a1.65 1.65 0 0 0 1 1.51h.09a1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 0 2 2 0 0 1 0 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82v.09a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 0 4h-.09a1.65 1.65 0 0 0-1.51 1Z" />
            </svg>
        </button>

        <button
            type="button"
            data-accessibility-action="show-help"
            class="inline-flex items-center justify-center p-2 text-sm font-medium rounded-md transition-colors hover:bg-zinc-100 dark:hover:bg-zinc-700"
            title="Keyboard shortcuts help (Alt+?)"
            aria-label="Show accessibility help"
        >
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-5 h-5">
                <circle cx="12" cy="12" r="10" />
                <path d="M12 16v-4M12 8h.01" />
            </svg>
        </button>
    </div>

    <section
        class="hidden absolute right-0 top-full z-20 mt-2 max-h-screen w-64 overflow-y-auto rounded-md border border-zinc-200 bg-white p-4 shadow-lg sm:w-80 dark:border-zinc-700 dark:bg-zinc-900"
        data-accessibility-panel
        aria-label="Accessibility settings"
    >
        <h3 class="text-sm font-semibold text-zinc-900 dark:text-zinc-100">Accessibility Settings</h3>

        <div class="mt-3 grid gap-3 text-xs">
            <label class="grid min-w-0 gap-1">
                <span class="font-medium text-zinc-700 dark:text-zinc-300">Locale</span>
                <select data-accessibility-setting="speech-locale" class="block w-full min-w-0 rounded-md border border-zinc-300 bg-white px-2 py-1.5 text-zinc-900 dark:border-zinc-600 dark:bg-zinc-800 dark:text-zinc-100">
                    <option value="">Auto (page language)</option>
                </select>
            </label>

            <label class="grid min-w-0 gap-1">
                <span class="font-medium text-zinc-700 dark:text-zinc-300">Accent / Voice</span>
                <select data-accessibility-setting="speech-voice" class="block w-full min-w-0 rounded-md border border-zinc-300 bg-white px-2 py-1.5 text-zinc-900 dark:border-zinc-600 dark:bg-zinc-800 dark:text-zinc-100">
                    <option value="">Default voice</option>
                </select>
            </label>

            <label class="grid min-w-0 gap-1">
                <span class="font-medium text-zinc-700 dark:text-zinc-300">Speed: <span data-accessibility-value="speech-rate">1.0</span>x</span>
                <input type="range" min="0.5" max="2" step="0.1" value="1" data-accessibility-setting="speech-rate" class="w-full min-w-0" />
            </label>

            <label class="grid min-w-0 gap-1">
                <span class="font-medium text-zinc-700 dark:text-zinc-300">Pitch: <span data-accessibility-value="speech-pitch">1.0</span></span>
                <input type="range" min="0.5" max="2" step="0.1" value="1" data-accessibility-setting="speech-pitch" class="w-full min-w-0" />
            </label>

            <label class="grid min-w-0 gap-1">
                <span class="font-medium text-zinc-700 dark:text-zinc-300">Volume: <span data-accessibility-value="speech-volume">1.0</span></span>
                <input type="range" min="0" max="1" step="0.1" value="1" data-accessibility-setting="speech-volume" class="w-full min-w-0" />
            </label>
        </div>

        <h3 class="mt-4 text-sm font-semibold text-zinc-900 dark:text-zinc-100">Reading Preferences</h3>
        <div class="mt-3 grid gap-2 text-xs text-zinc-700 dark:text-zinc-300">
            <label class="inline-flex items-center gap-2"><input type="checkbox" data-accessibility-setting="readable-spacing" /> Increase spacing</label>
            <label class="inline-flex items-center gap-2"><input type="checkbox" data-accessibility-setting="highlight-links" /> Highlight links</label>
            <label class="inline-flex items-center gap-2"><input type="checkbox" data-accessibility-setting="reduced-motion" /> Reduce motion</label>
        </div>
    </section>
</div>
