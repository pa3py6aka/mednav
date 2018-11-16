<?php

namespace frontend\controllers\trade;


use core\entities\Company\Company;
use core\readModels\Trade\TradeReadRepository;
use core\repositories\Trade\TradeCategoryRepository;
use core\repositories\GeoRepository;
use Yii;
use yii\base\Module;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

class TradeController extends Controller
{
    private $readRepository;
    private $geoRepository;
    private $categoryRepository;

    public function __construct
    (
        $id,
        Module $module,
        GeoRepository $geoRepository,
        TradeCategoryRepository $categoryRepository,
        TradeReadRepository $readRepository,
        array $config = [])
    {
        parent::__construct($id, $module, $config);
        $this->geoRepository = $geoRepository;
        $this->categoryRepository = $categoryRepository;
        $this->readRepository = $readRepository;
    }

    public function actionList($category = null, $region = null)
    {
        if (!$category && !$region) {
            return $this->redirect(['list', 'region' => 'all']);
        }

        $geo = $region && $region != 'all' ? $this->geoRepository->getBySlug($region) : null;
        if ($region) {
            Yii::$app->session->set("geo", $region); // Сохраняем выбранный регион в сессию
        }

        $category = $category ? $this->categoryRepository->getBySlug($category) : null;
        $categoryRegion = $geo && $category ? $this->categoryRepository->getRegion($category->id, $geo->id) : null;

        $provider = $this->readRepository->getAllByFilter($category, $geo, null, Yii::$app->request->get('q'));

        // Вывод объявлений по клику "показать ещё"
        if (Yii::$app->request->get('showMore')) {
            return $this->asJson([
                'result' => 'success',
                'html' => $this->renderPartial('card-items-block', [
                    'provider' => $provider,
                    'geo' => $geo,
                    'inCompany' => false,
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

    public function actionView($id, $slug)
    {
        $trade = $this->readRepository->getByIdAndSlug($id, $slug);
        $trade->updateCounters(['views' => 1]);

        return $this->render('view', [
            'trade' => $trade
        ]);
    }

    public function actionOutsite($url)
    {
        if (!$company = Company::findOne((int) $url)) {
            throw new NotFoundHttpException();
        }
        return $this->redirect($company->site);
    }

    public function actionVendor($id)
    {
        $trade = $this->readRepository->get((int) $id);
        return $this->redirect($trade->external_link);
    }
}