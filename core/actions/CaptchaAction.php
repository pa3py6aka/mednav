<?php

namespace core\actions;


class CaptchaAction extends \yii\captcha\CaptchaAction
{
    protected function generateVerifyCode()
    {
        if ($this->minLength > $this->maxLength) {
            $this->maxLength = $this->minLength;
        }
        if ($this->minLength < 3) {
            $this->minLength = 3;
        }
        if ($this->maxLength > 20) {
            $this->maxLength = 20;
        }
        $length = mt_rand($this->minLength, $this->maxLength);

        $letters = '012345678901234567890123456789';
        $vowels = '0123456789';
        $code = '';
        for ($i = 0; $i < $length; ++$i) {
            if ($i % 2 && mt_rand(0, 10) > 2 || !($i % 2) && mt_rand(0, 10) > 9) {
                $code .= $vowels[mt_rand(0, 9)];
            } else {
                $code .= $letters[mt_rand(0, 29)];
            }
        }

        return $code;
    }
}