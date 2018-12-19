<?php

use core\helpers\BoardHelper;

/* @var $widget \frontend\widgets\RegionsModalWidget */
/* @var $countries array */

$n = 1;

?>
<div id="modalRegion" class="modal fade"<?= $widget->type === 'delivery' && $widget->deliveryId ? ' data-delivery-id="' .$widget->deliveryId . '"'
    : ($widget->type === 'delivery' && $widget->countryId ? ' data-country-id="' .$widget->countryId . '"' : '') ?>>
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <?php /*if ($widget->type === 'delivery'): ?>
                    <button type="button" class="btn btn-primary pull-right" data-dismiss="modal" aria-hidden="true">Выбрать</button>
                <?php endif;*/ ?>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h4>Выбор региона</h4>
            </div>
            <div class="modal-body">
                <ul class="nav nav-tabs">
                    <?php foreach ($countries as $country): ?>
                        <li<?= $n == 1 ? ' class="active"' : '' ?>><a data-toggle="tab" href="#panel<?= $n ?>"><b><?= $country['country']->name ?></b></a></li>
                        <?php $n++; ?>
                    <?php endforeach; ?>
                    <?php if ($widget->type && $widget->type !== 'delivery'): ?>
                        <div class="nav-tabs-tools">
                            <?= $widget->link() ?>
                        </div>
                    <?php endif; ?>
                </ul>
                <div class="tab-content">
                    <?php $n = 1; ?>
                    <?php reset($countries); ?>
                    <?php foreach ($countries as $country): ?>
                    <div id="panel<?= $n ?>" class="tab-pane fade<?= $n == 1 ? ' in active' : '' ?>">
                        <p></p>
                        <div class="row">
                            <div class="col-md-12">
                                <p class="geo-pop">
                                    <?php foreach ($country['popular'] as $popularCountry): ?>
                                        <?= $widget->link($popularCountry) ?>
                                    <?php endforeach; ?>
                                </p>
                            </div>
                        </div>
                        <?php $num = 1; ?>

                        <?php foreach ($country['regions'] as $region): ?>
                            <?= !(($num % 2) == 0) ? '<div class="row">' : '' ?>
                            <div class="col-md-6 col-sm-12 col-xs-12">
                                <div class="css-treeview">
                                    <ul>
                                        <li>
                                            <input type="checkbox" id="item-<?= $num ?>" />
                                            <label for="item-<?= $num ?>"><img src="<?= Yii::$app->params['frontendHostInfo'] ?>/img/plus-icon.png"></label> <?= $widget->link($region['region']) ?>
                                            <ul>
                                                <?php foreach ($region['cities'] as $city): ?>
                                                    <li><?= $widget->link($city) ?></li>
                                                <?php endforeach; ?>
                                            </ul>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                            <?= (($num % 2) == 0) ? '</div>' : '' ?>
                            <?php $num++ ?>
                        <?php endforeach; ?>

                        <?= (($num % 2) == 0) ? '</div>' : '' ?>
                    </div>
                    <?php $n++ ?>
                    <?php endforeach; ?>
                </div>
            </div>
            <?php if ($widget->type === 'delivery'): ?>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" data-dismiss="modal" aria-hidden="true">Выбрать</button>
            </div>
            <?php endif; ?>
        </div>
    </div>
</div>
