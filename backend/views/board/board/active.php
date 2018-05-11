<?php

use yii\helpers\Html;
use yii\grid\GridView;
use core\entities\Board\Board;
use yii\helpers\ArrayHelper;
use core\entities\Board\BoardParameterOption;

/* @var $this yii\web\View */
/* @var $searchModel backend\forms\BoardSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Объявления';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="board-index box box-primary">
    <div class="box-header with-border">
        <?= Html::a('Добавить объявление', ['create'], ['class' => 'btn btn-success btn-flat']) ?>
        <?= Html::button('Удалить выбранные', ['class' => 'extend-btn btn btn-danger btn-flat']) ?>
    </div>
    <div class="box-body table-responsive">
        <?= $this->render('_tabs', ['tab' => 'active']) ?>

        <?= GridView::widget([
            'id' => 'grid',
            'dataProvider' => $dataProvider,
            'filterModel' => $searchModel,
            'layout' => "{items}\n{summary}\n{pager}",
            'columns' => [
                //['class' => 'yii\grid\SerialColumn'],
                ['class' => \yii\grid\CheckboxColumn::class],
                //'id',
                'name',
                [
                    'attribute' => 'typeParameter',
                    'label' => 'Тип',
                    'value' => function (Board $board) {
                        return $board->getDefaultType();
                    },
                    'filter' => ArrayHelper::map(BoardParameterOption::find()->where(['parameter_id' => 1])->asArray()->all(), 'id', 'name'),
                ],
                [
                    'attribute' => 'author',
                    'value' => function (Board $board) {
                        return Html::a($board->author->email, ['/user/view', 'id' => $board->author_id]);
                    },
                    'format' => 'raw',
                ],

                //'slug',
                [
                    'attribute' => 'category_id',
                    'value' => function (Board $board) {
                        return Html::a($board->category->name, ['/board/category/update', 'id' => $board->category_id]);
                    },
                    'format' => 'raw',
                ],
                // 'title',
                // 'description:ntext',
                // 'keywords:ntext',
                // 'note',
                // 'price',
                // 'currency',
                // 'price_from',
                // 'full_text:ntext',
                // 'term_id',
                // 'geo_id',
                // 'status',
                'active_until:datetime',
                // 'created_at',
                // 'updated_at',

                ['class' => 'yii\grid\ActionColumn'],
            ],
        ]); ?>
    </div>
    <div class="box-footer">
        <?= Html::button('Удалить выбранные', ['class' => 'extend-btn btn btn-danger btn-flat']) ?>
    </div>
</div>

<script>
    window.addEventListener('load', function () { 
        $('.extend-btn').on('click', function () {
            var checked = $('#grid').yiiGridView('getSelectedRows');
            if (checked.length < 1) {
                alert("Ничего не выбрано");
            }
            var $form = $('<form action="" method="post"></form>');
            $form.append('<input type="hidden" name="' + yii.getCsrfParam() + '" value="' + yii.getCsrfToken() + '">');
            $.each(checked, function (k, id) {
                $form.append('<input type="hidden" name="ids[]" value="' + id + '">');
            });
            $('body').prepend($form);
            $form.submit();
        });
    });
</script>
