<?php

namespace core\components;


use yii\base\Model;
use yii\helpers\Json;

class SettingsManager extends Settings
{
    private $settings;
    private $path;

    public function __construct()
    {
        $this->path = \Yii::getAlias('@core/data/settings.json');
        $this->settings = Json::decode(file_get_contents($this->path));
        $this->prepare();
    }

    public function get($param)
    {
        if (isset($this->settings[$param])) {
            return $this->settings[$param];
        }
        return $this->default[$param];
    }

    public function getAll(): array
    {
        return $this->settings;
    }

    public function saveForm(Model $form)
    {
        foreach ($form->attributes as $param => $value) {
            $this->settings[$param] = $value;
        }
        return file_put_contents($this->path, Json::encode($this->settings));
    }

    private function prepare(): void
    {
        foreach ($this->default as $param => $value) {
            if (!isset($this->settings[$param]) || $this->settings[$param] == '') {
                $this->settings[$param] = $value;
            }
        }
    }
}