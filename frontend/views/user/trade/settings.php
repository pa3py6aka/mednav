<?php
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $allDeliveries \core\entities\Trade\TradeDelivery[] */
/* @var $userDeliveries \core\entities\Company\CompanyDelivery[] */
/* @var $userDeliveryRegions \core\entities\Company\CompanyDeliveryRegion[] */

$this->params['tab'] = 'settings';
?>
<div class="has-overlay" id="main-delivery-block">
    <?= Html::beginForm() ?>
    <table style="margin-bottom:10px;">
        <tr>
            <th width="150" valign="top">Регионы доставки:</th>
            <td>
                <?php foreach (\core\entities\Geo::find()->countries()->active()->all() as $country): ?>
                    <?php //= Html::checkbox('region[' . $country->id. ']', array_key_exists($country->id, $userDeliveryRegions), ['label' => $country->name, 'data-country-main' => $country->id]) ?>
                    <div style="margin-bottom:5px;">
                        <input
                                type="checkbox"
                                name="countries[]"
                                value="<?= $country->id ?>"
                                data-country-main="<?= $country->id ?>"
                            <?= array_key_exists($country->id, $userDeliveryRegions) ? ' checked' : '' ?>
                        > <?= $country->name ?>
                        или <a
                                href="#"
                                data-link="regionsSelectLink"
                                data-country-id="<?= $country->id ?>"
                                style="border-bottom:1px dashed;color:#2d5d9c;"
                        ><?= \core\helpers\CompanyHelper::regionsSelectString($country->id, $userDeliveryRegions) ?></a>
                        <div class="hidden region-inputs" data-country-id="<?= $country->id ?>">
                            <?php foreach ($userDeliveryRegions as $geoId => $countryId): ?>
                                <?= $country->id == $countryId ? '<input name="regions[' . $countryId . '][]" value="' . $geoId . '">' : '' ?>
                            <?php endforeach; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            </td>
        </tr>
    </table>

    <b>Типы доставки:</b>
    <?php foreach ($allDeliveries as $delivery): ?>
        <div class="delivery-row" data-id="<?= $delivery->id ?>">
            <?= Html::checkbox('delivery[' . $delivery->id. ']', array_key_exists($delivery->id, $userDeliveries), ['label' => $delivery->name]) ?>

            <?php if ($delivery->has_terms): ?>
                &nbsp;| <a
                    href="#termsModal"
                    data-toggle="modal"
                    data-id="<?= $delivery->id ?>"
                    style="border-bottom:1px dashed;color:#2d5d9c;"
                    data-delivery-name="<?= $delivery->name ?>"
                >Условия доставки</a>
                <input type="hidden" name="description[<?= $delivery->id ?>]" value="<?= isset($userDeliveries[$delivery->id]) ? $userDeliveries[$delivery->id]->terms : '' ?>">
            <?php endif; ?>
        </div>
    <?php endforeach; ?>

    <input type="hidden" name="save-user-deliveries" value="1">
    <button type="submit" class="btn btn-primary" style="margin-top:7px;">Сохранить</button>
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
            var countryId = $(this).attr('data-country-id'),
                $mainBlock = $('#main-delivery-block'),
                $regionInputs = $mainBlock.find('.region-inputs[data-country-id=' + countryId + ']').find('input'),
                regionIds = [];
            $regionInputs.each(function (k, input) {
                regionIds.push($(input).val());
            });
            $.ajax({
                url: '/user/trade/get-country-regions',
                method: "post",
                dataType: "html",
                data: {countryId: countryId, regionIds: regionIds},
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
            var countryId = $(this).attr('data-country-id'),
                $inputsBlock = $('#main-delivery-block').find('.region-inputs[data-country-id=' + countryId + ']'),
                $checkBoxes = $(this).find('.v-checkbox:checked'),
                $regionsSelectLink = $('[data-link=regionsSelectLink][data-country-id=' + countryId + ']');

            if ($checkBoxes.length) {
                $checkBoxes.attr('type', 'hidden');
                $inputsBlock.html($checkBoxes);
                $('[data-country-main=' + countryId + ']').prop('checked', false);
                $regionsSelectLink.text(Mednav.public.pluralize($checkBoxes.length, ['Выбран','Выбрано','Выбрано']) + ' ' + $checkBoxes.length + ' ' + Mednav.public.pluralize($checkBoxes.length, ['регион','региона','регионов']))
            } else {
                $inputsBlock.html('');
                $regionsSelectLink.text('Выбрать регионы');
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
            var $inputs = $(this).closest('li').find('input.v-checkbox');
            $inputs.prop('checked', $(this).is(':checked'));

            /* if (!$(this).is(':checked')) {
                $(this).closest('ul').parent().find('>label>input.v-checkbox').prop('checked', false);
            } */
        });
    });
</script>

