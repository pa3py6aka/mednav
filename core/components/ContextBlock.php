<?php

namespace core\components;


use core\entities\Context;

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

    public static function afterRow($page, $pageSize): void
    {
        if (!self::$isPaginationAccepted) {
            self::$isPaginationAccepted = true;
            if (!self::$paginationNumeric) {
                self::$number += $page * $pageSize;
            }
        }

        if (self::$number == 6) {
            echo self::getBlock(2);
        } else if (self::$number == 11) {
            echo self::getBlock(3);
        } elseif (self::$number == 16) {
            echo self::getBlock(4);
        }
        self::$number++;
    }

    public static function afterList(): string
    {
        $toShow = [];
        foreach (Context::find()->all() as $block) {
            if (!in_array($block->id, self::$showed)) {
                $toShow[] = self::getBlock($block->id, $block);
            }
        }
        return implode("\n", $toShow);
    }
}