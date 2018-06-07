<?php

namespace core\useCases;


use core\entities\PhotoInterface;
use core\services\TransactionManager;
use Yii;
use core\helpers\FileHelper;
use yii\base\InvalidArgumentException;
use yii\imagine\Image;
use yii\web\UploadedFile;

class BasePhotoService
{
    protected $repository;
    protected $transaction;

    protected $component;

    /* @var PhotoInterface */
    protected $photoEntityClass;

    protected $defaultType = 'max';
    protected $sizes;

    private $tmpPath;

    public function __construct(TransactionManager $transaction)
    {
        if (!$this->component) {
            throw new InvalidArgumentException("Необходимо определить тип компонента (board/company/etc...)");
        }
        if (!$this->photoEntityClass) {
            throw new InvalidArgumentException("Не определена фото-сущность (BoardPhotoEntity/etc...");
        }
        if (!$this->sizes || !is_array($this->sizes)) {
            throw new InvalidArgumentException("Не определены размеры фотографий.");
        }
        if (!$this->repository) {
            throw new InvalidArgumentException("Необходимо определить репозиторий.");
        }

        $this->transaction = $transaction;
        $this->tmpPath = Yii::getAlias('@tmp');
    }

    public function savePhotosFromTempFolder($entity, $photos): void
    {
        if (!is_array($photos) || !count($photos)) {
            return;
        }

        $toBasePath = $this->getBaseFolder();
        $dirStart = Yii::getAlias('@frontend/web/') . $toBasePath;

        foreach ($photos as $k => $photo) {
            $toBase = '';
            if (is_file($this->tmpPath . '/' . $photo)) {
                $original = substr($photo, 6);
                $fileName = FileHelper::createFileName($dirStart . '/' . $this->defaultType . '/' . $entity->id . '-' . $original);
                foreach ($this->sizes as $type => $sizes) {
                    if (!copy($this->tmpPath . '/' . $type . '_' . $original, $dirStart . '/' . $type . '/' . $fileName)) {
                        Yii::error('Ошибка копирования файла');
                        throw new \DomainException('Ошибка копирования файла');
                    }
                    if ($type == $this->defaultType) {
                        $toBase = $toBasePath . '/' . $type . '/' . $fileName;
                    }
                }

                $boardPhoto = $this->photoEntityClass::create($entity->id, $toBase, $k);
                $this->repository->save($boardPhoto);
                if (!$entity->main_photo_id) {
                    $entity->main_photo_id = $boardPhoto->id;
                }
            }
        }

        // Удаляем временные файлы
        reset($photos);
        foreach ($photos as $photo) {
            $original = substr($photo, 6);
            foreach ($this->sizes as $type => $sizes) {
                @FileHelper::unlink($this->tmpPath . '/' . $type . '_' . $original);
            }
        }
    }

    public function addPhoto($entity, UploadedFile $photo)
    {
        $basePath = $this->getBaseFolder();
        $dirStart = Yii::getAlias('@frontend/web/') . $basePath;
        $fileName = FileHelper::createFileName($dirStart . '/' . $this->defaultType . '/' . $entity->id . '-' . $photo->name);
        if (!$photo->saveAs($dirStart . '/' . $this->defaultType . '/' . $fileName)) {
            Yii::error('Ошибка загрузки файла');
            throw new \DomainException('Ошибка загрузки файла');
        }

        foreach ($this->sizes as $type => $item) {
            $width = isset($item['width']) && (int) $item['width'] ? (int) $item['width'] : null;
            $height = isset($item['height']) && (int) $item['height'] ? (int) $item['height'] : null;

            Image::resize($dirStart . '/' . $this->defaultType . '/' . $fileName, $width, $height)
                ->save($dirStart . '/' . $type . '/' . $fileName);
        }

        $sort = (int) $entity->getPhotos()->max('sort') + 1;
        $boardPhoto = $this->photoEntityClass::create($entity->id, $basePath . '/' . $this->defaultType . '/' . $fileName, $sort);
        $this->repository->save($boardPhoto);
        if (!$entity->main_photo_id) {
            $entity->main_photo_id = $boardPhoto->id;
        }
    }

    public function movePhoto($entity, $id, $direction): void
    {
        $symbol = $direction == 'up' ? '<' : '>';
        $photo = $this->repository->get($id);
        $swapPhoto = $this->photoEntityClass::find()
            ->where([$symbol, 'sort', $photo->sort])
            ->andWhere(['board_id' => $entity->id])
            ->orderBy(['sort' => $direction == 'up' ? SORT_DESC : SORT_ASC])
            ->one();

        if ($swapPhoto !== null) {
            $oldSort = $photo->sort;
            $photo->sort = $swapPhoto->sort;
            $swapPhoto->sort = $oldSort;
            $this->transaction->wrap(function () use ($entity, $photo, $swapPhoto) {
                $this->repository->save($photo);
                $this->repository->save($swapPhoto);
                $this->updateMainPhoto($entity);
            });
        }
    }

    public function removePhoto($entity, $id): void
    {
        $photo = $this->repository->get($id);
        $this->repository->remove($photo);
        $this->updateMainPhoto($entity);
    }

    private function updateMainPhoto($entity)
    {
        if ($first = $entity->getPhotos()->one()) {
            $entity->updateAttributes(['main_photo_id' => $first->id]);
        } else {
            $entity->updateAttributes(['main_photo_id' => null]);
        }
    }

    private function getBaseFolder()
    {
        $toBasePath = 'i/' . $this->component . '/' . date('Y') . '/' . date('m');

        // Проверяем есть ли папки для сохранения фото формата i/board/YYYY/mm/ТИП, если нет - создаём
        $dirStart = Yii::getAlias('@frontend/web/') . $toBasePath;
        foreach ($this->sizes as $type => $sizes) {
            $folder = $dirStart . '/' . $type;
            if (!is_dir($folder)) {
                FileHelper::createDirectory($folder);
            }
        }
        reset($this->sizes);
        return $toBasePath;
    }
}