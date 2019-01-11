<?php

/* @var $this yii\web\View */
/* @var $boards \core\entities\Board\Board[] */

$this->title = '';
$cabinetLink = Yii::$app->frontendUrlManager->createAbsoluteUrl(['user/board/active']);

?>
<p>
    Здравствуйте, у добавленных Вами объявлений заканчивается срок размещения. Продлить данные объявления можно в <a href="<?= $cabinetLink ?>">личном кабинете</a>.
</p>
<table>
    <tr>
        <th>
            <span class="font-weight:bold;">Наименование:</span>
        </th>
        <th>
            <span class="font-weight:bold;">Размещено до:</span>
        </th>
    </tr>
    <?php foreach ($boards as $board): ?>
        <tr>
            <td>
                <?= \yii\helpers\Html::encode($board->name) ?>
            </td>
            <td>
                <?= date('d-m-Y', $board->active_until) ?>
            </td>
        </tr>
    <?php endforeach; ?>
</table>
<br>
<br>
<p>---</p>
<span style="font-style: italic;">Это письмо сформировано автоматически, отвечать на него не нужно.</span>
