<?php

namespace core\useCases\manage\Board;


use core\components\SettingsManager;
use core\entities\Board\Board;
use core\entities\Board\BoardPhoto;
use core\repositories\Board\BoardPhotoRepository;
use core\services\TransactionManager;
use core\useCases\BasePhotoService;
use Yii;
use core\helpers\FileHelper;
use yii\imagine\Image;
use yii\web\UploadedFile;

class BoardPhotoService extends BasePhotoService
{
    public $component = 'board';
    public $photoEntityClass = BoardPhoto::class;

    public function __construct(BoardPhotoRepository $repository, TransactionManager $transaction)
    {
        $this->repository = $repository;
        $this->sizes = [
            'small' => ['width' => Yii::$app->settings->get(SettingsManager::BOARD_SMALL_SIZE)],
            'big' => ['width' => Yii::$app->settings->get(SettingsManager::BOARD_BIG_SIZE)],
            'max' => ['width' => Yii::$app->settings->get(SettingsManager::BOARD_MAX_SIZE)],
        ];

        parent::__construct($transaction);
    }
}