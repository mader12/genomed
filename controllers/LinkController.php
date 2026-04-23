<?php

namespace app\controllers;

use Yii;
use app\models\Link;
use app\models\Ips;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use Da\QrCode\QrCode;
use yii\web\Response;
use yii\helpers\Url;

/**
 * LinkController implements the CRUD actions for Link model.
 */
class LinkController extends Controller
{

    public $short = '' ;
    /**
     * @inheritDoc
     */
    public function behaviors()
    {
        return array_merge(
            parent::behaviors(),
            [
                'verbs' => [
                    'class' => VerbFilter::className(),
                    'actions' => [
                        'delete' => ['POST'],
                    ],
                ],
            ]
        );
    }

    /**
     * Lists all Link models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new LinkSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function beforeAction($action)
    {
        if (!parent::beforeAction($action)) {
            return false;
        }

        $this->short = \Yii::$app->request->getHostInfo() . '/link/l/';

        return true; // или false для отмены
    }

      /**
     * Creates a new short Link.
     * Check Link and create short link, If creation is successful, create short link save to DB and response results.
     * @return string Results
     */
    public function actionCreateShortLink()
    {
        if (\Yii::$app->request->post('link')) {

            $link = \Yii::$app->request->post('link');

            if (!self::checkSite($link)) {
                echo '{"error": "Ссылка не работает. Сайт недоступен."}';
                return false;
            }

            $model = new Link();
            $model->url_real = $link;
            $model->url_short = Yii::$app->getSecurity()->generateRandomString(3) . time() ;
            if ($model->save()) {
                $qrCode = new QrCode($this->short  . $model->url_short);

                return '{"short": "' . $this->short  .  $model->url_short . '", "src" : "' . $qrCode->writeDataUri() . '"}';
            }

            echo '{"error": "' . $model->getErrors()  . '"}';
            // echo '{"error": "Возникла непредвиденная ошибка попробуйте еще раз или обратитесь к администратору."}';
            return false;
        }
    }

      /**
     * Displays a single Link model.
     * @param string $url url
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionL($url)
    {

        $link = Link::find()->where(['url_short' => $url])->one();
        $ips = new Ips();
        $ips->url_id = $link->id;
        $ips->ip = Yii::$app->getRequest()->getUserIP();
        $ips->save();

        return Yii::$app->response->redirect(Url::to($link->url_real));
    }

    // public function actionGenerateQr($link = null) {
    //     if (\Yii::$app->request->post('link')) {
    //         $link = \Yii::$app->request->post('link');
    //         Yii::$app->response->format = Response::FORMAT_RAW;
    //         $qrCode = new QrCode($link);
    //
    //     // Set headers to return an image directly
    //         //Yii::$app->response->headers->set('Content-Type', 'image/png');
    //         return $qrCode->writeDataUri();
    //         // return $qrCode->writeString(); // Returns binary data
    //     }
    //     else {
    //         Yii::$app->response->format = Response::FORMAT_RAW;
    //         $qrCode = new QrCode($link);
    //
    //     // Set headers to return an image directly
    //        // Yii::$app->response->headers->set('Content-Type', 'image/png');
    //         echo '<img src="' . $qrCode->writeDataUri() . '">';
    //
    //     }
    //
    //     return false;
    // }

    /**
     * Displays a single Link model.
     * @param int $id ID
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Link model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        $model = new Link();

        if ($this->request->isPost) {
            if ($model->load($this->request->post()) && $model->save()) {
                return $this->redirect(['view', 'id' => $model->id]);
            }
        } else {
            $model->loadDefaultValues();
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Link model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $id ID
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($this->request->isPost && $model->load($this->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Link model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $id ID
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Link model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id ID
     * @return Link the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Link::findOne(['id' => $id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    /**
     *  Check site for access
     * @param string $url URL
     *
     * */
    public static function checkSite($url) {

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); // Не выводить контент
        curl_setopt($ch, CURLOPT_HEADER, true);        // Получить заголовки
        curl_setopt($ch, CURLOPT_NOBODY, true);        // Не скачивать тело страницы
        curl_setopt($ch, CURLOPT_TIMEOUT, 10);         // Таймаут 10 секунд
        curl_exec($ch);
        $code = curl_getinfo($ch, CURLINFO_HTTP_CODE); // Получить HTTP код
        curl_close($ch);

        if ($code == 200)  {
            return true;
        }

        return false;
    }
}
