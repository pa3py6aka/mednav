<?php

namespace core\forms\manage\Board;

use Yii;
use yii\base\Model;
use yii\web\UploadedFile;

class BoardPhotosForm extends Model
{
    /**
     * @var UploadedFile[]
     */
    public $files;

    public function rules(): array
    {
        return [
            ['files', 'each', 'rule' => [
                    'image',
                    'extensions' => Yii::$app->params['imageExtensions'],
                    'maxSize' => Yii::$app->params['maxFileSize'],
                ]
            ],
        ];
    }

    public function beforeValidate(): bool
    {
        if (parent::beforeValidate()) {
            $this->files = UploadedFile::getInstances($this, 'files');
            return true;
        }
        return false;
    }
}