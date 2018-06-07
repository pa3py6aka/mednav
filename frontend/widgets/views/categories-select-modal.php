<?php

/* @var $widget \frontend\widgets\RegionsModalWidget */
/* @var $categories array */

?>
<!-- categories select modal -->
<div id="caterigoriesSelectModal" class="modal fade">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h4>Выбор разделов</h4>
            </div>
            <div class="modal-body">
                <div class="row">
                <?php $lastId = 0; ?>
                <?php foreach ($categories as $id => $category): ?>
                    <?php $isNew = $category['depth'] == 1 && $id != $lastId ?>
                    <?php if ($isNew && $lastId > 0): ?>
                        </div>
                    <?php endif; ?>
                    <?php if ($isNew): ?>
                        <div class="col-sm-6">
                    <?php endif; ?>

                        <?= $category['checkbox'] ?>

                    <?php $lastId = $id; ?>
                <?php endforeach; ?>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Отмена</button>
                <button type="button" class="btn btn-primary" id="caterigoriesSelectModalSubmit">Сохранить</button>
            </div>
        </div>
    </div>
</div>
<!-- // categories select modal -->