<?php

namespace core\useCases\manage\Trade;


use core\components\SettingsManager;
use core\entities\Trade\TradePhoto;
use core\repositories\Trade\TradePhotoRepository;
use core\services\TransactionManager;
use core\useCases\BasePhotoService;
use Yii;

class TradePhotoService extends BasePhotoService
{
    public $component = 'trade';
    public $photoEntityClass = TradePhoto::class;

    public function __construct(TradePhotoRepository $repository, TransactionManager $transaction)
    {
        $this->repository = $repository;
        $this->sizes = [
            'small' => ['width' => Yii::$app->settings->get(SettingsManager::TRADE_SMALL_SIZE)],
            'big' => ['width' => Yii::$app->settings->get(SettingsManager::TRADE_BIG_SIZE)],
            'max' => ['width' => Yii::$app->settings->get(SettingsManager::TRADE_MAX_SIZE)],
        ];

        parent::__construct($transaction);
    }
}