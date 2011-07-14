<br><br>

<form action="<?php echo url_for('default/forgotPassword') ?>" method="post">
	<fieldset>
    <label><?php echo __('Specify your email address')?>: </label> <input type="text" name="email_address">
    <div class="clr"></div>	
	<input type="submit" value="Continue" name="submit">
</form>