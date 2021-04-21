function route(btn) {
    var start = document.getElementById("json").innerHTML;
    var start2 = start.replace(/[()]/g, '');
    var str = btn.split("-").pop();
    var end = document.getElementById("position" + str).value;
    window.open("https://www.google.hu/maps/dir/" + start2 + "/" + end);
}