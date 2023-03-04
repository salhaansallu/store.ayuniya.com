<template>

</template>

<script>

export default {
    data() {
        return {
            name: 'priceFilter'
        }
    },
    methods: {
        filter() {
            location.href = window.location.protocol + "//" + window.location.host + window.location.pathname + "?priceFilter=" + document.getElementById("price_range").value;
        }
    }
}
</script>