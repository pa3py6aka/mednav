<?php
namespace backend\controllers;

use backend\actions\ErrorAction;
use core\grid\TreeViewColumn;
use core\useCases\auth\AuthService;
use Yii;
use yii\base\Module;
use yii\helpers\Url;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use core\forms\auth\LoginForm;

/**
 * Site controller
 */
class SiteController extends Controller
{
    private $service;

    public function __construct($id, Module $module, AuthService $service, array $config = [])
    {
        parent::__construct($id, $module, $config);
        $this->service = $service;
    }

    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'only' => ['login', 'error', 'logout'],
                'rules' => [
                    [
                        'actions' => ['login', 'error'],
                        'allow' => true,
                    ],
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => ErrorAction::class,
            ],
        ];
    }

    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionIndex()
    {
        return $this->render('index');
    }

    /**
     * Login action.
     *
     * @return string
     */
    public function actionLogin()
    {
        $this->layout = 'main-login';

        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $form = new LoginForm();

        if ($form->load(Yii::$app->request->post()) && $form->validate()) {
            try {
                $user = $this->service->auth($form);
                Yii::$app->user->login($user, $form->rememberMe ? Yii::$app->params['user.rememberMeDuration'] : 0);
                return $this->goBack();
            } catch (\DomainException $e) {
                Yii::$app->errorHandler->logException($e);
                Yii::$app->session->setFlash('error', $e->getMessage());
            }
        }

        return $this->render('login', [
            'model' => $form,
        ]);
    }

    /**
     * Logout action.
     *
     * @return string
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }

    public function actionTreeLoad()
    {
        $id = Yii::$app->request->post('id');
        $entityClass = Yii::$app->request->post('entity');
        $entity = $entityClass::find()->where(['id' => $id])->limit(1)->one();

        $rows = [];
        foreach ($entity->getChildren()->all() as $row) {
            $rows[] = '<tr data-key="' . $row->id . '" data-parent="' . $entity->id . '">
                           <td>' . $row->id . '</td>
                           <td class="tree-cell">' . TreeViewColumn::cellContent($row, $entityClass) . '</td>
                           <td>
                               <a href="' . Url::to(['/board/category/update', 'id' => $row->id, 'tab' => 'geo']) . '" aria-label="Привязка к регионам"><span class="glyphicon glyphicon-globe"></span></a> 
                               <a href="' . Url::to(['/board/category/delete', 'id' => $row->id]) . '" title="Удалить" aria-label="Удалить" data-pjax="0" data-confirm="Вы уверены?"><span class="glyphicon glyphicon-trash"></span></a>
                           </td>
                       </tr>';
        }

        return implode("\n", $rows);
    }
}
