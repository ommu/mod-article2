<?php
/**
 * Articles (articles)
 * @var $this SiteController
 * @var $model Articles
 *
 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2014 Ommu Platform (opensource.ommu.co)
 * @link https://github.com/ommu/ommu-article
 *
 */

	$this->breadcrumbs=array(
		'Articles'=>array('manage'),
		$model->category->title->message,
		$model->title,
	);
?>

<?php $this->widget('application.libraries.core.components.system.FDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'article_id',
		'publish',
		'cat_id',
		'title',
		'body',
		'quote',
		'media_file',
		'published_date',
		'headline',
		'comment_code',
		'creation_date',
		'creation_id',
		'modified_date',
		'modified_id',
		'headline_date',
		'slug',
	),
)); ?>

<?php if($random != null) {
	foreach($random as $key => $val) { ?>
		<a href="<?php echo Yii::app()->controller->createUrl('view', array('id'=>$val->article_id,'slug'=>Utility::getUrlTitle($val->title)));?>" title="<?php echo $val->title;?>"><?php echo $val->title;?></a>
		<br/><?php echo Utility::dateFormat($val->published_date);?>
		<br/><?php echo $val->view->views;?>
		<p><?php echo Utility::shortText(Utility::hardDecode($val->body),150);?></p>
<?php }
}?>