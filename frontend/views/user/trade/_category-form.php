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
<?php $form = ActiveForm::begin(); ?>

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

<?= Html::submitButton(!$model->isNew() ? 'Сохранить' : 'Добавить', ['class' => 'btn btn-success btn-flat']) ?>
<?php ActiveForm::end(); ?>
