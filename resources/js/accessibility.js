/**
 * Accessibility Module
 * Manages: color modes, font scaling, read aloud, reading preferences, and keyboard shortcuts.
 */

const STORAGE_KEY_THEME = 'lca-theme';
const STORAGE_KEY_FONT_SCALE = 'lca-font-scale';
const STORAGE_KEY_HIGH_CONTRAST = 'lca-high-contrast';
const STORAGE_KEY_SPEECH_SETTINGS = 'lca-speech-settings';
const STORAGE_KEY_READING_SETTINGS = 'lca-reading-settings';
const READ_ALOUD_TEXT_LIMIT = 12000;

const DEFAULT_SPEECH_SETTINGS = {
    locale: '',
    voiceUri: '',
    rate: 1,
    pitch: 1,
    volume: 1,
};

const DEFAULT_READING_SETTINGS = {
    readableSpacing: false,
    highlightLinks: false,
    reducedMotion: false,
};

let keyboardShortcutsBound = false;
let controlsBound = false;
let speechVoicesBound = false;
let speechSettings = { ...DEFAULT_SPEECH_SETTINGS };
let readingSettings = { ...DEFAULT_READING_SETTINGS };
let availableVoices = [];
let isReadAloudActive = false;
let currentUtterance = null;

export function initializeAccessibility() {
    stopReadAloud();

    initializeTheme();
    initializeFontScale();
    initializeHighContrast();
    initializeSpeechSettings();
    initializeReadingSettings();

    setupKeyboardShortcuts();
    bindAccessibilityControls();
    initializeSpeechVoices();
    syncAccessibilityControlStates();
}

function initializeTheme() {
    const savedTheme = localStorage.getItem(STORAGE_KEY_THEME);
    let theme = savedTheme;

    if (!theme) {
        theme = window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light';
    }

    applyTheme(theme);

    window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', (event) => {
        if (!localStorage.getItem(STORAGE_KEY_THEME)) {
            applyTheme(event.matches ? 'dark' : 'light');
        }
    });
}

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
    window.dispatchEvent(new CustomEvent('accessibility:theme-changed', { detail: { theme } }));
    syncAccessibilityControlStates();
}

export function toggleDarkMode() {
    const isDark = document.documentElement.classList.contains('dark');
    applyTheme(isDark ? 'light' : 'dark');
}

export function getCurrentTheme() {
    return document.documentElement.getAttribute('data-theme') || 'light';
}

function initializeFontScale() {
    const savedScale = localStorage.getItem(STORAGE_KEY_FONT_SCALE);
    const scale = savedScale ? parseFloat(savedScale) : 1;
    applyFontScale(scale);
}

function applyFontScale(scale) {
    const clampedScale = Math.max(0.75, Math.min(1.5, scale));
    document.documentElement.style.setProperty('--font-scale', clampedScale.toString());
    localStorage.setItem(STORAGE_KEY_FONT_SCALE, clampedScale.toString());

    window.dispatchEvent(new CustomEvent('accessibility:font-scale-changed', { detail: { scale: clampedScale } }));
    syncAccessibilityControlStates();
}

export function increaseFontSize() {
    const current = parseFloat(localStorage.getItem(STORAGE_KEY_FONT_SCALE) || '1');
    applyFontScale(current + 0.1);
}

export function decreaseFontSize() {
    const current = parseFloat(localStorage.getItem(STORAGE_KEY_FONT_SCALE) || '1');
    applyFontScale(current - 0.1);
}

export function resetFontSize() {
    applyFontScale(1);
}

export function getFontScale() {
    return parseFloat(localStorage.getItem(STORAGE_KEY_FONT_SCALE) || '1');
}

function initializeHighContrast() {
    const isEnabled = localStorage.getItem(STORAGE_KEY_HIGH_CONTRAST) === 'true';

    if (isEnabled) {
        enableHighContrast();
    } else {
        disableHighContrast();
    }
}

export function toggleHighContrast() {
    if (isHighContrastEnabled()) {
        disableHighContrast();
    } else {
        enableHighContrast();
    }
}

function enableHighContrast() {
    const html = document.documentElement;
    html.setAttribute('data-theme', 'high-contrast');
    html.classList.add('high-contrast');
    localStorage.setItem(STORAGE_KEY_HIGH_CONTRAST, 'true');

    window.dispatchEvent(new CustomEvent('accessibility:high-contrast-changed', { detail: { enabled: true } }));
    syncAccessibilityControlStates();
}

