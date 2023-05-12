<?php

namespace app\controllers;
/** @var app\models\User $user */

use app\models\Category;
use app\models\Commentary;
use app\models\Post;
use app\models\Sort;
use app\models\Sortirovka;
use Yii;
use yii\base\BaseObject;
use yii\filters\AccessControl;
use yii\helpers\Url;
use yii\web\Controller;
use yii\web\Response;
use yii\filters\VerbFilter;
use app\models\LoginForm;
use app\models\ContactForm;
use app\models\SignUpForm;
use app\models\User;

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
                'only' => ['logout'],
                'rules' => [
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
     * @return string
     */
    public function actionIndex()
    {
        $model = new Sortirovka();
        return $this->render('index',[
            'model' => $model,
        ]);
    }

    /**
     * Login action.
     *
     * @return Response|string
     */
    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        }

        $model->password = '';
        return $this->render('login', [
            'model' => $model,
        ]);
    }
    public function actionSignup()
    {
        $model = new SignUpForm();
        $model->access_level = 1;
        if ($model->load($this->request->post())) {
            if ($user = $model->signUp()) {
                return $this->render('index',
                    ['model' => $user,
                        'id' => $user->id]);
            }
        }
        return $this->render('signup', [
            'model' => $model,
        ]);
    }
    /**
     * Logout action.
     *
     * @return Response
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }

    /**
     * Displays contact page.
     *
     * @return Response|string
     */
    public function actionContact()
    {
        $model = new ContactForm();
        if ($model->load(Yii::$app->request->post()) && $model->contact(Yii::$app->params['adminEmail'])) {
            Yii::$app->session->setFlash('contactFormSubmitted');

            return $this->refresh();
        }
        return $this->render('contact', [
            'model' => $model,
        ]);
    }

    /**
     * Displays about page.
     *
     * @return string
     */
    public function actionAbout()
    {
        return $this->render('about');
    }

    public function actionCreate()
    {
        if (Yii::$app->user->isGuest or Yii::$app->user->identity->access_level == User::BAN)
        {
            return $this->render('error');
        }
        $model = new Post();
        $model->author = Yii::$app->user->identity->id;
        if (Yii::$app->request->isPost)
        {
            $model->load(Yii::$app->request->post());
            if($model->save()){
                return $this->goBack();
            }

        }
        return $this->render('create',[
            'model' => $model
        ]) ;
    }
    public function actionComment()
    {
        if (Yii::$app->user->isGuest or Yii::$app->user->identity->access_level != User::BAN){
            return $this->render('comment');
        }

    }

    public function actionCommentcreate($post)
    {
        if (Yii::$app->user->isGuest or Yii::$app->user->identity->access_level != User::BAN)
        {
            $model = new Commentary();
            $model->post = $post;
            if (Yii::$app->user->isGuest)
            {
                $model->author = null;
            }
            if (!Yii::$app->user->isGuest)
            {
                $model->author = Yii::$app->user->identity->id;
            }
            if (Yii::$app->request->isPost)
            {
                $model->load(Yii::$app->request->post());
                if($model->save()){
                    return $this->render('comment',['post'=>$post]);
                }

            }
            return $this->render('commentcreate',[
                'model' => $model
            ]) ;
        }
    }

    public function actionCategorycreate()
    {
        if (Yii::$app->user->isGuest or Yii::$app->user->identity->access_level == User::BAN)
        {
            return $this->render('error');
        }
        $model = new Category();
        if (Yii::$app->request->isPost)
        {
            $model->load(Yii::$app->request->post());
            if($model->save()){
                return $this->goBack();
            }

        }
        return $this->render('categorycreate',[
            'model' => $model
        ]) ;
    }

    public function actionUserlist()
    {
        if (Yii::$app->user->isGuest or Yii::$app->user->identity->access_level != User::ADMIN)
        {
            return $this->render('error');
        }
        return $this->render('userlist');
    }

    public function actionDeletepost($id)
    {
        if (Yii::$app->user->isGuest or User::BAN)
        {
            return $this->render('error');
        }
        $post = Post::findOne($id);
        Commentary::deleteAll(['post'=>$id]);
        if (Yii::$app->user->identity->id == $post->author or User::ADMIN)
        $post->delete();
        return $this->goHome();
    }

    public function actionDeletecomment($id)
    {
        if (Yii::$app->user->isGuest or User::BAN)
        {
            return $this->render('error');
        }
        $comment = Commentary::findOne($id);
        if (Yii::$app->user->identity->id == $comment->author or User::ADMIN)
            $comment->delete();
        return $this->goBack();
    }

    public function actionDeleteuser($id)
    {
        if (Yii::$app->user->isGuest or !User::ADMIN)
        {
            return $this->render('error');
        }
        $user = User::findOne($id);
        Commentary::deleteAll(['author'=>$id]);
        Post::deleteAll(['author'=>$id]);
        $user->delete();
        return $this->render('userlist');
    }

    public function actionError()
    {
        return $this->render('error');
    }
}
