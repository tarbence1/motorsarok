function deleteItems() {
    localStorage.clear();
    var l, i;
    document.getElementById("json").innerHTML = "";
    document.getElementById("currentLocation").innerHTML = "";
    for (i = 0; i < localStorage.length; i++) {
        x = localStorage.key(i);
        document.getElementById("json").innerHTML += x;
        document.getElementById("currentLocation").innerHTML += x;
    }
}