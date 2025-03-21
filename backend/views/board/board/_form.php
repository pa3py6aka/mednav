<?php

use core\components\ImageManager\ImageManagerAsset;
use core\entities\Board\BoardTerm;
use core\entities\Currency;
use core\forms\manage\Board\BoardManageForm;
use frontend\widgets\RegionsModalWidget;
use mihaildev\ckeditor\CKEditor;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use core\actions\UploadAction;
use frontend\widgets\SelectCategoryWidget;
use core\entities\Board\BoardCategory;

/* @var $this yii\web\View */
/* @var $model BoardManageForm */
/* @var $board core\entities\Board\Board|null */
/* @var $form yii\widgets\ActiveForm */

$board = isset($board) ? $board : null;
$this->registerJsVar('_ImageUploadAction', Url::to(['/board/board/upload']));
ImageManagerAsset::register($this);
//$this->registerJs($model->getJs());
?>

<div class="board-form box box-primary">
    <?php $form = ActiveForm::begin(); ?>
    <div class="box-body table-responsive">

        <?= $form->field($model, 'authorId')->widget(\backend\widgets\UserFieldWidget\UserIdFieldWidget::class) ?>

        <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

        <?= $form->field($model, 'slug')->textInput(['maxlength' => true]) ?>

        <?= SelectCategoryWidget::widget([
            'entity' => BoardCategory::class,
            'model' => $model,
            'form' => $form,
        ]) ?>

        <div id="parameters-block">
            <?= $model->getParametersBlock() ?>
        </div>

        <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>

        <?= $form->field($model, 'description')->textarea(['rows' => 4]) ?>

        <?= $form->field($model, 'keywords')->textarea(['rows' => 4]) ?>

        <?= $form->field($model, 'note')
                ->textInput(['maxlength' => true, 'placeholder' => 'Прим: Состояние Новый/БУ, страна производитель и др. информация']) ?>

        <div class="container-fluid no-padding">
            <div class="col-xs-6 no-padding">
                <?= $form->field($model, 'price')->textInput() ?>
            </div>
            <div class="col-xs-3" style="margin-top:24px;">
                <?= $form->field($model, 'currency')
                    ->dropDownList(ArrayHelper::map(Currency::getAllFor(Currency::MODULE_BOARD), 'id', 'sign'))->label(false) ?>
            </div>
            <div class="col-xs-3" style="margin-top:30px;">
                <?= Html::activeCheckbox($model, 'priceFrom') ?>
            </div>
        </div>

        <?= $form->field($model, 'fullText')
                ->widget(CKEditor::class, ['editorOptions' => ['preset' => 'full']]) ?>

        <?= $form->field($model, 'tags')->textInput() ?>

        <?= $model->scenario == BoardManageForm::SCENARIO_ADMIN_EDIT ? ''
                : $form->field($model, 'termId')
                    ->dropDownList(ArrayHelper::map(BoardTerm::find()->asArray()->all(), 'id', 'daysHuman')) ?>

        <?= $form->field($model, 'geoId')
                ->hiddenInput(['id' => 'form-geo-id'])
                ->hint('<span class="input-modal-link" id="region-select-link" data-toggle="modal" data-target="#modalRegion">' . $model->geoName() . '</span>'); ?>

        <?php if (!$board): ?>
            <?= UploadAction::htmlBlock($model->formName()) ?>
        <?php endif; ?>

    </div>
    <div class="box-footer">
        <?= Html::submitButton($board ? 'Сохранить' : 'Добавить', ['class' => 'btn btn-success btn-flat']) ?>
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

<?= RegionsModalWidget::widget() ?>