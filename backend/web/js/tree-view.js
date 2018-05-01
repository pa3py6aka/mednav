(function () {
    $(document).on('click', '.tree-arrow', function () {
        var $arrow = $(this),
            $box = $arrow.parents('.box'),
            id = $arrow.attr('data-id'),
            entity = $arrow.attr('data-entity');

        if ($arrow.hasClass('glyphicon-triangle-bottom')) {
            $('tr[data-parent=' + id + ']').remove();
            $arrow.removeClass('glyphicon-triangle-bottom').addClass('glyphicon-triangle-right');
            return false;
        }

        $.ajax({
            url: '/site/tree-load',
            method: "post",
            dataType: "html",
            data: {id: id, entity: entity},
            beforeSend: function () {
                $box.prepend('<div class="overlay"><i class="fa fa-refresh fa-spin"></i></div>');
            },
            success: function(data, textStatus, jqXHR) {
                $arrow.parents('tr').after(data);
                $arrow.removeClass('glyphicon-triangle-right').addClass('glyphicon-triangle-bottom');
            },
            complete: function () {
                $box.find('.overlay').remove();
            }
        });



    });
})(jQuery);