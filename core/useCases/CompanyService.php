<?php

namespace core\useCases;


use core\components\SettingsManager;
use core\entities\Company\Company;
use core\entities\Company\CompanyCategory;
use core\entities\Company\CompanyCategoryAssignment;
use core\entities\Company\CompanyTag;
use core\entities\Company\CompanyTagsAssignment;
use core\forms\Company\CompanyForm;
use core\helpers\MarkHelper;
use core\repositories\Company\CompanyCategoryRepository;
use core\repositories\Company\CompanyRepository;
use core\services\TransactionManager;
use core\useCases\manage\Company\CompanyPhotoService;
use Yii;
use yii\base\Exception;
use yii\helpers\FileHelper;
use yii\helpers\StringHelper;
use yii\imagine\Image;
use yii\web\UploadedFile;

class CompanyService
{
    private $repository;
    private $transaction;

    public function __construct(CompanyRepository $repository, TransactionManager $transaction)
    {
        $this->repository = $repository;
        $this->transaction = $transaction;
    }

    public function create(CompanyForm $form): Company
    {
        $userId = $form->scenario == CompanyForm::SCENARIO_USER_MANAGE
            ? Yii::$app->user->id
            : ($form->user_id ?: Yii::$app->user->id);
        $status = $form->scenario == CompanyForm::SCENARIO_USER_MANAGE
            ? (Yii::$app->settings->get(SettingsManager::COMPANY_MODERATION) ? Company::STATUS_ON_PREMODERATION : Company::STATUS_ACTIVE)
            : Company::STATUS_ACTIVE;

        $company = Company::create(
            $userId,
            trim($form->form),
            trim($form->name),
            trim($form->site),
            $form->geoId,
            trim($form->address),
            $form->phones,
            trim($form->fax),
            $form->email,
            trim($form->info),
            trim($form->title),
            trim($form->shortDescription),
            trim($form->description),
            $status,
            $form->slug
        );

        $this->transaction->wrap(function () use ($company, $form) {
            $this->repository->save($company);
            $this->saveCategories($company, $form->categories);
            $this->saveTags($company, $form->tags);
            $this->saveLogo($company, $form->logo);
            Yii::createObject(CompanyPhotoService::class)->savePhotosFromTempFolder($company, $form->photos);
            $this->repository->save($company);
        });

        return $company;
    }

    public function edit(CompanyForm $form): void
    {

    }

    private function saveLogo(Company $company, $file): void
    {
        if (!$file instanceof UploadedFile) {
            return;
        }
        $name = $company->id . "-" . time() . "." . $file->extension;
        if (!$file->saveAs($company->logoPath() . '/' . $name)) {
            Yii::error("Ошибка сохранения логотипа компании.");
            throw new Exception("Ошибка сохранения логотипа компании.");
        }
        if ($company->logo && is_file($company->logoPath() . '/' . $company->logo) && $company->logo != $name) {
            FileHelper::unlink($company->logoPath() . '/' . $company->logo);
        }
        $company->logo = $name;
    }

    private function saveTags(Company $company, string $tags): void
    {
        $tags = StringHelper::explode($tags, ',', true, true);
        CompanyTagsAssignment::deleteAll(['company_id' => $company->id]);
        foreach ($tags as $tag) {
            if (strlen($tag) > 2) {
                if (!$tagEntity = CompanyTag::find()->where(['name' => $tag])->limit(1)->one()) {
                    $tagEntity = CompanyTag::create($tag);
                    if (!$tagEntity->save()) {
                        throw new \DomainException('Ошибка при сохранении тега');
                    }
                }
                //if (!$assignment = $this->repository->findTagAssignment($company->id, $tagEntity->id)) {
                    $assignment = CompanyTagsAssignment::create($company->id, $tagEntity->id);
                    $this->repository->saveTagAssignment($assignment);
                //}
            }
        }
    }

    private function saveCategories(Company $company, $categoryIds): void
    {
        CompanyCategoryAssignment::deleteAll(['company_id' => $company->id]);
        foreach ($categoryIds as $categoryId) {
            $assignment = CompanyCategoryAssignment::create($company->id, $categoryId);
            $this->repository->saveCategoryAssignment($assignment);
        }
    }
}