subcriptions = {
    1000: 'bronze',
    2000: 'silver',
    3000: 'gold'
}
for (let key in subcriptions) {
    paypal.Buttons({
        style: {
            color: 'blue',
            shape: 'pill'
        },
        createOrder: function(data, actions) {
            return actions.order.create({
                purchase_units: [{
                    amount: {
                        value: key
                    }
                }]
            });
        },
        onApprove: function(data, actions) {
            return actions.order.capture().then(function(details) {
                $.ajax({
                    url: "payment.php",
                    type: "POST",
                    data: {
                        package: subcriptions[key]
                    },
                    success: function(message) {
                        var response = JSON.parse(message);
                        alertify.success(response.message);
                        $('#premium').html(response.premium_name);
                        $('#premiumexpiration').html(response.premiumexpiration);
                    }
                });
            });
        },
        onCancel: function(data) {
            alertify.error('A tranzakció visszavonásra került.');
        }
    }).render('#paypal-payment-button-' + key);
}