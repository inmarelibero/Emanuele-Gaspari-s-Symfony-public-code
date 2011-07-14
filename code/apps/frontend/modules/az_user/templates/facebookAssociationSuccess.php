<?php $facebook_uid = $sf_user->getGuardUser()->getProfile()->getFacebookUid()?>
<?php if ($facebook_uid != null && $facebook_uid != 0):?>
	<div>
		<?php echo __('Your facebook profile is already associated with your account')?>
		<br><br>
		<?php echo __('Use the box below to edit it')?>
	</div>
<?php endif;?>

<br><br>

<fb:registration 
  fields="name,email" 
  redirect-uri="<?php echo url_for('user/facebookAssociationCreate', true)?>"
  width="530">
</fb:registration>