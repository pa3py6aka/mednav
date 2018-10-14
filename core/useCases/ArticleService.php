<?php

namespace core\useCases;


use core\components\SettingsManager;
use core\entities\Article\Article;
use core\entities\Article\ArticleTag;
use core\entities\Article\ArticleTagsAssignment;
use core\entities\Company\Company;
use core\entities\StatusesInterface;
use core\forms\Article\ArticleForm;
use core\forms\manage\PhotosForm;
use core\repositories\Article\ArticleRepository;
use core\services\TransactionManager;
use core\useCases\manage\Article\ArticlePhotoService;
use Yii;
use yii\helpers\StringHelper;

class ArticleService
{
    private $repository;
    private $transaction;

    public function __construct(ArticleRepository $repository, TransactionManager $transaction)
    {
        $this->repository = $repository;
        $this->transaction = $transaction;
    }

    public function create(ArticleForm $form): Article
    {
        $userId = $form->scenario == ArticleForm::SCENARIO_USER_MANAGE
            ? Yii::$app->user->id
            : ($form->user_id ?: Yii::$app->user->id);
        $status = $form->scenario == ArticleForm::SCENARIO_USER_MANAGE
            ? (Yii::$app->settings->get(SettingsManager::ARTICLE_MODERATION) ? Article::STATUS_ON_PREMODERATION : Article::STATUS_ACTIVE)
            : Article::STATUS_ACTIVE;
        $companyId = Company::find()->select('id')->where(['user_id' => $userId])->scalar() ?: null;

        $article = Article::create(
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

        $this->transaction->wrap(function () use ($article, $form) {
            $this->repository->save($article);
            $this->saveTags($article, $form->tags ?: '');
            Yii::createObject(ArticlePhotoService::class)->savePhotosFromTempFolder($article, $form->photos);
            $this->repository->save($article);
        });

        return $article;
    }

    public function edit($id, ArticleForm $form): void
    {
        $article = $this->repository->get($id);
        $userId = $form->scenario == ArticleForm::SCENARIO_USER_MANAGE
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
        if ($form->scenario == ArticleForm::SCENARIO_USER_MANAGE && Yii::$app->settings->get(SettingsManager::ARTICLE_MODERATION)) {
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
            Yii::createObject(ArticlePhotoService::class)->addPhoto($article, $file);
        }
        $this->repository->save($article);
    }

    private function saveTags(Article $article, string $tags): void
    {
        $tags = StringHelper::explode($tags, ',', true, true);
        ArticleTagsAssignment::deleteAll(['article_id' => $article->id]);
        foreach ($tags as $tag) {
            if (strlen($tag) > 2) {
                if (!$tagEntity = ArticleTag::find()->where(['name' => $tag])->limit(1)->one()) {
                    $tagEntity = ArticleTag::create($tag);
                    if (!$tagEntity->save()) {
                        throw new \DomainException('Ошибка при сохранении тега');
                    }
                }
                $assignment = ArticleTagsAssignment::create($article->id, $tagEntity->id);
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
            $company->setStatus(Article::STATUS_ACTIVE);
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