function disableHighContrast() {
    const html = document.documentElement;
    const theme = localStorage.getItem(STORAGE_KEY_THEME) || 'light';

    html.classList.remove('high-contrast');
    html.setAttribute('data-theme', theme);
    localStorage.setItem(STORAGE_KEY_HIGH_CONTRAST, 'false');

    window.dispatchEvent(new CustomEvent('accessibility:high-contrast-changed', { detail: { enabled: false } }));
    syncAccessibilityControlStates();
}

export function isHighContrastEnabled() {
    return document.documentElement.getAttribute('data-theme') === 'high-contrast';
}

function initializeSpeechSettings() {
    speechSettings = loadJsonStorage(STORAGE_KEY_SPEECH_SETTINGS, DEFAULT_SPEECH_SETTINGS);
    persistSpeechSettings();
}

function initializeReadingSettings() {
    readingSettings = loadJsonStorage(STORAGE_KEY_READING_SETTINGS, DEFAULT_READING_SETTINGS);
    applyReadingSettings();
}

function setupKeyboardShortcuts() {
    if (keyboardShortcutsBound) {
        return;
    }

    keyboardShortcutsBound = true;
    document.addEventListener('keydown', handleKeyboardShortcuts);
}

function handleKeyboardShortcuts(event) {
    if (event.altKey && event.key.toLowerCase() === 'd') {
        event.preventDefault();
        toggleDarkMode();
    }

    if (event.altKey && (event.key === '+' || event.key === '=')) {
        event.preventDefault();
        increaseFontSize();
    }

    if (event.altKey && event.key === '-') {
        event.preventDefault();
        decreaseFontSize();
    }

    if (event.altKey && event.key.toLowerCase() === 'r') {
        event.preventDefault();
        resetFontSize();
    }

    if (event.altKey && event.key.toLowerCase() === 'h') {
        event.preventDefault();
        toggleHighContrast();
    }

    if (event.altKey && event.key.toLowerCase() === 's') {
        event.preventDefault();
        toggleReadAloud();
    }

    if (event.altKey && (event.key === '?' || (event.shiftKey && event.key === '/'))) {
        event.preventDefault();
        showAccessibilityHelp();
    }

    if (event.key === 'Escape') {
        closeAllSettingsPanels();
    }
}

function bindAccessibilityControls() {
    if (controlsBound) {
        return;
    }

    controlsBound = true;
    document.addEventListener('click', handleDocumentClick);
    document.addEventListener('change', handleSettingsChange);

    window.addEventListener('accessibility:theme-changed', syncAccessibilityControlStates);
    window.addEventListener('accessibility:font-scale-changed', syncAccessibilityControlStates);
    window.addEventListener('accessibility:high-contrast-changed', syncAccessibilityControlStates);
}

function handleDocumentClick(event) {
    const actionButton = event.target.closest('[data-accessibility-action]');

    if (actionButton) {
        runAccessibilityAction(actionButton.dataset.accessibilityAction, actionButton);
        return;
    }

    if (!event.target.closest('[data-accessibility-controls-root]')) {
        closeAllSettingsPanels();
    }
}

function runAccessibilityAction(action, sourceButton) {
    if (action === 'toggle-dark-mode') {
        toggleDarkMode();
        return;
    }

    if (action === 'decrease-font-size') {
        decreaseFontSize();
        return;
    }

    if (action === 'reset-font-size') {
        resetFontSize();
        return;
    }

    if (action === 'increase-font-size') {
        increaseFontSize();
        return;
    }

    if (action === 'toggle-high-contrast') {
        toggleHighContrast();
        return;
    }

    if (action === 'toggle-read-aloud') {
        toggleReadAloud();
        return;
    }

    if (action === 'toggle-settings-panel') {
        toggleSettingsPanel(sourceButton);
        return;
    }

    if (action === 'show-help') {
        showAccessibilityHelp();
    }
}

