<?php

use core\entities\Board\BoardParameter;
use yii\bootstrap\Html;
use yii\grid\ActionColumn;
use yii\grid\GridView;
use yii\helpers\Url;
use yii\widgets\DetailView;

/* @var $this \yii\web\View */
/* @var $parameter BoardParameter */
/* @var $optionsProvider \yii\data\ActiveDataProvider */

$this->title = 'Настройки Доски Объявлений';

?>
<div class="box box-primary">
    <div class="box-body table-responsive">
        <?= $this->render('@backend/views/board/settings/_tabs', ['tab' => 'parameters']) ?>

        <div class="tab-content">
            <div role="tabpanel" class="tab-pane active" id="parameters">
                <br>
                <div class="col-md-6">
                    <div class="form-group">
                        <?= Html::a('Редактировать', ['/board/parameters/update', 'id' => $parameter->id], ['class' => 'btn btn-success btn-flat']) ?>
                        <?= Html::a('Удалить', ['/board/parameters/delete', 'id' => $parameter->id], ['class' => 'btn btn-danger btn-flat', 'data' => [
                            'confirm' => 'Вы уверены?',
                            'method' => 'post',
                        ]]) ?>
                    </div>
                    <?= DetailView::widget([
                        'model' => $parameter,
                        'attributes' => [
                            'id',
                            'name',
                            [
                                'attribute' => 'type',
                                'value' => function (BoardParameter $parameter) {
                                    return $parameter->getTypeName();
                                }
                            ],
                            'active:boolean',
                        ]
                    ]) ?>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <?= Html::a('Добавить опцию', ['/board/parameters/option-create', 'id' => $parameter->id], ['class' => 'btn btn-success btn-flat']) ?>
                    </div>
                    <?= GridView::widget([
                        'dataProvider' => $optionsProvider,
                        'layout' => '{items}',
                        'columns' => [
                            'name',
                            'slug',
                            [
                                'class' => ActionColumn::class,
                                'template' => '{update} {delete}',
                                'urlCreator' => function ($action, $model, $key, $index) {
                                    return Url::to(['/board/parameters/option-' . $action, 'id' => $key]);
                                }
                            ]
                        ],
                    ]) ?>
                </div>
            </div>
        </div>
    </div>
</div>
