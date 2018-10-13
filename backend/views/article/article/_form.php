<?php
use core\components\ImageManager\ImageManagerAsset;
use core\actions\UploadAction;
use mihaildev\ckeditor\CKEditor;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use backend\widgets\UserIdFieldWidget;
use frontend\widgets\SelectCategoryWidget;
use core\entities\Article\ArticleCategory;

/* @var $this yii\web\View */
/* @var $model \core\forms\Article\ArticleForm */

/* @var $form yii\widgets\ActiveForm */

$this->registerJsVar('_ImageUploadAction', Url::to(['/article/article/upload']));
ImageManagerAsset::register($this);

?>
<div class="company-form box box-primary">
    <?php $form = ActiveForm::begin(['id' => 'article-form']); ?>
    <div class="box-body table-responsive">

        <?= $form->field($model, 'user_id')->widget(UserIdFieldWidget::class) ?>

        <?= $form->field($model, 'categoryId')->dropDownList(\core\forms\manage\CategoryForm::parentCategoriesList(ArticleCategory::class)) ?>

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