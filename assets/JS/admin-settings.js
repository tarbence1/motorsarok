// Upload user form 

$(document).ready(function() {
    //form when submit
    $("form[id*=userData]").submit(function(e) {
        e.preventDefault();
        $.ajax({
            url: "user-settings.php",
            method: "post",
            data: $(this).serialize(),
            dataType: "text",
            success: function(message) {
                var response = JSON.parse(message);
                alertify.success(response.message);
                user = response.userid;
                //Update modal status
                $('#currentAdminStatus-' + user).html(response.admin);
                $('#currentPremiumStatus-' + user).html(response.premium);
                // Update table status
                $('#tableAdminStatus-' + user).html(response.admin);
                $('#tablePremiumStatus-' + user).html(response.premium);
                $('#tablePremiumStart-' + user).html(response.start_date);
                $('#tablePremiumEnd-' + user).html(response.end_date);

            }
        });
    });
});


// Modify manufacturer form

$(document).ready(function() {
    //form when submit
    $("form[id*=manufacturerData]").submit(function(e) {
        e.preventDefault();
        $.ajax({
            url: "manufacturer-settings.php",
            method: "post",
            data: $(this).serialize(),
            dataType: "text",
            success: function(message) {
                var response = JSON.parse(message);
                alertify.success(response.message);
                manufacturer = response.manufacturerid;
                //Update modal name
                $('#manufacturerModalTitle-' + manufacturer).html(response.newName);
                $('#tableManufacturerName-' + manufacturer).html(response.newName);
                $('#currentManufacturerName-' + manufacturer).val(response.newName);
            },
            error: function(data) {
                alertify.error('Kérjük adja meg az új nevet!');
            }
        });
    });
});


// Add manufacturer form

$(document).ready(function() {
    //form when submit
    $("#addManufacturerData").submit(function(e) {
        e.preventDefault();
        $.ajax({
            url: "add-manufacturer.php",
            method: "post",
            data: $(this).serialize(),
            dataType: "text",
            success: function(message) {
                location.reload();
            },
            error: function(data) {
                alertify.error('Kérjük adja meg a gyártó nevét!');
            }
        });
    });
});


// Delete manufacturer form 

$(document).ready(function() {
    //form when submit
    $("form[id*=manufacturerDelete]").submit(function(e) {
        e.preventDefault();
        $.ajax({
            url: "manufacturer-delete.php",
            method: "post",
            data: $(this).serialize(),
            dataType: "text",
            success: function(message) {
                var response = JSON.parse(message);
                manufacturer = response.manufacturerid;
                $('#manufacturers-row-' + manufacturer).remove();
                alertify.success('Sikeres törlés!');
            },
            error: function(data) {
                alertify.error('A kiválasztott gyártóhoz tartoznak hirdetések, így nem lehet törölni!');
            }
        });
    });
});


// Modify county form

$(document).ready(function() {
    //form when submit
    $("form[id*=countyData]").submit(function(e) {
        e.preventDefault();
        $.ajax({
            url: "county-settings.php",
            method: "post",
            data: $(this).serialize(),
            dataType: "text",
            success: function(message) {
                var response = JSON.parse(message);
                alertify.success(response.message);
                county = response.countyid;
                //Update modal name
                $('#countyModalLabel-' + county).html(response.newName);
                $('#tableCountyName-' + county).html(response.newName);
                $('#currentCountyName-' + county).val(response.newName);
            },
            error: function(data) {
                alertify.error('Kérjük adja meg az új nevet!');
            }
        });
    });
});


// Modify technical equipment form

$(document).ready(function() {
    //form when submit
    $("form[id*=technicalData]").submit(function(e) {
        e.preventDefault();
        $.ajax({
            url: "technical-settings.php",
            method: "post",
            data: $(this).serialize(),
            dataType: "text",
            success: function(message) {
                var response = JSON.parse(message);
                alertify.success(response.message);
                technical = response.technicalid;
                //Update modal name
                $('#technicalModallabel-' + technical).html(response.newName);
                $('#tableTechnicalName-' + technical).html(response.newName);
                $('#currentTechnicalName-' + technical).val(response.newName);
            },
            error: function(data) {
                alertify.error('Kérjük adja meg az új nevet!');
            }
        });
    });
});


// Add technical equipment form 

