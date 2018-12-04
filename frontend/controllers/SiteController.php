<?php
namespace frontend\controllers;

use core\access\Rbac;
use core\components\Settings;
use core\entities\Trade\Trade;
use core\helpers\PriceHelper;
use core\jobs\SendMailJob;
use core\services\Mailer;
use core\useCases\SearchService;
use Yii;
use yii\base\InvalidParamException;
use yii\web\BadRequestHttpException;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use common\models\LoginForm;
use frontend\models\PasswordResetRequestForm;
use frontend\models\ResetPasswordForm;
use frontend\models\SignupForm;
use core\forms\ContactForm;
use yii\web\Response;

/**
 * Site controller
 */
class SiteController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'only' => ['logout', 'signup'],
                'rules' => [
                    [
                        'actions' => ['signup'],
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
                    'edit-column' => ['post'],
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
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    /**
     * Displays homepage.
     *
     * @return mixed
     */
    public function actionIndex()
    {
        return $this->render('index');
    }

    public function actionOutsite($id)
    {
        return $this->redirect($id);
    }

    public function actionEditColumn()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $id = (int) Yii::$app->request->post('id');
        $value = Yii::$app->request->post('value');
        $entity = Trade::findOne($id);
        if (!Yii::$app->user->can(Rbac::PERMISSION_MANAGE, ['user_id' => $entity->getOwnerId()])) {
            return ['result' => 'error', 'message' => 'Нет прав на редактирование данного элемента'];
        }
        $price = PriceHelper::optimize($value);
        $entity->updateAttributes(['price' => $price]);
        return ['result' => 'success', 'value' => $entity->getPriceString(), 'original' => $entity->price / 100];
    }

    public function actionSearch()
    {
        $service = new SearchService();
        $component = Yii::$app->request->get('for');
        $text = Yii::$app->request->get('q');

        try {
            $url = $service->search($component, $text);
            return $this->redirect($url);
        } catch (\DomainException $e) {
            Yii::$app->session->setFlash('error', $e->getMessage());
            return $this->goHome();
        }

        return $this->render('search', ['provider' => $provider]);
    }

    /**
     * Logs in a user.
     *
     * @return mixed
     */
    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        } else {
            $model->password = '';

            return $this->render('login', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Logs out the current user.
     *
     * @return mixed
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }

    /**
     * Displays contact page.
     *
     * @return mixed
     */
    public function actionContact()
    {
        $model = new ContactForm();
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            try {
                Yii::$app->queue->push(new SendMailJob([
                    'to' => Yii::$app->settings->get(Settings::GENERAL_CONTACT_EMAIL),
                    'view' => 'contact',
                    'params' => ['contactForm' => $model],
                    'subject' => 'Сообщение с формы обратной связи Mednav.ru'
                ]));
                Yii::$app->session->setFlash('success', 'Сообщение отправлено.');
            } catch (\DomainException $e) {
                Yii::$app->session->setFlash('error', 'Ошибка отправки.');
            }

            return $this->refresh();
        }

        return $this->render('contact', [
            'model' => $model,
        ]);
    }

    /**
     * Displays about page.
     *
     * @return mixed
     */
    public function actionAbout()
    {
        return $this->render('about');
    }

    /**
     * Signs user up.
     *
     * @return mixed
     */
    public function actionSignup()
    {
        $model = new SignupForm();
        if ($model->load(Yii::$app->request->post())) {
            if ($user = $model->signup()) {
                if (Yii::$app->getUser()->login($user)) {
                    return $this->goHome();
                }
            }
        }

        return $this->render('signup', [
            'model' => $model,
        ]);
    }

    /**
     * Requests password reset.
     *
     * @return mixed
     */
    public function actionRequestPasswordReset()
    {
        $model = new PasswordResetRequestForm();
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if ($model->sendEmail()) {
                Yii::$app->session->setFlash('success', 'Check your email for further instructions.');

                return $this->goHome();
            } else {
                Yii::$app->session->setFlash('error', 'Sorry, we are unable to reset password for the provided email address.');
            }
        }

        return $this->render('requestPasswordResetToken', [
            'model' => $model,
        ]);
    }

    /**
     * Resets password.
     *
     * @param string $token
     * @return mixed
     * @throws BadRequestHttpException
     */
    public function actionResetPassword($token)
    {
        try {
            $model = new ResetPasswordForm($token);
        } catch (InvalidParamException $e) {
            throw new BadRequestHttpException($e->getMessage());
        }

        if ($model->load(Yii::$app->request->post()) && $model->validate() && $model->resetPassword()) {
            Yii::$app->session->setFlash('success', 'New password saved.');

            return $this->goHome();
        }

        return $this->render('resetPassword', [
            'model' => $model,
        ]);
    }
}
