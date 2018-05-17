<?php

namespace backend\widgets;


use core\entities\User\User;
use yii\helpers\Html;
use yii\widgets\InputWidget;

class UserIdFieldWidget extends InputWidget
{
    private $_inputId;

    public function init()
    {
        parent::init();
        $this->_inputId = Html::getInputId($this->model, $this->attribute);
        $this->registerJs();
        $this->value = $this->model->{$this->attribute};
    }

    public function run()
    {
        parent::run();
        ?>
        <div class="container-fluid no-padding">
            <div style="width: 145px;display:inline-flex;vertical-align:middle;">
                <div class="input-group input-group-sm">
                    <input id="<?= $this->_inputId ?>" type="number" class="form-control" name="<?= Html::getInputName($this->model, $this->attribute) ?>" value="<?= $this->value ?>">
                    <span class="input-group-btn">
                    <button id="<?= $this->_inputId . '-go' ?>" type="button" class="btn btn-info btn-flat"><i class="fa fa-search"></i></button>
                </span>
                </div>
            </div>
            <div id="<?= $this->_inputId . '-link' ?>" style="display:inline-flex;vertical-align:middle;margin-left:20px;">
                <?= $this->getUserLink() ?>
            </div>
        </div>
        <?php
    }

    private function getUserLink(): string
    {
        if ($this->value) {
            if ($user = User::findOne($this->value)) {
                return Html::a($user->getVisibleName(), ['/user/view', 'id' => $user->id], ['target' => '_blank']);
            } else {
                return "Пользователь не найден";
            }
        }
        return "";
    }

    private function registerJs()
    {
        $js = <<<JS
$(document).on('click', '#{$this->_inputId}-go', function() {
  var id = $('#{$this->_inputId}').val();
  var linkBlock = $('#{$this->_inputId}-link');
  $.ajax({
    url: '/user/get-user',
    method: "post",
    dataType: "json",
    data: {id: id},
    beforeSend: function () {
        
    },
    success: function(data, textStatus, jqXHR) {
        if (data.result === 'success') {
            linkBlock.html('<a href="' + data.url + '" target="_blank">' + data.name + '</a>');
        } else {
            linkBlock.html(data.message);
        }
    },
    complete: function () {
        
    }
  });
});
JS;
        $this->view->registerJs($js);
    }
}