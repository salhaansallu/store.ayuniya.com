<template>
    <button @click="proceedCheckout">Proceed to checkout</button>
</template>

<script>
export default {
    props: ['recurring_cart'],
    data() {
        return {
            name: 'cart_checkout_btn'
        }
    },
    methods: {
        proceedCheckout() {
            $("#checkout_btn").prop("disabled",true);
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
                if (response.data.error==0) {
                    location.href="/?pid="+response.data.orderno+"&_region="+response.data.region;
                }
                else if (response.data.error==1){
                    toastr.error(response.data.msg, "Error");
                }
                else {
                    toastr.error("Sorry, something went wrong", "Error")
                }
                $("#checkout_btn").removeAttr("disabled");
            })
            .catch((err) => toastr.error("Sorry, something went wrong", "Error"))
        }
    },
}
</script>
