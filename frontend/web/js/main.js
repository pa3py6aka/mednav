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

    // ADDED
    var overlay = '<div class="overlay"><i class="fa fa-refresh fa-spin"></i></div>';

    $(document).on('change', '.filter-form-auto select', function () {
        $(this).parents('.filter-form-auto').submit();
    });

    $('#list-btn-scroll').on('click', function () {
        var $cardItemsBlock = $('.card-items-block');
        var $link = $(this),
            url = $link.attr('data-url'),
            $loadBlock = $link.parent();
        $.ajax({
            url: url,
            method: "get",
            dataType: "json",
            data: {showMore: 1},
            beforeSend: function () {
                $loadBlock.prepend(overlay);
            },
            success: function(data, textStatus, jqXHR) {
                if (data.result === 'success') {
                    var $last = $cardItemsBlock.find('.list-item').last();
                    $cardItemsBlock.append(data.html);
                    scrollTo($last.next(), -18);

                    if (data.nextPageUrl) {
                        $link.attr('data-url', data.nextPageUrl);
                    } else {
                        $link.remove();
                    }
                } else {
                    alert('Ошибка загрузки данных');
                }
            },
            complete: function () {
                $loadBlock.find('.overlay').remove();
            }
        });
    });

    function scrollTo($el, margin) {
        margin = margin ? margin : 0;
        $('html, body').animate({
            scrollTop: $el.offset().top + margin
        }, 700);
    }

    $('[data-toggle="tooltip"]').tooltip();
});