<?php

use yii\helpers\Html;

/* @var $contactForm \core\forms\ContactForm */

?>
<p><strong>Дата:</strong> <?= Yii::$app->formatter->asDatetime(time()) ?></p>
<p><strong>Имя:</strong> <?= Html::encode($contactForm->name) ?></p>
<p><strong>Email:</strong> <?= Html::encode($contactForm->email) ?></p>
<p><strong>Телефон:</strong> <?= Html::encode($contactForm->phone) ?></p>
<?php /*<p><strong>Тема:</strong> <?= Html::encode($message->dialog->subject) ?></p> */ ?>
<p><strong>Сообщение:</strong> <?= Yii::$app->formatter->asNtext($contactForm->message) ?></p>
<p>---</p>
<span style="font-style:italic;">Данное сообщение отправлено посетителем c формы обратной связи.</span>