(function () {
    var $photosBlock = $('.photos-block');
    var loader = '<div class="overlay"><i class="fa fa-refresh fa-spin"></i><div class="uploader-progress"></div></div>';
    var formName = $photosBlock.attr('data-form-name');
    var attribute = $photosBlock.attr('data-attribute');

    $photosBlock.on('click', '.add-image-img', function () {
        $(this).parent().find('input[type=file]').click();
    });
    $photosBlock.on('change', 'input[type=file]', function (e) {
        var $link = $(this).parent();
        $link.append(loader);
        $photosBlock.find('.help-block').html('');

        var files = $(e.target)[0].files;
        var xhr = new XMLHttpRequest();
        xhr.upload.addEventListener('progress', uploadProgress, false);
        xhr.onreadystatechange = stateChange;
        xhr.open('POST', _ImageUploadAction);
        var formData = new FormData();
        $.each(files, function(k, file) {
            formData.append("file[]", file);
        });
        formData.append(yii.getCsrfParam(), yii.getCsrfToken());
        formData.append("num", $link.attr('data-num'));
        xhr.send(formData);

        function uploadProgress(event) {
            var percent = parseInt(event.loaded / event.total * 100);
            $link.find(".uploader-progress").css('width', percent + '%');
        }

        function stateChange(event) {
            if (event.target.readyState === 4) {
                if (event.target.status === 200) {
                    var data = jQuery.parseJSON(event.target.responseText);
                    if (data.result === 'error') {
                        $photosBlock.find('.help-block').html(data.message);
                    } else {
                        $.each(data.fileNames, function (k, fileName) {
                            $link.find('img').attr('src', data.url + fileName);
                            $link.find('.remove-btn').removeClass('hidden');
                            $link.find('input[type=hidden]').remove();

                            var input = '<input type="hidden" name="' + formName + '[' + attribute + '][]" value="' + fileName + '">';
                            $link.append(input);

                            $link = addImageItem();
                        });
                        console.log($photosBlock.find('.add-image-item').length);
                        if ($photosBlock.find('.add-image-item').length > 10 || $link.next().hasClass('add-image-item')) {
                            $link.remove();
                        }
                    }
                } else {
                    console.log("error");
                }
                $photosBlock.find('.add-image-item .overlay').remove();
            }
        }
    });

    $photosBlock.on('click', '.remove-btn', function () {
        $(this).parent().remove();
        if ($photosBlock.find('.add-image-item').length < 10 && !$photosBlock.find('.add-image-item .remove-btn.hidden').length) {
            addImageItem();
        }
    });

    function addImageItem() {
        var $last = $photosBlock.find('.add-image-item').last();
        var $newItem = $last.clone();
        $newItem.find('img').attr('src', '/img/add_image.png');
        $newItem.find('.remove-btn').removeClass('hidden').addClass('hidden');
        $newItem.find('.overlay').remove();
        $newItem.find('input[type=file]').val('');
        $newItem.find('input[type=hidden]').remove();
        $last.after($newItem);
        return $newItem;
    }
})(jQuery);