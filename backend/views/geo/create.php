<?php

use core\forms\manage\Geo\GeoForm;

/* @var $this yii\web\View */
/* @var $model GeoForm */

$this->title = 'Новый регион';
$this->params['breadcrumbs'][] = ['label' => 'Гео', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="geo-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>