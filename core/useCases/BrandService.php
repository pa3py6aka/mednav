<?php

namespace core\useCases;


use core\components\SettingsManager;
use core\entities\Brand\Brand;
use core\entities\Brand\BrandTag;
use core\entities\Brand\BrandTagsAssignment;
use core\entities\Company\Company;
use core\entities\StatusesInterface;
use core\forms\Brand\BrandForm;
use core\forms\manage\PhotosForm;
use core\repositories\Brand\BrandRepository;
use core\services\TransactionManager;
use core\useCases\manage\Brand\BrandPhotoService;
use Yii;
use yii\helpers\StringHelper;

class BrandService
{
    private $repository;
    private $transaction;

    public function __construct(BrandRepository $repository, TransactionManager $transaction)
    {
        $this->repository = $repository;
        $this->transaction = $transaction;
    }

    public function create(BrandForm $form): Brand
    {
        $userId = $form->scenario == BrandForm::SCENARIO_USER_MANAGE
            ? Yii::$app->user->id
            : ($form->user_id ?: Yii::$app->user->id);
        $status = $form->scenario == BrandForm::SCENARIO_USER_MANAGE
            ? (Yii::$app->settings->get(SettingsManager::BRANDS_MODERATION) ? Brand::STATUS_ON_PREMODERATION : Brand::STATUS_ACTIVE)
            : Brand::STATUS_ACTIVE;
        $companyId = Company::find()->select('id')->where(['user_id' => $userId])->scalar() ?: null;

        $brand = Brand::create(
            $userId,
            $companyId,
            $form->categoryId,
            trim($form->title),
            trim($form->metaDescription),
            trim($form->metaKeywords),
            trim($form->name),
            $form->slug,
            trim($form->intro),
            trim($form->fullText),
            $form->indirectLinks === null ? 1 : $form->indirectLinks,
            $status
        );

        $this->transaction->wrap(function () use ($brand, $form) {
            $this->repository->save($brand);
            $this->saveTags($brand, $form->tags ?: '');
            Yii::createObject(BrandPhotoService::class)->savePhotosFromTempFolder($brand, $form->photos);
            $this->repository->save($brand);
        });

        return $brand;
    }

    public function edit($id, BrandForm $form): void
    {
        $brand = $this->repository->get($id);
        $userId = $form->scenario == BrandForm::SCENARIO_USER_MANAGE
            ? $brand->user_id
            : ($form->user_id ?: $brand->user_id);
        $companyId = Company::find()->select('id')->where(['user_id' => $userId])->scalar() ?: null;

        $brand->edit(
            $userId,
            $companyId,
            $form->categoryId,
            trim($form->title),
            trim($form->metaDescription),
            trim($form->metaKeywords),
            trim($form->name),
            $form->slug,
            trim($form->intro),
            trim($form->fullText),
            $form->indirectLinks
        );
        if ($form->scenario == BrandForm::SCENARIO_USER_MANAGE && Yii::$app->settings->get(SettingsManager::BRANDS_MODERATION)) {
            $brand->setStatus(StatusesInterface::STATUS_ON_PREMODERATION);
        }

        $this->transaction->wrap(function () use ($brand, $form) {
            $this->saveTags($brand, $form->tags);
            $this->repository->save($brand);
        });
    }

    public function addPhotos($id, PhotosForm $form): void
    {
        $brand = $this->repository->get($id);
        foreach ($form->files as $file) {
            Yii::createObject(BrandPhotoService::class)->addPhoto($brand, $file);
        }
        $this->repository->save($brand);
    }

    private function saveTags(Brand $brand, string $tags): void
    {
        $tags = StringHelper::explode($tags, ',', true, true);
        BrandTagsAssignment::deleteAll(['brand_id' => $brand->id]);
        foreach ($tags as $tag) {
            if (strlen($tag) > 2) {
                if (!$tagEntity = BrandTag::find()->where(['name' => $tag])->limit(1)->one()) {
                    $tagEntity = BrandTag::create($tag);
                    if (!$tagEntity->save()) {
                        throw new \DomainException('Ошибка при сохранении тега');
                    }
                }
                $assignment = BrandTagsAssignment::create($brand->id, $tagEntity->id);
                $this->repository->saveTagAssignment($assignment);
            }
        }
    }

    public function publish($ids): void
    {
        if (!is_array($ids)) {
            $ids = [$ids];
        }
        foreach ($ids as $id) {
            $brand = $this->repository->get($id);
            $brand->setStatus(Brand::STATUS_ACTIVE);
            $this->repository->save($brand);
        }
    }

    public function massRemove(array $ids, $hardRemove = false): int
    {
        return $this->repository->massRemove($ids, $hardRemove);
    }

    public function remove($id, $safe = true): void
    {
        $brand = $this->repository->get($id);
        if ($safe) {
            $this->repository->safeRemove($brand);
        } else {
            $this->repository->remove($brand);
        }
    }
}