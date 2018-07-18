<?php

namespace core\useCases\manage\Trade;


use core\components\SettingsManager;
use core\entities\Trade\Trade;
use core\entities\Trade\TradeTag;
use core\entities\Trade\TradeTagAssignment;
use core\entities\Trade\TradeUserCategory;
use core\entities\User\User;
use core\forms\manage\PhotosForm;
use core\forms\manage\Trade\TradeManageForm;
use core\forms\manage\Trade\TradeUserCategoryForm;
use core\helpers\PriceHelper;
use core\repositories\Trade\TradeRepository;
use core\services\TransactionManager;
use Yii;
use yii\helpers\StringHelper;

class TradeManageService
{
    private $repository;
    private $transaction;

    public function __construct(TradeRepository $repository, TransactionManager $transaction)
    {
        $this->repository = $repository;
        $this->transaction = $transaction;
    }

    public function create(TradeManageForm $form): Trade
    {
        $userId = $form->scenario === TradeManageForm::SCENARIO_USER_CREATE
            ? Yii::$app->user->id
            : ($form->userId ?: Yii::$app->user->id);
        $status = $form->scenario === TradeManageForm::SCENARIO_USER_CREATE
            ? (Yii::$app->settings->get(SettingsManager::TRADE_MODERATION) ? Trade::STATUS_ON_PREMODERATION : Trade::STATUS_ACTIVE)
            : Trade::STATUS_ACTIVE;

        $userCategory = TradeUserCategory::findOne($form->categoryId);
        $user = $userId === Yii::$app->user->id ? Yii::$app->user->identity : User::findOne($userId);

        $trade = Trade::create(
            $userId,
            $userCategory->category_id,
            $userCategory->id,
            $user->company->geo_id,
            $form->name,
            $form->metaTitle ?: '',
            $form->metaDescription ?: '',
            $form->metaKeywords ?: '',
            $form->slug,
            $form->code,
            $form->price,
            $form->stock,
            $form->note,
            $form->description,
            $status
        );
        $trade->setWholesales($this->getWholesales($userCategory, $form));

        $this->transaction->wrap(function () use ($form, $trade) {
            $this->repository->save($trade);
            $this->saveTags($trade, $form->tags);
            Yii::createObject(TradePhotoService::class)->savePhotosFromTempFolder($trade, $form->photos);
            $this->repository->save($trade);
        });

        return $trade;
    }

    public function edit(Trade $trade, TradeManageForm $form): void
    {
        $userId = $form->scenario === TradeManageForm::SCENARIO_USER_EDIT
            ? $trade->user_id
            : ($form->userId ?: $trade->user_id);

        $userCategory = TradeUserCategory::findOne($form->categoryId);
        $user = $userId === Yii::$app->user->id ? Yii::$app->user->identity : User::findOne($userId);

        $trade->edit(
            $userId,
            $userCategory->category_id,
            $userCategory->id,
            $user->company->geo_id,
            $form->name,
            $form->metaTitle ?: '',
            $form->metaDescription ?: '',
            $form->metaKeywords ?: '',
            $form->slug,
            $form->code,
            $form->price,
            $form->stock,
            $form->note,
            $form->description
        );
        $trade->setWholesales($this->getWholesales($userCategory, $form));

        $this->transaction->wrap(function () use ($form, $trade) {
            $this->repository->save($trade);
            $this->saveTags($trade, $form->tags);
        });
    }

    private function getWholesales(TradeUserCategory $userCategory, TradeManageForm $form): array
    {
        $wholesales = [];
        if ($userCategory->wholesale && is_array($form->wholeSalePrice)) {
            foreach ($form->wholeSalePrice as $i => $price) {
                if ((float) $price && isset($form->wholeSaleFrom[$i]) && $form->wholeSaleFrom[$i]) {
                    $wholesales[] = ['price' => PriceHelper::optimize($price),'from' => (int) $form->wholeSaleFrom[$i]];
                }
            }
        }
        return $wholesales;
    }

    private function saveTags(Trade $trade, string $tags): void
    {
        $tags = StringHelper::explode($tags, ',', true, true);
        TradeTagAssignment::deleteAll(['trade_id' => $trade->id]);
        foreach ($tags as $tag) {
            if (strlen($tag) > 2) {
                if (!$tagEntity = TradeTag::find()->where(['name' => $tag])->limit(1)->one()) {
                    $tagEntity = TradeTag::create($tag);
                    if (!$tagEntity->save()) {
                        throw new \DomainException('Ошибка при сохранении тега');
                    }
                }
                $assignment = TradeTagAssignment::create($trade->id, $tagEntity->id);
                $this->repository->saveTagAssignment($assignment);
            }
        }
    }

    public function addPhotos($id, PhotosForm $form): void
    {
        $trade = $this->repository->get($id);
        foreach ($form->files as $file) {
            Yii::createObject(TradePhotoService::class)->addPhoto($trade, $file);
        }
        $this->repository->save($trade);
    }

    public function createUserCategory($userId, TradeUserCategoryForm $form): TradeUserCategory
    {
        $userCategory = TradeUserCategory::create($userId, $form->name, $form->categoryId, $form->uomId, $form->currencyId, $form->wholeSale);
        $this->repository->saveUserCategory($userCategory);
        return $userCategory;
    }

    public function editUserCategory(TradeUserCategory $userCategory, TradeUserCategoryForm $form): void
    {
        $userCategory->edit($form->name, $form->categoryId, $form->uomId, $form->currencyId, $form->wholeSale);
        $this->repository->saveUserCategory($userCategory);
    }

    public function massRemove(array $ids, $hardRemove = false): int
    {
        return $this->repository->massRemove($ids, $hardRemove);
    }

    public function remove($id, $safe = true): void
    {
        $trade = $this->repository->get($id);
        if ($safe) {
            $this->repository->safeRemove($trade);
        } else {
            $this->repository->remove($trade);
        }
    }
}