<?php if (isset($error)) :?>
	<?php echo $error?>
<?php elseif (isset($mail) && $mail):?>
	<?php echo __('Request correctly sent')?>.
<?php else:?>
	<?php echo __('Errors occurred in sending the request. Please contact the administrators')?>.
<?php endif;?>