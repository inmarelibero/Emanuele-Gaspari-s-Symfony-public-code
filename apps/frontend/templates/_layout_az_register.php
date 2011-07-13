<script>
	$(document).ready(function(){

		// form validation through jQuery plugin
		$("#form_register").validate({
			   //debug: true,
			   rules: {
				   	'sf_guard_user[email_address]': {
					   	required: true,
					   	email:		true
					   },
				   	'sf_guard_user[password]': "required",
				   	'sf_guard_user[password_confirmation]': {
			    		required: true,
			    		equalTo: "#sf_guard_user_password"
			    	},
			    	'sf_guard_user[termini]': {
					   	required: true
					   }
					},
					// errorPlacement layout
					errorPlacement: function(error, element) {
						error.appendTo( element.parent().next('.field_error') );
					},
		});
	});
</script>


<?php
	$form = new UtenteForm();
?>

<div id="modal_register">
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
				<?php echo __('Password (confirmation)')?>
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
				<input type="checkbox" name="sf_guard_user[term]"> I read and accepted the terms and conditions of the service
			</div>
			<div class="field_error"></div>
		</div>

	  <?php echo $form->renderHiddenFields() ?>
	  
	</form>
</div>