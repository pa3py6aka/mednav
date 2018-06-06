<?php

namespace core\helpers;


class Pluralize
{
    /** Склонение существительных с числительными
     * @param int $n число
     * @param string $form1 Единственная форма: 1 секунда
     * @param string $form2 Двойственная форма: 2 секунды
     * @param string $form5 Множественная форма: 5 секунд
     * @param bool $withoutDigit Вернуть результат без числа
     * @return string Правильная форма
     */
    public static function get($n, $form1, $form2, $form5, $withoutDigit = false) {
        $total = $n;
        $n = abs($n) % 100;
        $n1 = $n % 10;
        $rightForm = $form5;

        if ($n > 10 && $n < 20) {
            $rightForm = $form5;
        }
        if ($n1 > 1 && $n1 < 5) {
            $rightForm = $form2;
        }
        if ($n1 == 1) {
            $rightForm = $form1;
        }

        return $withoutDigit ? $rightForm : $total . ' ' . $rightForm;
    }
}