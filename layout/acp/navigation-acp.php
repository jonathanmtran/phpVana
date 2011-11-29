<p class="page-header">Admin CP Navigation Administration</p>

<?php if (!isset($action)): ?>

<table>
	<tr>
		<td><strong>Link</strong></td>
		<td><strong>Visibility</strong></td>
		<td><strong>Active</strong></td>
		<td><strong>Actions</strong></td>
	</tr>
<?php foreach ($navigation_data as $data): ?>
	<tr>
		<td style="width:50%"><?php echo $data['title']; ?></td>
		<td><?php echo $data['visibility']; ?></td>
		<td><?php echo $data['active']; ?></td>
		<td><a href="<?php echo $_SERVER['PHP_SELF']; ?>?page=acp&amp;section=navigation-acp&amp;action=edit&amp;id=<?php echo $data['id']; ?>">Edit</a></td>
	</tr>
<?php endforeach; ?>
</table>

<?php elseif ($action == "edit"): ?>
<p>Edit Navigation Link</p>
	<?php if (!$complete): ?>
		<form action="<?php echo $_SERVER['PHP_SELF']; ?>?page=acp&amp;section=navigation-acp&amp;action=edit&amp;id=<?php echo $data['id']; ?>" method="post">
 		<div>
			<input type="text" name="title" value="<?php echo $data['title']; ?>" readonly="readonly" /><br />
			<small>Name of Link</small><br />
			<br />
			<small>Active?</small> <input type="checkbox" name="active"<?php echo $data['active']; ?> /><br />
			<br />
			<small>Show links to:</small><br />
			<select name="access">
				<option value="3"<?php echo $data['admin']; ?>>Administrator</option>
				<option value="2"<?php echo $data['sgm']; ?>>Super GM</option>
				<option value="1"<?php echo $data['gm']; ?>>GM</option>
			</select><br />
			<br />
			<input type="submit" name="submit" value="Submit" />
			</div>
		</form>
	
	<?php elseif($complete): ?>
		<p>Your navigation link has been updated.<br />
	<?php endif; ?>

<?php endif; ?>

<?php if($complete): ?>
	<a href="<?php echo $_SERVER['PHP_SELF']; ?>?page=acp&amp;section=navigation-acp">Click here to continue</a></p>
<?php endif; ?>