<?php

namespace core\components\TreeManager;


use yii\base\Action;
use yii\base\InvalidArgumentException;
use yii\db\ActiveRecord;

class TreeManageActions extends Action
{
    /* @var $entityClass ActiveRecord */
    public $entityClass;

    public $url;

    private $entity;

    public function run()
    {
        $this->entity = $this->entityClass::findOne(\Yii::$app->request->get('id'));
        switch (\Yii::$app->request->get('act', 'load')) {
            case 'load':
                return $this->load();
            case 'over':
                return $this->moveTo();
            case 'before':
                return $this->insertBefore();
            case 'after':
                return $this->insertAfter();
        }
        throw new InvalidArgumentException("Экшин не задан");
    }

    private function load()
    {
        return TreeManagerWidget::getSourceData($this->entity->children, $this->url);
    }

    private function moveTo()
    {
        $to = $this->entityClass::findOne(\Yii::$app->request->get('to'));
        return $this->entity->appendTo($to)->save();
    }

    private function insertBefore()
    {
        $to = $this->entityClass::findOne(\Yii::$app->request->get('to'));
        return $this->entity->insertBefore($to)->save();
    }

    private function insertAfter()
    {
        $to = $this->entityClass::findOne(\Yii::$app->request->get('to'));
        return $this->entity->insertAfter($to)->save();
    }
}