<?php
/* @var $model \core\components\ContentBlocks\ContentBlockForm */

use core\entities\ContentBlock;
use yii\bootstrap\ActiveForm;
use core\components\ContentBlocks\ContentBlocksWidget;
use yii\helpers\Html;

?>
<?php $form = ActiveForm::begin([
    'id' => 'block-form',
    'layout' => 'horizontal',
    'fieldConfig' => [
        'template' => "{label}\n{beginWrapper}\n{input}\n{hint}\n{error}\n{endWrapper}",
        'horizontalCssClasses' => [
            'label' => 'col-sm-1',
            'offset' => 'col-sm-offset-1',
            'wrapper' => 'col-sm-11',
            'error' => '',
            'hint' => '',
        ],
    ],
]) ?>
<?= $form->field($model, 'page')->hiddenInput()->label(false) ?>
<div class="box-body">

    <?php //= $form->field($model, 'module')->dropDownList(ContentBlock::modulesArray()) ?>
    <?php // if ($model->module == ContentBlock::MODULE_MAIN_PAGE): ?>

        <?php //= Html::activeDropDownList($model, "htmlModule", ContentBlock::modulesArray(false), ['class' => 'form-control', 'style' => 'display:inline;width: 200px;']) ?>
    <?php // endif; ?>

    <?= $form->field($model, 'place')->dropDownList(ContentBlocksWidget::getPlacesFor($model->module), ['disabled' => true]) ?>

    <?= $form->field($model, 'type')
        ->dropDownList(ContentBlock::typesArray($model->page == ContentBlock::PAGE_VIEW && $model->module != ContentBlock::MODULE_MAIN_PAGE), ['id' => 'type-selector']) ?>

    <?= $form->field($model, 'forModule')
        ->dropDownList(ContentBlock::modulesArray(false), ['id' => 'for-module-selector', 'disabled' => $model->type == ContentBlock::TYPE_HTML])
        ->hint('С какого модуля брать контент') ?>

    <?= $form->field($model, 'name')->textInput() ?>

    <?= $form->field($model, 'showTitle')->checkbox() ?>

    <?= $form->field($model, 'view', ['wrapperOptions' => ['style' => 'display:inline-block']])
        ->inline(true)->radioList(ContentBlock::viewsArray($model->place != ContentBlock::PLACE_MAIN)) ?>

    <?= $form->field($model, 'items')->input('number') ?>

    <div class="form-group<?= $model->type == ContentBlock::TYPE_HTML ? '' : ' hidden' ?>" id="html-group">
        <label class="col-sm-1">Html</label>
        <div class="col-sm-11" id="html-blocks">
            <?php foreach ($model->html as $n => $html): ?>
                <div class="html-row" data-num="<?= $n ?>">
                    <button type="button" class="btn btn-xs btn-danger pull-right del-html-btn"><span class="fa fa-remove"></span></button>
                    <?php if ($model->module != ContentBlock::MODULE_MAIN_PAGE) : ?>
                        <?= Html::activeDropDownList($model, "htmlCategories[{$n}]", ContentBlocksWidget::getCategoriesFor($model->module), ['class' => 'form-control', 'multiple' => 'multiple', 'id' => 'cats-selector-' . $n]) ?>
                    <?php endif; ?>
                    <?= Html::activeTextarea($model, "html[{$n}]", ['rows' => 5, 'class' => 'form-control', 'placeholder' => 'Содержимое блока...']) ?>
                </div>
            <?php endforeach; ?>
        </div>
        <div class="help-block col-sm-offset-1 col-sm-11"><a href="#" id="add-html-btn">Добавить блок</a></div>
    </div>

</div>
<div class="box-footer">
    <button type="submit" class="btn btn-success">Сохранить</button>
</div>
<?php ActiveForm::end() ?>

<div id="templates" class="hidden">
    <div class="html-row" data-num="">
        <button type="button" class="btn btn-xs btn-danger pull-right del-html-btn"><span class="fa fa-remove"></span></button>
        <?php if ($model->module != ContentBlock::MODULE_MAIN_PAGE && $model->page == ContentBlock::PAGE_LISTING) : ?>
            <?= Html::activeDropDownList($model, 'htmlCategories[]', ContentBlocksWidget::getCategoriesFor($model->module), ['class' => 'form-control', 'multiple' => 'multiple', 'id' => 'cats-selector-']) ?>
        <?php endif; ?>
        <?= Html::activeTextarea($model, 'html[]', ['rows' => 5, 'class' => 'form-control', 'placeholder' => 'Содержимое блока...']) ?>
    </div>
