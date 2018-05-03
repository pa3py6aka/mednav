<?php

namespace core\helpers;


use yii\helpers\Inflector;

class FileHelper extends \yii\helpers\FileHelper
{
    public static function createFileName(string $file, $n = 1): string
    {
        $fileName = pathinfo($file, PATHINFO_FILENAME);
        $extension = pathinfo($file, PATHINFO_EXTENSION);
        $path = pathinfo($file, PATHINFO_DIRNAME);

        $num = $n > 1 ? '-' . $n : '';
        $name = substr(Inflector::slug($fileName), 0, 220) . $num . '.' . $extension;
        if (!is_file($path . '/' . $name)) {
            return $name;
        } else {
            return self::createFileName($file, ++$n);
        }
    }
}