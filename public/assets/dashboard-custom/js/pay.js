(function ($) {
    "use strict";

    function payWithPayStack(originalEmail, originalName, total, callback, key) {
        let split = originalName.split(" ");
        let handler = PaystackPop.setup({
            key: key, // Replace with your public key
            email: originalEmail,
            amount: total * 100, // the amount value is multiplied by 100 to convert to the lowest currency unit
            ref: 'sagecloud'+Math.floor((Math.random() * 1000000000) + 1),
            currency: 'NGN',
            firstname: split[0],
            lastname: split[1] ?? "Unknown",
            callback: callback,
            onClose: function() {
                alert("Transaction was not completed, window closed.");
                return false;
            },
        });

        handler.openIframe();
    }


    $(document).on('click', '.topUp', function () {
        let amount = $('#amount');

        if (typeof amount.val() === "undefined") {
            return false;
        }

        if (amount.val() < 100) {

            // Show error notice.
            alert("Please enter valid topup amount!");
            return false;
        }

        function payResponse(response) {
            //this happens after the payment is completed successfully
            let reference = response.reference;

            // Make an AJAX call to your server with the reference to verify the transaction
            $.ajax({
                url: '/merchant/wallet/top-up/'+ reference,
                method: 'GET',
                success: function (valResp) {
                    if (valResp.success) {
                        let charges = valResp.data.fees / 100;
                        alert("Your wallet has been credited!");

                        setTimeout(function () {
                            window.location.href = '/merchant/wallet/view';
                        }, 1000);

                        return false;
                    }

                    // Show error notice.
                    alert(valResp.message);
                    return false;
                },


                error : function (error) {

                    alert("Transaction was not completed, we couldn't verify your transaction.");

                    return false;
                }
            });

        }



        // Make an AJAX call to your server with the reference to verify the transaction
        $.ajax({
            url: '/merchant/wallet/top-up/details',
            method: 'GET',
            success: function (res) {
                // Call payment form
                payWithPayStack(res.email, res.name, amount.val(), payResponse, res.key);
            },
            error : function (error) {
                alert("There is an error getting payment information. Please try again later.");
                return false;
            }
        });
    });

})(jQuery);
