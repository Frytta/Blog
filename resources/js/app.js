import './bootstrap';

const rootElement = document.documentElement;
const themeToggleButton = document.querySelector('[data-theme-toggle]');
const sunIcon = document.querySelector('[data-theme-icon="sun"]');
const moonIcon = document.querySelector('[data-theme-icon="moon"]');

const updateThemeIcons = () => {
    const isDark = rootElement.classList.contains('dark');

    if (!sunIcon || !moonIcon) {
        return;
    }

    sunIcon.classList.toggle('hidden', isDark);
    moonIcon.classList.toggle('hidden', !isDark);
};

updateThemeIcons();

if (themeToggleButton) {
    themeToggleButton.addEventListener('click', () => {
        const isDarkNow = rootElement.classList.toggle('dark');

        localStorage.setItem('theme', isDarkNow ? 'dark' : 'light');
        updateThemeIcons();
    });
}
