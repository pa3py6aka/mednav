<?php

use yii\bootstrap\ActiveForm;
use yii\bootstrap\Html;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $form yii\widgets\ActiveForm */
/* @var $tab string */
/* @var $model \yii\base\Model */

$this->title = 'Настройки Доски Объявлений';

?>
<?php $form = ActiveForm::begin() ?>
<div class="box box-primary">
    <div class="box-body table-responsive">
        <ul class="nav nav-tabs" role="tablist">
            <li role="presentation"<?= $tab == 'main' ? ' class="active"' : '' ?>><a href="<?= Url::to(['/board/settings/main']) ?>">Основные</a></li>
            <li role="presentation"<?= $tab == 'index-page' ? ' class="active"' : '' ?>><a href="<?= Url::to(['/board/settings/index-page']) ?>">Главная страница</a></li>
            <li role="presentation"<?= $tab == 'terms' ? ' class="active"' : '' ?>><a href="<?= Url::to(['/board/settings/terms']) ?>">Сроки</a></li>
            <li role="presentation"<?= $tab == 'special' ? ' class="active"' : '' ?>><a href="<?= Url::to(['/board/settings/special']) ?>">Спецразмещение</a></li>
            <li role="presentation"<?= $tab == 'parameters' ? ' class="active"' : '' ?>><a href="<?= Url::to(['/board/settings/parameters']) ?>">Параметры</a></li>
        </ul>

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
