<?php

namespace core\helpers;


use core\entities\Board\Board;
use core\entities\StatusesInterface;
use yii\bootstrap\Html;

class StatusHelper
{
    public static function statusBadge($status, $name): string
    {
        switch ($status) {
            case StatusesInterface::STATUS_DELETED:
                $color = 'gray';
                break;
            case StatusesInterface::STATUS_ON_PREMODERATION:
            case Board::STATUS_ON_MODERATION:
                $color = 'red';
                break;
            case Board::STATUS_NOT_ACTIVE:
                $color = 'black';
                break;
            case StatusesInterface::STATUS_ACTIVE:
                $color = 'green';
                break;
            case Board::STATUS_ARCHIVE:
                $color = 'light-blue';
                break;
            default: $color = 'yellow';
        }

        return Html::tag('span', $name, ['class' => 'badge bg-' . $color]);
    }
}