<?php

namespace core\useCases\manage\Board;


use core\components\SettingsManager;
use core\entities\Board\Board;
use core\entities\Board\BoardPhoto;
use core\repositories\Board\BoardPhotoRepository;
use core\services\TransactionManager;
use Yii;
use core\helpers\FileHelper;
use yii\imagine\Image;
use yii\web\UploadedFile;

class BoardPhotoService
{
    private $repository;
    private $transaction;
    private $tmpPath;

    private $defaultType = 'max';
    private $sizes;

    public function __construct(BoardPhotoRepository $repository, TransactionManager $transaction)
    {
        $this->repository = $repository;
        $this->transaction = $transaction;
        $this->tmpPath = Yii::getAlias('@tmp');
        $this->sizes = [
            'small' => ['width' => Yii::$app->settings->get(SettingsManager::BOARD_SMALL_SIZE)],
            'big' => ['width' => Yii::$app->settings->get(SettingsManager::BOARD_BIG_SIZE)],
            'max' => ['width' => Yii::$app->settings->get(SettingsManager::BOARD_MAX_SIZE)],
        ];
    }

    public function savePhotosFromTempFolder(Board $board, $photos): void
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
                $fileName = FileHelper::createFileName($dirStart . '/' . $this->defaultType . '/' . $board->id . '-' . $original);
                foreach ($this->sizes as $type => $sizes) {
                    if (!copy($this->tmpPath . '/' . $type . '_' . $original, $dirStart . '/' . $type . '/' . $fileName)) {
                        Yii::error('Ошибка копирования файла');
                        throw new \DomainException('Ошибка копирования файла');
                    }
                    if ($type == $this->defaultType) {
                        $toBase = $toBasePath . '/' . $type . '/' . $fileName;
                    }
                }

                $boardPhoto = BoardPhoto::create($board->id, $toBase, $k);
                $this->repository->save($boardPhoto);
                if (!$board->main_photo_id) {
                    $board->main_photo_id = $boardPhoto->id;
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

    public function addPhoto(Board $board, UploadedFile $photo)
    {
        $basePath = $this->getBaseFolder();
        $dirStart = Yii::getAlias('@frontend/web/') . $basePath;
        $fileName = FileHelper::createFileName($dirStart . '/' . $this->defaultType . '/' . $board->id . '-' . $photo->name);
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

        $sort = (int) $board->getPhotos()->max('sort') + 1;
        $boardPhoto = BoardPhoto::create($board->id, $basePath . '/' . $this->defaultType . '/' . $fileName, $sort);
        $this->repository->save($boardPhoto);
        if (!$board->main_photo_id) {
            $board->main_photo_id = $boardPhoto->id;
        }
    }

    public function movePhoto(Board $board, $id, $direction): void
    {
        $symbol = $direction == 'up' ? '<' : '>';
        $photo = $this->repository->get($id);
        $swapPhoto = BoardPhoto::find()
            ->where([$symbol, 'sort', $photo->sort])
            ->andWhere(['board_id' => $board->id])
            ->orderBy(['sort' => $direction == 'up' ? SORT_DESC : SORT_ASC])
            ->one();

        if ($swapPhoto !== null) {
            $oldSort = $photo->sort;
            $photo->sort = $swapPhoto->sort;
            $swapPhoto->sort = $oldSort;
            $this->transaction->wrap(function () use ($board, $photo, $swapPhoto) {
                $this->repository->save($photo);
                $this->repository->save($swapPhoto);
                $this->updateMainPhoto($board);
            });
        }
    }

    public function removePhoto(Board $board, $id): void
    {
        $photo = $this->repository->get($id);
        $this->repository->remove($photo);
        $this->updateMainPhoto($board);
    }

    private function updateMainPhoto(Board $board)
    {
        if ($first = $board->getPhotos()->one()) {
            $board->updateAttributes(['main_photo_id' => $first->id]);
        } else {
            $board->updateAttributes(['main_photo_id' => null]);
        }
    }

    private function getBaseFolder()
    {
        $toBasePath = 'i/board/' . date('Y') . '/' . date('m');

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