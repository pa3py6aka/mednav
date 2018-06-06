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
                <?php $lastId = 0; ?>
                <?php foreach ($categories as $id => $category): ?>
                    <?php $isNew = $category['depth'] == 0 && $id != $lastId ?>
                    <?php if ($isNew): ?>
                        <div class="col-sm-6">
                    <?php endif; ?>

                        <?= $category['checkbox'] ?>

                    <?php if ($isNew): ?>
                        </div>
                    <?php endif; ?>
                    <?php $lastId = $id; ?>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</div>
<!-- // categories select modal -->