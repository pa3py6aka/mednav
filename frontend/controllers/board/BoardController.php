<?php

namespace frontend\controllers\board;


use core\access\Rbac;
use core\readModels\Board\BoardReadRepository;
use core\repositories\Board\BoardCategoryRepository;
use core\repositories\GeoRepository;
use Yii;
use yii\base\Module;
use yii\base\UserException;
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

        return $this->render('list', [
            'category' => $category,
            'geo' => $geo,
            'categoryRegion' => $categoryRegion,
            'provider' => $provider,
            'type' => $type,
        ]);
    }

    public function actionView($id, $slug)
    {
        $board = $this->readRepository->getByIdAndSlug($id, $slug);

        if ($board->isOnModeration() && !Yii::$app->user->can(Rbac::PERMISSION_MANAGE, ['user_id' => $board->author_id])) {
            throw new UserException("Данное объявление находится на проверке");
        }

        $board->updateCounters(['views' => 1]);

        return $this->render('view', [
            'board' => $board
        ]);
    }
}