<?php

namespace core\repositories\Company;


use core\entities\Company\Company;
use core\entities\Company\CompanyCategoryAssignment;
use core\entities\Company\CompanyTagsAssignment;
use yii\web\NotFoundHttpException;

class CompanyRepository
{
    public function get($id): Company
    {
        if (!$company = Company::findOne($id)) {
            throw new NotFoundHttpException('Компания не найдена.');
        }
        return $company;
    }

    public function findTagAssignment($companyId, $tagId): ?CompanyTagsAssignment
    {
        return CompanyTagsAssignment::find()->where(['company_id' => $companyId, 'tag_id' => $tagId])->limit(1)->one();
    }

    public function saveTagAssignment(CompanyTagsAssignment $assignment): void
    {
        if (!$assignment->save()) {
            throw new \RuntimeException('Ошибка записи в базу.');
        }
    }

    public function saveCategoryAssignment(CompanyCategoryAssignment $assignment): void
    {
        if (!$assignment->save()) {
            throw new \RuntimeException('Ошибка записи в базу.');
        }
    }

    public function save(Company $company): void
    {
        if (!$company->save()) {
            throw new \RuntimeException('Ошибка записи в базу.');
        }
    }

    public function safeRemove(Company $company): void
    {
        $company->setStatus(Company::STATUS_DELETED);
        if (!$company->save()) {
            throw new \RuntimeException('Ошибка при удалении из базы.');
        }
    }

    public function remove(Company $company): void
    {
        if (!$company->delete()) {
            throw new \RuntimeException('Ошибка при удалении из базы.');
        }
    }

    public function massRemove(array $ids, $hardRemove = false): int
    {
        if ($hardRemove) {
            return Company::deleteAll(['id' => $ids]);
        } else {
            return Company::updateAll(['status' => Company::STATUS_DELETED], ['id' => $ids]);
        }
    }
}