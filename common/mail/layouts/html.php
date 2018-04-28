<?php
use yii\helpers\Html;

/* @var $this \yii\web\View view component instance */
/* @var $message \yii\mail\MessageInterface the message being composed */
/* @var $content string main view render result */
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html>
<head lang="ru">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=1024">
    <title><?= Html::encode($this->title) ?></title>
    <link rel="shortcut icon" href="<?= Yii::$app->params['frontendHostInfo'] ?>/favicon.ico" type="image/x-icon" />
</head>
<body>
<?php $this->beginBody() ?>
<p style="float:left;padding:0px 30px 30px 0px;"><img src="<?= Yii::$app->params['frontendHostInfo'] ?>/img/mednav-logo.png" width="282" height="64" alt=""></p><table style="width:650px;max-width:650px;border-collapse:collapse;clear:both;">
    <tbody><tr>
        <td style="width:50px;min-width:50px;"> </td>
        <td colspan="2">
            <h1 style="font-family:Arial;font-size:22px;margin:0px 0px 20px 0px;padding:0px;font-weight:600;">
                <?= Html::encode($this->title) ?>
            </h1>
            <?= $content ?>
        </td>
        <td style="width:20px;min-width:20px;"> </td>
    </tr>
    <tr>
        <td style="width:20px;min-width:20px;"> </td>
        <td colspan="2" style="padding:40px 0px 50px 0px;font-size:14px;font-family:Arial;color:#aeaaa0;">
            © <?= date('Y') ?> mednav.ru</td>
        <td style="width:20px;min-width:20px;"> </td>
    </tr>
    </tbody></table>
<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>

