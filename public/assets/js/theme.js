// Dark Mode / Light Mode Theme Manager
(function() {
    'use strict';

    class ThemeManager {
        constructor() {
            this.themeToggleBtn = document.getElementById('theme-toggle');
            this.themeToggleLightIcon = document.getElementById('theme-toggle-light-icon');
            this.themeToggleDarkIcon = document.getElementById('theme-toggle-dark-icon');
            this.isToggling = false;

            this.init();
        }

        init() {
            // Set initial icons based on current theme
            this.updateIcons();

            // Add event listener to toggle button with debounce
            if (this.themeToggleBtn) {
                this.themeToggleBtn.addEventListener('click', (e) => {
                    e.preventDefault();
                    if (!this.isToggling) {
                        this.toggleTheme();
                    }
                });
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

            // Reload page after theme change
            setTimeout(() => {
                window.location.reload();
            }, 100);
        }

        toggleTheme() {
            if (this.isToggling) return;

            this.isToggling = true;
            const currentTheme = this.getTheme();
            const newTheme = currentTheme === 'dark' ? 'light' : 'dark';
            this.setTheme(newTheme);

            // Reset flag after a short delay
            setTimeout(() => {
                this.isToggling = false;
            }, 300);
        }

        updateIcons() {
            const theme = this.getTheme();
            const themeText = document.getElementById('theme-toggle-text');

            if (this.themeToggleLightIcon && this.themeToggleDarkIcon) {
                if (theme === 'dark') {
                    this.themeToggleLightIcon.classList.add('d-none');
                    this.themeToggleDarkIcon.classList.remove('d-none');
                    if (themeText) themeText.textContent = 'Light Mode';
                } else {
                    this.themeToggleDarkIcon.classList.add('d-none');
                    this.themeToggleLightIcon.classList.remove('d-none');
                    if (themeText) themeText.textContent = 'Dark Mode';
                }
            }
        }
    }

    // Initialize when DOM is ready
    function initThemeManager() {
        new ThemeManager();
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initThemeManager);
    } else {
        initThemeManager();
    }

    // Export for global access if needed
    window.ThemeManager = ThemeManager;
})();
