<?php

namespace core\validators;


use yii\validators\RegularExpressionValidator;

class SlugValidator extends RegularExpressionValidator
{
    public $pattern = '#^[A-Za-z0-9_-]*$#s';
    public $message = 'Только [A-Za-z0-9_-] символы допустимы.';
}