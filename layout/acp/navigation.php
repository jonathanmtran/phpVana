<p class="page-header">Navigation Administration</p>

<?php if (!isset($action)): ?>
<p><a href="<?php echo $_SERVER['PHP_SELF']; ?>?page=acp&amp;section=navigation&amp;action=new">Add New Link</a></p>

<table>
	<tr>
		<td><strong>Link</strong></td>
		<td><div style="text-align:center"><strong>Position</strong></div></td>
		<td><strong>Status</strong></td>
		<td><strong>Actions</strong></td>
	</tr>
<?php foreach ($navigation_data as $data): ?>
	<tr>
		<td style="width:50%"><?php echo $data['title']; ?></td>
		<td><div style="text-align:center"><a href="<?php echo $_SERVER['PHP_SELF']; ?>?page=acp&amp;section=navigation&amp;action=move_up&amp;difference=<?php echo $data['difference']; ?>&amp;id=<?php echo $data['id']; ?>"><img src="images/up.png" style="border:0" alt="Up" /></a><a href="<?php echo $_SERVER['PHP_SELF']; ?>?page=acp&amp;section=navigation&amp;action=move_down&amp;difference=<?php echo $data['difference']; ?>&amp;id=<?php echo $data['id']; ?>"><img src="images/down.png" style="border:0" alt="Down" /></a></div></td>
		<td><?php switch($data['active']){ case "1": echo("Active"); break; default: echo ("Inactive"); break;} ?></td>
		<td><a href="<?php echo $_SERVER['PHP_SELF']; ?>?page=acp&amp;section=navigation&amp;action=edit&amp;id=<?php echo $data['id']; ?>">Edit</a> | <a href="<?php echo $_SERVER['PHP_SELF']; ?>?page=acp&amp;section=navigation&amp;action=delete&amp;id=<?php echo $data['id']; ?>">Delete</a></td>
	</tr>
<?php endforeach; ?>
</table>

<?php elseif ($action == "new"): ?>
<p>Add Navigation Link</p>
	<?php if (!$complete): ?>
		<form action="<?php echo $_SERVER['PHP_SELF']; ?>?page=acp&amp;section=navigation&amp;action=new" method="post">
 		<div>
			<input type="text" name="title" /><br />
			<small>Name of Link</small><br />
			<br />
			<input type="text" name="target" /><br />
			<small>Target<br />NOTE: External targets must start with <strong>http://</strong></small><br />
			<br />
			<small>External Link</small> <input type="checkbox" name="external" /><br />
			<br />
			<small>Active?</small> <input type="checkbox" name="active" /><br />
			<br />
			<small>Show links to:</small><br />
			<select name="access">
				<option value="all">All</option>
				<option value="authorized">Authorized</option>
				<option value="gm">GM</option>
				<option value="logged_out">Special: Not logged in</option>
			</select><br />
			<br />
			<input type="submit" name="submit" value="Submit" />
			</div>
		</form>

	<?php elseif($complete): ?>
		<p>Your link has been added.<br />
	<?php endif; ?>

<?php elseif ($action == "edit"): ?>
<p>Edit Navgigation Link</p>
	<?php if (!$complete): ?>
		<form action="<?php echo $_SERVER['PHP_SELF']; ?>?page=acp&amp;section=navigation&amp;action=edit&amp;id=<?php echo $data['id']; ?>" method="post">
 		<div>
			<input type="text" name="title" value="<?php echo $data['title']; ?>" /><br />
			<small>Name of Link</small><br />
			<br />
			<input type="text" name="target" value="<?php echo $data['page']; ?>" /><br />
			<small>Target<br />NOTE: External targets must start with <strong>http://</strong></small><br />
			<br />
			<small>External Link</small> <input type="checkbox" name="external"<?php echo $data['external']; ?> /><br />
			<br />
			<small>Active?</small> <input type="checkbox" name="active"<?php echo $data['active']; ?> /><br />
			<br />
			<small>Show links to:</small><br />
			<select name="access">
				<option value="all" <?php echo $data['s_all'];?>>All</option>
				<option value="authorized" <?php echo $data['s_authorized']; ?>>Authorized</option>
				<option value="gm" <?php echo $data['s_gm']; ?>>GM</option>
				<option value="logged_out" <?php echo $data['s_special']; ?>>Special: Not logged in</option>
			</select><br />
			<br />
			<input type="submit" name="submit" value="Submit" />
			</div>
		</form>
	
	<?php elseif($complete): ?>
		<p>Your navigation link has been updated.<br />
	<?php endif; ?>

<?php elseif ($action == "delete"): ?>
<p>Delete Navgigation Link</p>
	<?php if (!$complete): ?>
		<?php if ($custom): ?>
		<form action="<?php echo $_SERVER['PHP_SELF']; ?>?page=acp&amp;section=navigation&amp;action=delete&amp;id=<?php echo $id; ?>" method="post">
 		<div>
			Are you sure you want to delete this navigation link?<br />
			<input type="submit" name="submit" value="Yes" /> <input type="button" value="No" onclick="history.go(-1)" />
			</div>
		</form>
		
		<?php elseif(!$custom): ?>
			<p>This item cannot be deleted, you may mark it inactive instead<br />	
		<?php endif; ?>
		
	<?php elseif($complete): ?>
		<p>Your navigation link has been deleted.<br />
	<?php endif; ?>

<?php endif; ?>

<?php if(($action == "delete" && !$custom) || ($complete)) : ?>
		<a href="<?php echo $_SERVER['PHP_SELF']; ?>?page=acp&amp;section=navigation">Click here to continue</a></p>
<?php endif; ?>