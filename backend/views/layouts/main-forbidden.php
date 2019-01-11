<?php
use backend\assets\AppAsset;
use yii\helpers\Html;

/* @var $this \yii\web\View */
/* @var $content string */

dmstr\web\AdminLteAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>"/>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>
<body class="login-page">

<div class="text-center" style="margin-top:100px;">
    <?php $this->beginBody() ?>

        <h3>403 - Доступ запрещён</h3>
        Статус Вашего профиля не имеет доступа к данной странице

    <?php $this->endBody() ?>
    <br><br>
    <a href="<?= \yii\helpers\Url::to(['/site/logout']) ?>" class="btn btn-warning" data-method="post">Выйти</a>
</div>

</body>
</html>
<?php $this->endPage() ?>
