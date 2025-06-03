let activeItem = null;
const pathname = window.location.pathname;

if(pathname.includes('Home')){
    activeItem = 'Home';
}
if(pathname.includes('Scoreboard')){
    activeItem = 'Scoreboard';
}
if(pathname.includes('Statistics')){
    activeItem = 'Statistics';
}

// Добавляем активный класс к сохраненному элементу при загрузке страницы
if (activeItem) {
    document.querySelector("." + activeItem.trim()).classList.add('active');
}
