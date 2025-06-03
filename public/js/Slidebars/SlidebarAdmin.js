let activeItem = null;
const pathname = window.location.pathname;

if(pathname.includes('Admin')){
    activeItem = 'Home';
}
if(pathname.includes('Scoreboard')){
    activeItem = 'Scoreboard';
}
if(pathname.includes('Admin/Tasks')){
    activeItem = 'Tasks';
}
if(pathname.includes('Admin/Teams')){
    activeItem = 'Teams';
}
if(pathname.includes('Admin/Settings')){
    activeItem = 'Settings';
}

// Добавляем активный класс к сохраненному элементу при загрузке страницы
if (activeItem) {
    document.querySelector("." + activeItem.trim()).classList.add('active');
}
