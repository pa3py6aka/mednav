<?php

/* @var $this yii\web\View */
/* @var $geo \core\entities\Geo */
/* @var $model \core\forms\manage\Geo\GeoForm */

$this->title = 'Редактирование региона: ' . $geo->name;
$this->params['breadcrumbs'][] = ['label' => 'Гео', 'url' => ['index']];
$this->params['breadcrumbs'][] = 'Редактирование';

?>
<div class="geo-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>