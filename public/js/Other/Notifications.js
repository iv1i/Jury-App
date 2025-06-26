let toastTimer1, toastTimer2;

function showToast(type, title, message, actions = null) {
    const toast = document.querySelector(".toast");
    const toastContent = toast.querySelector(".toast-content");
    const checkIcon = toast.querySelector(".check");
    const messageText1 = toast.querySelector(".text-1");
    const messageText2 = toast.querySelector(".text-2");
    const messageText3 = toast.querySelector(".text-3");
    const progress = toast.querySelector(".progress");

    // Очищаем предыдущие таймеры
    clearTimeout(toastTimer1);
    clearTimeout(toastTimer2);

    // Сбрасываем анимацию прогресс-бара
    progress.classList.remove("active");
    // Принудительный рефлоу для сброса анимации
    void progress.offsetWidth;

    // Удаляем предыдущие добавленные стили
    const existingStyles = document.querySelectorAll('style[data-toast-style]');
    existingStyles.forEach(style => style.remove());

    toast.style.display = "flex";

    // Set icon and colors based on type
    if (type === 'success') {
        checkIcon.className = "fas fa-solid fa-check check";
        checkIcon.style.backgroundColor = "#40f443";
        const style = document.createElement('style');
        style.innerHTML = '.toast .progress:before { background-color: #40f443 !important; }';
        style.setAttribute('data-toast-style', 'true');
        document.head.appendChild(style);
    }
    if (type === 'info'){
        checkIcon.className = "fas fa-solid fa-info check";
        checkIcon.style.backgroundColor = "#40aff4";
        const style = document.createElement('style');
        style.innerHTML = '.toast .progress:before { background-color: #40aff4 !important; }';
        style.setAttribute('data-toast-style', 'true');
        document.head.appendChild(style);
    }
    if (type === 'warning'){
        checkIcon.className = "fas fa-solid fa-exclamation check";
        checkIcon.style.backgroundColor = "#f4bb40";
        const style = document.createElement('style');
        style.innerHTML = '.toast .progress:before { background-color: #f4bb40 !important; }';
        style.setAttribute('data-toast-style', 'true');
        document.head.appendChild(style);
    }
    if (type === 'error') {
        checkIcon.className = "fas fa-solid fa-times check";
        checkIcon.style.backgroundColor = "#f4406a";
        const style = document.createElement('style');
        style.innerHTML = '.toast .progress:before { background-color: #f4406a !important; }';
        style.setAttribute('data-toast-style', 'true');
        document.head.appendChild(style);
    }

    messageText1.textContent = title;
    messageText2.textContent = message;

    // Очищаем предыдущие действия
    messageText3.innerHTML = '';

    // Добавляем действия с новой строки для каждого
    if (actions && actions.length > 0) {
        actions.forEach(action => {
            const actionElement = document.createElement('div');
            actionElement.textContent = `• ${action}`;
            messageText3.appendChild(actionElement);
        });
    }

    toast.classList.add("active");

    // Запускаем анимацию прогресс-бара снова
    setTimeout(() => {
        progress.classList.add("active");
    }, 10);

    const closeIcon = document.querySelector('.close');

    if (toast) {
        toastTimer1 = setTimeout(() => {
            toast.classList.remove('active');
        }, 5000);

        toastTimer2 = setTimeout(() => {
            progress.classList.remove('active');
        }, 5300);

        if (closeIcon) {
            closeIcon.removeEventListener('click', closeToast);
            closeIcon.addEventListener('click', closeToast);
        }
    }
}
function closeToast() {
    const toast = document.querySelector(".toast");
    const progress = toast.querySelector(".progress");

    toast.classList.remove('active');

    setTimeout(() => {
        progress.classList.remove('active');
    }, 300);

    clearTimeout(toastTimer1);
    clearTimeout(toastTimer2);
}
function callShowToast(data){
    const type = data.type || (data.success ? 'success' : 'error');
    const defaultTitles = {
        error: 'Ошибка',
        success: 'Успех',
        warning: 'Предупреждение',
        info: 'Информация'
    };
    const defaultMessages = {
        error: 'Произошла ошибка',
        success: 'Операция выполнена успешно',
        warning: 'Обратите внимание',
        info: 'Информационное сообщение'
    };

    showToast(
        type,
        defaultTitles[type] || 'Уведомление',
        data.message || defaultMessages[type],
        data.actions
    );
}
