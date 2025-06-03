const switchgrid = document.querySelector('.action-button.grid');
const switchlist = document.querySelector('.action-button.list');
const htmltable = document.querySelector('.products-area-wrapper');
const htmltable2 = document.querySelector('.Product-body');
const Table = localStorage.getItem('TableStyle');

if (htmltable){
    if (Table === 'gridView') {
        htmltable.classList.remove('tableView');
        htmltable.classList.add('gridView');
        if (htmltable2){
            htmltable2.classList.add('gridView');
        }
        switchgrid.classList.add('active')
        switchlist.classList.remove('active')
    }

    if (Table === 'tableView') {
        htmltable.classList.remove('gridView');
        if (htmltable2){
            htmltable2.classList.remove('gridView');
        }
        htmltable.classList.add('tableView');
        switchlist.classList.add('active')
        switchgrid.classList.remove('active')
    }

    if (!Table) {
        htmltable.classList.add('tableView');
        switchlist.classList.add('active')
        switchgrid.classList.remove('active')
    }
}

if (switchgrid){
    switchgrid.addEventListener('click', () => {
        localStorage.setItem('TableStyle', 'gridView');
    });
}

if (switchlist){
    switchlist.addEventListener('click', () => {
        localStorage.setItem('TableStyle', 'tableView');
    });
}

