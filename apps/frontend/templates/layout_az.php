<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
  <head>
    <?php include_http_metas() ?>
    <?php include_metas() ?>
    <?php include_title() ?>
    <link rel="shortcut icon" href="/favicon.ico" />
    <?php include_stylesheets() ?>
    <?php include_javascripts() ?>
       
   	<script type="text/javascript">
   		$(document).ready(function(){
	   			$('#link_registrati').click(function(){
		   			modal_register()
		   		});
   	  })
		</script>
		
		<script src="http://connect.facebook.net/en_US/all.js"></script>
		<script>
			// load facebbok apis
	  	window.fbAsyncInit = function() {
		    FB.init({
		      appId  : '<?php echo sfConfig::get('app_facebook_api_id')?>',
		      cookie : true, // enable cookies to allow the server to access the session
		    });
		  };
		  (function() {
		    var e = document.createElement('script');
		    e.src = document.location.protocol + '//connect.facebook.net/it_IT/all.js';
		    e.async = true;
		  }());
		</script>
    
  </head>
  <body>
  
	<?php require_once sfConfig::get('sf_app_lib_dir').'/facebook.php'; ?>
	
		<div id="fb-root"></div>
  	
		<div id="header">
			<div id="header_center">
				<div id="login">
					<?php if (!$sf_user->isAuthenticated()): ?>
							<?php $form_login = new sfGuardFormSignin(); ?>
							
							<form action="<?php echo url_for('@sf_guard_signin')?>" method="post" id="form_login">
								<div>
									<?php echo __('username')?>:<br><input name="signin[username]" type="text">
									<br>
									<?php echo $form_login['remember']?> <?php echo __('Keep me logged in')?>
								</div>
								<div>
									<?php echo __('password')?>:<br><input name="signin[password]" type="password">
									<br>
									<?php echo link_to(__('password lost?'), 'default/forgotPasswordForm')?>
								</div>
								<div>
									<br>
									<button type="submit"><?php echo __('enter')?></button>
									<br>
									<?php echo link_to('Register', 'user/formRegister', array('id'=>'link_register'))?>
								</div>
								<?php echo $form_login->renderHiddenFields()?>
							</form>
							<?php include_partial('global/register_layout_az_register')?>
					<?php else: ?>
							<div>
								<b><?php echo $sf_user->getUsername(); ?></b> (<?php echo link_to(__('EXIT'), '@sf_guard_signout'); ?>)
							</div>
							<div>
								<?php if ($sf_user->getGuardUser()->Profile->getFacebookUid() != ''):?>
									<img src="https://graph.facebook.com/<?php echo $sf_user->getGuardUser()->Profile->getFacebookUid(); ?>/picture">
								<?php elseif ($sf_user->getGuardUser()->Profile->getAvatar() != ''):?>
									<?php echo image_tag('../uploads/'.sfConfig::get('app_thumbnails_folder').'/'.sfConfig::get('app_thumbnails_prefix').$sf_user->getAvatar())?>
								<?php else:?>
									<?php echo image_tag('icon_person.gif')?>
								<?php endif;?>
							</div>
					<?php endif;?>
				</div>
				<div class="links">
					<div>
						<?php echo link_to('Home', '@homepage')?>
					</div>
					
					<?php if (!$sf_user->isAuthenticated()): ?>
						<div id="login_with_facebook">
							<script>
						    $("#login_with_facebook").click(function(){
						    	FB.login(function(response) {
									  if (response.session) {
										  window.location = '<?php echo url_for('user/facebookLogin')?>';
									  } else {

									  }
									}, {perms:'email'});
						    });
							</script>
							<?php echo image_tag('login_facebook.png')?>
						</div>
				  <?php endif?>
					
					<?php if ($sf_user->isAuthenticated()): ?>
						<div>
							<?php echo link_to('Edit my profile', '@edit_profile', array('method' => 'post'))?> |
							<?php echo link_to('My galleries', '@user_galleries', array('method' => 'post'))?>
						</div>
					<?php endif;?>
				</div>
			</div>
		</div>
		
		<div id="main">
			<div id="main_center">
    		<?php echo $sf_content ?>
    	</div>
    </div>
    
		<div id="waiting_operation">
			<?php echo image_tag('ajax-loader.gif')?>
		</div>
		
	</body>
</html>