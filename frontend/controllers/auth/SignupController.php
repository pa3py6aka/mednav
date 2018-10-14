<?php

namespace frontend\controllers\auth;


use core\entities\User\User;
use core\forms\auth\SignupForm;
use core\useCases\auth\SignupService;
use Yii;
use yii\filters\AccessControl;
use yii\helpers\Url;
use yii\web\Controller;

class SignupController extends Controller
{
    private $service;

    public function __construct($id, $module, SignupService $service, $config = [])
    {
        parent::__construct($id, $module, $config);
        $this->service = $service;
    }

    public function behaviors(): array
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'only' => ['request'],
                'rules' => [
                    [
                        'actions' => ['request'],
                        'allow' => true,
                        'roles' => ['?'],
                    ],
                ],
            ],
        ];
    }

    /**
     * @return mixed
     */
    public function actionRequest()
    {
        $form = new SignupForm();
        if ($form->load(Yii::$app->request->post()) && $form->validate()) {
            try {
                $user = $this->service->signup($form);
                if ($user->status == User::STATUS_WAIT) {
                    Yii::$app->session->setFlash('success', [['Форма отправлена', 'Вам на почту отправлено письмо со ссылкой для подтверждения регистрации']]);
                } else if ($user->status == User::STATUS_ON_PREMODERATION) {
                    Yii::$app->session->setFlash('success', [['Форма отправлена', 'Вы успешно зарегистрировались, в ближайшее время администратор проверит вашу заявку и активирует профиль.<br>Вам будет направлено уведомление после активации']]);
                } else {
                    Yii::$app->session->setFlash('success', [['Поздравляем!', 'Вы успешно зарегистрировались!<br><a href="' . Url::to(['/user/account/index']) . '">Перейти в личный кабинет.</a>']]);
                    Yii::$app->user->login($user);
                }
                return $this->goHome();
            } catch (\DomainException $e) {
                Yii::$app->errorHandler->logException($e);
                Yii::$app->session->setFlash('error', $e->getMessage());
            }
        }

        return $this->render('request', [
            'model' => $form,
        ]);
    }

    /**
     * @param $token
     * @return mixed
     */
    public function actionConfirm($token)
    {
        try {
            $this->service->confirm($token);
            Yii::$app->session->setFlash('success', [['Подтверждено!', 'Ваш e-mail успешно подтверждён']]);
            return $this->redirect(['auth/auth/login']);
        } catch (\DomainException $e) {
            Yii::$app->errorHandler->logException($e);
            Yii::$app->session->setFlash('error', $e->getMessage());
        }
        return $this->goHome();
    }
}