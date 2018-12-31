<?php

namespace frontend\controllers;


use core\entities\Page;
use core\readModels\PageReadRepository;
use Yii;
use yii\base\Module;
use yii\web\Controller;

class PageController extends Controller
{
    private $repository;
    private $_user;

    public function __construct(string $id, Module $module, PageReadRepository $repository, array $config = [])
    {
        parent::__construct($id, $module, $config);
        $this->repository = $repository;
        $this->_user = Yii::$app->user->identity;
        $this->view->params['user'] = $this->_user;
    }

    public function actionView($slug): string
    {
        $page = $this->repository->getBySlug($slug);
        $view = $page->type == Page::TYPE_UCP_PAGE ? 'view' : 'front-view';

        return $this->render($view, [
            'page' => $page,
        ]);
    }
}