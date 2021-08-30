$(document).ready(function () {
    AOS.init({
        delay: 50, // values from 0 to 3000, with step 50ms
        duration: 500
    });

    $(window).scroll(function () {
        let srcl = $(window).scrollTop();

        $("#navbar").toggleClass("shadow", srcl > 50);
    })
})