function handleSettingsChange(event) {
    const target = event.target;
    if (!(target instanceof HTMLElement)) {
        return;
    }

    if (!target.matches('[data-accessibility-setting]')) {
        return;
    }

    const setting = target.dataset.accessibilitySetting;
    if (!setting) {
        return;
    }

    if (setting === 'speech-locale' && target instanceof HTMLSelectElement) {
        speechSettings.locale = target.value;
        if (!voiceExistsForLocale(speechSettings.voiceUri, speechSettings.locale)) {
            speechSettings.voiceUri = '';
        }
        persistSpeechSettings();
        syncAccessibilityControlStates();
        return;
    }

    if (setting === 'speech-voice' && target instanceof HTMLSelectElement) {
        speechSettings.voiceUri = target.value;
        persistSpeechSettings();
        syncAccessibilityControlStates();
        return;
    }

    if (setting === 'speech-rate' && target instanceof HTMLInputElement) {
        speechSettings.rate = clampNumber(parseFloat(target.value), 0.5, 2, 1);
        persistSpeechSettings();
        syncAccessibilityControlStates();
        return;
    }

    if (setting === 'speech-pitch' && target instanceof HTMLInputElement) {
        speechSettings.pitch = clampNumber(parseFloat(target.value), 0.5, 2, 1);
        persistSpeechSettings();
        syncAccessibilityControlStates();
        return;
    }

    if (setting === 'speech-volume' && target instanceof HTMLInputElement) {
        speechSettings.volume = clampNumber(parseFloat(target.value), 0, 1, 1);
        persistSpeechSettings();
        syncAccessibilityControlStates();
        return;
    }

    if (setting === 'readable-spacing' && target instanceof HTMLInputElement) {
        readingSettings.readableSpacing = target.checked;
        persistReadingSettings();
        return;
    }

    if (setting === 'highlight-links' && target instanceof HTMLInputElement) {
        readingSettings.highlightLinks = target.checked;
        persistReadingSettings();
        return;
    }

    if (setting === 'reduced-motion' && target instanceof HTMLInputElement) {
        readingSettings.reducedMotion = target.checked;
        persistReadingSettings();
    }
}

function initializeSpeechVoices() {
    if (!isSpeechSynthesisSupported()) {
        return;
    }

    availableVoices = window.speechSynthesis.getVoices();

    if (speechVoicesBound) {
        syncAccessibilityControlStates();
        return;
    }

    speechVoicesBound = true;
    window.speechSynthesis.addEventListener('voiceschanged', () => {
        availableVoices = window.speechSynthesis.getVoices();
        syncAccessibilityControlStates();
    });

    syncAccessibilityControlStates();
}

function toggleReadAloud() {
    if (!isSpeechSynthesisSupported()) {
        window.alert('Read aloud is not supported by this browser.');
        return;
    }

    if (isReadAloudActive) {
        stopReadAloud();
        return;
    }

    const readableText = getReadablePageText();
    if (!readableText) {
        window.alert('No readable content was found on this page.');
        return;
    }

    const utterance = new SpeechSynthesisUtterance(readableText.slice(0, READ_ALOUD_TEXT_LIMIT));
    const selectedVoice = getSelectedVoice();

    utterance.rate = speechSettings.rate;
    utterance.pitch = speechSettings.pitch;
    utterance.volume = speechSettings.volume;

    if (speechSettings.locale) {
        utterance.lang = speechSettings.locale;
    }

    if (selectedVoice) {
        utterance.voice = selectedVoice;
        utterance.lang = selectedVoice.lang;
    }

    utterance.onstart = () => {
        isReadAloudActive = true;
        syncAccessibilityControlStates();
    };

    utterance.onend = () => {
        isReadAloudActive = false;
        currentUtterance = null;
        syncAccessibilityControlStates();
    };

    utterance.onerror = () => {
        isReadAloudActive = false;
        currentUtterance = null;
        syncAccessibilityControlStates();
    };

    currentUtterance = utterance;
    window.speechSynthesis.cancel();
    window.speechSynthesis.speak(utterance);
}

function stopReadAloud() {
    if (!isSpeechSynthesisSupported()) {
        isReadAloudActive = false;
        return;
    }

    window.speechSynthesis.cancel();
    currentUtterance = null;
    isReadAloudActive = false;
    syncAccessibilityControlStates();
}

function isSpeechSynthesisSupported() {
    return 'speechSynthesis' in window && 'SpeechSynthesisUtterance' in window;
}

function getReadablePageText() {
    const mainContent = document.querySelector('#main-content');
    const source = mainContent ?? document.body;

    if (!source) {
        return '';
    }

    return source.innerText.replace(/\s+/g, ' ').trim();
}

function getSelectedVoice() {
    if (!speechSettings.voiceUri) {
        return null;
    }

    return availableVoices.find((voice) => voice.voiceURI === speechSettings.voiceUri) ?? null;
}

