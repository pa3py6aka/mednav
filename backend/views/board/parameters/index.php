<?php

use core\entities\Board\BoardParameter;
use yii\bootstrap\Html;
use yii\grid\ActionColumn;
use yii\grid\GridView;

/* @var $this \yii\web\View */
/* @var $provider \yii\data\ActiveDataProvider */

$this->title = 'Настройки Доски Объявлений';

?>
<div class="box box-primary">
    <div class="box-body table-responsive">
        <?= $this->render('@backend/views/board/settings/_tabs', ['tab' => 'parameters']) ?>

        <div class="tab-content">
            <div role="tabpanel" class="tab-pane active" id="parameters">
                <br>
                <div class="form-group">
                    <?= Html::a('Добавить', ['/board/parameters/create'], ['class' => 'btn btn-success btn-flat']) ?>
                </div>
                <?= GridView::widget([
                    'dataProvider' => $provider,
                    'layout' => "{items}\n{pager}",
                    'columns' => [
                        [
                            'attribute' => 'name',
                            'value' => function (BoardParameter $parameter) {
                                return Html::a($parameter->name, ['view', 'id' => $parameter->id]);
                            },
                            'format' => 'raw',
                        ],
                        [
                            'attribute' => 'type',
                            'value' => function (BoardParameter $parameter) {
                                return $parameter->getTypeName();
                            }
                        ],
                        'active:boolean',
                        ['class' => ActionColumn::class]
                    ]
                ]) ?>
            </div>
        </div>
    </div>
</div>
