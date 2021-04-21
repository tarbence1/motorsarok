var x = document.getElementById("coordinate");
var y = document.getElementById("json");

function getLocation() {
    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(showPosition);
    } else {
        x.innerHTML = "Böngészője nem támogatja a helykeresést.";
    }
}

function showPosition(position) {
    //Create query for the API.
    var latitude = "latitude=" + position.coords.latitude;
    var longitude = "&longitude=" + position.coords.longitude;
    var query = latitude + longitude + "&localityLanguage=hu";

    const Http = new XMLHttpRequest();

    var bigdatacloud_api =
        "https://api.bigdatacloud.net/data/reverse-geocode-client?";

    bigdatacloud_api += query;
    console.log(bigdatacloud_api);

    Http.open("GET", bigdatacloud_api);
    Http.send();

    Http.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            var myObj = JSON.parse(this.responseText);
            console.log(myObj);
            y.innerHTML = myObj.locality;
            loc = myObj.locality;

            localStorage.setItem("loc", JSON.stringify({
                "lat": position.coords.latitude,
                "long": position.coords.longitude,
                "locality": myObj.locality
            }));
            location.reload();
        }
    };
}

if (localStorage.getItem("loc")) {
    localStorage.removeItem("loc2");
    // json to object
    var loc = JSON.parse(localStorage.getItem("loc"));
    console.log(loc);
    document.querySelector("#json").innerHTML = `${loc.locality}`;
    document.querySelector("#currentLocation").innerHTML = loc.locality;
}