function Copy(copy) {
    var url = window.location.href;
    var location = url + "#alkatresz-hirdetes-";
    var str = copy.split("-").pop();
    var copyText = location + str;
    var input = document.createElement('input');
    input.setAttribute('value', copyText);
    document.body.appendChild(input);
    input.select();
    input.focus();
    var result = document.execCommand('copy');
    document.body.removeChild(input);
    alertify.success('Vágólapra másolva!');
    return result;
}

function ModalCopy() {
    var url = window.location.href;
    var location = url;
    var copyText = location;
    var input = document.createElement('input');
    input.setAttribute('value', copyText);
    document.body.appendChild(input);
    input.select();
    input.focus();
    var result = document.execCommand('copy');
    document.body.removeChild(input);
    alertify.success('Vágólapra másolva!');
    return result;
}