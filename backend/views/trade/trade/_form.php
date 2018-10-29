<?php

use core\components\ImageManager\ImageManagerAsset;
use core\forms\manage\Trade\TradeManageForm;
use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\bootstrap\ActiveForm;
use mihaildev\ckeditor\CKEditor;
use core\entities\Trade\TradeUserCategory;
use frontend\widgets\WholesalesFormWidget;
use yii\helpers\Url;
use core\actions\UploadAction;

/* @var $this yii\web\View */
/* @var $model TradeManageForm */
/* @var $trade core\entities\Trade\Trade|null */
/* @var $form yii\widgets\ActiveForm */

$trade = isset($trade) ? $trade : null;
$this->registerJsVar('_ImageUploadAction', Url::to(['/trade/trade/upload']));
ImageManagerAsset::register($this);
?>

<div class="board-form box box-primary">
    <?php $form = ActiveForm::begin(); ?>
    <div class="box-body table-responsive">
        <?= $form->field($model, 'userId')->widget(\backend\widgets\UserFieldWidget\UserIdFieldWidget::class) ?>

        <?= $form->field($model, 'categoryId')
            ->dropDownList($model->getUserCategories(), [
                'prompt' => '',
                'id' => \backend\widgets\UserFieldWidget\dependencies\UserCategoryDependency::CATEGORY_SELECTOR_ID,
            ]) ?>

        <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

        <?= $form->field($model, 'metaTitle')->textInput(['maxlength' => true]) ?>

        <?= $form->field($model, 'metaDescription')->textarea(['rows' => 4]) ?>

        <?= $form->field($model, 'metaKeywords')->textInput(['maxlength' => true]) ?>

        <?= $form->field($model, 'slug')->textInput(['maxlength' => true]) ?>

        <?= $form->field($model, 'code')->textInput(['maxlength' => true]) ?>

        <?= $form->field($model, 'price', ['template' => "{label}<br>\n{hint}\n{input}<div style='display:inline-block;padding-left:10px;' id='price-currency'></div>\n{error}"])
            ->textInput(['maxlength' => true, 'style' => 'width:85%;display:inline-block;']) ?>

        <?= WholesalesFormWidget::widget(['model' => $model]) ?>

        <?= $form->field($model, 'stock')->checkbox() ?>

        <?= $form->field($model, 'externalLink')->textInput(['maxlength' => true]) ?>

        <?= $form->field($model, 'note')->textInput(['maxlength' => true]) ?>

        <?= $form->field($model, 'description')->widget(CKEditor::class, ['editorOptions' => ['preset' => 'full']]) ?>

        <?= $form->field($model, 'tags')->textInput() ?>

        <?php if (!$trade): ?>
            <?= UploadAction::htmlBlock($model->formName()) ?>
        <?php endif; ?>

    </div>
    <div class="box-footer">
        <?= Html::submitButton($trade ? 'Сохранить' : 'Добавить', ['class' => 'btn btn-success btn-flat']) ?>
    </div>
    <?php ActiveForm::end(); ?>
</div>