$(document).ready(function() {
    //form when submit
    $("#addTechnicalData").submit(function(e) {
        e.preventDefault();
        $.ajax({
            url: "add-technical.php",
            method: "post",
            data: $(this).serialize(),
            dataType: "text",
            success: function(message) {
                location.reload();
            },
            error: function(data) {
                alertify.error('Kérjük adja meg a felszereltség nevét!');
            }
        });
    });
});


// Delete technical form

$(document).ready(function() {
    //form when submit
    $("form[id*=technicalDelete]").submit(function(e) {
        e.preventDefault();
        $.ajax({
            url: "technical-delete.php",
            method: "post",
            data: $(this).serialize(),
            dataType: "text",
            success: function(message) {
                var response = JSON.parse(message);
                technical = response.technicalid;
                $('#technical-row-' + technical).remove();
                alertify.success('Sikeres törlés!');
            },
            error: function(data) {
                alertify.error('A kiválasztott felszereltséghez tartoznak hirdetések, így nem lehet törölni!');
            }
        });
    });
});


// Modify comfort equipment form 

$(document).ready(function() {
    //form when submit
    $("form[id*=comfortData]").submit(function(e) {
        e.preventDefault();
        $.ajax({
            url: "comfort-settings.php",
            method: "post",
            data: $(this).serialize(),
            dataType: "text",
            success: function(message) {
                var response = JSON.parse(message);
                alertify.success(response.message);
                comfort = response.comfortid;
                //Update modal name
                $('#comfortModalLabel-' + comfort).html(response.newName);
                $('#tableComfortName-' + comfort).html(response.newName);
                $('#currentComfortName-' + comfort).val(response.newName);
            },
            error: function(data) {
                alertify.error('Kérjük adja meg az új nevet!');
            }
        });
    });
});

// Add comfort equipment form

$(document).ready(function() {
    //form when submit
    $("#addComfortData").submit(function(e) {
        e.preventDefault();
        $.ajax({
            url: "add-comfort.php",
            method: "post",
            data: $(this).serialize(),
            dataType: "text",
            success: function(message) {
                location.reload();
            },
            error: function(data) {
                alertify.error('Kérjük adja meg a felszereltség nevét!');
            }
        });
    });
});


// Delete comfort form

$(document).ready(function() {
    //form when submit
    $("form[id*=comfortDelete]").submit(function(e) {
        e.preventDefault();
        $.ajax({
            url: "comfort-delete.php",
            method: "post",
            data: $(this).serialize(),
            dataType: "text",
            success: function(message) {
                var response = JSON.parse(message);
                comfort = response.comfortid;
                $('#comfort-row-' + comfort).remove();
                alertify.success('Sikeres törlés!');
            },
            error: function(data) {
                alertify.error('A kiválasztott felszereltséghez tartoznak hirdetések, így nem lehet törölni!');
            }
        });
    });
});


// Modify safety equipment form

$(document).ready(function() {
    //form when submit
    $("form[id*=safetyData]").submit(function(e) {
        e.preventDefault();
        $.ajax({
            url: "safety-settings.php",
            method: "post",
            data: $(this).serialize(),
            dataType: "text",
            success: function(message) {
                var response = JSON.parse(message);
                alertify.success(response.message);
                safety = response.safetyid;
                //Update modal name
                $('#safetyModalLabel-' + safety).html(response.newName);
                $('#tableSafetyName-' + safety).html(response.newName);
                $('#currentSafetyName-' + safety).val(response.newName);
            },
            error: function(data) {
                alertify.error('Kérjük adja meg az új nevet!');
            }
        });
    });
});


// Add safety equipment form

$(document).ready(function() {
    //form when submit
    $("#addSafetyData").submit(function(e) {
        e.preventDefault();
        $.ajax({
            url: "add-safety.php",
            method: "post",
            data: $(this).serialize(),
            dataType: "text",
            success: function(message) {
                location.reload();
            },
            error: function(data) {
                alertify.error('Kérjük adja meg a felszereltség nevét!');
            }
        });
    });
});


// Delete safety form

$(document).ready(function() {
    //form when submit
    $("form[id*=safetyDelete]").submit(function(e) {
        e.preventDefault();
        $.ajax({
            url: "safety-delete.php",
            method: "post",
            data: $(this).serialize(),
            dataType: "text",
            success: function(message) {
                var response = JSON.parse(message);
                safety = response.safetyid;
                $('#safety-row-' + safety).remove();
                alertify.success('Sikeres törlés!');
            },
            error: function(data) {
                alertify.error('A kiválasztott felszereltséghez tartoznak hirdetések, így nem lehet törölni!');
            }
        });
    });
});