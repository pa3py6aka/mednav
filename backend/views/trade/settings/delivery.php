<?php
use yii\bootstrap\Html;

/* @var $this yii\web\View */
/* @var $form yii\widgets\ActiveForm */
/* @var $model \core\forms\manage\Trade\TradeDeliveryForm */

$count = 10;
?>
<table class="table table-striped table-bordered without-margin">
    <tr>
        <th>Наименование</th>
        <th>Выводить поле "Условия"</th>
        <?php //<th>Выводить "Выбор региона"</th> ?>
        <th></th>
    </tr>
    <?php for ($i = 1; $i <= $count; $i++): ?>
        <?php if (isset($model->id[$i]) || $model->getMaxId() < $i): ?>
        <tr>
            <td><?= Html::activeInput('text', $model, 'name[' . $i . ']', ['class' => 'form-control']) ?></td>
            <td class="text-center"><?= Html::activeCheckbox($model, 'terms[' . $i . ']', ['label' => '']) ?></td>
            <?php /*<td class="text-center"><?= Html::activeCheckbox($model, 'regions[' . $i . ']', ['label' => '']) ?></td> */ ?>
            <td><i class="del-row-btn glyphicon glyphicon-trash text-red" style="cursor:pointer;"></i></td>
        </tr>
        <?php elseif ($model->getMaxId() > $i): ?>
            <?php $count++; ?>
        <?php endif; ?>
    <?php endfor; ?>
</table>

<script>
    window.addEventListener('load', function () {
        $(document).on('click', '.del-row-btn', function () {
            var $tr = $(this).parent().parent();
            $tr.find('input[type=text]').val('').end().find('input[type=checkbox]').prop('checked', false);
        });
    });
</script>


