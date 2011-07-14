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

		<div id="wrapper">
		
			<div id="header">
				<div id="header_center">
					<div class="name">
						<?php echo link_to('Home', '@homepage'); ?>
					</div>
					<div class="header_right">
						<?php include_component('eg_default', 'headerMenu') ?>
					</div>
				</div>
			</div>
		
			<div id="main">
    		<?php echo $sf_content; ?>
	    </div>
		    
			<div id="push"></div>
		</div>
		    
		<div id="footer">
			<div id="footer_center">
					<div id="icons_list">
						<?php echo image_tag('icons/php_30.png')?>
						<?php echo image_tag('icons/symfony_30.png')?>
						<?php echo image_tag('icons/jquery_30.png')?>
						<?php echo image_tag('icons/appcelerator_30.png')?>
					</div>
			</div>
		</div>

  </body>
</html>