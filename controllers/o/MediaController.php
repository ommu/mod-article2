<?php
/**
 * MediaController
 * @var $this ommu\article\controllers\o\MediaController
 * @var $model ommu\article\models\ArticleMedia
 *
 * MediaController implements the CRUD actions for ArticleMedia model.
 * Reference start
 * TOC :
 *	Index
 *	Manage
 *	Create
 *	Update
 *	View
 *	Delete
 *	RunAction
 *	Publish
 *
 *	findModel
 *
 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2017 OMMU (www.ommu.co)
 * @created date 20 October 2017, 11:00 WIB
 * @link https://github.com/ommu/mod-article
 *
 */
 
namespace ommu\article\controllers\o;

use Yii;
use yii\filters\VerbFilter;
use app\components\Controller;
use mdm\admin\components\AccessControl;
use yii\helpers\Url;
use ommu\article\models\ArticleMedia;
use ommu\article\models\search\ArticleMedia as ArticleMediaSearch;
use yii\web\UploadedFile;

class MediaController extends Controller
{
	/**
	 * {@inheritdoc}
	 */
	public function behaviors()
	{
		return [
			'access' => [
				'class' => AccessControl::className(),
			],
			'verbs' => [
				'class' => VerbFilter::className(),
				'actions' => [
					'delete' => ['POST'],
					'publish' => ['POST'],
				],
			],
		];
	}

	/**
	 * {@inheritdoc}
	 */
	public function actionIndex()
	{
		return $this->redirect(['manage']);
	}

	/**
	 * Lists all ArticleMedia models.
	 * @return mixed
	 */
	public function actionManage()
	{
		$searchModel = new ArticleMediaSearch();
		$dataProvider = $searchModel->search(Yii::$app->request->queryParams);

		$gridColumn = Yii::$app->request->get('GridColumn', null);
		$cols = [];
		if($gridColumn != null && count($gridColumn) > 0) {
			foreach($gridColumn as $key => $val) {
				if($gridColumn[$key] == 1)
					$cols[] = $key;
			}
		}
		$columns = $searchModel->getGridColumn($cols);

		$this->view->title = Yii::t('app', 'Article Media');
		$this->view->description = '';
		$this->view->keywords = '';
		return $this->render('admin_manage', [
			'searchModel' => $searchModel,
			'dataProvider' => $dataProvider,
			'columns' => $columns,
		]);
	}

	/**
	 * Creates a new ArticleMedia model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 * @return mixed
	 */
	public function actionCreate()
	{
		$model = new ArticleMedia();
		$model->scenario = 'formCreate';

		if(Yii::$app->request->isPost) {
			$model->load(Yii::$app->request->post());
			$model->image_filename = UploadedFile::getInstance($model, 'image_filename');

			if($model->save()) {
				Yii::$app->session->setFlash('success', Yii::t('app', 'Media success created.'));
				if (Yii::$app->request->get('article')) {
					return Yii::$app->response->redirect(Url::to(['index', 'article' => Yii::$app->request->get('article')]));
				}
				return $this->redirect(['manage']);

			}
		}

		$this->view->title = Yii::t('app', 'Create Article Media');
		$this->view->description = '';
		$this->view->keywords = '';
		return $this->render('admin_create', [
			'model' => $model,
		]);
	}

	/**
	 * Updates an existing ArticleMedia model.
	 * If update is successful, the browser will be redirected to the 'view' page.
	 * @param integer $id
	 * @return mixed
	 */
	public function actionUpdate($id)
	{
		$model = $this->findModel($id);

		if(Yii::$app->request->isPost) {
			$model->load(Yii::$app->request->post());
			$model->image_filename = UploadedFile::getInstance($model, 'image_filename');
			if(!($model->image_filename instanceof UploadedFile)) {
				$model->image_filename = $model->old_image_filename_i;
			}

			if($model->save()) {
				Yii::$app->session->setFlash('success', Yii::t('app', 'Media success updated.'));
				return $this->redirect(['manage']);
				//return $this->redirect( Url::to(['index', 'article' => Yii::$app->request->get('article')]));
			}
		}

		$this->view->title = Yii::t('app', 'Update Article Media: {image_filename}', ['image_filename' => $model->image_filename]);
		$this->view->description = '';
		$this->view->keywords = '';
		return $this->render('admin_update', [
			'model' => $model,
		]);

	}

	/**
	 * Displays a single ArticleMedia model.
	 * @param integer $id
	 * @return mixed
	 */
	public function actionView($id)
	{
		$model = $this->findModel($id);

		$this->view->title = Yii::t('app', 'View Article Media: {image_filename}', ['image_filename' => $model->image_filename]);
		$this->view->description = '';
		$this->view->keywords = '';
		return $this->render('admin_view', [
			'model' => $model,
		]);
	}

	/**
	 * Deletes an existing ArticleMedia model.
	 * If deletion is successful, the browser will be redirected to the 'index' page.
	 * @param integer $id
	 * @return mixed
	 */
	public function actionDelete($id)
	{
		$model = $this->findModel($id);
		$model->publish = 2;

		if($model->save(false, ['publish','modified_id'])) {
			//return $this->redirect(['view', 'id' => $model->media_id]);
			Yii::$app->session->setFlash('success', Yii::t('app', 'Article Media success deleted.'));
			return $this->redirect(['manage']);
			//return $this->redirect( Url::to(['index', 'article' => Yii::$app->request->get('article')]));
		}
	}

	/**
	 * actionPublish an existing ArticleMedia model.
	 * If publish is successful, the browser will be redirected to the 'index' page.
	 * @param integer $id
	 * @return mixed
	 */
	public function actionPublish($id)
	{
		$model = $this->findModel($id);
		$replace = $model->publish == 1 ? 0 : 1;
		$model->publish = $replace;

		if($model->save(false, ['publish','modified_id'])) {
			Yii::$app->session->setFlash('success', Yii::t('app', 'Article Media success updated.'));
			return $this->redirect(['manage']);
		}
	}

	/**
	 * Finds the ArticleMedia model based on its primary key value.
	 * If the model is not found, a 404 HTTP exception will be thrown.
	 * @param integer $id
	 * @return ArticleMedia the loaded model
	 * @throws NotFoundHttpException if the model cannot be found
	 */
	protected function findModel($id)
	{
		if(($model = ArticleMedia::findOne($id)) !== null)
			return $model;

		throw new \yii\web\NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
	}
}
