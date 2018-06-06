(function () {
    var $settingsModal = $('#regionSettingsModal'),
        $modalBox = $settingsModal.find('.box');

    $(document).on('click', '.region-settings-btn', function () {
        var regionId = $(this).attr('data-geo-id');
        var entityId = $(this).attr('data-entity-id');
        $settingsModal.modal();
        $settingsModal.find('.modal-title').text($(this).attr('data-geo-name'));
        $.ajax({
            url: RAW_url,
            method: "post",
            dataType: "html",
            data: {regionId: regionId, entityId: entityId},
            beforeSend: function () {
                $modalBox.prepend('<div class="overlay"><i class="fa fa-refresh fa-spin"></i></div>');
            },
            success: function(data, textStatus, jqXHR) {
                $modalBox.html(data);
            },
            complete: function () {
                $modalBox.find('.overlay').remove();
            }
        });

        return false;
    });
})(jQuery);