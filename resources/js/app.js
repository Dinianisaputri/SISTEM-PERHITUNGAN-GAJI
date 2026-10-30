
document.addEventListener('DOMContentLoaded', () => {
    const themeToggles = document.querySelectorAll('[data-theme-toggle]');

    const updateThemeControls = () => {
        const isDark = document.documentElement.classList.contains('dark');

        themeToggles.forEach((button) => {
            button.setAttribute('aria-pressed', String(isDark));
            button.setAttribute(
                'aria-label',
                isDark ? 'Aktifkan mode terang' : 'Aktifkan mode gelap'
            );
            button.setAttribute(
                'title',
                isDark ? 'Aktifkan mode terang' : 'Aktifkan mode gelap'
            );

            const label = button.querySelector('[data-theme-label]');
            const sun = button.querySelector('[data-theme-sun]');
            const moon = button.querySelector('[data-theme-moon]');

            if (label) {
                label.textContent = isDark ? 'Mode terang' : 'Mode gelap';
            }

            sun?.classList.toggle('hidden', !isDark);
            moon?.classList.toggle('hidden', isDark);
        });
    };

    updateThemeControls();

    themeToggles.forEach((button) => {
        button.addEventListener('click', () => {
            const isDark = !document.documentElement.classList.contains('dark');
            document.documentElement.classList.toggle('dark', isDark);
            document.documentElement.style.colorScheme = isDark ? 'dark' : 'light';
            localStorage.setItem('theme', isDark ? 'dark' : 'light');
            updateThemeControls();
        });
    });

    const navbar = document.querySelector('#desktop-navbar');

    if (!navbar) return;

    const dropdowns = navbar.querySelectorAll(
        'details.nav-dropdown'
    );

    dropdowns.forEach((dropdown) => {
        dropdown.addEventListener('toggle', () => {
            if (!dropdown.open) return;

            dropdowns.forEach((otherDropdown) => {
                if (
                    otherDropdown !== dropdown &&
                    otherDropdown.open
                ) {
                    otherDropdown.open = false;
                }
            });
        });
    });

    document.addEventListener('click', (event) => {
        if (!navbar.contains(event.target)) {
            dropdowns.forEach((dropdown) => {
                dropdown.open = false;
            });
        }
    });

    document.addEventListener('keydown', (event) => {
        if (event.key === 'Escape') {
            dropdowns.forEach((dropdown) => {
                dropdown.open = false;
            });
        }
    });
});
