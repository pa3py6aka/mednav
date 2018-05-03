<?php

namespace core\actions;


use Imagine\Exception\RuntimeException;
use Yii;
use yii\base\Action;
use yii\helpers\Inflector;
use yii\imagine\Image;
use yii\validators\ImageValidator;
use yii\web\Response;
use yii\web\UploadedFile;

class UploadAction extends Action
{
    public $path = '@frontend/web/tmp';
    public $baseUrl = '/tmp';
    public $maxSize = 1024 * 1024 * 10;
    public $extensions = ['jpg', 'jpeg', 'png', 'gif'];

    public $sizes = [
        'small' => ['width' => '100', 'height' => null],
        'big' => ['width' => '2500', 'height' => null],
        'max' => ['width' => '500', 'height' => null],
    ];

    public $returnType = 'small';

    public function init()
    {
        $this->path = Yii::getAlias($this->path);
        parent::init();
    }

    public function run()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $file = UploadedFile::getInstanceByName('file');

        try {
            $this->validate($file);
            $fileName = $this->saveFile($file);
        } catch (\DomainException $e) {
            return ['result' => 'error', 'message' => $e->getMessage()];
        }

        return [
            'result' => 'success',
            'fileName' => $fileName,
            'url' => $this->baseUrl . '/' . $fileName];
    }

    private function saveFile(UploadedFile $file): string
    {
        $fileName = $this->generateFileName($file);
        $original = $this->path . '/action_original_' . $fileName;
        if (!$file->saveAs($original)) {
            throw new \DomainException("Ошибка сохранения файла");
        }

        try {
            foreach ($this->sizes as $type => $item) {
                $width = isset($item['width']) && (int) $item['width'] ? (int) $item['width'] : null;
                $height = isset($item['height']) && (int) $item['height'] ? (int) $item['height'] : null;

                Image::resize($original, $width, $height)
                    ->save($this->path . '/' . $type . '_' . $fileName);
            }
        } catch (RuntimeException $e) {
            Yii::$app->errorHandler->logException($e);
            throw new \DomainException("Ошибка сохранения файла");
        }

        unlink($original);
        return $this->returnType . '_' . $fileName;
    }

    private function validate(UploadedFile $file = null): void
    {
        if (!$file) {
            throw new \DomainException('Файл не загружен');
        }
        $validator = new ImageValidator([
            'extensions' => $this->extensions,
            'maxSize' => $this->maxSize,
        ]);
        if (!$validator->validate($file, $error)) {
            throw new \DomainException($error);
        }
    }

    private function generateFileName(UploadedFile $file, $n = 1): string
    {
        $num = $n > 1 ? '-' . $n : '';
        $name = Inflector::slug($file->baseName) . $num . '.' . $file->extension;
        if (!is_file($this->path . '/' . $name)) {
            return $name;
        } else {
            //$n++;
            return $this->generateFileName($file, ++$n);
        }
    }
}