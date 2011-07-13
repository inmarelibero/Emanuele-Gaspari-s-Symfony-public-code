<?php if ($sf_user->hasFlash('error')):?>
	<div class="form_errors">
		<?php echo $sf_user->getFlash('error')?>
	</div>
<?php endif;?>

<?php
	$form = new UtenteForm();
?>
<form id="form_register" action="<?php echo url_for('default/registerFormSave') ?>" method="post" class="form"  enctype="multipart/form-data">

	<div id="form_register_title">
		<?php echo __('Register')?>
	</div>
	
	<br><br>
	
	<div>
		<div class="label">
			<?php echo __('Email')?>
		</div>
		<div class="field">
			<?php echo $form['email_address'] ?>
		</div>
		<div class="field_error"></div>
		
		<div class="label">
			<?php echo __('Password')?>
		</div>
		<div class="field">
			<?php echo $form['password'] ?>
		</div>
		<div class="field_error"></div>
		
		<div class="label">
			<?php echo __('Confirm Password')?>
		</div>
		<div class="field">
			<?php echo $form['password_confirmation'] ?>
		</div>
		<div class="field_error"></div>
		
		<div class="label">
			<?php echo __('Avatar')?>
		</div>
		<div class="field">
			<?php echo $form['user_profile']['avatar'] ?>
		</div>
		<div class="field_error"></div>
		
		<div class="label">
			<?php echo __('Name')?>
		</div>
		<div class="field">
			<?php echo $form['first_name'] ?>
		</div>
		<div class="field_error"></div>
		
		<div class="label">
			<?php echo __('Surname')?>
		</div>
		<div class="field">
			<?php echo $form['last_name'] ?>
		</div>
		<div class="field_error"></div>
		
		<div class="label">
			<?php echo __('Photo')?>
		</div>
		<div class="field">
			<?php echo $form['user_profile']['photo'] ?>
		</div>
		<div class="field_error"></div>
		
		<div class="label">
			<?php echo __('Description')?>
		</div>
		<div class="field">
			<?php echo $form['user_profile']['description'] ?>
		</div>
		<div class="field_error"></div>
		
		<div class="label"></div>
		<div class="field">
			<input type="checkbox" name="sf_guard_user[termini]" value="true"> <?php echo __('I read and accepted the terms and conditions of the service')?>
		</div>
		<div class="field_error"></div>
		
	</div>

  <?php echo $form->renderHiddenFields() ?>
  
  <div class="label">
			
	</div>
	<div class="field">
		<button type="submit"><?php echo __('Send')?></button>
	</div>
  
</form>