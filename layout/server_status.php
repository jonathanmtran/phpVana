		<p class="page-header">Server Status</p>
		<script type="text/javascript" src="includes/js/jquery.js"></script>
		<script type="text/javascript" src="includes/js/server_status.js"></script>

<?php if ($l_num != 0 && $w_num !=0): ?>
<?php foreach ($server_data as $server): ?>
<?php if ($server['type'] == 0): ?>
		<a target="loginserver"></a>
		<p><?php echo $server['name']; ?>: <span id="online-<?php echo $server['internal_id']; ?>"><span class="offline">offline</span></span><br /></p>

<?php elseif($server['type'] == 1): ?>
		<a target="world<?php echo $server['id']; ?>"></a>
		<p><?php echo get_world($server['id']); ?> <?php echo $server['name']; ?>: <span id="online-<?php echo $server['internal_id']; ?>"><span class="offline">offline</span></span><br />
		[ Exp Rate: <?php echo $server['exp_rate']; ?> | Quest Rate: <?php echo $server['quest_rate']; ?> | Meso Rate: <?php echo $server['meso_rate']; ?> | Drop Rate: <?php echo $server['drop_rate']; ?> ]<br />
		Total characters online: <?php echo $server['total_characters']; ?>
        </p>
		<ul>
<?php if ($server['has_cash_server']): ?>
			<li>
				<a target="world<?php echo $server['id']."_cashserver"; ?>"></a>
				Cash Server: <span id="online-<?php echo $server['cash_server_internal_id']; ?>"><span class="offline">offline</span></span>
<?php if ($server["cash_server_chars"] > 0): ?>
				<a href="#world<?php echo $server['id']."_cashserver"; ?>" id="link-<?php echo $server['id']; ?>51" onclick="get_characters(<?php echo $server['id']; ?>, 51);">Characters online: <span id="online_chars-<?php echo $server['internal_id']; ?>"><?php echo $server["cash_server_chars"]; ?></span></a>
				<ul id="online_characters-<?php echo $server['id']; ?>51"></ul>
<?php endif; ?>
			</li>
<?php endif; ?>
<?php if ($server['channel_servers']): ?>
<?php foreach ($server['channel_servers'] as $channel): ?>
			<li>
				<a target="world<?php echo $server['id']."_channel".$channel['id']; ?>"></a>
				<?php echo $channel['name']; ?>: <span id="online-<?php echo $channel['internal_id']; ?>"><span class="offline">offline</span></span><br />
<?php if ($channel["charsonline"] > 0): ?>
				<a href="#world<?php echo $server['id']."_channel".$channel['id']; ?>" id="link-<?php echo $server['id'] . $channel['id']; ?>" onclick="get_characters(<?php echo $server['id']; ?>, <?php echo $channel['id']; ?>);">Characters online: <?php echo $channel["charsonline"]; ?></a>
				<ul id="online_characters-<?php echo $server['id'] . $channel['id']; ?>"></ul>
<?php endif; ?>
			</li>
<?php endforeach; ?>
<?php else: ?>
			<li>There are currently no channels in this world.</li>
<?php endif; ?>
		</ul>
		<br />
<?php endif; ?>
<?php endforeach; ?>
		<script type="text/javascript">
			function refreshAll() {
<?php
foreach ($server_data as $server) {
	echo "\t\t\t\tisOnline(".$server['internal_id'].");\n";
	if($server['type'] == 1) {
		if ($server['has_cash_server']) {
			echo "\t\t\t\tisOnline(".$server['cash_server_internal_id'].");\n";
		}
		if ($server['channel_servers']) {
			foreach ($server['channel_servers'] as $channel) {
				echo "\t\t\t\tisOnline(".$channel['internal_id'].");\n";
			}
		}
	}
}
?>
			}
			refreshAll();
			setInterval(function() {refreshAll();}, 15000);
		</script>
<?php else: ?>
		<p>There is no server information to display.</p>
<?php endif; ?>