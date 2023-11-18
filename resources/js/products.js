var mdprice_range;
var mdmax_price = document.getElementById("md_max_price");

$('#md_price_range').change(function () {
    mdprice_range = document.getElementById("md_price_range").value;
    mdmax_price.innerHTML=mdprice_range;
});

var close_filter = document.getElementById("close_filter");
var pannel = document.getElementById("md_filter_pannel");

$('#close_filter').click(function () {
    pannel.style.height="0";
});

var price_range;
var max_price = document.getElementById("max_price");

$('#price_range').change(function () {
    price_range = document.getElementById("price_range").value;
    max_price.innerHTML=price_range;
});

$("#filter_btn").click(function () {
    location.href = window.location.protocol + "//" + window.location.host + window.location.pathname + "?priceFilter=" + document.getElementById("price_range").value;
});

$("#md_filter_btn").click(function () {
    location.href = window.location.protocol + "//" + window.location.host + window.location.pathname + "?priceFilter=" + document.getElementById("md_price_range").value;
});