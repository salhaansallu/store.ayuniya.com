<template>
    <button @click="proceedCheckout">Proceed to checkout</button>
</template>

<script>

function setCookie(name, value) {
    var expires = "";
    document.cookie = name + "=" + (value || "") + "; path=";
}

export default {
    props: ['recurring_cart'],
    data() {
        return {
            name: 'cart_checkout_btn'
        }
    },
    methods: {
        getCookie(cookieName) {
            let cookie = {};
            document.cookie.split(';').forEach(function (el) {
                let [key, value] = el.split('=');
                cookie[key.trim()] = value;
            })
            return cookie[cookieName];
        },
        proceedCheckout() {
            if (this.getCookie('promo_spm')) {

                $(this).prop("disabled", true);
                axios
                    .post("/confirm-checkout", {
                        action: 'cart_checkout',
                        address1: $("#address1").val(),
                        postal: $("#postal").val(),
                        city: $("#city").val(),
                        country: $("#country").val(),
                        recurring_cart: this.recurring_cart,
                        _token: $("meta[name='csrf-token']").attr('content'),
                    })
                    .then(function (response) {
                        document.cookie = "order_confirmed=false; expires=Thu, 18 Dec 2013 12:00:00 UTC; path=/";
                        if (response.data.error == 0) {
                            location.href = "/?pid=" + response.data.orderno + "&_region=" + response.data.region;
                        }
                        else if (response.data.error == 1) {
                            toastr.error(response.data.msg, "Error");
                        }
                        else {
                            toastr.error("Sorry, something went wrong", "Error")
                        }
                        $(this).removeAttr("disabled");
                    })
                    .catch((err) => toastr.error("Sorry, something went wrong", "Error"))
            }
            else {
                toastr.error("Please upload payment slip", "Error")
                $(this).removeAttr("disabled");
            }
        }
    },
}
</script>
