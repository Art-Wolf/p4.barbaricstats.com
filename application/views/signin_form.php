<?php

$attributes = array('class' => '', 'id' => '');

?>

<?php echo form_open('users/signin', $attributes); ?>

	<legend>Signin</legend>
	
	<label>User Name</label>
	<input id="user_name" type="text" name="user_name" maxlength="30" value="<?php echo set_value('user_name'); ?>"  />
	<?php echo form_error('user_name'); ?>

	<label>Password</label>
	<input id="password" type="password" name="password" maxlength="255" value="<?php echo set_value('password'); ?>"  />
	<?php echo form_error('password'); ?>

	<button type="submit" class="btn">Signin</button></td>

<?php echo form_close(); ?>

