<?php

namespace core\validators;


use yii\validators\RegularExpressionValidator;

class SlugValidator extends RegularExpressionValidator
{
    public $pattern = '#^[A-Za-z0-9_-]*$#s';
    public $message = 'Допустимы только символы латиницы, цифры, знак подчёркивани и дефис';
}