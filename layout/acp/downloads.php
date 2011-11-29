<p class="page-header">Downloads Administration</p>

<?php if(!isset($action)): ?>
<p><a href="<?php echo $_SERVER['PHP_SELF']; ?>?page=acp&amp;section=downloads&amp;action=new">Add Download</a></p>
	<?php if($list_downloads): ?>
		<table>
			<tr>
				<td style="width:50%"><div style="text-decoration:underline;"><strong>Title, Description &amp; Links</strong></div></td>
				<td><div style="text-align:center; text-decoration:underline;"><strong>Position</strong></div></td>
				<td><div style="text-decoration:underline;"><strong>Status</strong></div></td>
				<td><div style="text-decoration:underline;"><strong>Actions</strong></div></td>
			</tr>
		<?php foreach($download_data as $data): ?>
			<tr valign="top">
				<td>
					<strong><?php echo $data['title']; ?></strong><br />
					<i><?php echo $data['description']; ?></i><br />
					<br />
					Primary Mirror:<br />
					<a href="<?php echo $data['address']; ?>"><?php echo $data['host']; ?></a><br />
					<br />
					Alternative Mirrors:<br />
					<?php if ($data['mirrors'] != NULL): ?>
						<?php foreach($data['mirrors'] as $m_data): ?>
						<a href="<?php echo $m_data['address']; ?>"><?php echo $m_data['host']; ?></a><br />
						-<?php switch($m_data['active']){ case "1": echo("Active"); break; default: echo ("Inactive"); break;} ?><br />
						-<a href="<?php echo $_SERVER['PHP_SELF']; ?>?page=acp&amp;section=downloads&amp;action=edit_mirror&amp;id=<?php echo $m_data['id']; ?>">Edit</a> | <a href="<?php echo $_SERVER['PHP_SELF']; ?>?page=acp&amp;section=downloads&amp;action=delete_mirror&amp;id=<?php echo $m_data['id']; ?>">Delete</a><br />
						<br />
						<?php endforeach; ?>
					<?php endif; ?>
					[<a href="<?php echo $_SERVER['PHP_SELF']; ?>?page=acp&amp;section=downloads&amp;action=add_mirror&amp;id=<?php echo $data['id']; ?>">Add mirror</a>]
				</td>
				<td>
					<div style="text-align:center"><a href="<?php echo $_SERVER['PHP_SELF']; ?>?page=acp&amp;section=downloads&amp;action=move_up&amp;difference=<?php echo $data['difference']; ?>&amp;id=<?php echo $data['id']; ?>"><img src="images/up.png" style="border:0" alt="Up" /></a><a href="<?php echo $_SERVER['PHP_SELF']; ?>?page=acp&amp;section=downloads&amp;action=move_down&amp;difference=<?php echo $data['difference']; ?>&amp;id=<?php echo $data['id']; ?>"><img src="images/down.png" style="border:0" alt="Down" /></a></div>
				</td>
				<td>
					<?php switch($data['active']){ case "1": echo("Active"); break; default: echo ("Inactive"); break;} ?>
				</td>
				<td>
					<a href="<?php echo $_SERVER['PHP_SELF']; ?>?page=acp&amp;section=downloads&amp;action=edit&amp;id=<?php echo $data['id']; ?>">Edit</a> | <a href="<?php echo $_SERVER['PHP_SELF']; ?>?page=acp&amp;section=downloads&amp;action=delete&amp;id=<?php echo $data['id']; ?>">Delete</a>
				</td>
			</tr>
		<?php endforeach; ?>
		</table>
	<?php elseif(!$list_downloads): ?>
		<p>No downloads to display</p>
	<?php endif; ?>

<?php elseif($action == "new"): ?>
<p>Add Download</p>
	<?php if(!$complete): ?>
		<form action="<?php echo $_SERVER['PHP_SELF']; ?>?page=acp&amp;section=downloads&amp;action=new" method="post">
 		<div>
			<input type="text" name="title" /><br />
			<small>Title of Download</small><br />
			<br />
			<textarea name="description" rows="5" cols="20"></textarea>
			<small>Description of Download</small><br />
			<br />
			<input type="text" name="host" /><br />
			<small>Mirror Host</small><br />
			<br />
			<input type="text" name="address" value="http://" /><br />
			<small>Mirror address</small><br />
			<br />
			Active? <input type="checkbox" name="active" /><br />
			<br />
			<input type="submit" name="submit" value="Submit" />
			</div>
		</form>
	<?php elseif($complete): ?>
		<p>Your download has been added<br />
	<?php endif; ?>

