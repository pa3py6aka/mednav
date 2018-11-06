<?php
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $allDeliveries \core\entities\Trade\TradeDelivery[] */
/* @var $userDeliveries \core\entities\Company\CompanyDelivery[] */

$this->params['tab'] = 'settings';

?>
<div class="has-overlay" id="main-delivery-block">
    <p>Отметьте требуемые типы доставки</p>
    <?= Html::beginForm() ?>
    <?php foreach ($allDeliveries as $delivery): ?>
        <div class="delivery-row" data-id="<?= $delivery->id ?>">
            <?= Html::checkbox('delivery[' . $delivery->id. ']', array_key_exists($delivery->id, $userDeliveries), ['label' => $delivery->name]) ?>

            <?php if ($delivery->has_regions): ?>
                <?= Html::checkbox(
                    'regions[' . $delivery->id. '][]',
                    isset($userDeliveries[$delivery->id]) && array_key_exists(Yii::$app->params['geoRussiaId'], $userDeliveries[$delivery->id]->geos),
                    ['label' => 'По России', 'value' => Yii::$app->params['geoRussiaId'], 'class' => 'russia-region']
                ) ?>
                &nbsp;ИЛИ <a
                    href="#"
                    data-link="regionsSelectLink"
                    data-id="<?= $delivery->id ?>"
                    style="border-bottom:1px dashed;color:#2d5d9c;"
                >Выбрать регионы</a>

                <div class="hidden region-inputs"></div>
            <?php endif; ?>

            <?php if ($delivery->has_terms): ?>
                &nbsp;| <a
                    href="#termsModal"
                    data-toggle="modal"
                    data-id="<?= $delivery->id ?>"
                    style="border-bottom:1px dashed;color:#2d5d9c;"
                    data-delivery-name="<?= $delivery->name ?>"
                >Описание</a>
                <input type="hidden" name="description[<?= $delivery->id ?>]" value="<?= isset($userDeliveries[$delivery->id]) ? $userDeliveries[$delivery->id]->terms : '' ?>">
            <?php endif; ?>
        </div>
    <?php endforeach; ?>

    <input type="hidden" name="save-user-deliveries" value="1">
    <button type="submit" class="btn btn-primary">Сохранить</button>
    <?= Html::endForm() ?>
</div>

<div class="modal fade" tabindex="-1" role="dialog" id="termsModal">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title"></h4>
            </div>
            <div class="modal-body">
                <form>
                    <div class="form-group">
                        <label for="message-text" class="control-label">Описание:</label>
                        <textarea class="form-control" id="terms-modal-input"></textarea>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Отмена</button>
                <button type="button" class="btn btn-primary submit-button">Ок</button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<script>
    window.addEventListener('load', function () {
        $(document).on('click', '[data-link=regionsSelectLink]', function (e) {
            e.preventDefault();
            $("#modalRegion").remove();
            var deliveryId = $(this).attr('data-id'),
                $mainBlock = $('#main-delivery-block'),
                $regionInputs = $mainBlock.find('.delivery-row[data-id=' + deliveryId + ']').find('.region-inputs').find('input'),
                regionIds = [];
            $regionInputs.each(function (k, input) {
                regionIds.push($(input).val());
            });
            $.ajax({
                url: '/user/trade/get-delivery-regions',
                method: "post",
                dataType: "html",
                data: {deliveryId: deliveryId, regionIds: regionIds},
                beforeSend: function () {
                    $mainBlock.prepend('<div class="overlay"><i class="fa fa-refresh fa-spin"></i></div>');
                },
                success: function(data, textStatus, jqXHR) {
                    $mainBlock.append(data);
                    $("#modalRegion").modal();
                },
                complete: function () {
                    $mainBlock.find('.overlay').remove();
                }
            });
        });

        $(document).on('hidden.bs.modal', '#modalRegion', function (e) {
            var deliveryId = $(this).attr('data-delivery-id'),
                $row = $('#main-delivery-block').find('.delivery-row[data-id=' + deliveryId + ']'),
                $inputsBlock = $row.find('.region-inputs'),
                $checkBoxes = $(this).find('.v-checkbox:checked');

            if ($checkBoxes.length) {
                $checkBoxes.attr('type', 'hidden');
                $inputsBlock.html($checkBoxes);
                $row.find('.russia-region').prop('checked', false);
            } else {
                $inputsBlock.html('');
            }
        });

        $('#termsModal').on('show.bs.modal', function (e) {
            var $button = $(e.relatedTarget),
                terms = $('.delivery-row[data-id=' + $button.data('id') + ']').find('input[name*=description]').val();
            $(this).attr('data-delivery-id', $button.data('id'))
                .find('.modal-title').text($button.data('delivery-name'));
            $('#terms-modal-input').val(terms);
        }).on('click', '.submit-button', function (e) {
            var $modal = $('#termsModal');
            $('.delivery-row[data-id=' + $modal.attr('data-delivery-id') + ']')
                .find('input[name*=description]').val($('#terms-modal-input').val());
            $modal.modal('hide');
        });

        $(document).on('change', '#modalRegion .v-checkbox', function(e) {
            alert(8);
        });
    });
</script>

