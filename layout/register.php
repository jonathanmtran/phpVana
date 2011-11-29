<p class="page-header">Register</p>
<?php if(!isset($username)): ?>

<?php if(isset($error_code)): ?>
	<p>The following error has occured: <br />
	<?php error_message($error_code); ?></p>
<?php endif; ?>
<form action="<?php echo $_SERVER['PHP_SELF']; ?>?page=register" method="post">
	<div>
		Username:<br />
		<input type="text" name="username" maxlength="12" value="" /><br />
		<small>12 characters max</small><br />
		<br />
	
		Password:<br />
		<input type="password" name="password" maxlength="12" value="" /><br />
		<small>12 characters max</small><br />
		<br />
	
<?php if ($char_delete_method == "birthdate"): ?>
		Birthdate:<br />
		<input type="text" name="day" size="2" maxlength="2" /> - 
		<select name="month">
			<option value="01">January</option>
			<option value="02">February</option>
			<option value="03">March</option>
			<option value="04">April</option>
			<option value="05">May</option>
			<option value="06">June</option>
			<option value="07">July</option>
			<option value="08">August</option>
			<option value="09">September</option>
			<option value="10">October</option>
			<option value="11">November</option>
			<option value="12">December</option>
		</select> - 
		<select name="year">
<?php for ($i = date("Y"); $i >= 1900; $i--): ?>
			<option value="<?php echo $i; ?>"><?php echo $i; ?></option>
<?php endfor; ?>
		</select><br />
		<small>Day - Month - Year</small><br />
<?php elseif ($char_delete_method == "password"): ?>
		Character Delete Password:<br />
		<input type="text" name="char_delete_password" maxlength="8" /><br />
		<small>8 Numbers max</small><br />
<?php endif; ?>
<?php if ($use_captcha == "yes"): ?>
		<br />
<script type="text/javascript">
var RecaptchaOptions = { theme : 'clean' };
</script>

<?php
require_once('includes\recaptchalib.php');
echo recaptcha_get_html($public_cap_code);
endif; 
?>
		<br />
		
		<input type="submit" value="Submit" name="submit" />
	</div>
</form>
<?php endif; ?>

<?php if(isset($username)): ?>
<p>Welcome, <?php echo $username; ?>!<br />
Your account has been created. <br />
<br />
Click <a href="<?php echo $_SERVER['PHP_SELF']; ?>?page=downloads">here</a> to go to our downloads page and download our client.</p>
<?php endif; ?>
