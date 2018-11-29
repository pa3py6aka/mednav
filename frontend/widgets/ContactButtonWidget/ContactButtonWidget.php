<?php

namespace frontend\widgets\ContactButtonWidget;


use yii\base\InvalidArgumentException;
use yii\base\Widget;

class ContactButtonWidget extends Widget
{
    public $contactId;

    public function init()
    {
        parent::init();
        if (!$this->contactId) {
            throw new InvalidArgumentException('Отсутствует обязательный параметр - $contactId');
        }
    }

    public function run(): string
    {
        if (\Yii::$app->user->id !== $this->contactId) {
            $this->registerJs();
            return $this->render('button', ['contactId' => $this->contactId]);
        }
        return '';
    }

    public function registerJs(): void
    {
        $js = <<<JS
$(document).on('click', '[data-btn=contact-widget]', function(e) {
  e.preventDefault();
  if (UserIsGuest) {
      window.location.href = '/login';
      return;
  }
  
  var button = $(this),
      contactId = button.attr('data-contact-id');
  $.ajax({
    url: '/user/message/add-contact',
    method: "post",
    dataType: "json",
    data: {contactId:contactId},
    beforeSend: function () {
        button.prepend(Mednav.overlay);
    },
    success: function(data, textStatus, jqXHR) {
        if (data.result === 'success') {
            alert(data.message, 'Уведомление')
        } else if (data.result === 'error') {
            alert(data.message, 'Уведомление');
        } else {
            alert('Ошибка');
        }
    },
    complete: function () {
        button.find('.overlay').remove();
    }
  });
});
JS;
        $this->view->registerJs($js);
    }
}