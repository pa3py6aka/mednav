<?php

use backend\assets\ImagesManagerAsset;
use core\entities\Board\BoardTerm;
use core\entities\Currency;
use core\forms\manage\Board\BoardManageForm;
use core\forms\manage\Geo\GeoForm;
use mihaildev\ckeditor\CKEditor;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model BoardManageForm */
/* @var $board core\entities\Board\Board|null */
/* @var $form yii\widgets\ActiveForm */

$board = isset($board) ? $board : null;
ImagesManagerAsset::register($this);
?>

<div class="board-form box box-primary">
    <?php $form = ActiveForm::begin(); ?>
    <div class="box-body table-responsive">
        <?= $form->field($model, 'authorId')->input('number') ?>

        <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

        <?= $form->field($model, 'slug')->textInput(['maxlength' => true]) ?>

        <div id="category-block">
            <?= $model->getCategoryDropdowns($form) ?>
            <?php /*foreach ($model->categoryId as $n => $categoryId): ?>
                <?php
                $categories = $n == 0 ? BoardCategory::find()->roots()->asArray()->all() : BoardCategory::findOne($model->categoryId[$n - 1])->children;
                if ($n == 0) {
                    echo $form->field($model, 'categoryId[' . $n . ']')
                            ->dropDownList(
                                ArrayHelper::map($categories, 'id', 'name'),
                                ['prompt' => 'Выберите раздел']
                            );
                } else {
                    $dropdown = Html::activeDropDownList($model, 'categoryId[' . $n . ']', ArrayHelper::map($categories, 'id', 'name'), ['class' => 'form-control']);
                    echo Html::tag('div', $dropdown, ['class' => 'form-group category-dropdown']);
                }
                ?>
            <?php endforeach; ?>
            <?php if ($model->categoryId[$n] && $children = BoardCategory::findOne($model->categoryId[$n])->getChildren()->active()->all()) {
                $dropdown = Html::activeDropDownList($model, 'categoryId[' . ($n + 1) . ']', ArrayHelper::map($children, 'id', 'name'), ['class' => 'form-control', 'prompt' => '']);
                echo Html::tag('div', $dropdown, ['class' => 'form-group category-dropdown']);
            }*/ ?>
        </div>

        <div id="parameters-block">
            <?= $model->getParametersBlock() ?>
        </div>

        <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>

        <?= $form->field($model, 'description')->textarea(['rows' => 4]) ?>

        <?= $form->field($model, 'keywords')->textarea(['rows' => 4]) ?>

        <?= $form->field($model, 'note')
                ->textInput(['maxlength' => true, 'placeholder' => 'Прим: Состояние Новый/БУ, страна производитель и др. информация']) ?>

        <?= $form->field($model, 'price')->textInput() ?>

        <?= $form->field($model, 'currency')
                ->dropDownList(ArrayHelper::map(Currency::find()->asArray()->all(), 'id', 'name')) ?>

        <?= $form->field($model, 'priceFrom')->checkbox() ?>

        <?= $form->field($model, 'fullText')
                ->widget(CKEditor::class,['editorOptions' => ['preset' => 'full']]) ?>

        <?= $form->field($model, 'tags')->textInput() ?>

        <?= $model->scenario == BoardManageForm::SCENARIO_ADMIN_EDIT ? ''
                : $form->field($model, 'termId')
                    ->dropDownList(ArrayHelper::map(BoardTerm::find()->asArray()->all(), 'id', 'daysHuman')) ?>

        <?= $form->field($model, 'geoId')
                ->dropDownList(GeoForm::parentCategoriesList(false), ['prompt' => '']) ?>

        <?php if (!$board): ?>
        <div class="photos-block" data-form-name="<?= $model->formName() ?>" data-attribute="photos">
            <div class="add-image-item">
                <img src="/img/add_image.png" alt="Добафить фото" class="add-image-img">
                <input type="file" class="hidden">
                <span class="remove-btn fa fa-remove hidden"></span>
            </div>
            <div class="help-block"></div>
        </div>
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

<script>
    window.addEventListener('load', function () {
        var formName = '<?= $model->formName() ?>';
        $(document).on('change', 'select[name*=categoryId]', function () {
            var $select = $(this);
            var $box = $($select.parents('.box')[0]);
            $select.parent().nextAll('.form-group').remove();
            var id = $select.val();
            var emptySelect = false;
            if (!id) {
                var $prev = $select.parent().prev('.form-group').find('select');
                if ($prev.length) {
                    id = $prev.val();
                    emptySelect = true;
                } else {
                    $('#parameters-block').html('');
                    return;
                }
            }
            $.ajax({
                url: '/board/board/get-children',
                method: "post",
                dataType: "json",
                data: {id: id, formName: formName},
                beforeSend: function () {
                    $box.prepend('<div class="overlay"><i class="fa fa-refresh fa-spin"></i></div>');
                },
                success: function(data, textStatus, jqXHR) {
                    if (!emptySelect && data.items.length) {
                        var $dropdownBlock = $('#templates').find('.category-dropdown').clone();
                        var $dropdown = $dropdownBlock.find('select');
                        var num = $('#category-block').find('.form-group').length;
                        $dropdown.attr('name', formName + '[categoryId][' + num + ']');
                        $.each(data.items, function (k, item) {
                            $dropdown.append('<option value="' + item.id + '">' + item.name + '</option>');
                        });
                        $select.parent().after($dropdownBlock);
                    }
                    refreshParameters(data.params);
                },
                complete: function () {
                    $box.find('.overlay').remove();
                }
            });
        });

        function refreshParameters(params) {
            var $paramsBlock = $('#parameters-block');
            $paramsBlock.html(params);
        }
    });
</script>