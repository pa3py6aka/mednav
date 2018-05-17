<?php

namespace frontend\controllers\board;


use core\readModels\Board\BoardReadRepository;
use core\repositories\Board\BoardCategoryRepository;
use core\repositories\GeoRepository;
use Yii;
use yii\base\Module;
use yii\web\Controller;

class BoardController extends Controller
{
    private $readRepository;
    private $geoRepository;
    private $categoryRepository;

    public function __construct
    (
        $id,
        Module $module,
        GeoRepository $geoRepository,
        BoardCategoryRepository $categoryRepository,
        BoardReadRepository $readRepository,
        array $config = [])
    {
        parent::__construct($id, $module, $config);
        $this->geoRepository = $geoRepository;
        $this->categoryRepository = $categoryRepository;
        $this->readRepository = $readRepository;
    }

    public function actionIndex($category = null, $region = null)
    {
        $geo = $region && $region != 'all' ? $this->geoRepository->getBySlug($region) : null;
        $category = $category ? $this->categoryRepository->getBySlug($category) : null;
        $categoryRegion = $geo && $category ? $this->categoryRepository->getRegion($category->id, $geo->id) : null;
        $type =  (int) Yii::$app->request->get('type');

        $provider = $this->readRepository->getAllByFilter($category, $geo, $type);

        // Вывод объявлений по клику "показать ещё"
        if (Yii::$app->request->get('showMore')) {
            return $this->asJson([
                'result' => 'success',
                'html' => $this->renderPartial('card-items-block', [
                    'provider' => $provider,
                    'geo' => $geo,
                ]),
                'nextPageUrl' => $provider->getPagination()->pageCount > $provider->getPagination()->page + 1
                                    ? $provider->getPagination()->createUrl($provider->getPagination()->page + 1)
                                    : false
            ]);
        }

        return $this->render('index', [
            'category' => $category,
            'geo' => $geo,
            'categoryRegion' => $categoryRegion,
            'provider' => $provider,
            'type' => $type,
        ]);
    }
}