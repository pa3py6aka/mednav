<?php

namespace backend\controllers\blocks;


use core\components\ContentBlocks\ContentBlockForm;
use core\components\ContentBlocks\ContentBlockService;
use core\components\ContentBlocks\ContentBlocksWidget;
use core\entities\ContentBlock;
use Yii;
use yii\web\Controller;
use yii\web\Response;

class ContentsController extends Controller
{
    public function actionIndex()
    {
        return $this->render('index');
    }

    public function actionAddBlock($module, $place, $page)
    {
        $form = new ContentBlockForm();
        $form->module = $module;
        $form->place = $place;
        $form->page = $page;

        if ($form->load(Yii::$app->request->post()) && $form->validate()) {
            (new ContentBlockService())->create($form);
            return $this->redirect(['index']);
        }

        return $this->render('add-block', [
            'model' => $form,
        ]);
    }

    public function actionEdit($id)
    {
        $block = ContentBlock::findOne($id);
        $form = new ContentBlockForm($block);

        if ($form->load(Yii::$app->request->post()) && $form->validate()) {
            (new ContentBlockService())->edit($id, $form);
            return $this->redirect(['index']);
        }

        return $this->render('edit', [
            'model' => $form,
        ]);
    }

    public function actionDelete($id)
    {
        ContentBlock::findOne($id)->delete();
        return $this->redirect(['index']);
    }

    public function actionGetCategories()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        return [
            'result' => 'success',
            'items' => ContentBlocksWidget::getCategoriesFor(Yii::$app->request->post('module'))
        ];
    }

    public function actionSwitchStatus()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $id = (int) Yii::$app->request->post('id');
        $block = ContentBlock::findOne($id);
        $block->enable = (int) !$block->enable;
        Yii::debug($block->toArray(), "lknr;ltvnlrtnvrl;tnvrtv");
        $block->save();
        return [
            'result' => 'success',
            'status' => $block->enable,
        ];
    }
}