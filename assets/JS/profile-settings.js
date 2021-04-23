$(document).ready(function() {
    //form when submit
    $("#reset-email").submit(function(e) {
        e.preventDefault();
        $.ajax({
            url: "reset-email.php",
            method: "post",
            data: $(this).serialize(),
            dataType: "text",
            success: function(message) {
                var response = JSON.parse(message);
                if (response.ok == 1) {
                    alertify.success(response.new_email_err);
                    $('#email-address').html(response.new_email);
                } else {
                    alertify.error(response.new_email_err);
                }
            },
            error: function(message) {
                alertify.error('Valami hiba történt!');
            }
        });
    });
});

$(document).ready(function() {
    //form when submit
    $("#reset-password").submit(function(e) {
        e.preventDefault();
        $.ajax({
            url: "reset-password.php",
            method: "post",
            data: $(this).serialize(),
            dataType: "text",
            success: function(message) {
                var response = JSON.parse(message);
                if (response.ok == 1) {
                    alertify.success(response.password_err);
                } else {
                    alertify.error(response.password_err);
                }
            },
            error: function(message) {
                alertify.error('Valami hiba történt!');
            }
        });
    });
});