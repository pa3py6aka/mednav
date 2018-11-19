<?php

namespace core\useCases;


use core\components\Settings;
use core\entities\Expo\Expo;
use core\entities\Expo\ExpoTag;
use core\entities\Expo\ExpoTagsAssignment;
use core\entities\Company\Company;
use core\entities\StatusesInterface;
use core\forms\Expo\ExpoForm;
use core\forms\manage\PhotosForm;
use core\repositories\Expo\ExpoRepository;
use core\services\TransactionManager;
use core\useCases\manage\Expo\ExpoPhotoService;
use Yii;
use yii\helpers\StringHelper;

class ExpoService
{
    private $repository;
    private $transaction;

    public function __construct(ExpoRepository $repository, TransactionManager $transaction)
    {
        $this->repository = $repository;
        $this->transaction = $transaction;
    }

    public function create(ExpoForm $form): Expo
    {
        $userId = $form->scenario == ExpoForm::SCENARIO_USER_MANAGE
            ? Yii::$app->user->id
            : ($form->user_id ?: Yii::$app->user->id);
        $status = $form->scenario == ExpoForm::SCENARIO_USER_MANAGE
            ? (Yii::$app->settings->get(Settings::EXPO_MODERATION) ? Expo::STATUS_ON_PREMODERATION : Expo::STATUS_ACTIVE)
            : Expo::STATUS_ACTIVE;
        $companyId = Company::find()->select('id')->where(['user_id' => $userId])->scalar() ?: null;

        $expo = Expo::create(
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
            $status,
            $form->showDates,
            strtotime($form->startDate),
            strtotime($form->endDate),
            $form->city
        );

        $this->transaction->wrap(function () use ($expo, $form) {
            $this->repository->save($expo);
            $this->saveTags($expo, $form->tags ?: '');
            Yii::createObject(ExpoPhotoService::class)->savePhotosFromTempFolder($expo, $form->photos);
            $this->repository->save($expo);
        });

        return $expo;
    }

    public function edit($id, ExpoForm $form): void
    {
        $expo = $this->repository->get($id);
        $userId = $form->scenario == ExpoForm::SCENARIO_USER_MANAGE
            ? $expo->user_id
            : ($form->user_id ?: $expo->user_id);
        $companyId = Company::find()->select('id')->where(['user_id' => $userId])->scalar() ?: null;

        $expo->edit(
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
            $form->indirectLinks,
            $form->showDates,
            Yii::$app->formatter->asTimestamp($form->startDate),
            Yii::$app->formatter->asTimestamp($form->endDate),
            $form->city
        );
        if ($form->scenario == ExpoForm::SCENARIO_USER_MANAGE && Yii::$app->settings->get(Settings::EXPO_MODERATION)) {
            $expo->setStatus(StatusesInterface::STATUS_ON_PREMODERATION);
        }

        $this->transaction->wrap(function () use ($expo, $form) {
            $this->saveTags($expo, $form->tags);
            $this->repository->save($expo);
        });
    }

    public function addPhotos($id, PhotosForm $form): void
    {
        $expo = $this->repository->get($id);
        foreach ($form->files as $file) {
            Yii::createObject(ExpoPhotoService::class)->addPhoto($expo, $file);
        }
        $this->repository->save($expo);
    }

    private function saveTags(Expo $expo, string $tags): void
    {
        $tags = StringHelper::explode($tags, ',', true, true);
        ExpoTagsAssignment::deleteAll(['exposition_id' => $expo->id]);
        foreach ($tags as $tag) {
            if (strlen($tag) > 2) {
                if (!$tagEntity = ExpoTag::find()->where(['name' => $tag])->limit(1)->one()) {
                    $tagEntity = ExpoTag::create($tag);
                    if (!$tagEntity->save()) {
                        throw new \DomainException('Ошибка при сохранении тега');
                    }
                }
                $assignment = ExpoTagsAssignment::create($expo->id, $tagEntity->id);
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
            $expo = $this->repository->get($id);
            $expo->setStatus(Expo::STATUS_ACTIVE);
            $this->repository->save($expo);
        }
    }

    public function massRemove(array $ids, $hardRemove = false): int
    {
        return $this->repository->massRemove($ids, $hardRemove);
    }

    public function remove($id, $safe = true): void
    {
        $expo = $this->repository->get($id);
        if ($safe) {
            $this->repository->safeRemove($expo);
        } else {
            $this->repository->remove($expo);
        }
    }
}