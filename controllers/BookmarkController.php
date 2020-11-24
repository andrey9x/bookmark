<?php

namespace app\controllers;

use app\services\BookmarkExcel;
use Yii;
use app\exceptions\BusinessException;
use app\forms\BookmarkForm;
use app\models\Bookmark;
use app\models\BookmarkSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * BookmarkController implements the CRUD actions for Bookmark model.
 */
class BookmarkController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all Bookmark models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new BookmarkSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $dataProvider->sort = ['attributes' => ['url', 'title', 'created_at']];
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Bookmark model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Bookmark model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        /*$model = new Bookmark();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }*/

        $bookmarkForm = new BookmarkForm();

        try {
            if ($bookmarkForm->load(Yii::$app->request->post())) {
                $bookmark = $bookmarkForm->parse();
                return $this->redirect(['view', 'id' => $bookmark->id]);
            }
        } catch (BusinessException $businessException) {
            Yii::$app->session->addFlash('warning', $businessException->getMessage());
        } catch (\Exception $exception) {
            Yii::error($exception->getMessage());
            Yii::$app->session->addFlash('error', 'Ошибка в приложении');
        }

        return $this->render('create', [
            'bookmarkForm' => $bookmarkForm,
        ]);
    }

    /**
     * Updates an existing Bookmark model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Bookmark model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    public function actionDownloadExcel()
    {
        (new BookmarkExcel())->download();
        die();
    }

    /**
     * Finds the Bookmark model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Bookmark the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Bookmark::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
