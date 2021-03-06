<?php
/**
 * DownloadController
 * @var $this ommu\article\controllers\history\DownloadController
 * @var $model ommu\article\models\ArticleDownloadHistory
 *
 * DownloadController implements the CRUD actions for ArticleDownloadHistory model.
 * Reference start
 * TOC :
 *	Index
 *	Manage
 *	View
 *	Delete
 *
 *	findModel
 *
 * @author Putra Sudaryanto <putra@ommu.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2017 OMMU (www.ommu.id)
 * @created date 20 October 2017, 10:38 WIB
 * @modified date 13 May 2019, 09:42 WIB
 * @link https://github.com/ommu/mod-article
 *
 */

namespace ommu\article\controllers\history;

use Yii;
use app\components\Controller;
use mdm\admin\components\AccessControl;
use yii\filters\VerbFilter;
use ommu\article\models\ArticleDownloadHistory;
use ommu\article\models\search\ArticleDownloadHistory as ArticleDownloadHistorySearch;

class DownloadController extends Controller
{
	/**
	 * {@inheritdoc}
	 */
	public function init()
	{
        parent::init();

        if (Yii::$app->request->get('download') || Yii::$app->request->get('id')) {
            $this->subMenu = $this->module->params['article_submenu'];
        }
	}

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
	 * Lists all ArticleDownloadHistory models.
	 * @return mixed
	 */
	public function actionManage()
	{
        $searchModel = new ArticleDownloadHistorySearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        $gridColumn = Yii::$app->request->get('GridColumn', null);
        $cols = [];
        if ($gridColumn != null && count($gridColumn) > 0) {
            foreach ($gridColumn as $key => $val) {
                if ($gridColumn[$key] == 1) {
                    $cols[] = $key;
                }
            }
        }
        $columns = $searchModel->getGridColumn($cols);

        if (($download = Yii::$app->request->get('download')) != null) {
            $download = \ommu\article\models\ArticleDownloads::findOne($download);
			$this->subMenuParam = $download->file->article_id;
			$setting = $download->file->article->getSetting(['media_image_limit', 'media_file_limit']);
            if ($download->file->article->category->single_photo || $setting->media_image_limit == 1) {
                unset($this->subMenu['photo']);
            }
            if ($download->file->article->category->single_file || $setting->media_file_limit == 1) {
                unset($this->subMenu['document']);
            }
        }

		$this->view->title = Yii::t('app', 'Download Histories');
		$this->view->description = '';
		$this->view->keywords = '';
		return $this->render('admin_manage', [
			'searchModel' => $searchModel,
			'dataProvider' => $dataProvider,
			'columns' => $columns,
			'download' => $download,
		]);
	}

	/**
	 * Displays a single ArticleDownloadHistory model.
	 * @param integer $id
	 * @return mixed
	 */
	public function actionView($id)
	{
        $model = $this->findModel($id);

        if (!Yii::$app->request->isAjax) {
			$this->subMenuParam = $model->download->file->article_id;
			$setting = $model->download->file->article->getSetting(['media_image_limit', 'media_file_limit']);

            if ($model->download->file->article->category->single_photo || $setting->media_image_limit == 1) {
				unset($this->subMenu['photo']);
            }
            if ($model->download->file->article->category->single_file || $setting->media_file_limit == 1) {
				unset($this->subMenu['document']);
            }
        }

		$this->view->title = Yii::t('app', 'Detail Download History: {download-id}', ['download-id' => $model->download->file->file_filename]);
		$this->view->description = '';
		$this->view->keywords = '';
		return $this->oRender('admin_view', [
			'model' => $model,
		]);
	}

	/**
	 * Deletes an existing ArticleDownloadHistory model.
	 * If deletion is successful, the browser will be redirected to the 'index' page.
	 * @param integer $id
	 * @return mixed
	 */
	public function actionDelete($id)
	{
		$model = $this->findModel($id);
		$model->delete();

		Yii::$app->session->setFlash('success', Yii::t('app', 'Article download history success deleted.'));
		return $this->redirect(Yii::$app->request->referrer ?: ['manage', 'download' => $model->download_id]);
	}

	/**
	 * Finds the ArticleDownloadHistory model based on its primary key value.
	 * If the model is not found, a 404 HTTP exception will be thrown.
	 * @param integer $id
	 * @return ArticleDownloadHistory the loaded model
	 * @throws NotFoundHttpException if the model cannot be found
	 */
	protected function findModel($id)
	{
        if (($model = ArticleDownloadHistory::findOne($id)) !== null) {
            return $model;
        }

		throw new \yii\web\NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
	}
}
