<?php

namespace core\actions;


use core\access\Rbac;
use core\entities\UserOwnerInterface;
use core\useCases\BasePhotoService;
use Yii;
use yii\base\Action;
use yii\db\ActiveRecord;
use yii\web\ForbiddenHttpException;
use yii\web\NotFoundHttpException;

class DeletePhotoAction extends Action
{
    /* @var $entityClass ActiveRecord */
    public $entityClass;

    /* @var $serviceClass BasePhotoService */
    public $serviceClass;

    /**
     *  @var $redirectUrl array Для указания ID при редиректе, используйте метку {id}.
     *     Например ['update', 'id' => '{id}', 'tab' => 'photos']
     */
    public $redirectUrl = null;

    public function run($id, $photo_id)
    {
        $entity = $this->findModel($id);

        if (!Yii::$app->user->can(Rbac::PERMISSION_MANAGE, ['user_id' => $entity->getOwnerId()])) {
            throw new ForbiddenHttpException("У вас нет прав на это действие");
        }

        try {
            Yii::createObject($this->serviceClass)->removePhoto($entity, $photo_id);
            Yii::$app->session->setFlash('info', 'Фотография удалена');
        } catch (\DomainException $e) {
            Yii::$app->session->setFlash('error', $e->getMessage());
        }

        return $this->controller->redirect($this->getRedirectUrl($entity->getPrimaryKey()));
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
     * @return null|ActiveRecord|UserOwnerInterface
     */
    private function findModel($entityId)
    {
        if (!$entity = $this->entityClass::findOne($entityId)) {
            throw new NotFoundHttpException("Сущность не найдена");
        }
        return $entity;
    }
}