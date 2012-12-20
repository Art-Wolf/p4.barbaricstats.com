<?php

$attributes = array('class' => '', 'id' => '');


$chat_lines = array_reverse($chat);
?>

<div class="10">
<?php
	foreach ($chat_lines as $msg) :
?>
		<p>(<?php echo $msg->timestamp; ?>) <strong><?php echo $msg->username; ?></strong>: <?php echo $msg->message; ?></p>
<?php
	endforeach;
?>
</div>
