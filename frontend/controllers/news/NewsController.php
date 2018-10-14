<?php

namespace frontend\controllers\news;


use core\readModels\NewsReadRepository;
use core\repositories\News\NewsCategoryRepository;
use Yii;
use yii\base\Module;
use yii\web\Controller;

class NewsController extends Controller
{
    private $readRepository;
    private $categoryRepository;

    public function __construct
    (
        $id,
        Module $module,
        NewsCategoryRepository $categoryRepository,
        NewsReadRepository $readRepository,
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

        // Вывод новостей по клику "показать ещё"
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
        $news = $this->readRepository->getByIdAndSlug($id, $slug);
        $news->updateCounters(['views' => 1]);

        return $this->render('view', [
            'news' => $news
        ]);
    }
}