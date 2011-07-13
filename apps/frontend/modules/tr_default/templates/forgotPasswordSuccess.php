<?php if(!isset($error)):?>
	<?php if (isset($mail) && $mail):?>
		<?php echo __('An email was sent to your address to reset your password')?>.
	<?php else:?>
		<?php echo __('Errors occurred in sending email. Please contact the administrators')?>.
	<?php endif;?>
<?php else:?>
	<?php echo $error?>
<?php endif;?>