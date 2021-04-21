var product_name = document.getElementById("product_name");
var condition = document.getElementById("condition");
var advertisername = document.getElementById("advertiser-name");
var tel = document.getElementById("tel-number");
var county = document.getElementById("county");
var settlement = document.getElementById("settlement");
var price = document.getElementById("price");


product_name.oninput = function(e) {
    if (product_name.value != '') {
        e.target.style.border = "none";
    } else {
        e.target.style.border = "1px solid red";
    }
};


condition.onchange = function(e) {
    if (condition.value != '') {
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