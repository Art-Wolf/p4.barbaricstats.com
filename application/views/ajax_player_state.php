<?php
$attributes = array('class' => '', 'id' => '');
?>
			<table>
				<tr>
					<?php
					$count = 1;
					foreach ($game_info as $info) :
					$game_id = $info->id;
					?>
					<td id="player-<?php echo $count; ?>">
						<table class="table">
							<tr id="<?php echo $info->user_name; ?>" <?php if($info->user_name == $this->session->userdata('user_name')) { ?>class="success"<?php } ?> <?php if($info->dead <> "1" && $info->user_name <> $this->session->userdata('user_name')) { ?> onclick="sendVote(this);" <?php } ?> ><td class="pagination-centered"><img id="<?php echo $info->user_name; ?>-img" src="<?php if($info->dead == "1") { ?>/img/dead.png<?php } else { ?>/img/user.png<?php } ?>" width="60px"></td></tr>
							<tr <?php if($info->user_name == $this->session->userdata('user_name')) { ?>class="success"<?php } ?>><td class="pagination-centered" <?php if($info->user_name == $this->session->userdata('user_name')) { ?>style="color: white"<?php } ?>><?php echo $info->user_name; ?></td></tr>
						</table>
					</td>
					<?php

						$count++;
						endforeach;
					?>
				</tr>
			</table>
