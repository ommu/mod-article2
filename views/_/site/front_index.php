<?php
/**
 * Articles (articles)
 * @var $this SiteController
 * @var $model Articles
 * @var $dataProvider CActiveDataProvider
 *
 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2014 Ommu Platform (opensource.ommu.co)
 * @link https://github.com/ommu/ommu-article
 *
 */

	$this->breadcrumbs=array(
		'Articles',
	);
?>

<?php $this->widget('application.libraries.core.components.system.FListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
	'pager' => array(
		'header' => '',
	), 
	'summaryText' => '',
	'itemsCssClass' => 'items clearfix',
	'pagerCssClass'=>'pager clearfix',
)); ?>
