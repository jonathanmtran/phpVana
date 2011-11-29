<p><strong>Edit User Account</strong></p>

<?php if (!$username): ?>
<p>Enter a username to edit their account.<br /></p>
<form action="<?php echo $_SERVER['PHP_SELF']; ?>?page=acp&amp;section=edit_user" method="post">
	<div>
	<input type="text" name="username" /><br />
	Username<br />
	<input type="submit" value="Submit" />
	</div>
</form>

<?php else: ?>

<?php if (!$complete): ?>
<form action="<?php echo $_SERVER['PHP_SELF']; ?>?page=acp&amp;section=edit_user" method="post">
	<div>
	<input type="hidden" name="id" value="<?php echo $user_data['id']; ?>" />
	<input type="text" name="username" value="<?php echo $user_data['username']; ?>" /><br />
	Username<br />
	<br />
	<input type="text" name="password" /><br />
	Password<br />
	<small>Leave it blank if you are not changing the password</small><br />
	<br />
	<input type="text" name="pin" value="<?php echo $user_data['pin']; ?>" /><br />
	Pin<br />
	<br />
	<select name="gender">
	<?php foreach ($user_data['gender'] as $gender): ?>
		<option value="<?php echo $gender['value']; ?>"<?php echo $gender['selected']; ?>><?php echo $gender['text']; ?></option>
	<?php endforeach; ?>
	</select><br />
	Gender<br />
	<br />
	<select name="gm">
	<?php foreach ($user_data['gm'] as $gm): ?>
		<option value="<?php echo $gm['value']; ?>"<?php echo $gm['selected']; ?>><?php echo $gm['text']; ?></option>
	<?php endforeach; ?>
	</select><br />
	User Level<br />
	<br />
	<input type="submit" name="submit" value="Submit" />
	</div>
</form>

<?php else: ?>
<p>The user account has been updated<br />
Click <a href="<?php echo $_SERVER['PHP_SELF']; ?>?page=acp&amp;section=edit_user">here</a> to continue.</p>
<?php endif; ?>

<?php endif; ?>