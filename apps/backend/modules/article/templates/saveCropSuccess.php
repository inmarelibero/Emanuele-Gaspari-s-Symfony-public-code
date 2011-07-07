<?php if ($sf_user->hasFlash('notice')):?>
	<div class="notice">
		<?php echo $sf_user->getFlash('notice')?>
	</div>
<?php endif;?>

<br><br>

<?php echo link_to('Go back to articles list', 'articles/index')?>