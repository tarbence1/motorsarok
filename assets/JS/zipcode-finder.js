// postalcodes is filled by the JSON callback and used by the mouse event handlers of the suggest box
var postalcodes;
// this function will be called by our JSON callback
// the parameter jData will contain an array with postalcode objects
function getLocation2(jData) {
    if (jData == null) {
        // There was a problem parsing search results
        return;
    }
    // save place array in 'postalcodes' to make it accessible from mouse event handlers
    postalcodes = jData.postalcodes;

    if (postalcodes.length == 1) {
        // exactly one place for postalcode
        // directly fill the form, no suggest box required 
        var placeInput = document.getElementById("placeInput");
        placeInput.value = postalcodes[0].placeName;
    }

}
// this function is called when the user leaves the postal code input field
// it call the geonames.org JSON webservice to fetch an array of places 
// for the given postal code 
function postalCodeLookup() {
    var country = document.getElementById("countrySelect").value;
    if (geonamesPostalCodeCountries.toString().search(country) == -1) {
        return; // selected country not supported by geonames
    }
    var postalcode = document.getElementById("postalcodeInput").value;
    request = 'http://api.geonames.org/postalCodeLookupJSON?postalcode=' + postalcode + '&country=' + country + '&username=tarbence1&callback=getLocation2';
    // Create a new script object
    aObj = new JSONscriptRequest(request);
    // Build the script tag
    aObj.buildScriptTag();
    // Execute (add) the script tag
    aObj.addScriptTag();
}

// set the country of the user's ip (included in geonamesData.js) as selected country 
// in the country select box of the address form
function setDefaultCountry() {
    var countrySelect = document.getElementById("countrySelect");
    for (i = 0; i < countrySelect.length; i++) {
        // the javascript geonamesData.js contains the countrycode
        // of the userIp in the variable 'geonamesUserIpCountryCode'
        if (countrySelect[i].value == geonamesUserIpCountryCode) {
            // set the country selectionfield
            countrySelect.selectedIndex = i;
        }
    }
}