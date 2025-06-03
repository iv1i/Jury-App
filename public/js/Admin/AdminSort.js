// Получаем элемент с классом 'product-cell category'
const divElem = document.querySelector('.product-cell.category');

// Проверяем, существует ли элемент
if (divElem) {
    // Получаем все дочерние узлы элемента
    const childNodes = divElem.childNodes;

    // Переменная для хранения значения
    let scoreValue = '';

    // Ищем текстовый узел, который идет после элемента <span>
    for (let i = 0; i < childNodes.length; i++) {
        const node = childNodes[i];

        // Проверяем, является ли узел элементом <span>
        if (node.nodeType === Node.ELEMENT_NODE && node.tagName.toLowerCase() === 'span') {
            // Если это <span>, проверяем следующий узел
            if (childNodes[i + 1] && childNodes[i + 1].nodeType === Node.TEXT_NODE) {
                // Получаем текст из следующего узла
                scoreValue = childNodes[i + 1].textContent.trim();
                break; // Выходим из цикла, так как нашли нужное значение
            }
        }
    }

    // Выводим полученное значение
    console.log(scoreValue ? scoreValue : '<empty string>'); // Показываем результат
} else {
    console.log('Элемент не найден');
}
