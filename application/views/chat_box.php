<!-- Forms
================================================== -->
<section id="forms">
	<div class="page-header">
		<h1>Forms</h1>
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
                                        url: "/ajax_chat<?php echo $game->id; ?>",
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
		</div>
	</div>

</section>
