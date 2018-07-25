(function () {
    $('#chat-message-form').on('submit', function (e) {
        var $form = $(this),
            message = $form.find('textarea').val();

        if (!message.length) {
            return false;
        }

        $.ajax({
            url: $form.attr('action'),
            method: "post",
            dataType: "json",
            data: $form.serialize(),
            beforeSend: function () {
                $form.prepend(Mednav.overlay);
            },
            success: function(data, textStatus, jqXHR) {
                if (data.result === 'success') {
                    var $message = $(data.message);
                    $('.message-block').append($message);
                    $form.find('textarea').val('');
                    Mednav.public.scrollTo($message);
                } else {
                    alert(data.message);
                }
            },
            complete: function () {
                $form.find('.overlay').remove();
            }
        });

        return false;
    });

    Mednav.public.scrollTo($('.message-row').last(), 5);
})();