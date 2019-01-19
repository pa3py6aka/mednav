<?php

namespace core\entities;


use yii\helpers\ArrayHelper;

/**
 * Trait StatusesTrait
 * Используется вместе с StatusesInterface
 *
 * @property int $status [tinyint(3)]
 *
 * @property string $statusName
 * @property int $prev_status
 *
 * @see \core\entities\StatusesInterface
 */
trait StatusesTrait
{
    public function isOnModeration(): bool
    {
        return $this->status === self::STATUS_ON_PREMODERATION;
    }

    public function isDeleted(): bool
    {
        return $this->status === self::STATUS_DELETED;
    }

    public function isActive(): bool
    {
        return $this->status === self::STATUS_ACTIVE;
    }

    public static function getStatusesArray(): array
    {
        return [
            self::STATUS_DELETED => 'Удалён',
            self::STATUS_ON_PREMODERATION => 'На премодерации',
            self::STATUS_ACTIVE => 'Активен',
        ];
    }

    public function getStatusName(): string
    {
        return ArrayHelper::getValue(self::getStatusesArray(), $this->status);
    }
}