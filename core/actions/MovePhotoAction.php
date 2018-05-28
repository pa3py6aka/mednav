<?php

namespace core\actions;


use core\access\Rbac;
use core\entities\Board\Board;
use core\useCases\manage\Board\BoardPhotoService;
use Yii;
use yii\base\Action;
use yii\base\InvalidArgumentException;
use yii\db\ActiveRecord;
use yii\web\ForbiddenHttpException;
use yii\web\NotFoundHttpException;

class MovePhotoAction extends Action
{
    /* @var $entityClass ActiveRecord */
    public $entityClass;

    /**
     *  @var $redirectUrl array Для указания ID при редиректе, используйте метку {id}.
     *     Например ['update', 'id' => '{id}', 'tab' => 'photos']
     */
    public $redirectUrl = null;

    public function init()
    {
        if (!$this->entityClass) {
            throw new InvalidArgumentException("Необходимо указать класс AR-модели: `entityClass`");
        }
        $this->redirectUrl = (array) $this->redirectUrl;
        parent::init();
    }

    public function run($entityId, $photoId, $direction)
    {
        $entity = $this->findModel($entityId);

        if (!Yii::$app->user->can(Rbac::PERMISSION_MANAGE, ['user_id' => $entity->author_id])) {
            throw new ForbiddenHttpException("У вас нет прав на это действие");
        }

        try {
            Yii::createObject(BoardPhotoService::class)->movePhoto($entity, $photoId, $direction);
        } catch (\DomainException $e) {
            Yii::$app->session->setFlash('error', $e->getMessage());
        }

        return $this->controller->redirect($this->getRedirectUrl($entity->id));
    }

    private function getRedirectUrl($id): array
    {
        if (!$this->redirectUrl) {
            return ['view', 'id' => $id, 'tab' => 'photos'];
        }
        array_walk($this->redirectUrl, function (&$value, $key) use ($id) {
            if ($value === '{id}') {
                $value = $id;
            }
        });
        return $this->redirectUrl;
    }

    /**
     * @param $entityId
     * @return null|ActiveRecord|Board
     */
    private function findModel($entityId)
    {
        if (!$entity = $this->entityClass::findOne($entityId)) {
            throw new NotFoundHttpException("Сущность не найдена");
        }
        return $entity;
    }
}