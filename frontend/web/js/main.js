var Mednav = Mednav || {};
Mednav = (function () {
    var overlay = '<div class="overlay"><i class="fa fa-refresh fa-spin"></i></div>';

    function listen() {
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
    }

    var Public = {
        pluralize: function (iNumber, aEndings) {
            var sEnding, i;
            iNumber = iNumber % 100;
            if (iNumber>=11 && iNumber<=19) {
                sEnding=aEndings[2];
            }
            else {
                i = iNumber % 10;
                switch (i)
                {
                    case (1): sEnding = aEndings[0]; break;
                    case (2):
                    case (3):
                    case (4): sEnding = aEndings[1]; break;
                    default: sEnding = aEndings[2];
                }
            }
            return sEnding;
        },
        scrollTo: function ($el, margin) {
            scrollTo($el, margin);
        }
    };

    function init() {
        $('[data-toggle="tooltip"]').tooltip();
        $('.fancybox').fancybox();
        $(".owl-carousel").owlCarousel({
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
        listen();
    }

    function scrollTo($el, margin) {
        margin = margin ? margin : 0;
        $('html, body').animate({
            scrollTop: $el.offset().top + margin
        }, 700);
    }

    return {
        init: init,
        public: Public,
        overlay: overlay
    };
})();

$(document).ready(function() {
    Mednav.init();
});

window.alert = function (message, title) {
    var $modal = $('#alert-modal');
    if (!title) { $modal.find('.modal-header').hide(); } else { $modal.find('.modal-header').show(); }
    $modal.find('.modal-body p').html(message).end().find('.modal-title').text(title).end().modal();
};