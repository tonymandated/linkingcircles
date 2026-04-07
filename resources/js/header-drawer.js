let headerDrawerBound = false;

export function initializeHeaderDrawer() {
    if (headerDrawerBound) {
        syncAllHeaderDrawers();
        return;
    }

    headerDrawerBound = true;

    document.addEventListener('click', handleHeaderDrawerClick);
    document.addEventListener('keydown', handleHeaderDrawerKeydown);

    syncAllHeaderDrawers();
}

function handleHeaderDrawerClick(event) {
    const toggleButton = event.target.closest('[data-header-drawer-toggle]');
    if (toggleButton) {
        const root = toggleButton.closest('[data-header-drawer-root]');
        if (!root) {
            return;
        }

        toggleDrawer(root);
        return;
    }

    const drawerLink = event.target.closest('[data-header-drawer-link]');
    if (drawerLink) {
        const root = drawerLink.closest('[data-header-drawer-root]');
        if (root) {
            closeDrawer(root);
        }

        return;
    }

    const backdrop = event.target.closest('[data-header-drawer-backdrop]');
    if (backdrop) {
        const root = backdrop.closest('[data-header-drawer-root]');
        if (root) {
            closeDrawer(root);
        }
    }
}

function handleHeaderDrawerKeydown(event) {
    if (event.key !== 'Escape') {
        return;
    }

    document.querySelectorAll('[data-header-drawer-root]').forEach((root) => {
        closeDrawer(root);
    });
}

function toggleDrawer(root) {
    if (isDrawerOpen(root)) {
        closeDrawer(root);
        return;
    }

    openDrawer(root);
}

function openDrawer(root) {
    const container = root.querySelector('[data-header-drawer-container]');
    const panel = root.querySelector('[data-header-drawer-panel]');
    const backdrop = root.querySelector('[data-header-drawer-backdrop]');
    const toggle = root.querySelector('[data-header-drawer-toggle]');
    const menuIcon = root.querySelector('[data-header-icon="menu"]');
    const closeIcon = root.querySelector('[data-header-icon="close"]');

    if (!container || !panel || !backdrop || !toggle) {
        return;
    }

    container.classList.remove('hidden', 'pointer-events-none');
    container.setAttribute('aria-hidden', 'false');
    panel.classList.remove('-translate-x-full');
    backdrop.classList.remove('opacity-0');
    backdrop.classList.add('opacity-100');

    toggle.setAttribute('aria-expanded', 'true');
    toggle.setAttribute('aria-label', 'Close menu');

    menuIcon?.classList.add('hidden');
    closeIcon?.classList.remove('hidden');

    document.body.classList.add('overflow-hidden');
    panel.focus();
}

function closeDrawer(root, options = { returnFocus: true }) {
    const container = root.querySelector('[data-header-drawer-container]');
    const panel = root.querySelector('[data-header-drawer-panel]');
    const backdrop = root.querySelector('[data-header-drawer-backdrop]');
    const toggle = root.querySelector('[data-header-drawer-toggle]');
    const menuIcon = root.querySelector('[data-header-icon="menu"]');
    const closeIcon = root.querySelector('[data-header-icon="close"]');

    if (!container || !panel || !backdrop || !toggle) {
        return;
    }

    panel.classList.add('-translate-x-full');
    backdrop.classList.remove('opacity-100');
    backdrop.classList.add('opacity-0');

    toggle.setAttribute('aria-expanded', 'false');
    toggle.setAttribute('aria-label', 'Open menu');

    menuIcon?.classList.remove('hidden');
    closeIcon?.classList.add('hidden');

    window.setTimeout(() => {
        if (!isDrawerOpen(root)) {
            container.classList.add('hidden', 'pointer-events-none');
            container.setAttribute('aria-hidden', 'true');
        }
    }, 300);

    document.body.classList.remove('overflow-hidden');
    if (options.returnFocus) {
        toggle.focus();
    }
}

function isDrawerOpen(root) {
    const toggle = root.querySelector('[data-header-drawer-toggle]');

    return toggle?.getAttribute('aria-expanded') === 'true';
}

function syncAllHeaderDrawers() {
    document.querySelectorAll('[data-header-drawer-root]').forEach((root) => {
        closeDrawer(root, { returnFocus: false });
    });
}
