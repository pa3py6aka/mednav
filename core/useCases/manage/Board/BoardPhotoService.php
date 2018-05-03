<?php

namespace core\useCases\manage\Board;


use core\entities\Board\BoardPhoto;
use core\repositories\Board\BoardPhotoRepository;
use Yii;
use core\helpers\FileHelper;

class BoardPhotoService
{
    private $repository;
    private $tmpPath;

    private $folders = ['small', 'big', 'max'];
    private $defaultType = 'max';

    public function __construct(BoardPhotoRepository $repository)
    {
        $this->repository = $repository;
        $this->tmpPath = Yii::getAlias('@tmp');
    }

    public function savePhotos($boardId, $photos): void
    {
        if (!is_array($photos) || !count($photos)) {
            return;
        }

        $toBasePath = 'i/board/' . date('Y') . '/' . date('m');

        // Проверяем есть ли папки для сохранения фото формата i/board/YYYY/mm/ТИП, если нет - создаём
        $dirStart = Yii::getAlias('@frontend/web/') . $toBasePath;
        foreach ($this->folders as $type) {
            $folder = $dirStart . '/' . $type;
            if (!is_dir($folder)) {
                FileHelper::createDirectory($folder);
            }
        }
        reset($this->folders);

        foreach ($photos as $k => $photo) {
            $toBase = '';
            if (is_file($this->tmpPath . '/' . $photo)) {
                $original = substr($photo, 6);
                $fileName = FileHelper::createFileName($dirStart . '/' . $this->defaultType . '/' . $boardId . '-' . $original);
                foreach ($this->folders as $type) {

                    if (!copy($this->tmpPath . '/' . $type . '_' . $original, $dirStart . '/' . $type . '/' . $fileName)) {
                        Yii::error('Ошибка копирования файла');
                        throw new \DomainException('Ошибка копирования файла');
                    }
                    if ($type == $this->defaultType) {
                        $toBase = $toBasePath . '/' . $type . '/' . $fileName;
                    }
                }

                $boardPhoto = BoardPhoto::create($boardId, $toBase, $k);
                $this->repository->save($boardPhoto);
            }
        }

        // Удаляем временные файлы
        reset($photos);
        foreach ($photos as $photo) {
            $original = substr($photo, 6);
            foreach ($this->folders as $type) {
                @unlink($this->tmpPath . '/' . $type . '_' . $original);
            }
        }
    }
}