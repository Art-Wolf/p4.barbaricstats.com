<?php
$attributes = array('class' => '', 'id' => '');
$game_id = 0;
$mafia_ind = FALSE;
?>

<section id="forms">
	<div class="page-header">
		<h1>Game <?php if (count($game_info) < 7) { ?> Waiting On Players <?php } else { ?> In Progress <?php } ?></h1>
	</div>

	<div class="row">
		<div class="span11" id="game-state">
			<img src="/img/startup.png" width="600px">
		</div>

		<div class="span11" id="players">
			<table>
				<tr>
					<?php
					$count = 1;
					foreach ($game_info as $info) :
					$game_id = $info->id;
					?>
					<td id="player-<?php echo $count; ?>">
						<table class="table">

							<?php

							if($info->user_name == $this->session->userdata('user_name')) 
							{
								if($info->role == 1)
								{
									$mafia_ind = TRUE;
								}
							}

							?>
 
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
		</div>
<script type="text/javascript">
   function sendVote(item) {

	<?php
		$count = 0;

		foreach ($game_info as $info) :
	?>
		if ("<?php echo $info->user_name; ?>" != $(item).attr("id") && "<?php echo $info->dead; ?>" != "1")
		{  document.getElementById("<?php echo $info->user_name; ?>-img").src="/img/user.png"; }
	<?php	
		endforeach;
	?>

        document.getElementById($(item).attr("id") + "-img").src="/img/vote.png";


        $.ajax({
               url: "/game/send_vote/<?php echo $game_id; ?>/" + $(item).attr("id"),
               async: false,
               type: "POST",
               data: "type=chat",
               dataType: "html"
         });
   }
  </script>
		<div class="span11 chat-input" id="chat-input">
		</div>


		<div class="span10 offset1">
			<table>
				<tr>
					<?php
						if($this->session->userdata('user_name')) {
					?>
					<td><label class="control-label" for="chat-input"><?php echo $this->session->userdata('user_name'); ?></label></td>
					<td><input type="text" class="input-xxlarge search-query" id="chat-message"></td>
					<td><button type="submit" class="btn" id="chat-submit">Submit</button></td>
					<?php } ?>
				</tr>
			</table>
			<script type="text/javascript">
			$('#chat-submit').click(function() {
				$.ajax({
                        	        url: "/game/chat_message",
                        	        async: false,
                        	        type: "POST",
                        	        data: "chat-message=" + $('#chat-message').val(),
                        	        success: function() {
                        	                $('#chat-message').val('');
                        	        }
                        	});
			});
			</script>
        <script type="text/javascript">
                setInterval(function() {
                                $.ajax({
                                        url: "/game/chat/<?php echo $game_id; ?>",
                                        async: false,
                                        type: "POST",
                                        data: "type=chat",
                                        dataType: "html",
                                        success: function(data) {
                                                $('#chat-input').html(data);
                                        }
                                })
                        }, 1000);

		setInterval(function() {
                                $.ajax({
                                        url: "/game/get_state",
                                        async: false,
                                        type: "POST",
                                        data: "type=chat",
                                        dataType: "html",
                                        success: function(data) {
                                                $('#game-state').html(data);
                                        }
                                });
				return false;
                        }, 5000);

		setInterval(function() {
                                $.ajax({
                                        url: "/game/get_player_state/<?php echo $game_id; ?>",
                                        async: false,
                                        type: "POST",
                                        data: "type=chat",
                                        dataType: "html",
                                        success: function(data) {
                                                $('#players').html(data);
                                        }
                                });
                                return false;
                        }, 30000);
        </script>
		</div>

		<?php
		if($mafia_ind)
		{
		?>
		
		<h1>Private Mafia Chat</h1>
		<div class="span11 mafia-chat-input" id="mafia-chat-input">
                </div>


                <div class="span10 offset1">
                        <table>
                                <tr>
                                        <td><label class="control-label" for="mafia-chat-input"><?php echo $this->session->userdata('user_name'); ?></label></td>
                                        <td><input type="text" class="input-xxlarge search-query" id="mafia-chat-message"></td>
                                        <td><button type="submit" class="btn" id="mafia-chat-submit">Mafia Chat</button></td>
                                </tr>
                        </table>
                        <script type="text/javascript">
                        $('#mafia-chat-submit').click(function() {
                                $.ajax({
                                        url: "/game/mafia_chat_message",
                                        async: false,
                                        type: "POST",
                                        data: "mafia-chat-message=" + $('#mafia-chat-message').val(),
                                        success: function() {
                                                $('#mafia-chat-message').val('');
                                        }
                                });
                        });
                        </script>
        <script type="text/javascript">
                setInterval(function() {
                                $.ajax({
                                        url: "/game/mafia_chat",
                                        async: false,
                                        type: "POST",
                                        data: "type=chat",
                                        dataType: "html",
                                        success: function(data) {
                                                $('#mafia-chat-input').html(data);
                                        }
                                })
                        }, 1000);
        </script>
                </div>
		<?php
		}
		?>
	</div>

</section>
