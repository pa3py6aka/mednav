<?php

use yii\bootstrap\ActiveForm;
use yii\bootstrap\Html;

/* @var $this yii\web\View */
/* @var $form yii\widgets\ActiveForm */
/* @var $tab string */
/* @var $model \yii\base\Model */

$this->title = 'Настройки компонента "Статьи"';

?>
<?php $form = ActiveForm::begin() ?>
<div class="box box-primary">
    <div class="box-body table-responsive">
        <?= $this->render('_tabs', ['tab' => $tab]) ?>

        <div class="tab-content">
            <div role="tabpanel" class="tab-pane active" id="<?= $tab ?>">
                <br>
                <?= $this->render($tab, [
                    'model' => $model,
                    'form' => $form,
                ]) ?>
            </div>
        </div>
    </div>
    <div class="box-footer">
        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success btn-flat']) ?>
    </div>
</div>
<?php ActiveForm::end() ?>
