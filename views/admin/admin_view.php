<?php
/**
 * Articles (articles)
 * @var $this app\components\View
 * @var $this ommu\article\controllers\AdminController
 * @var $model ommu\article\models\Articles
 *
 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2017 OMMU (www.ommu.co)
 * @created date 20 October 2017, 09:33 WIB
 * @modified date 13 May 2019, 21:24 WIB
 * @link https://github.com/ommu/mod-article
 *
 */

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\DetailView;
use ommu\article\models\Articles;

$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Articles'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $model->title;
?>

<div class="articles-view">

<?php
$attributes = [
	'id',
	[
		'attribute' => 'publish',
		'value' => $model->quickAction(Url::to(['publish', 'id'=>$model->primaryKey]), $model->publish),
		'format' => 'raw',
	],
	[
		'attribute' => 'categoryName',
		'value' => function ($model) {
			$categoryName = isset($model->category) ? $model->category->title->message : '-';
			if($categoryName != '-')
				return Html::a($categoryName, ['setting/category/view', 'id'=>$model->cat_id], ['title'=>$categoryName, 'class'=>'modal-btn']);
			return $categoryName;
		},
		'format' => 'html',
	],
	'title',
	[
		'attribute' => 'image',
		'value' => function ($model) {
			$uploadPath = join('/', [Articles::getUploadPath(false), $model->id]);
			return $model->cover ? Html::img(Url::to(join('/', ['@webpublic', $uploadPath, $model->cover]))) : '-';
		},
		'format' => 'html',
	],
	[
		'attribute' => 'body',
		'value' => $model->body ? $model->body : '-',
		'format' => 'html',
	],
	[
		'attribute' => 'file',
		'value' => function ($model) {
			$uploadPath = join('/', [Articles::getUploadPath(false), $model->id]);
			return $model->cover ? Html::a($model->document, Url::to(join('/', ['@webpublic', $uploadPath, $model->document])), ['target'=>'_blank']) : '-';
		},
		'format' => 'html',
		'visible' => $model->category->single_file ? true : false,
	],
	[
		'attribute' => 'tag',
		'value' => $model->tag ? $model->tag : '-',
	],
	[
		'attribute' => 'published_date',
		'value' => Yii::$app->formatter->asDate($model->published_date, 'medium'),
	],
	[
		'attribute' => 'headline',
		'value' => $model->quickAction(Url::to(['headline', 'id'=>$model->primaryKey]), $model->headline, 'Yes,No', true),
		'format' => 'raw',
	],
	[
		'attribute' => 'headline_date',
		'value' => Yii::$app->formatter->asDatetime($model->headline_date, 'medium'),
	],
	[
		'attribute' => 'creation_date',
		'value' => Yii::$app->formatter->asDatetime($model->creation_date, 'medium'),
	],
	[
		'attribute' => 'creationDisplayname',
		'value' => isset($model->creation) ? $model->creation->displayname : '-',
	],
	[
		'attribute' => 'modified_date',
		'value' => Yii::$app->formatter->asDatetime($model->modified_date, 'medium'),
	],
	[
		'attribute' => 'modifiedDisplayname',
		'value' => isset($model->modified) ? $model->modified->displayname : '-',
	],
	[
		'attribute' => 'updated_date',
		'value' => Yii::$app->formatter->asDatetime($model->updated_date, 'medium'),
	],
	[
		'attribute' => 'files',
		'value' => function ($model) {
			$files = $model->getFiles(true);
			return Html::a($files, ['o/file/manage', 'article'=>$model->primaryKey, 'publish'=>1], ['title'=>Yii::t('app', '{count} files', ['count'=>$files])]);
		},
		'format' => 'html',
	],
	[
		'attribute' => 'likes',
		'value' => function ($model) {
			$likes = $model->getLikes(true);
			return Html::a($likes, ['o/like/manage', 'article'=>$model->primaryKey, 'publish'=>1], ['title'=>Yii::t('app', '{count} likes', ['count'=>$likes])]);
		},
		'format' => 'html',
	],
	[
		'attribute' => 'medias',
		'value' => function ($model) {
			$medias = $model->getMedias('count');
			return Html::a($medias, ['o/image/manage', 'article'=>$model->primaryKey, 'publish'=>1], ['title'=>Yii::t('app', '{count} medias', ['count'=>$media])]);
		},
		'format' => 'html',
	],
	[
		'attribute' => 'tags',
		'value' => function ($model) {
			$tags = $model->getTags('count');
			return Html::a($tags, ['o/tag/manage', 'article'=>$model->primaryKey], ['title'=>Yii::t('app', '{count} tags', ['count'=>$tags])]);
		},
		'format' => 'html',
	],
	[
		'attribute' => 'views',
		'value' => function ($model) {
			$views = $model->getViews(true);
			return Html::a($views, ['o/view/manage', 'article'=>$model->primaryKey, 'publish'=>1], ['title'=>Yii::t('app', '{count} views', ['count'=>$views])]);
		},
		'format' => 'html',
	],
	[
		'attribute' => '',
		'value' => Html::a(Yii::t('app', 'Update'), ['update', 'id'=>$model->id], ['title'=>Yii::t('app', 'Update'), 'class'=>'btn btn-primary']),
		'format' => 'html',
		'visible' => Yii::$app->request->isAjax ? true : false,
	],
];

echo DetailView::widget([
	'model' => $model,
	'options' => [
		'class'=>'table table-striped detail-view',
	],
	'attributes' => $attributes,
]); ?>

</div>