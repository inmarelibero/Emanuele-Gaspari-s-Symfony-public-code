<script>
	$(document).ready(function(){
		// convert textares into tinyMCE editors 
		tinyMCE.init({
	        mode : "textareas",
	        theme : "simple"   //(n.b. no trailing comma, this will be critical as you experiment later)
		});

		// form validation
		$("#form_edit_profile").validate({
			rules: {
				'sf_guard_user[password_confirmation]': {
					equalTo: "#sf_guard_user_password"
				},
			},
			errorPlacement: function(error, element) {
				error.appendTo( element.parent().next('.field_error') );
			},
		});
	});
});
</script>


<!-- associate facebook profile -->
	<?php $facebook_uid = $sf_user->getGuardUser()->getProfile()->getFacebookUid()?>
	
	<?php if ($facebook_uid == '' || $facebook_uid == '0' || $facebook_uid == null):?>
		<div id="associate_facebook_profile">
			<script>
		    $("#associate_facebook_profile").click(function(){
				  FB.login(function(response) {
					  if (response.session) {
						  window.location = '<?php echo url_for('user/facebookAssociationCreate')?>';
					  } else {
							// user cancelled login
					  }
				  }, {perms:'email'});
			  });

			</script>

			<?php echo image_tag('associate_facebook.png')?>
		</div>
	<?php else:?>
		<div id="fb-root"></div>
		<?php echo __('Associated Facebook profile')?> <span id="email_facebook"></span>.

		<script>
			// retrieves the email of a facebook connected user
			var query = FB.Data.query('select name, uid, email from user where uid={0}', <?php echo $facebook_uid?>);
			query.wait(function(rows) {
				var name = rows[0].name;
				var email = rows[0].email;
				$('#email_facebook').html(email);
			});
		</script>
	<?php endif;?>

<br><br><br><br>

<?php echo $form->renderGlobalErrors()?>

<div id="edit_profile">
	<form id="form_edit_profile" action="<?php echo url_for('user/updateProfile') ?>" method="post" class="form"  enctype="multipart/form-data">
	
		<div id="form_register_title">
			<?php echo __('Edit your infos')?>
		</div>
		
		<br><br>
		
		<div>			
			<div class="label">
				<?php echo __('Password')?>
			</div>
			<div class="field">
				<?php echo $form['password'] ?>
			</div>
			<div class="field_error"></div>
			
			<div class="label">
				<?php echo __('Confirm password')?>
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
				<?php echo $form['user_profile']['foto'] ?>
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
				<button type="submit"><?php echo __('Send')?></button>
			</div>
		</div>
		
		<?php echo $form['user_profile']['facebook_uid'] ?>
		
	  <?php echo $form->renderHiddenFields() ?>
	</form>
</div>