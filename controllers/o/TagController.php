<?php
/**
 * TagController
 * @var $this ommu\article\controllers\o\TagController
 * @var $model ommu\article\models\ArticleTag
 *
 * TagController implements the CRUD actions for ArticleTag model.
 * Reference start
 * TOC :
 *  Index
 *  Manage
 *  View
 *  Delete
 *
 *  findModel
 *
 * @author Putra Sudaryanto <putra@ommu.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2021 OMMU (www.ommu.id)
 * @created date 1 July 2021, 11:24 WIB
 * @link https://github.com/ommu/mod-article
 *
 */

namespace ommu\article\controllers\o;

use Yii;
use app\components\Controller;
use mdm\admin\components\AccessControl;
use yii\filters\VerbFilter;
use ommu\article\models\ArticleTag;
use ommu\article\models\search\ArticleTag as ArticleTagSearch;

class TagController extends Controller
{
	/**
	 * {@inheritdoc}
	 */
	public function init()
	{
        parent::init();

        if (Yii::$app->request->get('id') || Yii::$app->request->get('article')) {
            $this->subMenu = $this->module->params['article_submenu'];
        } else {
            $this->subMenu = $this->module->params['setting_submenu'];
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
	 * Lists all ArticleTag models.
	 * @return mixed
	 */
	public function actionManage()
	{
        $searchModel = new ArticleTagSearch();
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

        if (($article = Yii::$app->request->get('article')) != null) {
            $this->subMenuParam = $article;
            $article = \ommu\article\models\Articles::findOne($article);
			$setting = $article->getSetting(['media_image_limit', 'media_file_limit']);
            if ($article->category->single_photo || $setting->media_image_limit == 1) {
                unset($this->subMenu['photo']);
            }
            if ($article->category->single_file || $setting->media_file_limit == 1) {
                unset($this->subMenu['document']);
            }
        }
        if (($tag = Yii::$app->request->get('tag')) != null) {
            $tag = \app\models\CoreTags::findOne($tag);
        }

		$this->view->title = Yii::t('app', 'Tags');
        if ($tag) {
            $this->view->title = Yii::t('app', 'Tag: {tag}', ['tag' => $tag->body]);
        }
		$this->view->description = '';
		$this->view->keywords = '';
		return $this->render('admin_manage', [
			'searchModel' => $searchModel,
			'dataProvider' => $dataProvider,
			'columns' => $columns,
			'article' => $article,
			'tag' => $tag,
		]);
	}

	/**
	 * Displays a single ArticleTag model.
	 * @param integer $id
	 * @return mixed
	 */
	public function actionView($id)
	{
        $model = $this->findModel($id);

        if (!Yii::$app->request->isAjax) {
			$this->subMenuParam = $model->article_id;
			$setting = $model->article->getSetting(['media_image_limit', 'media_file_limit']);

            if ($model->article->category->single_photo || $setting->media_image_limit == 1) {
				unset($this->subMenu['photo']);
            }
            if ($model->article->category->single_file || $setting->media_file_limit == 1) {
				unset($this->subMenu['document']);
            }
        }

		$this->view->title = Yii::t('app', 'Detail Tag: {tag-id}', ['tag-id' => $model->tag->body]);
		$this->view->description = '';
		$this->view->keywords = '';
		return $this->oRender('admin_view', [
			'model' => $model,
		]);
	}

	/**
	 * Deletes an existing ArticleTag model.
	 * If deletion is successful, the browser will be redirected to the 'index' page.
	 * @param integer $id
	 * @return mixed
	 */
	public function actionDelete($id)
	{
		$model = $this->findModel($id);
		$model->delete();

		Yii::$app->session->setFlash('success', Yii::t('app', 'Article tag success deleted.'));
		return $this->redirect(Yii::$app->request->referrer ?: ['manage']);
	}

	/**
	 * Finds the ArticleTag model based on its primary key value.
	 * If the model is not found, a 404 HTTP exception will be thrown.
	 * @param integer $id
	 * @return ArticleTag the loaded model
	 * @throws NotFoundHttpException if the model cannot be found
	 */
	protected function findModel($id)
	{
        if (($model = ArticleTag::findOne($id)) !== null) {
            return $model;
        }

		throw new \yii\web\NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
	}
}