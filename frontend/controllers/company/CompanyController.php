<?php

namespace frontend\controllers\company;


use core\readModels\Company\CompanyReadRepository;
use core\repositories\Company\CompanyCategoryRepository;
use core\repositories\GeoRepository;
use Yii;
use yii\base\Module;
use yii\web\Controller;

class CompanyController extends Controller
{
    private $repository;
    private $categoryRepository;
    private $service;

    public function __construct(
        string $id,
        Module $module,
        CompanyReadRepository $repository,
        CompanyCategoryRepository $categoryRepository,
        array $config = []
    )
    {
        parent::__construct($id, $module, $config);
        $this->repository = $repository;
        $this->categoryRepository = $categoryRepository;
    }

    public function actionList($category = null, $region = null)
    {
        if (!$category && !$region) {
            return $this->redirect(['list', 'region' => 'all']);
        }
        if ($region) {
            Yii::$app->session->set("geo", $region); // Сохраняем выбранный регион в сессию
        }
        $geo = $region && $region != 'all' ? (new GeoRepository())->getBySlug($region) : null;
        $category = $category ? $this->categoryRepository->getBySlug($category) : null;
        $categoryRegion = $geo && $category ? $this->categoryRepository->getRegion($category->id, $geo->id) : null;

        $provider = $this->repository->getAllBy($category, $geo);

        // Вывод объявлений по клику "показать ещё"
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
            'geo' => $geo,
            'categoryRegion' => $categoryRegion,
            'provider' => $provider,
        ]);
    }
}