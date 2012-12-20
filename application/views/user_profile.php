<?php

$attributes = array('class' => '', 'id' => '');

?>

<section id="forms">
        <div class="page-header">
                <h1>Profile</h1>
        </div>

        <div class="row">

                <div class="span10 offset1">

			<div class="span1">
				<div class="pagination-centered"><img src="/img/<?php if (empty($profile->photo)) { ?>user.png<?php } else { echo $profile->photo; } ?>" width="60px" class="img-rounded"></div>
			</div>

			<div class="span5">
				<table class="table table-striped">
					<tr>
						<td>User Name</td>
       						<td><?php echo $profile->user_name; ?></td>
					</tr>
					<tr>
						<td>Location</td>
						<td><?php echo $profile->location; ?></td>
					</tr>
					<tr>
						<td>Home Page</td>
						<td><?php echo $profile->website; ?></td>
					</tr>
					<tr>
						<td>Last Login</td>
						<td><?php if ($profile->last_login) {echo date('F d, Y h:mA', strtotime($profile->last_login));} ?></td>
					</tr>
					<tr>
						<td>Bio</td>
						<td><?php echo $profile->bio; ?></td>
					</tr>
				</table>
			</div>
		</div>
	</div>
</section>

