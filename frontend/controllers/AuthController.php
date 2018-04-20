<?php

namespace frontend\controllers;


use core\entities\User\User;
use core\forms\auth\LoginForm;
use core\forms\auth\SignupForm;
use core\services\RoleManager;
use core\useCases\auth\AuthService;
use Yii;
use yii\base\Module;
use yii\base\UserException;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\BadRequestHttpException;
use yii\web\Controller;

class AuthController extends Controller
{
    private $roleManager;
    private $service;

    public function __construct($id, Module $module, AuthService $service, RoleManager $roleManager, array $config = [])
    {
        parent::__construct($id, $module, $config);
        $this->service = $service;
        $this->roleManager = $roleManager;
    }

    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'only' => ['logout', 'signup', 'confirm', 'signup-validation', 'login', 'login-validation'],
                'rules' => [
                    [
                        'actions' => ['signup', 'signup-validation', 'confirm', 'login', 'login-validation'],
                        'allow' => true,
                        'roles' => ['?'],
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

    public function actions()
    {
        return [
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
            ],
        ];
    }

    public function actionLogin()
    {
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

    public function actionLoginValidation()
    {
        $form = new LoginForm();
        if (Yii::$app->request->isAjax && $form->load(Yii::$app->request->post())) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($form);
        }
        return "";
    }

    public function actionSignup()
    {
        $form = new SignupForm();

        if ($form->load(Yii::$app->request->post()) && $form->validate()) {
            $user = User::signupRequest($form->email, $form->password);
            try {
                if ($user->save()) {
                    $sent = Yii::$app->mailer->compose(
                        ['html' => 'emailConfirmation-html', 'text' => 'emailConfirmation-text'],
                        ['user' => $user]
                    )
                        ->setFrom([Yii::$app->params['supportEmail'] => Yii::$app->name . ' robot'])
                        ->setTo($user->email)
                        ->setSubject('Подтверждение регистрации на сайте ' . Yii::$app->name)
                        ->send();

                    if (!$sent) {
                        throw new \DomainException("Ошибка отправки e-mail");
                    }

                    Yii::$app->session->setFlash('success', [['Вы зарегистрированы', 'На Ваш e-mail отправлено сообщение со ссылкой для подтверждения регистрации.']]);
                    return $this->redirect(Yii::$app->homeUrl);
                }
            } catch (\DomainException $e) {
                Yii::$app->session->setFlash('error', [['Ошибка регистрации', $e->getMessage()]]);
            }
        }

        return $this->render('signup', [
            'model' => $form
        ]);
    }

    public function actionSignupValidation()
    {
        $form = new SignupForm();
        if (Yii::$app->request->isAjax && $form->load(Yii::$app->request->post())) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($form);
        }
        return "";
    }

    public function actionConfirm($token)
    {
        if (empty($token)) {
            throw new UserException('Пустой токен.');
        }

        $user = User::find()->where(['email_confirm_token' => $token, 'status' => User::STATUS_WAIT])->one();
        if (!$user) {
            throw new UserException('Пользователь с таким токеном не найден!');
        }

        $user->confirmSignup();
        if ($user->save()) {
            $this->roleManager->assign($user->id, Rbac::ROLE_USER);
            Yii::$app->user->login($user, Yii::$app->params['user.rememberMeDuration']);
            Yii::$app->session->setFlash("confirm-success", "Ваш адрес e-mail успешно подтверждён!");
            return $this->redirect(['/users/settings']);
        }
        throw new UserException("Ошибка сохранения в базу, обратитесь в службу поддержки!");
    }

    public function actionLogout()
    {
        Yii::$app->user->logout();
        return $this->goHome();
    }

    /**
     * Requests password reset.
     * @return mixed
     * @throws UserException
     */
    public function actionRequestPasswordReset()
    {
        $model = new PasswordResetRequestForm();
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if ($model->sendEmail()) {
                throw new UserException("На Ваш адрес электронной почты отправлена ссылка для восстановления пароля");
            }
            throw new UserException("Извините, мы не можем отослать ссылку для восстановления пароля, возможно она уже была отправлена ранее.");
        }

        throw new UserException("Ошибка при восстановлении пароля.");
    }

    public function actionRequestPasswordResetValidation()
    {
        $form = new PasswordResetRequestForm();
        if (Yii::$app->request->isAjax && $form->load(Yii::$app->request->post())) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($form);
        }
        return "";
    }

    /**
     * Resets password.
     *
     * @param string $token
     * @return mixed
     * @throws BadRequestHttpException
     * @throws UserException
     */
    public function actionResetPassword($token)
    {
        try {
            $model = new ResetPasswordForm($token);
        } catch (InvalidParamException $e) {
            throw new BadRequestHttpException($e->getMessage());
        }

        if ($model->load(Yii::$app->request->post()) && $model->validate() && $model->resetPassword()) {
            throw new UserException("Новый пароль успешно установлен.");
        }

        return $this->render('resetPassword', [
            'model' => $model,
        ]);
    }
}