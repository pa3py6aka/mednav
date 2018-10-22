<?php

namespace core\useCases;


use core\components\SettingsManager;
use core\entities\News\News;
use core\entities\News\NewsTag;
use core\entities\News\NewsTagsAssignment;
use core\entities\Company\Company;
use core\entities\StatusesInterface;
use core\forms\News\NewsForm;
use core\forms\manage\PhotosForm;
use core\repositories\News\NewsRepository;
use core\services\TransactionManager;
use core\useCases\manage\News\NewsPhotoService;
use Yii;
use yii\helpers\StringHelper;

class NewsService
{
    private $repository;
    private $transaction;

    public function __construct(NewsRepository $repository, TransactionManager $transaction)
    {
        $this->repository = $repository;
        $this->transaction = $transaction;
    }

    public function create(NewsForm $form): News
    {
        $userId = $form->scenario == NewsForm::SCENARIO_USER_MANAGE
            ? Yii::$app->user->id
            : ($form->user_id ?: Yii::$app->user->id);
        $status = $form->scenario == NewsForm::SCENARIO_USER_MANAGE
            ? (Yii::$app->settings->get(SettingsManager::NEWS_MODERATION) ? News::STATUS_ON_PREMODERATION : News::STATUS_ACTIVE)
            : News::STATUS_ACTIVE;
        $companyId = Company::find()->select('id')->where(['user_id' => $userId])->scalar() ?: null;

        $news = News::create(
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

        $this->transaction->wrap(function () use ($news, $form) {
            $this->repository->save($news);
            $this->saveTags($news, $form->tags ?: '');
            Yii::createObject(NewsPhotoService::class)->savePhotosFromTempFolder($news, $form->photos);
            $this->repository->save($news);
        });

        return $news;
    }

    public function edit($id, NewsForm $form): void
    {
        $article = $this->repository->get($id);
        $userId = $form->scenario == NewsForm::SCENARIO_USER_MANAGE
            ? $article->user_id
            : ($form->user_id ?: $article->user_id);
        $companyId = Company::find()->select('id')->where(['user_id' => $userId])->scalar() ?: null;

        $article->edit(
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
        if ($form->scenario == NewsForm::SCENARIO_USER_MANAGE && Yii::$app->settings->get(SettingsManager::NEWS_MODERATION)) {
            $article->setStatus(StatusesInterface::STATUS_ON_PREMODERATION);
        }

        $this->transaction->wrap(function () use ($article, $form) {
            $this->saveTags($article, $form->tags);
            $this->repository->save($article);
        });
    }

    public function addPhotos($id, PhotosForm $form): void
    {
        $article = $this->repository->get($id);
        foreach ($form->files as $file) {
            Yii::createObject(NewsPhotoService::class)->addPhoto($article, $file);
        }
        $this->repository->save($article);
    }

    private function saveTags(News $article, string $tags): void
    {
        $tags = StringHelper::explode($tags, ',', true, true);
        NewsTagsAssignment::deleteAll(['news_id' => $article->id]);
        foreach ($tags as $tag) {
            if (strlen($tag) > 2) {
                if (!$tagEntity = NewsTag::find()->where(['name' => $tag])->limit(1)->one()) {
                    $tagEntity = NewsTag::create($tag);
                    if (!$tagEntity->save()) {
                        throw new \DomainException('Ошибка при сохранении тега');
                    }
                }
                $assignment = NewsTagsAssignment::create($article->id, $tagEntity->id);
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
            $company = $this->repository->get($id);
            $company->setStatus(News::STATUS_ACTIVE);
            $this->repository->save($company);
        }
    }

    public function massRemove(array $ids, $hardRemove = false): int
    {
        return $this->repository->massRemove($ids, $hardRemove);
    }

    public function remove($id, $safe = true): void
    {
        $company = $this->repository->get($id);
        if ($safe) {
            $this->repository->safeRemove($company);
        } else {
            $this->repository->remove($company);
        }
    }
}