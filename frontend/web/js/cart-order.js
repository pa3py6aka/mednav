$(function(){

    $('.spinner .btn:first-of-type').on('click', function() {
        var btn = $(this);
        var input = btn.closest('.spinner').find('input');
        if (input.attr('max') === undefined || parseInt(input.val()) < parseInt(input.attr('max'))) {
            input.val(parseInt(input.val(), 10) + 1);
            updateSum();
        } else {
            btn.next("disabled", true);
        }
    });
    $('.spinner .btn:last-of-type').on('click', function() {
        var btn = $(this);
        var input = btn.closest('.spinner').find('input');
        if (input.attr('min') === undefined || parseInt(input.val()) > parseInt(input.attr('min'))) {
            input.val(parseInt(input.val(), 10) - 1);
            updateSum();
        } else {
            btn.prev("disabled", true);
        }
    });

    $(document).on('change', '.item-amount-input', function (e) {
        updateSum();
    });

    function updateSum() {
        var numberFormatter = new Intl.NumberFormat('ru-RU', { style: 'decimal' });
        $('[data-type="order-row"]').each(function (k, row) {
            var $row = $(row),
                $sumDiv = $row.find('.cart-total-price'),
                sum = 0,
                currency = '';

            $row.find('[data-type="item-price"]').each(function (k, priceDiv) {
                var $priceDiv = $(priceDiv),
                    tradeId = $priceDiv.attr('data-trade-id'),
                    price = parseInt($priceDiv.attr('data-price')),
                    //uom = $priceDiv.attr('data-uom'),
                    amount = parseInt($('.item-amount-input[data-trade-id=' + tradeId + ']').val()),
                    itemSum = price * amount,
                    $itemSumDiv = $('[data-type="item-sum"][data-trade-id=' + tradeId + ']');

                currency = $priceDiv.attr('data-currency');
                sum = sum + itemSum;
                $itemSumDiv.text(numberFormatter.format(itemSum / 100) + ' ' + currency);
            });

            $sumDiv.text('Итого: ' + numberFormatter.format(sum / 100) + ' ' + currency);
        });
    }

    $("#order-form-submit-btn").on('click', function (e) {
        var $form = $('#order-form');
        $form.find('input.item-amount-input').remove().end()
            .find('select.order-delivery-selector').remove().end()
            .find('textarea.order-comment-textarea').remove();
        $("input.item-amount-input").clone().appendTo($form).hide();
        $("select.order-delivery-selector").clone().appendTo($form).hide();
        $("textarea.order-comment-textarea").clone().appendTo($form).hide();
        $form.submit();
    });

    updateSum();
});