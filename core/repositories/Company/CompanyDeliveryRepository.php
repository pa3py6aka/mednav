<?php

namespace core\repositories\Company;


use core\entities\Company\CompanyDelivery;
use core\repositories\NotFoundException;

class CompanyDeliveryRepository
{
    public function get($id): CompanyDelivery
    {
        if (!$companyDelivery = CompanyDelivery::findOne($id)) {
            throw new NotFoundException('Сущность не найден.');
        }
        return $companyDelivery;
    }

    public function findBy($companyId, $deliveryId): ?CompanyDelivery
    {
        return CompanyDelivery::find()
            ->where(['company_id' => $companyId, 'delivery_id' => $deliveryId])
            ->one();
    }

    public function clearByCompany($companyId): void
    {
        CompanyDelivery::deleteAll(['company_id' => $companyId]);
    }

    public function save(CompanyDelivery $companyDelivery)
    {
        if (!$companyDelivery->save()) {
            throw new \RuntimeException('Ошибка записи в базу.');
        }
    }

    public function remove(CompanyDelivery $companyDelivery)
    {
        if (!$companyDelivery->delete()) {
            throw new \RuntimeException('Ошибка при удалении из базы.');
        }
    }
}