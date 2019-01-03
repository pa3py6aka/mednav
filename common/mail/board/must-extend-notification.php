<?php

/* @var $this yii\web\View */
/* @var $board \core\entities\Board\Board */

$this->title = $board->getTitle();
$cabinetLink = Yii::$app->frontendUrlManager->createAbsoluteUrl(['user/board/active']);

?>
<p>
    <strong>Заканчивается срок публикации объявления. Вы можете продлить его в <a href="<?= $cabinetLink ?>">личном кабинете.</a></strong>
</p>