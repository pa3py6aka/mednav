<?php

namespace core\components;


use core\entities\Context;

class ContextBlock
{
    public static $number = 1;

    public static function getBlock($id): string
    {
        if (($block = Context::findOne($id)) && $block->enable) {
            return $block->html;
        }
        return '';
    }

    public static function afterRow()
    {
        if (self::$number == 5) {
            echo self::getBlock(2);
        } else if (self::$number == 10) {
            echo self::getBlock(3);
        } elseif (self::$number == 15) {
            echo self::getBlock(4);
        }
        self::$number++;
    }
}