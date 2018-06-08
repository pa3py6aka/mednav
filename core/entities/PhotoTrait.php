<?php

namespace core\entities;

use Yii;
use yii\helpers\FileHelper;


trait PhotoTrait
{
    public function getUrl($type = 'small', $absolute = false): string
    {
        return ($absolute ? Yii::$app->params['frontendHostInfo'] . '/' : '/')
            . str_replace('/max/', '/' . $type . '/', $this->file);
    }

    public function getPhotos(): array
    {
        return [
            'small' => str_replace('/max/', '/small/', $this->file),
            'big' => str_replace('/max/', '/big/', $this->file),
            'max' => $this->file,
        ];
    }

    public function removePhotos()
    {
        $path = Yii::getAlias('@frontend/web/');
        foreach ($this->getPhotos() as $file) {
            if (is_file($path . $file)) {
                FileHelper::unlink($path . $file);
            }
        }
    }

    public function afterDelete()
    {
        $this->removePhotos();
        return parent::afterDelete();
    }

    public function getId()
    {
        return $this->id;
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'board_id' => 'Board ID',
            'file' => 'File',
            'sort' => 'Sort',
        ];
    }
}
