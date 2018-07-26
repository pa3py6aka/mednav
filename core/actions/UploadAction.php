<?php

namespace core\actions;


use core\components\SettingsManager;
use Imagine\Exception\RuntimeException;
use Yii;
use yii\base\Action;
use yii\helpers\Inflector;
use yii\helpers\FileHelper;
use yii\imagine\Image;
use yii\validators\ImageValidator;
use yii\web\Response;
use yii\web\UploadedFile;
use Spatie\ImageOptimizer\OptimizerChainFactory;

class UploadAction extends Action
{
    public $path = '@frontend/web/tmp';
    public $baseUrl;
    public $maxSize;
    public $extensions;
    public $sizes;
    public $returnType = 'small';

    public function init()
    {
        $this->path = Yii::getAlias($this->path);
        if (!$this->baseUrl) {
            $this->baseUrl = Yii::$app->params['frontendHostInfo'] . '/tmp';
        }
        if (!$this->maxSize) {
            $this->maxSize = Yii::$app->params['maxFileSize'];
        }
        if (!$this->extensions) {
            $this->extensions = Yii::$app->params['imageExtensions'];
        }
        if (!$this->sizes) {
            $this->sizes = [
                'small' => ['width' => Yii::$app->settings->get(SettingsManager::BOARD_SMALL_SIZE)],
                'big' => ['width' => Yii::$app->settings->get(SettingsManager::BOARD_BIG_SIZE)],
                'max' => ['width' => Yii::$app->settings->get(SettingsManager::BOARD_MAX_SIZE)],
            ];
        }
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
            'url' => $this->baseUrl . '/' . $fileName
        ];
    }

    private function saveFile(UploadedFile $file): string
    {
        $fileName = $this->generateFileName($file);
        $original = $this->path . '/action_original_' . $fileName;
        if (!$file->saveAs($original)) {
            throw new \DomainException("Ошибка сохранения файла");
        }

        $optimizerChain = OptimizerChainFactory::create();
        try {
            foreach ($this->sizes as $type => $item) {
                $width = isset($item['width']) && (int) $item['width'] ? (int) $item['width'] : null;
                $height = isset($item['height']) && (int) $item['height'] ? (int) $item['height'] : null;

                Image::resize($original, $width, $height)
                    ->save($this->path . '/' . $type . '_' . $fileName);
                $optimizerChain->optimize($this->path . '/' . $type . '_' . $fileName);
            }
        } catch (RuntimeException $e) {
            Yii::$app->errorHandler->logException($e);
            throw new \DomainException("Ошибка сохранения файла");
        }

        FileHelper::unlink($original);
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
            'maxFiles' => 10,
        ]);
        if (!$validator->validate($file, $error)) {
            throw new \DomainException($error);
        }
    }

    private function generateFileName(UploadedFile $file, $n = 1): string
    {
        $num = $n > 1 ? '-' . $n : '';
        $name = substr(Inflector::slug($file->baseName), 0, 220) . $num . '.' . $file->extension;
        if (!is_file($this->path . '/' . $name)) {
            return $name;
        } else {
            return $this->generateFileName($file, ++$n);
        }
    }

    public static function htmlBlock($formName): string
    {
        return <<<HTML
<label class="control-label" for="file">Фото</label>
<div class="photos-block" data-form-name="$formName" data-attribute="photos">
    <div class="add-image-item has-overlay">
        <img src="/img/add_image.png" alt="Добафить фото" class="add-image-img">
        <input type="file" class="hidden" accept="image/*">
        <span class="remove-btn fa fa-remove hidden"></span>
    </div>
    <div class="help-block"></div>
</div>
HTML;
    }
}