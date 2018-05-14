$(function () {
    $('.action-btn').on('click', function () {
        var checked = $('#grid').yiiGridView('getSelectedRows');
        if (checked.length < 1) {
            alert("Ничего не выбрано");
            return;
        }

        var action = $(this).attr('data-action');
        var $form = $('<form action="" method="post"></form>');
        $form.append('<input type="hidden" name="' + yii.getCsrfParam() + '" value="' + yii.getCsrfToken() + '">');
        $form.append('<input type="hidden" name="action" value="' + action + '">');
        $.each(checked, function (k, id) {
            $form.append('<input type="hidden" name="ids[]" value="' + id + '">');
        });
        $('body').prepend($form);
        $form.submit();
    });
});

