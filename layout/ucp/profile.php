<p><strong>Update Profile</strong></p>

<?php if(!$success): ?>

<?php if(isset($error_msg)): ?>
	<p>The following error has occured: <br />
	<?php echo error_message($error_msg); ?></p>
<?php endif; ?>

<form action="index.php?page=ucp&amp;section=profile" method="post">
	<div>
	<input type="text" name="username" value="<?php echo $username; ?>" readonly="readonly" /><br />
	<small>Username [Cannot be changed]</small><br />
	<br />
	
	<input type="password" name="password" maxlength="12" /><br />
	<small>Current password</small><br />
	<br />
	
	<input type="password" name="new_password" maxlength="12" /><br />
	<small>New password. [12 characters max]</small><br />
	<br />
	
	<input type="password" name="cnew_password" maxlength="12" /><br />
	<small>Confirm new password</small><br />
	<br />
	
	<input type="submit" name="submit" value="Submit" />
	</div>
</form>
<?php else: ?>

<p>Success! Your profile has been updated</p>

<?php endif; ?>