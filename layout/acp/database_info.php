<p><strong>Database Information</strong></p>

<p>SQL files executed for Vana: <strong><?php echo $vana_info; ?></strong><br />
The above number should not be filled in by you.<br />
LoginServer does the execution of the sql files automatically.</p>

<p>SQL files executed for phpVana:<br />
<?php foreach($phpVana_info as $sql): ?>
	-<?php echo $sql; ?><br />
<?php endforeach; ?>
</p>