// Функция для применения стилей таблицы
function applyTableView() {
    const switchgrid = document.querySelector('.action-button.grid');
    const switchlist = document.querySelector('.action-button.list');
    const htmltable = document.querySelector('.products-area-wrapper');
    const htmltable2 = document.querySelector('.Product-body');
    const Table = localStorage.getItem('TableStyle');

    if (htmltable) {
        if (Table === 'gridView') {
            htmltable.classList.remove('tableView');
            htmltable.classList.add('gridView');
            if (htmltable2) {
                htmltable2.classList.add('gridView');
            }
            switchgrid?.classList.add('active');
            switchlist?.classList.remove('active');
        } else {
            htmltable.classList.remove('gridView');
            if (htmltable2) {
                htmltable2.classList.remove('gridView');
            }
            htmltable.classList.add('tableView');
            switchlist?.classList.add('active');
            switchgrid?.classList.remove('active');
        }
    }
}

// Инициализация при загрузке страницы
document.addEventListener('DOMContentLoaded', function() {
    applyTableView();
});

// Обработчики переключения видов
document.addEventListener('click', function(e) {
    if (e.target.closest('.action-button.grid')) {
        localStorage.setItem('TableStyle', 'gridView');
        applyTableView();
    } else if (e.target.closest('.action-button.list')) {
        localStorage.setItem('TableStyle', 'tableView');
        applyTableView();
    }
});
