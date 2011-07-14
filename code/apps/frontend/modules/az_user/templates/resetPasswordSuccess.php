<?php if(isset($error)):?>
	<?php echo $error?>
<?php else:?>
	<?php echo __('Password successfully resetted')?>.
	<br><br>
	<?php echo __('An email containing the new password has been sent to you')?>.
<?php endif;?>