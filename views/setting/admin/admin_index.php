<?php
/**
 * Article Settings (article-setting)
 * @var $this app\components\View
 * @var $this ommu\article\controllers\setting\AdminController
 * @var $model ommu\article\models\ArticleSetting
 *
 * @author Putra Sudaryanto <putra@ommu.co>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2019 OMMU (www.ommu.co)
 * @created date 11 May 2019, 23:45 WIB
 * @link https://github.com/ommu/mod-article
 *
 */

use yii\helpers\Html;
use yii\helpers\Url;

$this->params['breadcrumbs'][] = $this->title;
?>

<?php echo $this->renderWidget('/setting/category/admin_manage', [
	'contentMenu' => true,
	'searchModel' => $searchModel,
	'dataProvider' => $dataProvider,
	'columns' => $columns,
]); ?>

<?php echo $this->renderWidget(!$model->isNewRecord ? 'admin_view' : 'admin_update', [
	'contentMenu' => true,
	'model' => $model,
]); ?>