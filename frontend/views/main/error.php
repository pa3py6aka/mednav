<?php

/* @var $this yii\web\View */
/* @var $name string */
/* @var $message string */
/* @var $exception Exception */

use yii\helpers\Html;

$this->title = $name;
?>
<div class="site-error">

    <h1><?= $exception instanceof \yii\base\UserException ? "Внимание!" : Html::encode($this->title) ?></h1>

    <div class="alert alert-danger">
        <?= nl2br($message) ?>
    </div>

</div>
