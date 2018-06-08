<?php

use yii\bootstrap\ActiveForm;
use yii\helpers\Html;
use kartik\file\FileInput;

/* @var $photos \core\entities\PhotoTrait[] */
/* @var $entityId int */
/* @var $photosForm \core\forms\manage\PhotosForm */

?>
<div class="row">
<?php foreach ($photos as $photo): ?>
    <div class="col-md-2 col-xs-3" style="text-align: center">
            <div class="btn-group">
                <?= Html::a('<span class="glyphicon glyphicon-arrow-left"></span>', ['move-photo', 'entityId' => $entityId, 'photoId' => $photo->getId(), 'direction' => 'up'], [
                    'class' => 'btn btn-default',
                    'data-method' => 'post',
                ]); ?>
                <?= Html::a('<span class="glyphicon glyphicon-remove"></span>', ['delete-photo', 'id' => $entityId, 'photo_id' => $photo->getId()], [
                    'class' => 'btn btn-default',
                    'data-method' => 'post',
                    'data-confirm' => 'Удалить фото?',
                ]); ?>
                <?= Html::a('<span class="glyphicon glyphicon-arrow-right"></span>', ['move-photo', 'entityId' => $entityId, 'photoId' => $photo->getId(), 'direction' => 'down'], [
                    'class' => 'btn btn-default',
                    'data-method' => 'post',
                ]); ?>
            </div>
            <div>
                <?= Html::a(
                    Html::img($photo->getUrl('small', true)),
                    $photo->getUrl('max', true),
                    ['class' => 'thumbnail', 'target' => '_blank']
                ) ?>
            </div>
    </div>
<?php endforeach; ?>
</div>

<?php $form = ActiveForm::begin([
    'options' => ['enctype'=>'multipart/form-data'],
]); ?>

<?= $form->field($photosForm, 'files[]')->label(false)->widget(FileInput::class, [
    'options' => [
        'accept' => 'image/*',
        'multiple' => true,
    ]
]) ?>

<div class="form-group">
    <?= Html::submitButton('Загрузить', ['class' => 'btn btn-success']) ?>
</div>
<?php ActiveForm::end(); ?>
