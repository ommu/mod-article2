<?php
/**
 * Articles (articles)
 * @var $this AdminController
 * @var $model Articles
 *
 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2016 Ommu Platform (opensource.ommu.co)
 * @created date 20 October 2016, 10:14 WIB
 * @link https://github.com/ommu/ommu-article
 *
 */
?>

<li id="upload" <?php echo $media_limit != 0 && count($medias) == $media_limit ? 'class="hide"' : '' ?>>
	<a id="upload-gallery" href="<?php echo Yii::app()->controller->createUrl('o/admin/insertcover', array('id'=>$model->article_id,'hook'=>'admin'));?>" title="<?php echo Yii::t('phrase', 'Upload Photo'); ?>"><?php echo Yii::t('phrase', 'Upload Photo'); ?></a>
	<img src="<?php echo Utility::getTimThumb(Yii::app()->request->baseUrl.'/public/article/article_default.png', 320, 250, 1);?>" alt="" />
</li>