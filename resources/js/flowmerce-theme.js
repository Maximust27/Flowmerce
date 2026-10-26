const STORAGE_KEY = 'flowmerce-theme';

function readPreference() {
    try { return localStorage.getItem(STORAGE_KEY); } catch (_) { return null; }
}

function applyTheme(theme) {
    document.documentElement.dataset.fmTheme = theme;
    document.documentElement.style.colorScheme = theme;
}

export function initializeTheme() {
    const saved = readPreference();
    const preferred = window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light';
    applyTheme(saved === 'light' || saved === 'dark' ? saved : preferred);
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

            toggleTheme() {
                const next = document.documentElement.dataset.fmTheme === 'dark' ? 'light' : 'dark';
                applyTheme(next);
                try { localStorage.setItem(STORAGE_KEY, next); } catch (_) {}
            },
        }));
    };

    if (window.Alpine) register();
    else document.addEventListener('alpine:init', register, { once: true });
}

initializeTheme();
registerFlowmerceShell();
