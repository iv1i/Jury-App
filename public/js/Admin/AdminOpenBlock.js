function OpenBlocks(Type){
    if (Type === 'tasks'){
        document.getElementById('MyFormPlus').addEventListener('submit', async function (event) {
            event.preventDefault(); // предотвращаем стандартное поведение формы

            const formData = new FormData(this); // создаем объект FormData из формы
            const responseplus = await fetch('/Admin/Tasks/Add', {
                method: 'POST',
                body: formData,
            })

            const data = await responseplus.json();

            if (responseplus.ok && data.success) {
                showToast('success', 'Успех', data.message || 'Операция выполнена успешно');
                return data;
            } else {
                showToast('error', 'Ошибка', data.message || 'Произошла ошибка');
                return data;
            }
        });
        document.getElementById('MyFormMinus').addEventListener('submit', async function (event) {
            event.preventDefault(); // предотвращаем стандартное поведение формы

            const formData = new FormData(this); // создаем объект FormData из формы

            const responseminus = await fetch('/Admin/Tasks/Delete', {
                method: 'POST',
                body: formData,
            })


            const data = await responseminus.json();

            if (responseminus.ok && data.success) {
                showToast('success', 'Успех', data.message || 'Операция выполнена успешно');
                return data;
            } else {
                showToast('error', 'Ошибка', data.message || 'Произошла ошибка');
                return data;
            }
        });

        const blurButton = document.getElementById('CloseBtnPlus');
        blurButton.addEventListener('click', function() {
            const TopmostDiv = document.querySelector('.topmost-div-task-plus');
            const appContent = document.querySelector('.app-content');
            const minusButton = document.querySelector('.button-minus');
            const plusButton = document.querySelector('.button-plus');
            plusButton.style.display = 'block';
            minusButton.style.display = 'block';
            appContent.style.filter = 'none';
            TopmostDiv.style.display = 'none';
            CloseTaskBanner.style.display = 'none';
        });

        const blurButton2 = document.getElementById('CloseBtnMinus');
        blurButton2.addEventListener('click', function() {
            const TopmostDiv = document.querySelector('.topmost-div-task-minus');
            const appContent = document.querySelector('.app-content');
            const plusButton = document.querySelector('.button-plus');
            const minusButton = document.querySelector('.button-minus');
            minusButton.style.display = 'block';
            plusButton.style.display = 'block';
            appContent.style.filter = 'none';
            TopmostDiv.style.display = 'none';
            CloseTaskBanner.style.display = 'none';

        });

        const plusButton = document.getElementById('button-plus');
        plusButton.addEventListener('click', function() {
            const TopmostDiv = document.querySelector('.topmost-div-task-plus');
            const appContent = document.querySelector('.app-content');
            const minusButton = document.querySelector('.button-minus');
            const plusButton = document.querySelector('.button-plus');
            plusButton.style.display = 'none';
            minusButton.style.display = 'none';
            appContent.style.filter = 'blur(4px)';
            TopmostDiv.style.display = 'block';
            CloseTaskBanner.style.display = 'block';
        });

        const minusButton = document.getElementById('button-minus');
        minusButton.addEventListener('click', function() {
            const TopmostDiv = document.querySelector('.topmost-div-task-minus');
            const appContent = document.querySelector('.app-content');
            const plusButton = document.querySelector('.button-plus');
            const minusButton = document.querySelector('.button-minus');
            minusButton.style.display = 'none';
            plusButton.style.display = 'none';
            appContent.style.filter = 'blur(4px)';
            TopmostDiv.style.display = 'block';
            CloseTaskBanner.style.display = 'block';
        });
    }
    if (Type === 'teams'){
        document.getElementById('MyFormPlus').addEventListener('submit', async function (event) {
            event.preventDefault(); // предотвращаем стандартное поведение формы

            const formData = new FormData(this); // создаем объект FormData из формы
            const responseplus = await fetch('/Admin/Teams/Add', {
                method: 'POST',
                body: formData,
            })

            const data = await responseplus.json();

            if (responseplus.ok && data.success) {
                showToast('success', 'Успех', data.message || 'Операция выполнена успешно');
                return data;
            } else {
                showToast('error', 'Ошибка', data.message || 'Произошла ошибка');
                return data;
            }
        });
        document.getElementById('MyFormMinus').addEventListener('submit', async function (event) {
            event.preventDefault(); // предотвращаем стандартное поведение формы

            const formData = new FormData(this); // создаем объект FormData из формы

            const responseminus = await fetch('/Admin/Teams/Delete', {
                method: 'POST',
                body: formData,
            })


            const data = await responseminus.json();

            if (responseminus.ok && data.success) {
                showToast('success', 'Успех', data.message || 'Операция выполнена успешно');
                return data;
            } else {
                showToast('error', 'Ошибка', data.message || 'Произошла ошибка');
                return data;
            }
        });

        const blurButton = document.getElementById('CloseBtnPlus');
        blurButton.addEventListener('click', function() {
            const TopmostDiv = document.querySelector('.topmost-div-task-plus');
            const appContent = document.querySelector('.app-content');
            const minusButton = document.querySelector('.button-minus');
            const plusButton = document.querySelector('.button-plus');
            plusButton.style.display = 'block';
            minusButton.style.display = 'block';
            appContent.style.filter = 'none';
            TopmostDiv.style.display = 'none';
            CloseTeamBanner.style.display = 'none';
        });

        const blurButton2 = document.getElementById('CloseBtnMinus');
        blurButton2.addEventListener('click', function() {
            const TopmostDiv = document.querySelector('.topmost-div-task-minus');
            const appContent = document.querySelector('.app-content');
            const plusButton = document.querySelector('.button-plus');
            const minusButton = document.querySelector('.button-minus');
            minusButton.style.display = 'block';
            plusButton.style.display = 'block';
            appContent.style.filter = 'none';
            TopmostDiv.style.display = 'none';
            CloseTeamBanner.style.display = 'none';

        });

        const plusButton = document.getElementById('button-plus');
        plusButton.addEventListener('click', function() {
            const TopmostDiv = document.querySelector('.topmost-div-task-plus');
            const appContent = document.querySelector('.app-content');
            const minusButton = document.querySelector('.button-minus');
            const plusButton = document.querySelector('.button-plus');
            plusButton.style.display = 'none';
            minusButton.style.display = 'none';
            appContent.style.filter = 'blur(4px)';
            TopmostDiv.style.display = 'block';
            CloseTeamBanner.style.display = 'block';
        });

        const minusButton = document.getElementById('button-minus');
        minusButton.addEventListener('click', function() {
            const TopmostDiv = document.querySelector('.topmost-div-task-minus');
            const appContent = document.querySelector('.app-content');
            const plusButton = document.querySelector('.button-plus');
            const minusButton = document.querySelector('.button-minus');
            minusButton.style.display = 'none';
            plusButton.style.display = 'none';
            appContent.style.filter = 'blur(4px)';
            TopmostDiv.style.display = 'block';
            CloseTeamBanner.style.display = 'block';
        });
    }
}
