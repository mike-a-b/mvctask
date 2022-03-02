let popupBg = document.querySelector('.popup__bg'); // Фон попап окна
let popup = document.querySelector('.popup'); // Само окно
let openPopupButtons = document.querySelectorAll('.open-popup'); // Кнопки для показа окна
let closePopupButton = document.querySelector('.close-popup'); // Кнопка для скрытия окна

function main2() {
    window.location.href = '/main2';
}
function main1() {
    window.location.href = '/main';
}
function main3() {
    window.location.href = '/main3';
}function main4() {
    window.location.href = '/main4';
}
function sendbtnclick() {
    let sendBtn = document.querySelector('.sendbtn');
    let mainTitle = document.querySelector('.mainTitle');
    let instrbtn = document.querySelector('.instr');
    let resetbtn = document.querySelector('.reset');
    let ogranbtn = document.querySelector('.ogran');
    let instrSpan = document.querySelector('.instrSpan');
    let resetSpan = document.querySelector('.resetSpan');
    let ogranSpan = document.querySelector('.ogranSpan');
    sendBtn.classList.remove('activ');
    sendBtn.style.display = 'none'
    ogranbtn.classList.add('activ');
    ogranbtn.style.display = 'block';
    instrbtn.classList.add('activ');
    instrbtn.style.display = 'block';
    // instrSpan.innerText ="";

    instrSpan.classList.add('hdn');
    resetSpan.classList.add('hdn');
    ogranSpan.classList.add('hdn');
    resetbtn.classList.add('activ');
    resetbtn.style.display = 'block';
    mainTitle.innerText = 'Результаты принтования';

}
function resetbtnclck(){
    let sendBtn = document.querySelector('.sendbtn');
    let mainTitle = document.querySelector('.mainTitle');
    let instrbtn = document.querySelector('.instr');
    let resetbtn = document.querySelector('.reset');
    let ogranbtn = document.querySelector('.ogran');
    let textArea = document.querySelector('.mainTArea')
    document.getElementById("exampleFormControlTextarea1").value  = "";


    sendBtn.classList.add('activ');
    sendBtn.style.display='block';
    ogranbtn.classList.remove('activ');
    ogranbtn.style.display = 'none';
    instrbtn.classList.remove('activ');
    instrbtn.style.display= 'none';
    resetbtn.classList.remove('activ');
    resetbtn.style.display= 'none';
    mainTitle.innerText = 'Инструкции бледным текстом';
    // alert('ok');
}
openPopupButtons.forEach((button) => { // Перебираем все кнопки
    button.addEventListener('click', (e) => { // Для каждой вешаем обработчик событий на клик
        e.preventDefault(); // Предотвращаем дефолтное поведение браузера
        popupBg.classList.add('active'); // Добавляем класс 'active' для фона
        popup.classList.add('active'); // И для самого окна
    })
});

closePopupButton.addEventListener('click',() => { // Вешаем обработчик на крестик
    popupBg.classList.remove('active'); // Убираем активный класс с фона
    popup.classList.remove('active'); // И с окна
});

document.addEventListener('click', (e) => { // Вешаем обработчик на весь документ
    if(e.target === popupBg) { // Если цель клика - фот, то:
        popupBg.classList.remove('active'); // Убираем активный класс с фона
        popup.classList.remove('active'); // И с окна
    }
});

