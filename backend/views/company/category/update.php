<?php

/* @var $this yii\web\View */
/* @var $category \core\entities\Company\CompanyCategory */
/* @var $model \core\forms\manage\Company\CompanyCategoryForm */
/* @var $tab string */

$this->title = 'Раздел КК #' . $category->id;
$this->params['breadcrumbs'][] = ['label' => 'Разделы КК', 'url' => ['index']];
$this->params['breadcrumbs'][] = 'Редактирование';



?>
<div class="board-update">
    <ul class="nav nav-tabs" role="tablist">
        <li role="presentation"<?= $tab == 'home' ? ' class="active"' : '' ?>><a href="#home" aria-controls="home" role="tab" data-toggle="tab">Раздел</a></li>
        <li role="presentation"<?= $tab == 'geo' ? ' class="active"' : '' ?>><a href="#geo" aria-controls="geo" role="tab" data-toggle="tab">Привязка к региону</a></li>
    </ul>

    <!-- Tab panes -->
    <div class="tab-content">
        <div role="tabpanel" class="tab-pane<?= $tab == 'home' ? ' active' : '' ?>" id="home">
            <?= $this->render('_form', [
                'model' => $model,
            ]) ?>
        </div>
        <div role="tabpanel" class="tab-pane<?= $tab == 'geo' ? ' active' : '' ?>" id="geo">
            <?= $this->render('_regions', [
                'category' => $category
            ]) ?>
        </div>
    </div>
</div>