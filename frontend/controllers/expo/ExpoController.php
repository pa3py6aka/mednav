<?php

namespace frontend\controllers\expo;


use core\readModels\ExpoReadRepository;
use core\repositories\Expo\ExpoCategoryRepository;
use Yii;
use yii\base\Module;
use yii\web\Controller;

class ExpoController extends Controller
{
    private $readRepository;
    private $categoryRepository;

    public function __construct
    (
        $id,
        Module $module,
        ExpoCategoryRepository $categoryRepository,
        ExpoReadRepository $readRepository,
        array $config = [])
    {
        parent::__construct($id, $module, $config);
        $this->categoryRepository = $categoryRepository;
        $this->readRepository = $readRepository;
    }

    public function actionList($category = null)
    {
        $category = $category ? $this->categoryRepository->getBySlug($category) : null;
        $provider = $this->readRepository->getAllBy($category);

        // Вывод выставок по клику "показать ещё"
        if (Yii::$app->request->get('showMore')) {
            return $this->asJson([
                'result' => 'success',
                'html' => $this->renderPartial('card-items-block', [
                    'provider' => $provider,
                ]),
                'nextPageUrl' => $provider->getPagination()->pageCount > $provider->getPagination()->page + 1
                                    ? $provider->getPagination()->createUrl($provider->getPagination()->page + 1)
                                    : false
            ]);
        }

        return $this->render('list', [
            'category' => $category,
            'provider' => $provider,
        ]);
    }

    public function actionView($id, $slug)
    {
        $expo = $this->readRepository->getByIdAndSlug($id, $slug);
        $expo->updateCounters(['views' => 1]);

        return $this->render('view', [
            'expo' => $expo
        ]);
    }
}