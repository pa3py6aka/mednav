<?php

namespace frontend\controllers\board;


use core\repositories\Board\BoardCategoryRepository;
use core\repositories\GeoRepository;
use yii\base\Module;
use yii\web\Controller;

class BoardController extends Controller
{
    private $boardRepository;
    private $geoRepository;
    private $categoryRepository;

    public function __construct
    (
        $id,
        Module $module,
        GeoRepository $geoRepository,
        BoardCategoryRepository $categoryRepository,
        array $config = [])
    {
        parent::__construct($id, $module, $config);
        $this->geoRepository = $geoRepository;
        $this->categoryRepository = $categoryRepository;
    }

    public function actionIndex($category = null, $region = null)
    {
        $geo = $region && $region != 'all' ? $this->geoRepository->getBySlug($region) : null;
        $category = $category ? $this->categoryRepository->getBySlug($category) : null;
        $categoryRegion = $geo && $category ? $this->categoryRepository->getRegion($category->id, $geo->id) : null;

        return $this->render('index', [
            'category' => $category,
            'geo' => $geo,
            'categoryRegion' => $categoryRegion,
        ]);
    }
}