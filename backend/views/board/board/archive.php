<?php

use yii\helpers\Html;
use yii\grid\GridView;
use core\entities\Board\Board;
use yii\helpers\ArrayHelper;
use core\entities\Board\BoardParameterOption;
use core\helpers\PaginationHelper;
use yii\grid\CheckboxColumn;
use core\entities\User\User;
use core\helpers\BoardHelper;
use core\helpers\HtmlHelper;

/* @var $this yii\web\View */
/* @var $searchModel backend\forms\BoardSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Объявления';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="board-index box box-primary">
    <div class="box-header with-border">
        <?= Html::a('Добавить объявление', ['create'], ['class' => 'btn btn-success btn-flat']) ?>
        <?= HtmlHelper::actionButtonForSelected('Продлить выбранные', 'extend', 'primary') ?>
        <?= HtmlHelper::actionButtonForSelected('Удалить выбранные', 'remove', 'danger') ?>

        <div class="box-tools">
            <?= PaginationHelper::pageSizeSelector($dataProvider->pagination) ?>
        </div>
    </div>
    <div class="box-body table-responsive">
        <?= $this->render('_tabs', ['tab' => 'archive']) ?>

        <?= GridView::widget([
            'id' => 'grid',
            'dataProvider' => $dataProvider,
            'filterModel' => $searchModel,
            'layout' => "{items}\n{summary}\n{pager}",
            'columns' => [
                ['class' => CheckboxColumn::class],
                [
                    'attribute' => 'name',
                    'value' => function (Board $board) {
                        return Html::a($board->name, ['/board/board/view', 'id' => $board->id]);
                    },
                    'format' => 'raw',
                ],
                [
                    'attribute' => 'typeParameter',
                    'label' => 'Тип',
                    'value' => function (Board $board) {
                        return $board->getDefaultType();
                    },
                    'filter' => ArrayHelper::map(BoardParameterOption::find()->where(['parameter_id' => 1])->asArray()->all(), 'id', 'name'),
                ],
                ['class' => \core\grid\ExtendColumn::class],
                [
                    'attribute' => 'author',
                    'label' => 'Пользователь',
                    'value' => function (Board $board) {
                        return $board->author_id . ' ' . Html::a($board->author->getVisibleName(), ['/user/view', 'id' => $board->author_id]);
                    },
                    'format' => 'raw',
                ],
                ['class' => \core\grid\UserProfileColumn::class],
                ['class' => \core\grid\CategoryColumn::class],
                'created_at:datetime:Размещено',
                ['class' => \yii\grid\ActionColumn::class, 'template' => '{update} {delete}'],
            ],
        ]); ?>
    </div>
    <div class="box-footer">
        <?= HtmlHelper::actionButtonForSelected('Продлить выбранные', 'extend', 'primary') ?>
        <?= HtmlHelper::actionButtonForSelected('Удалить выбранные', 'remove', 'danger') ?>
    </div>
</div>
