<?php

namespace core\forms;


use yii\base\Model;

class SupportMessageForm extends Model
{
    public $subject;
    public $text;

    public function rules()
    {
        return [
            [['subject', 'text'], 'required'],
            ['subject', 'string', 'max' => 255],
            ['text', 'string', 'max' => 20000],
        ];
    }

    public function attributeLabels()
    {
        return [
            'subject' => 'Тема',
            'text' => 'Текст сообщения',
        ];
    }
}