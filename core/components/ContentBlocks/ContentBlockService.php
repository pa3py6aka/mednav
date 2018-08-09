<?php

namespace core\components\ContentBlocks;


use core\entities\ContentBlock;
use yii\helpers\Json;

class ContentBlockService
{
    public function create(ContentBlockForm $form): ContentBlock
    {
        $block = new ContentBlock();
        $block->type = $form->type;
        $block->name = $form->name ?: 'Без названия';
        $block->show_title = $form->showTitle;
        $block->enable = 1;
        $block->view = $form->view;
        $block->items = $form->items;
        $block->module = $form->module;
        $block->for_module = $form->forModule;
        $block->place = $form->place;
        $block->page = $form->page;
        $block->sort = ContentBlock::find()->where(['module' => $form->module, 'place' => $form->place, 'page' => $form->page])->max('sort') + 1;

        $htmls = [];
        $htmlCategories = [];
        foreach ($form->html as $n => $html) {
            $htmls[$n] = $html;
            $htmlCategories[$n] = isset($form->htmlCategories[$n]) ? $form->htmlCategories[$n] : [];
        }
        $block->html = Json::encode($htmls);
        $block->htmlCategories = Json::encode($htmlCategories);

        $block->save(false);
        return $block;
    }

    public function edit($id, ContentBlockForm $form): void
    {
        $block = ContentBlock::findOne($id);
        $block->type = $form->type;
        $block->name = $form->name ?: 'Без названия';
        $block->show_title = $form->showTitle;
        $block->enable = 1;
        $block->view = $form->view;
        $block->items = $form->items;
        $block->module = $form->module;
        $block->place = $form->place;
        $block->page = $form->page;
        $block->for_module = $form->forModule;

        $htmls = [];
        $htmlCategories = [];
        foreach ($form->html as $n => $html) {
            $htmls[$n] = $html;
            $htmlCategories[$n] = isset($form->htmlCategories[$n]) ? $form->htmlCategories[$n] : [];
        }
        $block->html = Json::encode($htmls);
        $block->htmlCategories = Json::encode($htmlCategories);

        $block->save(false);
    }
}