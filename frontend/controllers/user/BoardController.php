<?php

namespace frontend\controllers\user;


use core\access\Rbac;
use core\actions\BoardCategorySelectAction;
use core\actions\UploadAction;
use core\components\SettingsManager;
use core\forms\manage\Board\BoardManageForm;
use core\useCases\manage\Board\BoardManageService;
use Yii;
use yii\base\Module;
use yii\filters\AccessControl;
use yii\web\Controller;

class BoardController extends Controller
{
    public $layout = '@frontend/views/user/board/layout';

    private $service;
    private $_user;

    public function __construct(string $id, Module $module, BoardManageService $service, array $config = [])
    {
        parent::__construct($id, $module, $config);
        $this->service = $service;
        $this->_user = Yii::$app->user->identity;
        $this->view->params['user'] = $this->_user;
    }

    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => [Rbac::ROLE_USER],
                    ],
                ],
            ],
        ];
    }

    public function actions()
    {
        return [
            'upload' => [
                'class' => UploadAction::class,
                'baseUrl' => Yii::$app->params['frontendHostInfo'] . '/tmp',
                'sizes' => [
                    'small' => ['width' => Yii::$app->settings->get(SettingsManager::BOARD_SMALL_SIZE)],
                    'big' => ['width' => Yii::$app->settings->get(SettingsManager::BOARD_BIG_SIZE)],
                    'max' => ['width' => Yii::$app->settings->get(SettingsManager::BOARD_MAX_SIZE)],
                ]
            ],
            'select-category' => [
                'class' => BoardCategorySelectAction::class,
            ],
        ];
    }

    public function actionActive()
    {


        return $this->render('active', [
            'user' => $this->_user
        ]);
    }

    public function actionWaiting()
    {


        return $this->render('waiting', [
            'user' => $this->_user
        ]);
    }

    public function actionCreate()
    {
        $this->layout = 'main';

        $form = new BoardManageForm();
        $form->scenario = BoardManageForm::SCENARIO_USER_CREATE;

        if ($form->load(Yii::$app->request->post()) && $form->validate()) {
            try {
                $board = $this->service->create($form);
                Yii::$app->session->setFlash('success', $board->isActive() ? "Объявление опубликовано" : "Объявление отправлено на проверку");
                return $this->redirect([$board->isActive() ? 'active' : 'waiting']);
            } catch (\DomainException $e) {
                Yii::$app->session->setFlash("error", $e->getMessage());
            }
        }

        return $this->render('create', [
            'model' => $form,
        ]);
    }
}