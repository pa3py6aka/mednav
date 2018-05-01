<?php

namespace backend\widgets;


use core\entities\Geo;
use yii\bootstrap\Widget;
use yii\data\ActiveDataProvider;
use yii\grid\ActionColumn;
use yii\grid\GridView;
use yii\helpers\Html;

class RegionsAttachWidget extends Widget
{
    /* @var array Массив с айдишниками привязанных регионов */
    public $attachedRegions;

    public $entityId;

    public function run()
    {
        $provider = new ActiveDataProvider([
            'query' => Geo::find()->notRoot(),
            'pagination' => false,
        ]);

        return GridView::widget([
            'dataProvider' => $provider,
            'layout' => "{items}\n{pager}",
            'columns' => [
                [
                    'label' => 'Название',
                    'attribute' => 'name',
                    'format' => 'raw',
                    'value' => function (Geo $geo) {
                        $indent = ($geo->depth > 1 ? str_repeat('&nbsp; &nbsp;', $geo->depth -1) . ' ' : '');
                        $checked = in_array($geo->id, $this->attachedRegions);
                        $checkbox = Html::checkbox('rId[' . $geo->id . ']', $checked, ['value' => $geo->id]) . ' ';

                        return $indent . $checkbox . $geo->name;
                    }
                ],
                ['class' => ActionColumn::class, 'template' => '{edit}', 'buttons' => [
                    'edit' => function ($url, $model, $key) {
                        return Html::a('<i class="glyphicon glyphicon-pencil"></i>', '#', [
                            'class' => 'region-settings-btn',
                            'data' => [
                                'geo-id' => $model->id,
                                'entity-id' => $this->entityId,
                                'geo-name' => $model->name,
                            ]
                        ]);
                    }
                ]]
            ]
        ]);
    }
}