<p class="page-header">User Lookup</p>

<?php if(!isset($search)): ?>
<form action="<?php echo $_SERVER['PHP_SELF']; ?>?page=acp&amp;section=user-char_lookup" method="post">
	<div>
	<p>Looking for:<br />
	<input type="text" name="query" /></p>
	<p>Who is a: <br />
	<select name="mode">
		<option value="character">Character&nbsp;</option>
		<option value="user">User&nbsp;</option>
	</select>
	</p>
	<input type="submit" name="submit" value="Submit" />
	</div>
</form>

<?php elseif ($search == "character"): ?>
<p>Looking for a character whose name contains: <?php echo $query; ?> ...</p>
<table>
	<tr>
		<td style="width:250px"><strong>Character Name</strong></td>
		<td style="width:250px"><strong>User Name</strong></td>
	</tr>
	<?php foreach($search_result as $result): ?>
	<tr>
		<td><?php echo $result['character']; ?></td>
		<td><?php echo $result['user']; ?></td>
	</tr>
	<?php endforeach; ?>
</table>

<?php elseif ($search == "user"): ?>
<p>Looking for a username whose name contains: <?php echo $query; ?> ...</p>
<table cellspacing="0" cellpadding="0">
	<tr>
		<td style="width:250px"><strong>User Name</strong></td>
		<td style="width:250px"><strong>Character Name</strong></td>
	</tr>
	<?php foreach($search_result as $result): ?>
	<tr>
		<td><?php echo $result['user']; ?></td>
		<td><?php foreach($result['character'] as $char): ?><?php echo $char; ?><br /><?php endforeach; ?></td>
	</tr>
	<?php endforeach; ?>
</table>

<?php elseif ($search == "ns"): ?>
<p>No matches found.</p>

<?php endif; ?>