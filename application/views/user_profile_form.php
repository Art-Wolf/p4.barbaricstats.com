<?php

$attributes = array('class' => '', 'id' => '');

?>
<h1>Edit Profile</h1>

<div class="container span12">
		<?php echo form_open_multipart('users/edit', $attributes); ?>
                	<table class="table table-striped">
                	        <tr>
                	                <td>Email Address</td>
					<td><input id="email_address" type="text" name="email_address" maxlength="255" value="<?php echo $profile->email_address; ?>" /></td>
					<td><?php echo form_error('email_address'); ?></td>	
                	        </tr>
                        	</tr>
				<tr>
                                	<td>Location</td>
                                	<td><input id="location" type="text" name="location" maxlength="255" value="<?php echo $profile->location; ?>" /></td>
					<td><?php echo form_error('location'); ?></td>
                        	</tr>
                        	<tr>
                                	<td>Home Page</td>
                                	<td><input id="website" type="text" name="website" maxlength="255" value="<?php echo $profile->website; ?>"/></td>
					<td><?php echo form_error('website'); ?></td>
                        	</tr>
                        	<tr>
                                	<td>Bio</td>
                                	<td><textarea id="bio" name="bio" class="field span12" id="textarea" rows="4" placeholder="Who? What? Where? WHY?!"><?php echo $profile->bio; ?></textarea></td>
					<td><?php echo form_error('bio'); ?></td>
                        	</tr>
				<tr>
					<td>Photo</td>
					<td><img src="/img/<?php if (empty($profile->photo)) { ?>user.png<?php } else { echo $profile->photo; } ?>" width="60px" class="img-rounded"><input type="file" id="photo" name="photo" size="20" /></td>
					<td></td>
				</tr>
				<tr>
					<td><button type="submit" class="btn btn-primary">Edit</button></td>
					<td></td>
					<td></td>
				</tr>
                	</table>
		<?php echo form_close(); ?>
</div>

