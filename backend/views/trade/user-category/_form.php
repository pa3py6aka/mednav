<?php
use core\forms\manage\Trade\TradeUserCategoryForm;
use core\entities\Trade\TradeCategory;
use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\widgets\ActiveForm;
use core\entities\Currency;
use core\entities\Trade\TradeUoM;
use frontend\widgets\SelectCategoryWidget;

/* @var $this yii\web\View */
/* @var $model TradeUserCategoryForm */

?>
<div class="board-form box box-primary">
    <?php $form = ActiveForm::begin(); ?>
    <div class="box-body table-responsive">
        <?= $form->field($model, 'userId')->widget(\backend\widgets\UserFieldWidget\UserIdFieldWidget::class) ?>

        <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

        <?= SelectCategoryWidget::widget([
            'entity' => TradeCategory::class,
            'model' => $model,
            'form' => $form,
        ]) ?>

        <?= $form->field($model, 'uomId')
            ->dropDownList(ArrayHelper::map(TradeUoM::find()->asArray()->all(), 'id', 'sign')) ?>

        <?= $form->field($model, 'currencyId')
            ->dropDownList(ArrayHelper::map(Currency::getAllFor(Currency::MODULE_TRADE), 'id', 'sign')) ?>

        <?= $form->field($model, 'wholeSale')->checkbox() ?>
    </div>
    <div class="box-footer">
        <?= Html::submitButton(!$model->isNew() ? 'Сохранить' : 'Добавить', ['class' => 'btn btn-success btn-flat']) ?>
    </div>
    <?php ActiveForm::end(); ?>
</div>

<div class="hidden" id="templates">
    <div class="form-group category-dropdown">
        <select class="form-control" name="" aria-invalid="true" title="Раздел">
            <option></option>
        </select>
    </div>
</div>
