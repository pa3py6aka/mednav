<?php
Yii::setAlias('@common', dirname(__DIR__));
Yii::setAlias('@frontend', dirname(dirname(__DIR__)) . '/frontend');
Yii::setAlias('@backend', dirname(dirname(__DIR__)) . '/backend');
Yii::setAlias('@console', dirname(dirname(__DIR__)) . '/console');
Yii::setAlias('@core', dirname(dirname(__DIR__)) . '/core');
Yii::setAlias('@tmp', dirname(dirname(__DIR__)) . '/frontend/web/tmp');
Yii::setAlias('@images', '@frontend/web/i');