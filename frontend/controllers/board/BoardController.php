<?php

namespace frontend\controllers\board;


use core\readModels\Board\BoardReadRepository;
use core\repositories\Board\BoardCategoryRepository;
use core\repositories\Board\BoardRepository;
use core\repositories\GeoRepository;
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
        $geo = $region && $region != 'all-regions' ? $this->geoRepository->getBySlug($region) : null;
        $category = $category ? $this->categoryRepository->getBySlug($category) : null;
        $categoryRegion = $geo && $category ? $this->categoryRepository->getRegion($category->id, $geo->id) : null;
        
        $provider = $this->readRepository->getAllByFilter($category, $geo, (int) \Yii::$app->request->get('type'));

        return $this->render('index', [
            'category' => $category,
            'geo' => $geo,
            'categoryRegion' => $categoryRegion,
            'provider' => $provider,
        ]);
    }
}