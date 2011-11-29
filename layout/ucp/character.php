<p>Character Information</p>

<?php if(!isset($character_id)): ?>
<p>Select your character<br />
<?php if (sizeof($characters) > 0): ?>
<table>
	<tr>
<?php 
$i = 0;
foreach ($characters as $character):
?>
		<td align="center">
			<a href="index.php?page=ucp&amp;section=character&amp;character=<?php echo $character['id']; ?>"><img src="character_image.php?id=<?php echo $character['id']; ?>" alt="<?php echo $character['name']; ?>" /></a>
		</td>
<?php 
	$i++;
	if ($i == 3):
		$i = 0;
?>
	</tr>
<?php
	endif; 
endforeach;
?>
	</tr>
</table>
<?php else: ?>
You don't have any characters.
<?php endif; ?>
</p>
<?php else: ?>
<table border="0">
  <tr>
    <td><img src="character_stats.php?id=<?php echo $character_id; ?>" alt="<?php echo $name; ?>" /></td>
	<td valign="bottom" align="center"><img src="character_image.php?id=<?php echo $character_id; ?>" alt="<?php echo $name; ?>" />
	<img src="character_info.php?id=<?php echo $character_id; ?>" alt="<?php echo $name; ?>" /></td>
  </tr>
</table>
<p>Buddylist: <br /></p>
<?php if ($num_buddies > 0): ?>
<table width="90%" border="0">
	<tr>
		<th>Name</th>
		<th>Level</th>
		<th>Job</th>
		<th>State</th>
		<th>Channel</th>
		<th>Map</th>
	</tr>
<?php foreach($buddies as $bc): ?>
	<tr>
		<td><?php echo $bc['name']; ?></td>
		<td><?php echo $bc['level']; ?></td>
		<td><?php echo $bc['job']; ?></td>
<?php if ($bc['channel'] >= 20000): ?>
		<td><span class="online">Online</span></td>
<?php if ($bc['channel'] % 100 == 50): ?>
		<td>Cash Shop</td>
<?php else: ?>
		<td><?php echo ($bc['channel'] % 100) + 1; ?></td>
<?php endif; ?>
		<td><?php echo htmlspecialchars($bc['map']); ?></td>
<?php else: ?>
		<td><span class="offline">Offline</span></td>
		<td>-</td>
		<td>-</td>
<?php endif; ?>
	</tr>
<?php endforeach; ?>
</table>

<?php else: ?>
<p>This character has no buddies.</p>
<?php endif; ?>

<?php endif; ?>