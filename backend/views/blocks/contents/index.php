<?php

use core\components\ContentBlocks\ContentBlocksWidget;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Контентные блоки';
$this->params['breadcrumbs'][] = $this->title;

?>
<?= ContentBlocksWidget::widget() ?>

<script>
    window.addEventListener('load', function () {
        $(document).on('click', '.status-switcher', function (e) {
            e.preventDefault();
            var $button = $(this),
                blockId = $button.attr('data-block-id'),
                $box = $button.closest('.box');

            $.ajax({
                url: '/blocks/contents/switch-status',
                method: "post",
                dataType: "json",
                data: {id:blockId},
                beforeSend: function () {
                    $box.prepend(Mednav.public.overlay);
                },
                success: function(data, textStatus, jqXHR) {
                    if (data.result === 'success') {
                        $button.toggleClass('btn-success');
                        $button.toggleClass('btn-danger');
                        $button.text(data.status ? 'On' : 'Off');
                    } else {
                        alert('Ошибка.');
                    }
                },
                complete: function () {
                    $box.find('.overlay').remove();
                }
            });
        });
    });
</script>