function voiceExistsForLocale(voiceUri, locale) {
    if (!voiceUri) {
        return false;
    }

    const voice = availableVoices.find((item) => item.voiceURI === voiceUri);
    if (!voice) {
        return false;
    }

    if (!locale) {
        return true;
    }

    return voice.lang.toLowerCase().startsWith(locale.toLowerCase());
}

function syncAccessibilityControlStates() {
    const isDark = document.documentElement.classList.contains('dark');
    const isHighContrast = isHighContrastEnabled();
    const unsupportedSpeech = !isSpeechSynthesisSupported();

    document.querySelectorAll('[data-accessibility-controls]').forEach((group) => {
        const darkToggle = group.querySelector('[data-accessibility-action="toggle-dark-mode"]');
        if (darkToggle) {
            darkToggle.setAttribute('aria-pressed', isDark ? 'true' : 'false');
            darkToggle.setAttribute('aria-label', isDark ? 'Switch to light mode' : 'Switch to dark mode');
            darkToggle.setAttribute('title', `${isDark ? 'Light' : 'Dark'} mode (Alt+D)`);
        }

        const sunIcon = group.querySelector('[data-accessibility-icon="sun"]');
        const moonIcon = group.querySelector('[data-accessibility-icon="moon"]');
        if (sunIcon && moonIcon) {
            sunIcon.classList.toggle('hidden', isDark);
            moonIcon.classList.toggle('hidden', !isDark);
        }

        const contrastToggle = group.querySelector('[data-accessibility-action="toggle-high-contrast"]');
        if (contrastToggle) {
            contrastToggle.setAttribute('aria-pressed', isHighContrast ? 'true' : 'false');
            contrastToggle.setAttribute('aria-label', isHighContrast ? 'Disable high contrast' : 'Enable high contrast');
            contrastToggle.setAttribute('title', `${isHighContrast ? 'Disable' : 'Enable'} high contrast (Alt+H)`);
        }

        const contrastIcon = group.querySelector('[data-accessibility-icon="contrast"]');
        if (contrastIcon) {
            contrastIcon.classList.toggle('opacity-50', !isHighContrast);
            contrastIcon.classList.toggle('opacity-100', isHighContrast);
        }

        const readAloudToggle = group.querySelector('[data-accessibility-action="toggle-read-aloud"]');
        if (readAloudToggle) {
            readAloudToggle.setAttribute('aria-pressed', isReadAloudActive ? 'true' : 'false');
            readAloudToggle.setAttribute('aria-label', isReadAloudActive ? 'Stop reading page' : 'Start reading page');
            readAloudToggle.setAttribute('title', `${isReadAloudActive ? 'Stop' : 'Start'} reading page (Alt+S)`);
            readAloudToggle.toggleAttribute('disabled', unsupportedSpeech);
            readAloudToggle.classList.toggle('opacity-50', unsupportedSpeech);
            readAloudToggle.classList.toggle('cursor-not-allowed', unsupportedSpeech);
        }

        syncSettingsPanel(group);
    });
}

function syncSettingsPanel(group) {
    const localeSelect = group.querySelector('[data-accessibility-setting="speech-locale"]');
    if (localeSelect instanceof HTMLSelectElement) {
        syncLocaleOptions(localeSelect);
    }

    const voiceSelect = group.querySelector('[data-accessibility-setting="speech-voice"]');
    if (voiceSelect instanceof HTMLSelectElement) {
        syncVoiceOptions(voiceSelect);
    }

    setInputValue(group, 'speech-rate', speechSettings.rate);
    setInputValue(group, 'speech-pitch', speechSettings.pitch);
    setInputValue(group, 'speech-volume', speechSettings.volume);

    setOutputValue(group, 'speech-rate', speechSettings.rate.toFixed(1));
    setOutputValue(group, 'speech-pitch', speechSettings.pitch.toFixed(1));
    setOutputValue(group, 'speech-volume', speechSettings.volume.toFixed(1));

    setCheckboxValue(group, 'readable-spacing', readingSettings.readableSpacing);
    setCheckboxValue(group, 'highlight-links', readingSettings.highlightLinks);
    setCheckboxValue(group, 'reduced-motion', readingSettings.reducedMotion);

    const panelToggle = group.querySelector('[data-accessibility-action="toggle-settings-panel"]');
    const panel = group.querySelector('[data-accessibility-panel]');
    if (panelToggle && panel) {
        panelToggle.setAttribute('aria-expanded', panel.classList.contains('hidden') ? 'false' : 'true');
    }
}

