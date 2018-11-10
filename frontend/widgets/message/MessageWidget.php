<?php

namespace frontend\widgets\message;


use core\entities\User\User;
use Yii;
use yii\base\Widget;
use yii\helpers\Url;

class MessageWidget extends Widget
{
    public $buttonType = self::BTN_TYPE_BIG;

    /* @var User */
    public $toUser;

    public $subject;
    public $subjectType = self::SUBJECT_TYPE_SOLID;
    public $btnClass = 'kk-btn-contact';
    public $btnWidth = '100%';

    private $action = ['/user/message/send-message'];

    const BTN_TYPE_BIG = "big";
    const BTN_TYPE_SMALL = "small";
    const SUBJECT_TYPE_SOLID = "solid";
    const SUBJECT_TYPE_INPUT = "input";

    public function run()
    {
        if (Yii::$app->user->id === $this->toUser->id) {
            return "";
        }
        $this->registerAssets();
        $form = new NewMessageForm();
        $form->toId = $this->toUser->id;
        $form->subject = $this->subject;

        return $this->render('message-widget', [
            'buttonType' => $this->buttonType,
            'subjectType' => $this->subjectType,
            'toName' => $this->toUser->getVisibleName(),
            'btnClass' => $this->btnClass,
            'btnWidth' => $this->btnWidth,
            'model' => $form,
        ]);
    }

    public function registerAssets()
    {
        $url = Url::to($this->action);
        $js = <<<JS
$('#ModalMsg').on('click', '.send-message-form-button', function(e) {
  $('#message-form').yiiActiveForm().submit();
});
$('#message-form').on('beforeSubmit', function (e) {
	var data = $(this).serialize(),
	    modal = $('#ModalMsg'),
	    loadBlock = modal.find('.modal-content');
	$.ajax({
        url: '{$url}',
        method: "post",
        dataType: "json",
        data: data,
        beforeSend: function () {
            loadBlock.prepend(Mednav.overlay);
        },
        success: function(data, textStatus, jqXHR) {
            if (data.result === 'success') {
                modal.modal('hide');
                alert("Ваше сообщение успешно отправлено");
            } else {
                alert(data.message);
            }
        },
        error: function () {
            alert("Системная ошибка, сообщите нам о ней ,пожалуйста.");
        },
        complete: function () {
            loadBlock.find('.overlay').remove();
        }
    });
	return false;
});
JS;
        $this->view->registerJs($js);
    }
}