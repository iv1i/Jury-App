let modeSwitch = document.querySelector('.mode-switch');

if (document.querySelector(".jsFilter")){
    document.querySelector(".jsFilter").addEventListener("click", function (event) {
        event.stopPropagation(); // Останавливаем всплытие события
        document.querySelector(".filter-menu").classList.toggle("active");
    });

// Обработчик клика по всему документу
    document.addEventListener("click", function (event) {
        const filterMenu = document.querySelector(".filter-menu");
        // Проверяем, был ли клик внутри .filter-menu или на кнопке .jsFilter
        if (filterMenu.classList.contains("active") && !filterMenu.contains(event.target) && !event.target.classList.contains("jsFilter")) {
            filterMenu.classList.remove("active");
        }
    });

    document.addEventListener('DOMContentLoaded', function() {
        // Обработчик нажатия клавиш
        document.addEventListener('keydown', function(event) {
            // Проверяем, нажата ли клавиша Esc (код 27)
            const filterMenu = document.querySelector(".filter-menu");
            if (event.key === 'Escape' || event.keyCode === 27) {
                filterMenu.classList.remove("active");
            }
        });
    });
}

if(document.querySelector(".grid")){
    document.querySelector(".grid").addEventListener("click", function () {
        document.querySelector(".list").classList.remove("active");
        document.querySelector(".grid").classList.add("active");
        document.querySelector(".products-area-wrapper").classList.add("gridView");
        document
            .querySelector(".products-area-wrapper")
            .classList.remove("tableView");
    });
}

if (document.querySelector(".list")){
    document.querySelector(".list").addEventListener("click", function () {
        document.querySelector(".list").classList.add("active");
        document.querySelector(".grid").classList.remove("active");
        document.querySelector(".products-area-wrapper").classList.remove("gridView");
        document.querySelector(".products-area-wrapper").classList.add("tableView");
    });
}

if(modeSwitch){
    modeSwitch.addEventListener('click', function () {
        document.documentElement.classList.toggle('light');
        modeSwitch.classList.toggle('active');

    });
}
