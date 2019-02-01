<?php

namespace core\components;


use core\entities\Context;
use yii\data\Pagination;

class ContextBlock
{
    public static $number = 1;
    public static $showed = [];
    public static $isPaginationAccepted = false;
    public static $paginationNumeric = false;

    public static function getBlock($id, $block = null, $paginationNumeric = null): string
    {
        if ($paginationNumeric !== null) {
            self::$paginationNumeric = $paginationNumeric;
        }

        if (($block || ($block = Context::findOne($id))) && $block->enable) {
            self::$showed[] = $id;
            return $block->html;
        }
        return '';
    }

    public static function afterRow(Pagination $pagination, $totalCount): void
    {
        if (!self::$isPaginationAccepted) {
            self::$isPaginationAccepted = true;
            if (!self::$paginationNumeric) {
                self::$number += $pagination->page * $pagination->pageSize;
            }
        }


        if (self::$number === 5 && $totalCount > 5) {
            echo self::getBlock(2);
        } else if (self::$number === 10 && $totalCount > 10) {
            echo self::getBlock(3);
        } else if (self::$number === 15 && $totalCount > 15) {
            echo self::getBlock(4);
        } else if ($totalCount > 16) {
            // Вывод пятого блока в конце списка
            if ($pagination->pageSize > 15) {
                if (!self::$paginationNumeric) {
                    if ((self::$number - $pagination->page * $pagination->pageSize) === $pagination->pageSize/* && $pagination->page === 0*/) {
                        echo self::getBlock(5);
                    }
                } else {
                    if (self::$number === $pagination->pageSize) {
                        echo self::getBlock(5);
                    }
                }
            } else {
                if (!self::$paginationNumeric) {
                    $page = ceil(16 / $pagination->pageSize);
                    $last = $page * $pagination->pageSize;
                    if ($last > $totalCount) {
                        $last = $totalCount;
                    }
                    if (self::$number == $last) {
                        echo self::getBlock(5);
                    }
                }
            }
        }
        self::$number++;
    }
}