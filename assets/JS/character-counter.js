var textarea = document.getElementById('description');
var maxCharacters = 1000;

window.onload = textareaLengthCheck();

function textareaLengthCheck() {
    var textArea = textarea.value.length;
    var charactersLeft = maxCharacters - textArea;
    var count = document.getElementById('characters-left');
    count.innerHTML = "Hátralévő betűk száma: " + charactersLeft;
}

textarea.addEventListener('keyup', textareaLengthCheck, false);
textarea.addEventListener('keydown', textareaLengthCheck, false);