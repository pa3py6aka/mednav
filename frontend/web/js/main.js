$(document).ready(function() {
    $('.fancybox').fancybox();

    $(".owl-carousel-2").owlCarousel({
        loop: true,
        nav: true,
        dots: false,
        autoplay: false,
        //margin: 20,
        navText: true,
        smartSpeed: 1000, //Время движения слайда
        autoplayTimeout: 4000, //Время смены слайда
        pagination: false,
        responsiveClass: true,
        responsive: {
            1000: {
                items: 3
            },
            600: {
                items: 3
            },
            0: {
                items: 1
            }
        }
    });
});