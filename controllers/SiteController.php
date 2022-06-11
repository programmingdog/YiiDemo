<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\Response;
use yii\filters\VerbFilter;
use app\models\LoginForm;
use app\models\ContactForm;
use app\models\Supplier;
use app\models\SupplierSearch;
use Csv;

class SiteController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
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
                'class' => VerbFilter::className(),
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
        return $this->render('index');
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
    
    public function actionExport(){
        $params = Yii::$app->request->get();
        $search = $params['SupplierSearch'];
        $exportParam = [
            'table' => 'supplier',
            'fields' => explode(",",$params["checkType"])
        ];
        if(!empty($params["selectedRow"])){
            $exportParam['condition'] = ['in',"id",explode(",",$params["selectedRow"])];
        }else{
            if($search['t_status']||$search['name']||$search['id']||$search['code']){
                $exportParam['condition'] = ["and"];
                if($search['id']&&$search['id_operator']){
                    $exportParam['condition'][] = [$search['id_operator'],'id',$search['id']];
                }
                $exportParam['condition'][] = ['t_status' => $search['t_status']];
                $exportParam['condition'][] = ['like', 'name', $search['name']];
                $exportParam['condition'][] = ['like', 'code', $search['code']];
            }
        }
        $csv = Csv::export($exportParam);
        return $csv;
    }
    
    public function actionSupplier()
	{
		$searchModel = new SupplierSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->get());

		return $this->render('supplier',[
		    "dataProvider"=>$dataProvider,
		    'searchModel' => $searchModel,
		    "totalCount" => $dataProvider->getTotalCount()
		]);
	}
}
