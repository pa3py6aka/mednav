<?php

namespace frontend\controllers\company;


use core\helpers\PaginationHelper;
use core\readModels\ArticleReadRepository;
use core\readModels\Board\BoardReadRepository;
use core\readModels\CNewsReadRepository;
use core\readModels\Company\CompanyReadRepository;
use core\readModels\Trade\TradeReadRepository;
use core\repositories\Article\ArticleCategoryRepository;
use core\repositories\Board\BoardCategoryRepository;
use core\repositories\CNews\CNewsCategoryRepository;
use core\repositories\Company\CompanyCategoryRepository;
use core\repositories\GeoRepository;
use core\repositories\Trade\TradeCategoryRepository;
use Yii;
use yii\base\Module;
use yii\base\UserException;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

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
            return $this->redirect(['list', 'region' => 'all'], 301);
        }

        $geo = $region && $region !== 'all' ? (new GeoRepository())->getBySlug($region) : null;
        if ($region) {
            Yii::$app->session->set('geo', $region); // Сохраняем выбранный регион в сессию
        }

        $category = $category ? $this->categoryRepository->getBySlug($category) : null;
        $categoryRegion = $geo && $category ? $this->categoryRepository->getRegion($category->id, $geo->id) : null;

        $provider = $this->repository->getAllBy($category, $geo, Yii::$app->request->get('q'));

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

    public function actionView($id, $slug)
    {
        $company = $this->repository->getByIdAndSlug($id, $slug);
        $company->updateCounters(['views' => 1]);

        return $this->render('view', [
            'company' => $company,
            'page' => 'main',
        ]);
    }

    public function actionContacts($id, $slug): string
    {
        $company = $this->repository->getByIdAndSlug($id, $slug);

        return $this->render('view', [
            'company' => $company,
            'page' => 'contacts',
        ]);
    }

    public function actionBoards($id, $slug)
    {
        $company = $this->repository->getByIdAndSlug($id, $slug);
        if ($category = Yii::$app->request->get('category') ?: null) {
            $category = (new BoardCategoryRepository())->get((int) $category);
        }

        $provider = (new BoardReadRepository())->getAllByFilter($category, null, null, $company->user_id);

        // Вывод объявлений по клику "показать ещё"
        if (
            ($showMore = PaginationHelper::getShowMore($this, $provider, '@frontend/views/board/board/card-items-block', [
                    'provider' => $provider,
                    'inCompany' => true,
                ])) !== null
        ) {
            Yii::debug($showMore);
            return $showMore;
        }

        return $this->render('boards', [
            'company' => $company,
            'provider' => $provider,
            'category' => $category,
        ]);
    }

    public function actionTrades($id, $slug)
    {
        $company = $this->repository->getByIdAndSlug($id, $slug);
        if ($category = Yii::$app->request->get('category') ?: null) {
            $category = (new TradeCategoryRepository())->get((int) $category);
        }

        $provider = (new TradeReadRepository())->getAllByFilter($category, null, $company->id);

        // Вывод товаров по клику "показать ещё"
        if (
            ($showMore = PaginationHelper::getShowMore($this, $provider, '@frontend/views/trade/trade/card-items-block', [
                'provider' => $provider,
                'inCompany' => true,
            ])) !== null
        ) {
            Yii::debug($showMore);
            return $showMore;
        }

        return $this->render('trades', [
            'company' => $company,
            'provider' => $provider,
            'category' => $category,
        ]);
    }

    /*public function actionArticles($id, $slug)
    {
        $company = $this->repository->getByIdAndSlug($id, $slug);
        if ($category = Yii::$app->request->get('category') ?: null) {
            $category = (new ArticleCategoryRepository())->get((int) $category);
        }

        $provider = (new ArticleReadRepository())->getAllBy($category, $company->id);

        // Вывод статей по клику "показать ещё"
        if (
            ($showMore = PaginationHelper::getShowMore($this, $provider, '@frontend/views/article/article/card-items-block', [
                'provider' => $provider,
                'inCompany' => true,
            ])) !== null
        ) {
            return $showMore;
        }

        return $this->render('articles', [
            'company' => $company,
            'provider' => $provider,
            'category' => $category,
        ]);
    }*/

    public function actionCnews($id, $slug)
    {
        $company = $this->repository->getByIdAndSlug($id, $slug);
        if ($category = Yii::$app->request->get('category') ?: null) {
            $category = (new CnewsCategoryRepository())->get((int) $category);
        }

        $provider = (new CNewsReadRepository())->getAllBy($category, $company->id);

        // Вывод статей по клику "показать ещё"
        if (
            ($showMore = PaginationHelper::getShowMore($this, $provider, '@frontend/views/cnews/cnews/card-items-block', [
                'provider' => $provider,
                'inCompany' => true,
            ])) !== null
        ) {
            return $showMore;
        }

        return $this->render('cnews', [
            'company' => $company,
            'provider' => $provider,
            'category' => $category,
        ]);
    }

    public function actionOutsite($id = null, $url = null): \yii\web\Response
    {
        if ($id) {
            $company = $this->repository->getById($id);
            if ($company->site) {
                $url = $company->site;
            } else {
                throw new UserException('Сайт компании не установлен.');
            }
        }

        if ($url) {
            return $this->redirect($url);
        }

        throw new UserException('Неверный запрос.');
    }
}