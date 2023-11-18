<template>
    <button class="buy_now" @click="buy">Buy now</button>
    <button class="add_cart" @click="cart">Add to cart</button>
</template>

<script>

function getCookie(cookieName) {
    let cookie = {};
    document.cookie.split(';').forEach(function (el) {
        let [key, value] = el.split('=');
        cookie[key.trim()] = value;
    })
    return cookie[cookieName];
}

function setCookie(name, value) {
    var expires = "";
    document.cookie = name + "=" + (value || "") + "; path=";
}

export default {
    data() {
        return {
            name: 'product_btns'
        }
    },
    methods: {
        cart() {

            if (document.getElementById("varient_active").classList.contains("varient_selected")) {

                axios
                    .post("/cart", {
                        action: 'add_cart',
                        sku: getCookie("cartsku"),
                        qty: document.getElementById("quantity").value,
                    })
                    .then(function (response) {
                        if (response.data.error == 1) {
                            toastr.error(response.data.msg, "Error");
                            if (response.data.msg == "not_loggedin") {
                                location.href = "/login";
                            }
                        }
                        else if (response.data.error == 0) {
                            toastr.success(response.data.msg, "Success");
                            if (typeof response.data['count'] != 'undefined') {
                                document.getElementById("item_count").innerText=response.data.count;
                            }
                        }
                        else {
                            toastr.error("Sorry, something went wrong", "Error");
                        }
                    })
                    .catch((err) => toastr.error("Sorry, something went wrong", "Error"))

            }
            else {
                toastr.warning("Please select a varient");
            }

        },

        buy() {
            if (document.getElementById("varient_active").classList.contains("varient_selected")) {

                const buyForm = document.createElement("form");
                const input = document.createElement("input");
                const csrf = document.createElement("input");
                const qty = document.createElement("input");
                input.value = getCookie("cartsku");
                csrf.value = $("meta[name='csrf-token']").attr('content');
                csrf.name = "_token";
                qty.value = document.getElementById("quantity").value;
                input.name = "sku";
                qty.name = "qty";
                buyForm.appendChild(input);
                buyForm.appendChild(qty);
                buyForm.appendChild(csrf);
                buyForm.method = "POST";
                buyForm.action = "/checkout";
                document.body.appendChild(buyForm);
                buyForm.submit();

            }
            else {
                toastr.warning("Please select a varient");
            }
        }
    }
}
</script>
