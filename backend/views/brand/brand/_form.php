<?php
use core\components\ImageManager\ImageManagerAsset;
use core\actions\UploadAction;
use mihaildev\ckeditor\CKEditor;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use backend\widgets\UserFieldWidget\UserIdFieldWidget;
use core\entities\Brand\BrandCategory;

/* @var $this yii\web\View */
/* @var $model \core\forms\Brand\BrandForm */

/* @var $form yii\widgets\ActiveForm */

$this->registerJsVar('_ImageUploadAction', Url::to(['/brand/brand/upload']));
ImageManagerAsset::register($this);

?>
<div class="company-form box box-primary">
    <?php $form = ActiveForm::begin(['id' => 'brand-form']); ?>
    <div class="box-body table-responsive">

        <?= $form->field($model, 'user_id')->widget(UserIdFieldWidget::class) ?>

        <?= $form->field($model, 'categoryId')->dropDownList(\core\forms\manage\CategoryForm::parentCategoriesList(BrandCategory::class)) ?>

        <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>

        <?= $form->field($model, 'metaKeywords')->textInput(['maxlength' => true]) ?>

        <?= $form->field($model, 'metaDescription')->textarea(['rows' => 4]) ?>

        <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

        <?= $form->field($model, 'slug')->textInput(['maxlength' => true]) ?>

        <?= $form->field($model, 'intro')->textarea(['rows' => 4]) ?>

        <?= $form->field($model, 'fullText')->widget(CKEditor::class) ?>

        <?= $form->field($model, 'indirectLinks')->checkbox() ?>

        <?= $form->field($model, 'tags')->textInput() ?>

        <?php if (!$model->article): ?>
            <?= UploadAction::htmlBlock($model->formName()) ?>
        <?php endif; ?>

    </div>
    <div class="box-footer">
        <?= Html::submitButton($model->article ? 'Сохранить' : 'Добавить', ['class' => 'btn btn-success btn-flat']) ?>
    </div>
    <?php ActiveForm::end(); ?>
</div>