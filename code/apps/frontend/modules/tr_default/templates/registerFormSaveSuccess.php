<?php if(isset($error)):?>
	<?php echo $error?>
<?php else:?>
	<?php echo __('Registration succeded')?>
	<br><br>
	<?php echo __('An email has been sent to your email address')?>
<?php endif;?>