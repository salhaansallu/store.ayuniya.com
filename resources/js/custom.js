var scroller = $("#productscroller");
var scrollerwidth = document.getElementById("productscroller");
var right_btn = $("#rightcontrol");
var left_btn = $("#leftcontrol");
var scrollposition = 0;
var scrollmax = scrollerwidth.scrollWidth - scrollerwidth.clientWidth;
var scrollamt = document.getElementById("clientwidth").clientWidth;

$(scroller).scroll(function () { 
    scrollposition = scroller.scrollLeft();
    //console.log(scrollposition)
});


$(left_btn).click(function () {

    if (scroller.scrollLeft() > 0) {
        scrollposition -= scrollamt;
        scroller.scrollLeft(scrollposition);
        //console.log(scrollposition)
    }
    else{
        scroller.scrollLeft(0);
        //console.log(scrollposition);
    }
    
});


$(right_btn).click(function () {

    if (scroller.scrollLeft() < scrollmax) {
        scrollposition += scrollamt;
        scroller.scrollLeft(scrollposition);
        //console.log(scrollposition);
    }
    else{
        scroller.scrollLeft(scrollmax);
        //console.log(scrollposition);
    }
    
    
});



// ======== Mobile Navigation========= //

var open_menu = document.getElementById("open_menu");
var nav_close = document.getElementById("nav_close");
var menu = document.getElementById("menu");

$(open_menu).click(function () {
    menu.classList.add("mobile_nav_open");
});

$(nav_close).click(function () {
    menu.classList.remove("mobile_nav_open");
});


// ======== Login password shower ====== //

var show_Pass = document.getElementById("show_pass");

$(show_Pass).click(function () {
    if (show_Pass.checked) {
        document.getElementById("password").setAttribute('type', 'text');
    }
    else{
        document.getElementById("password").setAttribute('type', 'password');
    }
});