</div>

<script type="text/javascript">
    window.addEventListener('load', function (ev) {
        var overlay = '<div class="overlay"><i class="fa fa-refresh fa-spin"></i></div>';

        $('#block-form').find('select[name*="htmlCategories"]').each(function (k, selector) {
            $(selector).multiselect(multiSelectConfig);
        });

        $(document).on('click', '.del-html-btn', function () {
            $(this).parent().remove();
        });

        $('#add-html-btn').on('click', function (e) {
            e.preventDefault();
            var $row = $('#templates').find('.html-row').clone(),
                n = parseInt($('#html-blocks').find('.html-row').last().attr('data-num')) + 1;
            if (!n) {
                n = 1;
            }
            $row.attr('data-num', n)
                .find('select[name*=htmlCategories]')
                .attr('name', 'ContentBlockForm[htmlCategories]['  + n + '][]')
                .attr('id', 'cats-selector-' + n)
                .end()
                .find('textarea[name*=html]').attr('name', 'ContentBlockForm[html]['  + n + ']');
            $row.find('select[name*="htmlCategories"]').multiselect(multiSelectConfig);

            $row.find('select[name*=htmlModules]')
                .attr('name', 'ContentBlockForm[htmlModules]['  + n + ']')
                .attr('id', 'module-selector-' + n);

            $('#html-blocks').append($row);
        });

        $('#type-selector').on('change', function () {
            if ($(this).val() !== '<?= ContentBlock::TYPE_HTML ?>') {
                $('#for-module-selector').prop('disabled', false);
                $('#html-group').addClass('hidden');
                $('textarea[name*=html]').prop('disabled', true);
                $('#add-html-btn').hide();
            } else {
                $('#for-module-selector').prop('disabled', true);
                $('#html-group').removeClass('hidden');
                $('textarea[name*=html]').prop('disabled', false);
                $('#add-html-btn').show();
            }
        });

        $(document).on('change', 'select[name*=htmlModules]', function () {
            var module = $(this).val();
            var $selector = $(this).parent().find('select[name*=htmlCategories]');
            $.ajax({
                url: '/blocks/contents/get-categories',
                method: "post",
                dataType: "json",
                data: {module:module},
                beforeSend: function () {
                    $('.box').prepend(overlay);
                },
                success: function(data, textStatus, jqXHR) {
                    if (data.result === 'success') {
                        $selector.multiselect('destroy');
                        $selector.html('');
                        $.each(data.items, function (id, name) {
                            $selector.append('<option value="' + id + '">' + name + '</option>');
                        });
                        $selector.multiselect(multiSelectConfig);
                    } else {
                        alert('Ошибка загрузки данных');
                    }
                },
                complete: function () {
                    $('.box').find('.overlay').remove();
                }
            });
        });

        $('#block-form').on('submit', function() {
            var $form = $(this);
            $form.find('select[name*="htmlCategories"]').each(function (k, selector) {
                var $selector = $(selector),
                    values = $selector.val();
                $.each(values, function (k, value) {
                    var $input = $('<input type="hidden" name="' + $selector.attr('name') + '" value="' + value + '">');
                    $form.append($input);
                });
                $selector.remove();
            });
            return true;
        });

        var multiSelectConfig = {
            includeSelectAllOption: true,
            selectAllValue: 'Выбрать все',
            preventInputChangeEvent: true,
            buttonText: function(options, select) {
                if (options.length === 0) {
                    return 'Выберите разделы...';
                }
                else if (options.length > 1) {
                    return 'Выбрано: ' + options.length;
                }
                else {
                    var labels = [];
                    options.each(function() {
                        if ($(this).attr('label') !== undefined) {
                            labels.push($(this).attr('label'));
                        }
                        else {
                            labels.push($(this).html());
                        }
                    });
                    return labels.join(', ') + '';
                }
            }
        };
    });
</script>
