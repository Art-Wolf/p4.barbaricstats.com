<?php

$attributes = array('class' => '', 'id' => '');

$game_list = array_reverse($game_list);
?>
<section id="forms">
        <div class="page-header">
                <h1>Open Games</h1>
        </div>
	<div class="row">
		<div class="span10 offset1">

<?php
				function get_time_ago($time_stamp) {
					$time_difference = strtotime('now') - $time_stamp;
					if ($time_difference >= 60 * 60 * 24 * 365.242199) {
						return get_time_ago_string($time_stamp, 60 * 60 * 24 * 365.242199, 'year');
					} elseif ($time_difference >= 60 * 60 * 24 * 30.4368499) {
						return get_time_ago_string($time_stamp, 60 * 60 * 24 * 30.4368499, 'month');
					} elseif ($time_difference >= 60 * 60 * 24 * 7) {
						return get_time_ago_string($time_stamp, 60 * 60 * 24 * 7, 'week');
					} elseif ($time_difference >= 60 * 60 * 24) {
						return get_time_ago_string($time_stamp, 60 * 60 * 24, 'day');
					} elseif ($time_difference >= 60 * 60) {
						return get_time_ago_string($time_stamp, 60 * 60, 'hour');
					} else {
						return get_time_ago_string($time_stamp, 60, 'minute');
					}
				}

				function get_time_ago_string($time_stamp, $divisor, $time_unit) {
					$time_difference = strtotime("now") - $time_stamp;
					$time_units      = floor($time_difference / $divisor);
					settype($time_units, 'string');

					if ($time_units === '0') {
						return 'less than 1 ' . $time_unit . ' ago';
					} elseif ($time_units === '1') {
						return '1 ' . $time_unit . ' ago';
					} else {
						return $time_units . ' ' . $time_unit . 's ago';
					}
				}
        foreach ($game_list as $game) :
?>
			<div class="row">
				<div class="span4 collapse-group">
					<div class="collapse">
						<table class="table table-striped">
							<caption>Listing of Active Players</caption>
							<thead>
								<tr><td>&nbsp;</td><td>Player Name</td></tr>
							</thead>
							<tbody>
								<?php 
								$player_count = 1;
								foreach ($game_players as $player) 
								{
									if ($player->id == $game->id) {
								?>
								<tr><td><?php echo $player_count; ?></td><td><?php echo $player->user_name; ?></td></tr>
								<?php
										$player_count++;
									}
								}
								?>
							</tbody>
						</table>
					</div>
					<p class="collapse">Run Time: <?php 
echo get_time_ago(strtotime($game->start_time)); 
?></p>
					<p>
					<?php 
						if($this->session->userdata('user_name')) {
					?>
						<a class="btn btn-success" href="/game/join/<?php echo $game->id; ?>">Join Game <?php echo $game->id; ?></a>
					<?php } ?>
						<a class="btn btn-info" href="#">More Info (<?php echo $game->cnt; ?>/7)</a>
					</p>
				</div>
			</div>
<?php
        endforeach;
?>
		</div>
<script type="text/javascript">
$('.row .btn-info').on('click', function(e) {
    e.preventDefault();
    var $this = $(this);
    var $collapse = $this.closest('.collapse-group').find('.collapse');
    $collapse.collapse('toggle');
});
</script>
	</div>
</section>
<section id="game-lobby">
	<div class="page-header">
               	<h1>Lobby Chat</h1>
        </div>
	<div class="row">
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

			<?php
                                                if($this->session->userdata('user_name')) {
                                        ?>

			<script type="text/javascript">
			$('#chat-submit').click(function() {
				$.ajax({
                        	        url: "/ajax_chat/submit",
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
                                        url: "/ajax_chat/lobby",
                                        async: false,
                                        type: "POST",
                                        data: "type=chat",
                                        dataType: "html",
                                        success: function(data) {
                                                $('#chat-input').html(data);
                                        }
                                })
                        }, 1000);
        </script>
			<?php } ?>
		</div>
	</div>

</section>
