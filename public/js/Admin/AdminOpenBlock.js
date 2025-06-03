const blurButton = document.getElementById('CloseBtn');
blurButton.addEventListener('click', function() {
    const TopmostDiv = document.querySelector('.topmost-div-task-plus');
    const appContent = document.querySelector('.app-content');
    const minusButton = document.querySelector('.button-minus');
    const plusButton = document.querySelector('.button-plus');
    plusButton.style.display = 'block';
    minusButton.style.display = 'block';
    appContent.style.filter = 'none';
    TopmostDiv.style.display = 'none';
});

const blurButton2 = document.getElementById('CloseBtn2');
blurButton2.addEventListener('click', function() {
    const TopmostDiv = document.querySelector('.topmost-div-task-minus');
    const appContent = document.querySelector('.app-content');
    const plusButton = document.querySelector('.button-plus');
    const minusButton = document.querySelector('.button-minus');
    minusButton.style.display = 'block';
    plusButton.style.display = 'block';
    appContent.style.filter = 'none';
    TopmostDiv.style.display = 'none';
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
});
