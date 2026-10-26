const STORAGE_KEY = 'flowmerce-theme';
const root = document.documentElement;
const prefersDark = window.matchMedia('(prefers-color-scheme: dark)');
const prefersReducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)');
let transitionCleanup;

function storedTheme() {
    try {
        const value = localStorage.getItem(STORAGE_KEY);
        return value === 'light' || value === 'dark' ? value : null;
    } catch {
        return null;
    }
}

function desiredTheme() {
    return storedTheme() ?? (prefersDark.matches ? 'dark' : 'light');
}

function paintTheme(theme) {
    root.dataset.fmTheme = theme;
    root.style.colorScheme = theme;
}

function persistTheme(theme) {
    try { localStorage.setItem(STORAGE_KEY, theme); } catch { /* Private mode. */ }
}

function restoreTheme() {
    const theme = desiredTheme();
    if (root.dataset.fmTheme !== theme) paintTheme(theme);
    else root.style.colorScheme = theme;
}

export function initializeTheme() {
    restoreTheme();
}

function switchTheme() {
    const next = root.dataset.fmTheme === 'dark' ? 'light' : 'dark';

    // CSS transitions only run for an intentional toggle, never at initial paint.
    clearTimeout(transitionCleanup);
    if (!prefersReducedMotion.matches) root.classList.add('fm-theme-morph');

    // Persist first: Livewire page morphs will always have the correct preference.
    persistTheme(next);
    paintTheme(next);

    transitionCleanup = window.setTimeout(() => {
        root.classList.remove('fm-theme-morph');
    }, 570);
}

export function registerFlowmerceShell() {
    const register = () => {
        if (!window.Alpine) return;
        window.Alpine.data('flowmerceShell', () => ({
            sidebarOpen: false,
            commandOpen: false,
            commandQuery: '',

            openCommand() {
                this.sidebarOpen = false;
                this.commandQuery = '';
                this.commandOpen = true;
                this.$nextTick(() => this.$refs.commandSearch?.focus());
            },

            toggleTheme() { switchTheme(); },
        }));
    };

    if (window.Alpine) register();
    else document.addEventListener('alpine:init', register, { once: true });
}

// Livewire morphs the document/head during wire:navigate. Reapply the stored
// preference before and after navigation, not only on the initial JS import.
document.addEventListener('livewire:navigate', restoreTheme);
document.addEventListener('livewire:navigating', restoreTheme);
document.addEventListener('livewire:navigated', restoreTheme);

// If the HTML element is morphed and loses its data attribute between those
// events, immediately restore it. Never persist a server-rendered default.
new MutationObserver(() => {
    if (root.dataset.fmTheme !== desiredTheme()) restoreTheme();
}).observe(root, { attributes: true, attributeFilter: ['data-fm-theme'] });

prefersDark.addEventListener?.('change', () => { if (!storedTheme()) restoreTheme(); });


// Static Blade routes lack Livewire's Alpine runtime; share this theme logic.
document.addEventListener('click', event => {
    const button = event.target.closest('[data-fm-theme-toggle]');
    if (!button) return;
    if (window.Alpine && button.closest('[x-data]')) return;
    switchTheme();
});

initializeTheme();
registerFlowmerceShell();
