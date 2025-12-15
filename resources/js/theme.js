// Theme Management
class ThemeManager {
    constructor() {
        this.themeToggleBtn = document.getElementById('theme-toggle');
        this.themeToggleLightIcon = document.getElementById('theme-toggle-light-icon');
        this.themeToggleDarkIcon = document.getElementById('theme-toggle-dark-icon');

        this.init();
    }

    init() {
        // Set initial icons based on current theme
        this.updateIcons();

        // Add event listener to toggle button
        if (this.themeToggleBtn) {
            this.themeToggleBtn.addEventListener('click', () => this.toggleTheme());
        }

        // Listen for system theme changes
        window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', (e) => {
            if (!localStorage.getItem('theme')) {
                this.setTheme(e.matches ? 'dark' : 'light');
            }
        });
    }

    getTheme() {
        return localStorage.getItem('theme') ||
               (window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light');
    }

    setTheme(theme) {
        if (theme === 'dark') {
            document.documentElement.classList.add('dark');
            localStorage.setItem('theme', 'dark');
        } else {
            document.documentElement.classList.remove('dark');
            localStorage.setItem('theme', 'light');
        }
        this.updateIcons();
    }

    toggleTheme() {
        const currentTheme = this.getTheme();
        const newTheme = currentTheme === 'dark' ? 'light' : 'dark';
        this.setTheme(newTheme);
    }

    updateIcons() {
        const theme = this.getTheme();

        if (this.themeToggleLightIcon && this.themeToggleDarkIcon) {
            if (theme === 'dark') {
                this.themeToggleLightIcon.classList.add('d-none');
                this.themeToggleDarkIcon.classList.remove('d-none');
            } else {
                this.themeToggleLightIcon.classList.remove('d-none');
                this.themeToggleDarkIcon.classList.add('d-none');
            }
        }
    }
}

// Initialize when DOM is ready
if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', () => {
        new ThemeManager();
    });
} else {
    new ThemeManager();
}
