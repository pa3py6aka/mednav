$(function(){
    $('.spinner .btn:first-of-type').on('click', function() {
        var btn = $(this);
        var input = btn.closest('.spinner').find('input');
        if (input.attr('max') == undefined || parseInt(input.val()) < parseInt(input.attr('max'))) {
            input.val(parseInt(input.val(), 10) + 1);
        } else {
            btn.next("disabled", true);
        }
    });
    $('.spinner .btn:last-of-type').on('click', function() {
        var btn = $(this);
        var input = btn.closest('.spinner').find('input');
        if (input.attr('min') == undefined || parseInt(input.val()) > parseInt(input.attr('min'))) {
            input.val(parseInt(input.val(), 10) - 1);
        } else {
            btn.prev("disabled", true);
        }
    });
    $(document).on('submit', '.order-button-form', function (e) {
        e.preventDefault();
        var $form = $(this),
            $button = $form.find('[data-button=order-button]');
        console.log($button);

        // Переход на страницу заказа
        if ($button.hasClass('btn-success')) {
            window.location.href = $form.attr('action');
            return;
        }

        // Добавление товара в корзину
        var productId = $button.attr('data-product-id'),
            amount = $form.find('.product-amount').val(),
            overlay = '<div class="overlay"><i class="fa fa-refresh fa-spin"></i></div>';
        $.ajax({
            url: '/user/order/add-to-cart',
            method: "post",
            dataType: "json",
            data: {productId: productId, amount: amount},
            beforeSend: function () {
                $form.prepend(overlay);
            },
            success: function(data, textStatus, jqXHR) {
                if (data.result === 'success') {
                    $button.removeClass('kt-btn-cart').addClass('btn-success').text('Оформить заказ');
                    $('.cart-block-box').find('.products-amount').text(data.cartItemsCount);
                } else {
                    alert('Ошибка загрузки данных');
                }
            },
            complete: function () {
                $form.find('.overlay').remove();
            }
        });
    });
});