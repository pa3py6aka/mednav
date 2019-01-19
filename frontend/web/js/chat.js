(function () {
    $('#chat-message-form').on('submit', function (e) {
        var $form = $(this),
            message = $form.find('textarea').val(),
            $subject = $form.find('#subject-input');

        if (!message.length) {
            return false;
        }
        if ($subject.length && !$subject.val()) {
            $form.find('#subject-error').html('Укажите тему сообщения');
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

                    if ($subject.length && $subject.val()) {
                        $('#subject-block').html('<h1>Тема: ' + $subject.val() + '</h1>');
                        $subject.parent().remove();
                    }

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