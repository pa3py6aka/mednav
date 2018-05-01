<?php

use core\entities\Board\BoardParameter;
use yii\bootstrap\ActiveForm;
use yii\bootstrap\Html;

/* @var $this \yii\web\View */
/* @var $model \core\forms\manage\Board\BoardParameterForm */

$this->title = 'Параметры Доски Объявлений';

?>
<?php $form = ActiveForm::begin() ?>
<div class="box box-primary">
    <div class="box-body table-responsive">
        <?= $this->render('@backend/views/board/settings/_tabs', ['tab' => 'parameters']) ?>

        <div class="tab-content">
            <div role="tabpanel" class="tab-pane active" id="parameters">
                <br>
                <?= $form->field($model, 'name') ?>
                <?= $form->field($model, 'type')->dropDownList(BoardParameter::typesArray()) ?>
                <?= $form->field($model, 'active')->checkbox() ?>
            </div>
        </div>
    </div>
    <div class="box-footer">
        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success btn-flat']) ?>
        <?= Html::a('Отмена', ['/board/parameters/index'], ['class' => 'btn btn-default btn-flat']) ?>
    </div>
</div>
<?php ActiveForm::end() ?>
