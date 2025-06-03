let activeItem = null;
const pathname = window.location.pathname;

if(pathname.includes('Auth') && pathname.includes('/')){
    activeItem = 'Authorize';
}
if(pathname.includes('Rules')){
    activeItem = 'Rules';
}
if(pathname.includes('Statistics')){
    activeItem = 'Statistics';
}
// Добавляем активный класс к сохраненному элементу при загрузке страницы
if (activeItem) {
    document.querySelector("." + activeItem.trim()).classList.add('active');
}
