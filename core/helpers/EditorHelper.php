<?php

namespace core\helpers;


use Yii;

class EditorHelper
{
    public static function minimumPreset()
    {
        $preset = Yii::$app->params['CKEditorPreset'];
        $preset['editorOptions']['removeButtons'] .= ',Link,Unlink';
        return $preset;
    }
}