<?php

use yii\bootstrap\Html;

/* @var $this yii\web\View */
/* @var $form yii\widgets\ActiveForm */
/* @var $model \core\forms\manage\Board\BoardTermsForm */

$count = 10;
?>
<table class="table table-striped table-bordered without-margin">
    <tr>
        <th>#</th>
        <th>Кол-во дней</th>
        <th>Название на сайте</th>
        <th>Уведомлять о продлении за</th>
        <th>Выбран по умолчанию</th>
    </tr>
    <?php for ($i = 1; $i <= $count; $i++): ?>
        <?php if (isset($model->id[$i]) || $model->getMaxId() < $i): ?>
        <tr>
            <td><?= $i ?></td>
            <td><?= Html::activeInput('number', $model, 'days[' . $i . ']', ['class' => 'form-control']) ?></td>
            <td><?= Html::activeInput('text', $model, 'daysHuman[' . $i . ']', ['class' => 'form-control']) ?></td>
            <td><?= Html::activeInput('number', $model, 'notification[' . $i . ']', ['class' => 'form-control']) ?></td>
            <td><?= Html::activeCheckbox($model, 'default[' . $i . ']') ?></td>
        </tr>
        <?php elseif ($model->getMaxId() > $i): ?>
            <?php $count++; ?>
        <?php endif; ?>
    <?php endfor; ?>
</table>

<script>
    window.addEventListener('load', function () {
        $(document).on('change', 'input[type=checkbox][name*=default]', function () {
            var checked = $(this).is(':checked');
            var id = $(this).attr('id');
            if (checked) {
                $('input[type=checkbox][name*=default]').not('#' + id).prop('checked', false);
            }
        });
    });
</script>


