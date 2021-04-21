var manufacturer = document.getElementById("manufacturer");
var model = document.getElementById("model");
var type = document.getElementById("type");
var year = document.getElementById("made-year");
var month = document.getElementById("made-month");
var documents = document.getElementById("documents");
var documentsvalidity = document.getElementById("documentsvalidity");
var kilometers = document.getElementById("kilometers");
var kw = document.getElementById("kw");
var license = document.getElementById("driver-license");
var advertisername = document.getElementById("advertiser-name");
var tel = document.getElementById("tel-number");
var county = document.getElementById("county");
var settlement = document.getElementById("settlement");
var price = document.getElementById("price");
var ccm = document.getElementById("ccm");

manufacturer.onchange = function(e) {
    if (manufacturer.value != '') {
        e.target.style.border = "none";
    } else {
        e.target.style.border = "1px solid red";
    }
};

model.oninput = function(e) {
    if (model.value != '') {
        e.target.style.border = "none";
    } else {
        e.target.style.border = "1px solid red";
    }
};

ccm.oninput = function(e) {
    if (ccm.value != '') {
        e.target.style.border = "none";
    } else {
        e.target.style.border = "1px solid red";
    }
};

type.onchange = function(e) {
    if (type.value != '') {
        e.target.style.border = "none";
    } else {
        e.target.style.border = "1px solid red";
    }
};

price.oninput = function(e) {
    if (price.value != '') {
        e.target.style.border = "none";
    } else {
        e.target.style.border = "1px solid red";
    }
};

year.onchange = function(e) {
    if (year.value != '') {
        e.target.style.border = "none";
    } else {
        e.target.style.border = "1px solid red";
    }
};

month.onchange = function(e) {
    if (month.value != '') {
        e.target.style.border = "none";
    } else {
        e.target.style.border = "1px solid red";
    }
};

documents.onchange = function(e) {
    if (documents.value != '') {
        e.target.style.border = "none";
    } else {
        e.target.style.border = "1px solid red";
    }
};

documentsvalidity.onchange = function(e) {
    if (documentsvalidity.value != '') {
        e.target.style.border = "none";
    } else {
        e.target.style.border = "1px solid red";
    }
};

kilometers.oninput = function(e) {
    if (kilometers.value != '') {
        e.target.style.border = "none";
    } else {
        e.target.style.border = "1px solid red";
    }
};

// KW & Driver license
kw.oninput = function(e) {
    if (kw.value == "") {
        license.selectedIndex = "0";
        license.style.border = "1px solid red";
        e.target.style.border = "1px solid red";
    } else if (kw.value > 0 && kw.value <= 4) {
        license.selectedIndex = "1";
        license.style.border = "none";
        e.target.style.border = "none";
    } else if (kw.value > 4 && kw.value <= 11) {
        license.selectedIndex = "2";
        license.style.border = "none";
        e.target.style.border = "none";
    } else if (kw.value > 11 && kw.value <= 35) {
        license.selectedIndex = "3";
        license.style.border = "none";
        e.target.style.border = "none";
    } else {
        license.selectedIndex = "4";
        license.style.border = "none";
        e.target.style.border = "none";
    }
};

license.onchange = function(e) {
    if (license.value != '') {
        e.target.style.border = "none";
    } else {
        e.target.style.border = "1px solid red";
    }
};


advertisername.oninput = function(e) {
    if (advertisername.value != '') {
        e.target.style.border = "none";
    } else {
        e.target.style.border = "1px solid red";
    }
};

tel.oninput = function(e) {
    if (tel.value != '') {
        e.target.style.border = "none";
    } else {
        e.target.style.border = "1px solid red";
    }
};

county.onchange = function(e) {
    if (county.value != '') {
        e.target.style.border = "none";
    } else {
        e.target.style.border = "1px solid red";
    }
};

settlement.oninput = function(e) {
    if (settlement.value != '') {
        e.target.style.border = "none";
    } else {
        e.target.style.border = "1px solid red";
    }
};