function syncLocaleOptions(select) {
    const locales = Array.from(new Set(availableVoices.map((voice) => voice.lang))).sort((a, b) => a.localeCompare(b));
    const preserved = speechSettings.locale;

    select.innerHTML = '';
    select.append(new Option('Auto (page language)', ''));

    locales.forEach((locale) => {
        select.append(new Option(locale, locale));
    });

    select.value = locales.includes(preserved) ? preserved : '';
    speechSettings.locale = select.value;
}

function syncVoiceOptions(select) {
    const filteredVoices = availableVoices.filter((voice) => {
        if (!speechSettings.locale) {
            return true;
        }

        return voice.lang.toLowerCase().startsWith(speechSettings.locale.toLowerCase());
    });

    const preserved = speechSettings.voiceUri;
    select.innerHTML = '';
    select.append(new Option('Default voice', ''));

    filteredVoices.forEach((voice) => {
        const truncatedName = voice.name.length > 28 ? `${voice.name.slice(0, 25)}...` : voice.name;
        const descriptor = `${truncatedName} (${voice.lang})${voice.default ? ' - default' : ''}`;
        select.append(new Option(descriptor, voice.voiceURI));
    });

    const isValid = filteredVoices.some((voice) => voice.voiceURI === preserved);
    select.value = isValid ? preserved : '';
    speechSettings.voiceUri = select.value;
}

function setInputValue(group, setting, value) {
    const element = group.querySelector(`[data-accessibility-setting="${setting}"]`);
    if (element instanceof HTMLInputElement) {
        element.value = value.toString();
    }
}

function setOutputValue(group, setting, value) {
    const element = group.querySelector(`[data-accessibility-value="${setting}"]`);
    if (element) {
        element.textContent = value;
    }
}

function setCheckboxValue(group, setting, value) {
    const element = group.querySelector(`[data-accessibility-setting="${setting}"]`);
    if (element instanceof HTMLInputElement) {
        element.checked = value;
    }
}

function applyReadingSettings() {
    const html = document.documentElement;

    html.setAttribute('data-readable-spacing', readingSettings.readableSpacing ? 'true' : 'false');
    html.setAttribute('data-highlight-links', readingSettings.highlightLinks ? 'true' : 'false');
    html.setAttribute('data-reduced-motion', readingSettings.reducedMotion ? 'true' : 'false');

    syncAccessibilityControlStates();
}

function persistSpeechSettings() {
    localStorage.setItem(STORAGE_KEY_SPEECH_SETTINGS, JSON.stringify(speechSettings));
}

function persistReadingSettings() {
    localStorage.setItem(STORAGE_KEY_READING_SETTINGS, JSON.stringify(readingSettings));
    applyReadingSettings();
}

function loadJsonStorage(key, fallback) {
    const raw = localStorage.getItem(key);
    if (!raw) {
        return { ...fallback };
    }

    try {
        const parsed = JSON.parse(raw);
        return { ...fallback, ...parsed };
    } catch {
        return { ...fallback };
    }
}

function clampNumber(value, min, max, fallback) {
    if (Number.isNaN(value)) {
        return fallback;
    }

    return Math.max(min, Math.min(max, value));
}

function toggleSettingsPanel(sourceButton) {
    const root = sourceButton?.closest('[data-accessibility-controls-root]');
    if (!root) {
        return;
    }

    const panel = root.querySelector('[data-accessibility-panel]');
    if (!panel) {
        return;
    }

    const willOpen = panel.classList.contains('hidden');
    closeAllSettingsPanels();

    if (willOpen) {
        panel.classList.remove('hidden');
    }

    syncAccessibilityControlStates();
}

function closeAllSettingsPanels() {
    document.querySelectorAll('[data-accessibility-panel]').forEach((panel) => {
        panel.classList.add('hidden');
    });

    syncAccessibilityControlStates();
}

function showAccessibilityHelp() {
    window.alert('Keyboard Shortcuts:\n\nAlt+D: Toggle dark mode\nAlt++: Increase text size\nAlt+-: Decrease text size\nAlt+R: Reset text size\nAlt+H: Toggle high contrast\nAlt+S: Start/stop reading page\nAlt+?: Show this help');
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
    toggleReadAloud,
};
