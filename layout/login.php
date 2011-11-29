<p class="page-header">Login</p>

<?php if(isset($error_code)): ?>
	<p>The following error has occured: <br />
	<?php error_message($error_code); ?></p>
<?php endif; ?>

<form action="<?php echo $_SERVER['PHP_SELF']; ?>?page=login" method="post">
	<div>
	<input type="text" name="username" maxlength="12" /><br />
	<small>Username</small><br />
	<br />
	<input type="password" name="password" maxlength="12" /><br />
	<small>Password</small><br />
	<br />
	<input type="submit" name="submit" value="Submit" />
	</div>
</form>