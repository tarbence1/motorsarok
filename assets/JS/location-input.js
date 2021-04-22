function saveLocation() {
    var town = document.getElementById("placeInput").value;
    if (town.length > 0) {
        deleteItems();
        var loc2 = {
            'town': town
        };

        // Put the object into storage
        localStorage.setItem('loc2', JSON.stringify(loc2));
        location.reload();
    }
}

if (localStorage.getItem("loc2")) {
    var retrievedObject = JSON.parse(localStorage.getItem("loc2"));
    document.querySelector("#json").innerText = `${retrievedObject.town}`;
    document.querySelector("#currentLocation").innerText = retrievedObject.town;
}