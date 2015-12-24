<?php
namespace site\controllers;

use Yii;
use common\models\LoginForm;
use site\models\PasswordResetRequestForm;
use site\models\ResetPasswordForm;
use site\models\SignupForm;
use site\models\EditProfileForm;
use site\models\ContactForm;
use common\models\User;
use yii\base\InvalidParamException;
use yii\web\BadRequestHttpException;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;

/**
 * Site controller
 */
class SiteController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['login', 'logout', 'signup', 'privacy', 'terms', 'about', 'welcome'],
                'rules' => [
                    [
                        'actions' => ['index', 'error', 'privacy', 'terms', 'about'],
                        'allow' => true,
                    ],
                    [
                        'actions' => ['login', 'signup'],
                        'allow' => true,
                        'roles' => ['?'],
                    ],
                    [
                        'actions' => ['logout', 'welcome'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' =>  null,
            ],
        ];
    }

    public function actionIndex()
    {
        return $this->render('index');
    }

    public function actionLogin()
    {
        if (!\Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        } else {
            return $this->render('login', [
                'model' => $model,
            ]);
        }
    }
    
    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }

    public function actionContact()
    {
        $model = new ContactForm();
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if($model->sendEmail(Yii::$app->params['adminEmail'])) {
                Yii::$app->session->setFlash('success', 'Thank you for contacting us. We will respond to you as soon as possible.');
            } else {
                Yii::$app->session->setFlash('error', 'There was an error sending email.');
            }

            return $this->refresh();
        } else {
            return $this->render('contact', [
                'model' => $model,
            ]);
        }
    }

    public function actionAbout()
    {
        return $this->render('about');
    }

    public function actionWelcome()
    {
        return $this->render('welcome');
    }

    public function actionSignup()
    {
        $model = new SignupForm();

		if (Yii::$app->request->isAjax && $model->load($_POST))
		{
			Yii::$app->response->format = 'json';
			return \yii\widgets\ActiveForm::validate($model);
		}

        if ($model->load(Yii::$app->request->post())) {
            $user = $model->signup();
            if ($user) {
                $user->sendSignupNotificationEmail();
                if (Yii::$app->getUser()->login($user)) {
                    return $this->redirect('/welcome',302);
                }
            }
        }

        return $this->render('signup', [
            'model' => $model,
        ]);
    }

    public function actionRequestPasswordReset()
    {
        $model = new PasswordResetRequestForm();
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if ($model->sendEmail()) {
                Yii::$app->getSession()->setFlash('success', 'Check your email for further instructions.');

                return $this->goHome();
            } else {
                Yii::$app->getSession()->setFlash('error', 'Sorry, we are unable to reset password for email provided.');
            }
        }

        return $this->render('requestPasswordResetToken', [
            'model' => $model,
        ]);
    }

    public function actionResetPassword($token)
    {
        try {
            $model = new ResetPasswordForm($token);
        } catch (InvalidParamException $e) {
            throw new BadRequestHttpException($e->getMessage());
        }

        if ($model->load(Yii::$app->request->post()) && $model->validate() && $model->resetPassword()) {
            Yii::$app->getSession()->setFlash('success', 'New password was saved.');

            return $this->goHome();
        }

        return $this->render('resetPassword', [
            'model' => $model,
        ]);
    }

    public function actionProfile()
    {
        $model = new EditProfileForm();

		if (Yii::$app->request->isAjax && $model->load($_POST))
		{
			Yii::$app->response->format = 'json';
			return \yii\widgets\ActiveForm::validate($model);
		}

        $user = User::findOne(Yii::$app->user->id);
        $model->username = $user->username;
        $model->email = $user->email;
        $model->timezone = $user->timezone;
        $model->send_email = (isset($user->email_threshold) && (isset($user->partner_email1) || isset($user->partner_email2) || isset($user->partner_email3)));
        $model->email_threshold = $user->email_threshold;
        $model->partner_email1 = $user->partner_email1;
        $model->partner_email2 = $user->partner_email2;
        $model->partner_email3 = $user->partner_email3;

        if ($model->load(Yii::$app->request->post())) {
            $saved_user = $model->saveProfile();
            if($saved_user) {
            	Yii::$app->getSession()->setFlash('success', 'New profile data saved!');
            }
        }

        return $this->render('profile', [
            'model' => $model,
        ]);
    }

    public function actionPrivacy()
    {
      return $this->render('privacy');
    }

    public function actionTerms()
    {
      return $this->render('terms');
    }
}
