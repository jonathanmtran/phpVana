<p class="page-header">Website Settings</p>
<?php if(!$submit): ?>
	<form action="<?php echo $_SERVER['PHP_SELF']; ?>?page=acp&amp;section=settings" method="post">
		<div>
			<table style="width:92%">
				<tr>
					<td style="width:70%">Website Name<br /><span class="small">This setting defines the name that is shown in the title bar, the welcome page and the footer.</span></td>
					<td><input type="text" name="site_name" value="<?php echo stripslashes($phpVana_config['site_name']); ?>" /></td>
				</tr>
				<tr>
					<td>Website Slogan<br />
						<span class="small">This setting defines the name that is shown in the title bar after the website name and other places defined by the template. One line per slogan.</span></td>
					<td><textarea name="site_slogan" rows="5" cols="20" wrap="off"><?php echo stripslashes($phpVana_config['site_slogan']); ?></textarea></td>
				</tr>
				<tr>
					<td>Front Page News Limit<br /><span class="small">This setting limits how many news posts are shown on the front page.</span></td>
					<td><input type="text" name="news_limit" value="<?php echo $phpVana_config['news_limit']; ?>" /></td>
				</tr>
				<tr>
					<td>Website Template<br /><span class="small">This setting defines what template to use for your website<br />If you delete your template, the RandomlyPoked template will be applied as a backup.</span></td>
					<td>
					<select name="template">
					<?php foreach($templates as $template): ?>
						<option value="<?php echo $template['file']; ?>" <?php echo $template['selected'] ? 'selected="selected"' : ''; ?>><?php echo $template['file']; ?></option>
					<?php endforeach; ?>
					</select>
					</td>
				</tr>
				<tr>
					<td>Rebirth Cost<br /><span class="small">This setting is for the cost of rebirth. <br />If you do not wish to charge mesos for rebirth, enter 0.</span></td>
					<td><input type="text" name="rebirth_cost" value="<?php echo $phpVana_config['rebirth_cost']; ?>" /></td>
				</tr>
				<tr>
					<td>Time Format<br /><span class="small">This setting is for formatting the display of the time. <br />Formatting options can be found at <a href="http://www.php.net/date" rel="top">http://www.php.net/date</a></span></td>
					<td><input type="text" name="time_format" value="<?php echo $phpVana_config['time_format']; ?>" /></td>
				</tr>
				<tr>
					<td>Date Format<br /><span class="small">This setting is for formatting the display of the date. <br />Formatting options can be found at <a href="http://www.php.net/date" rel="top">http://www.php.net/date</a></span></td>
					<td><input type="text" name="date_format" value="<?php echo $phpVana_config['date_format']; ?>" /></td>
				</tr>
				<tr>
					<td>Character Delete Method<br /><span class="small">You can choose between having your users use their birthdate or an 8 digit number to delete their characters.</span></td>
					<td>
						<select name="char_delete_method">
							<option value="birthdate"<?php echo $phpVana_config['char_delete_method'] == "birthdate" ? ' selected="selected"' : ""; ?>>Birthdate</option>
							<option value="password"<?php echo $phpVana_config['char_delete_method'] == "password" ? ' selected="selected"' : ""; ?>>Password</option>
						</select>
					</td>
				</tr>
				<tr>
					<td>Use Captcha for user creation<br />
					<span class="small">You can use this to prevent bots that create users. The user needs to type over 2 pieces of text and if this is correct, the account will be created. <a href="http://en.wikipedia.org/wiki/CAPTCHA" target="_blank">Click here for more information about Captcha.</a></span></td>
					<td><label>
						<select name="use_captcha" id="use_captcha">
							<option value="yes"<?php echo $phpVana_config['use_captcha'] == "yes" ? ' selected="selected"' : ""; ?>>Yes</option>
							<option value="no"<?php echo $phpVana_config['use_captcha'] == "no" ? ' selected="selected"' : ""; ?>>No</option>
						</select>
					</label></td>
				</tr>
				<tr>
					<td>Automatic 'Go to:' submission<br />
					<span class="small">You can set if the 'Go to:' selection automaticly gets submitted, so you don't need to click 'Go', or revert it so you need to press the button.</span></td>
					<td><select name="auto_sub_goto" id="auto_sub_goto">
						<option value="yes"<?php echo $phpVana_config['auto_sub_goto'] == "yes" ? ' selected="selected"' : ""; ?>>Yes</option>
						<option value="no"<?php echo $phpVana_config['auto_sub_goto'] == "no" ? ' selected="selected"' : ""; ?>>No</option>
					</select></td>
				</tr>
				<tr>
					<td>reCAPTCHA private code<br />
					<span class="small">This code is used for the reCAPTCHA connection between their server and your server for checking the reCAPTCHA code. I suggest that you use your own and not mine :). <br />
					You can get a code at the webste <a href="http://www.recaptcha.net/" rel="_blank">http://www.recaptcha.net/</a></span></td>
					<td><textarea name="private_cap_code" id="private_cap_code" rows="5" cols="20"><?php echo $phpVana_config['private_cap_code']; ?></textarea></td>
				</tr>
				<tr>
					<td>reCAPTCHA public code<br />
					<span class="small">This code is used for the reCAPTCHA connection between the client and your server for getting the reCAPTCHA code. I suggest that you use your own and not mine :). <br />
					You can get a code at the webste <a href="http://www.recaptcha.net/" rel="_blank">http://www.recaptcha.net/</a></span></td>
					<td><textarea name="public_cap_code" id="public_cap_code" rows="5" cols="20"><?php echo $phpVana_config['public_cap_code']; ?></textarea></td>
				</tr>
				<tr>
				  <td>Password encryption<br />
				    <span class="small">You're able to use SHA-1 and SHA-512 for encrypting the passwords. SHA-512 is a stronger encryption, but is not supported by Vana where the rev is lower than 2897. I suggest you use SHA-512 for hashing, but the default is SHA-1 (for old versions of Vana).</span></td>
				  <td><label>
				    <select name="password_encryption" id="password_encryption">
				      <option value="SHA-1"<?php echo $phpVana_config['password_encryption'] == "SHA-1" ? ' selected="selected"' : ""; ?>>SHA-1</option>
				      <option value="SHA-512"<?php echo $phpVana_config['password_encryption'] == "SHA-512" ? ' selected="selected"' : ""; ?>>SHA-512</option>
			        </select>
			      </label></td>
			  </tr>
			</table>
			<input type="submit" name="submit" value="Submit" />
		</div>
	</form>
<?php elseif($submit): ?>
<p>Settings have been updated.<br />
Click <a href="<?php echo $_SERVER['PHP_SELF']; ?>?page=acp&amp;section=settings">here</a> to continue.</p>
<?php endif; ?>