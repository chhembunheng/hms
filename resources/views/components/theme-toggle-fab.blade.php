<div class="fixed bottom-4 right-4 z-50">
    <button type="button" id="theme-toggle-fab"
            class="navbar-nav-link navbar-nav-link-icon rounded-pill shadow-lg bg-white dark:bg-gray-800 hover:bg-gray-100 dark:hover:bg-gray-700 p-3"
            title="Toggle theme">
        <i class="fa-solid fa-sun fa-fw text-xl" id="theme-toggle-fab-light-icon"></i>
        <i class="fa-solid fa-moon fa-fw text-xl d-none" id="theme-toggle-fab-dark-icon"></i>
    </button>
</div>

<script>
    // Theme toggle for floating action button
    (function() {
        const btn = document.getElementById('theme-toggle-fab');
        const lightIcon = document.getElementById('theme-toggle-fab-light-icon');
        const darkIcon = document.getElementById('theme-toggle-fab-dark-icon');

        function getTheme() {
            return localStorage.getItem('theme') ||
                   (window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light');
        }

        function updateIcons() {
            const theme = getTheme();
            if (lightIcon && darkIcon) {
                if (theme === 'dark') {
                    lightIcon.classList.add('d-none');
                    darkIcon.classList.remove('d-none');
                } else {
                    lightIcon.classList.remove('d-none');
                    darkIcon.classList.add('d-none');
                }
            }
        }

        function toggleTheme() {
            const currentTheme = getTheme();
            const newTheme = currentTheme === 'dark' ? 'light' : 'dark';

            if (newTheme === 'dark') {
                document.documentElement.classList.add('dark');
                localStorage.setItem('theme', 'dark');
            } else {
                document.documentElement.classList.remove('dark');
                localStorage.setItem('theme', 'light');
            }
            updateIcons();
        }

        updateIcons();

        if (btn) {
            btn.addEventListener('click', toggleTheme);
        }
    })();
</script>
