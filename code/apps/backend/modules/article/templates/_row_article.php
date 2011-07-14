<li class="article">

	<div class="photo">
		<?php include_partial('photo', array('article'=>$article))?>
	</div>

	<div class="title">
		<?php echo $article->getTitleTruncate(40)?>
	</div>

	<div class="actions">
		<ul>
			<li>
				<?php echo link_to(image_tag('icon_edit.png'), 'article/edit?id='.$article->getId())?>
			</li>
			<li>
				<?php echo link_to(image_tag('icon_delete.png'), 'article_delete', $article, array('method' => 'delete', 'confirm' =>  'Are you sure?')); ?>
			</li>
			<li>
				<?php echo link_to(image_tag('icon_editcrop.png'), 'article/crop?id='.$article->getId())?>
			</li>
		</ul>
	</div>
	
	<input type="hidden" name="article[<?php echo $article->getId()?>][id]" value="<?php echo $article->getId()?>">

</li>