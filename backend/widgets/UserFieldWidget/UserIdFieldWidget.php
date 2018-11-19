<?php

namespace backend\widgets\UserFieldWidget;


use backend\widgets\UserFieldWidget\dependencies\UserCategoryDependency;
use core\entities\User\User;
use yii\helpers\Html;
use yii\widgets\InputWidget;

class UserIdFieldWidget extends InputWidget
{
    public const INPUT_ID = 'input-field-widget-user_id';

    public $dependencies;

    public function init()
    {
        parent::init();
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
                    <input id="<?= self::INPUT_ID ?>" type="number" class="form-control" name="<?= Html::getInputName($this->model, $this->attribute) ?>" value="<?= $this->value ?>">
                    <span class="input-group-btn">
                    <button id="<?= self::INPUT_ID . '-go' ?>" type="button" class="btn btn-primary btn-flat"><i class="fa fa-search"></i></button>
                </span>
                </div>
            </div>
            <div id="<?= self::INPUT_ID . '-link' ?>" style="display:inline-flex;vertical-align:middle;margin-left:20px;">
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
        $inputId = self::INPUT_ID;
        $dependencies = (new UserCategoryDependency())->getJs();
        $js = <<<JS
$(document).on('click', '#{$inputId}-go', function() {
  var id = $('#{$inputId}').val();
  var linkBlock = $('#{$inputId}-link');
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
            var geoIdInput = $('#form-geo-id');
            console.log(typeof data.geo);
            if (data.geo && typeof data.geo.id !== 'undefined' && geoIdInput.length) {
                geoIdInput.val(data.geo.id);
                $('#region-select-link').text(data.geo.name);
            }
        } else {
            linkBlock.html(data.message);
        }
        {$dependencies}
    },
    complete: function () {
        
    }
  });
});
JS;
        $this->view->registerJs($js);
    }
}