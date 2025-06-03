// document.getElementById('MyFormPlus').addEventListener('submit', function(event) {
//     event.preventDefault(); // предотвращаем стандартное поведение формы
//
//     const formData = new FormData(this); // создаем объект FormData из формы
//
//     fetch('/Home/Tasks/Check', {
//         method: 'POST',
//         body: formData,
//     })
// });
document.getElementById('MyFormPlus1').addEventListener('submit', async function(event) {
    event.preventDefault(); // предотвращаем стандартное поведение формы

    const formData = new FormData(this); // создаем объект FormData из формы

    try {
        const response = await fetch('/Home/Tasks/Check', {
            method: 'POST',
            body: formData,
        });

        // Проверяем, успешен ли ответ
        if (!response.ok) {
            throw new Error('Сетевая ошибка: ' + response.statusText);
        }

        const data = await response.json(); // предполагаем, что сервер возвращает JSON
        console.log('Успех:', data); // обрабатываем успешный ответ от сервера

        // Здесь вы можете обновить интерфейс или выполнить другие действия после успешной отправки формы

    } catch (error) {
        console.error('Ошибка:', error); // обрабатываем ошибки
    }
});