<?php elseif($action == "edit"): ?>
<p>Edit Download</p>
	<?php if(!$complete): ?>
		<form action="<?php echo $_SERVER['PHP_SELF']; ?>?page=acp&amp;section=downloads&amp;action=edit&amp;id=<?php echo $data['id']; ?>" method="post">
 		<div>
			<input type="text" name="title" value="<?php echo $data['title']; ?>" /><br />
			<small>Title of Download</small><br />
			<br />
			<textarea name="description" rows="5" cols="20"><?php echo $data['description']; ?></textarea>
			<small>Description of Download</small><br />
			<br />
			<input type="text" name="host" value="<?php echo $data['host']; ?>" /><br />
			<small>Mirror Host</small><br />
			<br />
			<input type="text" name="address" value="<?php echo $data['address']; ?>" /><br />
			<small>Mirror address</small><br />
			<br />
			Active? <input type="checkbox" name="active"<?php echo $data['active']; ?> /><br />
			<br />
			<input type="submit" name="submit" value="Submit" />
			</div>
		</form>
	<?php elseif($complete): ?>
		<p>Your download link has been updated.<br />
	<?php endif ;?>

<?php elseif($action == "delete"): ?>
<p>Delete Download</p>
	<?php if(!$complete): ?>
		<form action="<?php echo $_SERVER['PHP_SELF']; ?>?page=acp&amp;section=downloads&amp;action=delete&amp;id=<?php echo $id; ?>" method="post">
		<div>
		Are you sure you want to delete this download and all associated mirrors [if applicable]?<br />
		<input type="submit" name="submit" value="Yes" /> <input type="button" value="No" onclick="history.go(-1)" />
		</div>
		</form>
	<?php elseif($complete): ?>
		<p>Your download has been deleted.<br />
	<?php endif; ?>

<?php elseif($action == "add_mirror"): ?>
<p>Add Download Mirror</p>
	<?php if(!$complete): ?>
		<form action="<?php echo $_SERVER['PHP_SELF']; ?>?page=acp&amp;section=downloads&amp;action=add_mirror&amp;id=<?php echo $id; ?>" method="post">
 		<div>
			<input type="text" name="host" /><br />
			<small>Mirror Host</small><br />
			<br />
			<input type="text" name="address" value="http://" /><br />
			<small>Mirror address</small><br />
			<br />
			Active? <input type="checkbox" name="active" /><br />
			<br />
			<input type="submit" name="submit" value="Submit" />
			</div>
		</form>
	<?php elseif($complete): ?>
		<p>Your mirror has been added<br />
	<?php endif; ?>

<?php elseif($action == "edit_mirror"): ?>
<p>Edit Download Mirror</p>
	<?php if(!$complete): ?>
		<form action="<?php echo $_SERVER['PHP_SELF']; ?>?page=acp&amp;section=downloads&amp;action=edit_mirror&amp;id=<?php echo $data['id']; ?>" method="post">
 		<div>
			<input type="text" name="host" value="<?php echo $data['host']; ?>" /><br />
			<small>Mirror Host</small><br />
			<br />
			<input type="text" name="address" value="<?php echo $data['address']; ?>" /><br />
			<small>Mirror address</small><br />
			<br />
			Active? <input type="checkbox" name="active"<?php echo $data['active']; ?> /><br />
			<br />
			<input type="submit" name="submit" value="Submit" />
			</div>
		</form>
	<?php elseif($complete): ?>
		<p>Your download mirror has been updated.<br />
	<?php endif; ?>

<?php elseif($action == "delete_mirror"): ?>
<p>Delete Download Mirror</p>
	<?php if(!$complete): ?>
		<form action="<?php echo $_SERVER['PHP_SELF']; ?>?page=acp&amp;section=downloads&amp;action=delete_mirror&amp;id=<?php echo $id; ?>" method="post">
		<div>
		Are you sure you want to delete this mirror?<br />
		<input type="submit" name="submit" value="Yes" /> <input type="button" value="No" onclick="history.go(-1)" />
		</div>
		</form>
	<?php elseif($complete): ?>
		<p>Your download mirror has been deleted.<br />
	<?php endif; ?>
	
<?php endif; ?>

<?php if($complete): ?>
	<a href="<?php echo $_SERVER['PHP_SELF']; ?>?page=acp&amp;section=downloads">Click here to continue</a></p>
<?php endif; ?>