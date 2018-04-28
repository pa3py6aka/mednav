<?php

namespace core\forms\manage;


use Yii;
use yii\base\Model;

class CommonSettingsForm extends Model
{
    public function init()
    {
        $settings = Yii::$app->settings->getAll();
        foreach ($this->attributes as $attribute => $value) {
            $this->$attribute = $settings[$attribute];
        }

        parent::init();
    }
}