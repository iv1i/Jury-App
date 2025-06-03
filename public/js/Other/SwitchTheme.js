document.addEventListener('DOMContentLoaded', function() {
    const themeButtons = document.querySelectorAll('.switch-theme-btn, #switchTheme'); // Поддержка и ID, и класса
    const html = document.documentElement;
    const savedTheme = localStorage.getItem('theme');

    // Установка начальной темы
    if (savedTheme === 'dark') {
        html.classList.add('dark');
        html.classList.remove('light');
    } else if (savedTheme === 'light') {
        html.classList.add('light');
        html.classList.remove('dark');
    } else {
        // Если тема не задана, можно использовать prefers-color-scheme
        const prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
        if (prefersDark) {
            html.classList.add('dark');
            localStorage.setItem('theme', 'dark');
        } else {
            html.classList.add('light');
            localStorage.setItem('theme', 'light');
        }
    }

    // Обработка клика по кнопке (работает для всех кнопок)
    themeButtons.forEach(button => {
        button.addEventListener('click', () => {
            if (html.classList.contains('dark')) {
                html.classList.remove('dark');
                html.classList.add('light');
                localStorage.setItem('theme', 'light');
            } else {
                html.classList.remove('light');
                html.classList.add('dark');
                localStorage.setItem('theme', 'dark');
            }
        });
    });
});
