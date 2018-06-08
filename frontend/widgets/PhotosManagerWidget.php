<?php

namespace frontend\widgets;


use core\entities\PhotoTrait;
use core\forms\manage\PhotosForm;
use yii\base\Widget;

class PhotosManagerWidget extends Widget
{
    /* @var PhotoTrait[] */
    public $photos;

    public $entityId;

    public function run()
    {
        $photosForm = new PhotosForm();
        if ($photosForm->load(\Yii::$app->request->post())) {
            $photosForm->validate();
        }

        return $this->render('photos-manager-view', [
            'photos' => $this->photos,
            'entityId' => $this->entityId,
            'photosForm' => $photosForm,
        ]);
    }
}