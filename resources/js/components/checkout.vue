<template>
    <button @click="proceedCheckout">Proceed to checkout</button>
</template>

<script>
export default {
    props: ['sku', 'qty'],
    data() {
        return {
            name: 'checkout_btn',
        }
    },
    methods: {
        proceedCheckout() {
            $("#checkout_btn").prop("disabled",true);
            axios
            .post("/confirm-checkout", {
                action: 'confirm_checkout',
                sku: this.sku,
                qty: this.qty,
                address1: $("#address1").val(),
                postal: $("#postal").val(),
                city: $("#city").val(),
                country: $("#country").val(),
                _token: $("meta[name='csrf-token']").attr('content')
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
