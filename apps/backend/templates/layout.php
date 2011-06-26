<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
  <head>
    <?php include_http_metas() ?>
    <?php include_metas() ?>
    <?php include_title() ?>
    <link rel="shortcut icon" href="/favicon.ico" />
    <?php include_stylesheets() ?>
    <?php include_javascripts() ?>
  </head>
  <body>
  
  	<div id="header">
	 	<?php if (!$sf_user->isAuthenticated()): ?>
				<div id="login">
					<?php echo link_to('Login', '@sf_guard_signin')?>
				</div>
			<?php else: ?>
				<div id="exit">
					logged as: <?php echo $sf_user->getUsername()?>
					(<?php echo link_to('logout', '@sf_guard_signout'); ?>)
					<br><br><br>
				</div>
			<?php endif;?>
	  
			<?php if ($sf_user->isAuthenticated()): ?>
			  <div>
				  <?php echo link_to('Utenti', '@users'); ?> | 
				  <?php echo link_to('Categories', '@categories'); ?>
			  </div>
		  <?php endif;?>
	</div>
	  
  	<div id="main">  
		<?php echo $sf_content ?>
	</div>
  </body>
</html>
