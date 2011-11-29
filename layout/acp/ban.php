<p class="page-header">Ban User</p>

<?php if(!isset($action)): ?>
	<p><a href="<?php echo $_SERVER['PHP_SELF']; ?>?page=acp&amp;section=ban&amp;action=ban_user">Ban User</a></p>
	<?php if ($list): ?>
		<table>
		<tr>
			<td><strong>Username</strong></td>
			<td style="width:25%"><strong>Ban Reason</strong></td>
			<td><strong>Ban Expire</strong></td>
			<td><strong>Actions</strong></td>
		</tr>
		
		<?php foreach ($banned_users as $banned): ?>
		<tr>
			<td><?php echo $banned['username']; ?></td>
			<td><?php echo $banned['ban_reason']; ?></td>
			<td><?php echo $banned['ban_expire']; ?></td>
			<td><a href="<?php echo $_SERVER['PHP_SELF']; ?>?page=acp&amp;section=ban&amp;action=revise_ban&amp;id=<?php echo $banned['id']; ?>">Revise</a> | <a href="<?php echo $_SERVER['PHP_SELF']; ?>?page=acp&amp;section=ban&amp;action=remove_ban&amp;id=<?php echo $banned['id']; ?>">Remove</a></td>
		</tr>
		<?php endforeach; ?>
		
		</table>
	<?php else: ?>
		<p>There are no banned users to display</p>
	<?php endif; ?>
<?php elseif($action == 'ban_user'): ?>
	<?php if(!$complete): ?>
		<p>Ban User</p>
		<form action="<?php echo $_SERVER['PHP_SELF']; ?>?page=acp&amp;section=ban&amp;action=ban_user" method="post">
 		<div>
			<input type="text" name="username" /><br />
			<small>Username</small><br />
			<br />
			<select name="ban_reason">
			<?php foreach($ban_reason as $reason): ?>
			<option value="<?php echo $reason['code']; ?>"><?php echo $reason['text']; ?></option>
			<?php endforeach; ?>
			</select><br />
			<small>Ban Reason</small><br />
			<br />
			<?php
			hour_selector();
			minsec_selector("minute");
			minsec_selector("seconds");
			meridiem_selector();
			?>
			<br />
			<small>Time of Ban Expire</small><br />
			<br />
			<script type="text/javascript" src="acp/calendarDateInput.js">
		
			/***********************************************
			* Jason's Date Input Calendar- By Jason Moon http://calendar.moonscript.com/dateinput.cfm
			* Script featured on and available at http://www.dynamicdrive.com
			* Keep this notice intact for use.
			***********************************************/
		
			</script>
			<script type="text/javascript">DateInput('ban_expire', true, 'YYYY-MM-DD')</script>
			<small>Date of Ban Expire</small><br />
			<br />
			<input type="submit" name="submit" value="Submit" />
			</div>
		</form>	
	<?php elseif($complete): ?>
		<p><?php echo $username; ?> has been banned.<br />
		Reason: <?php echo $ban_reason; ?><br />
	<?php endif; ?>

<?php elseif($action == 'revise_ban'): ?>
	<?php if(!$complete): ?>
		<p>Revise Ban</p>
		<form action="<?php echo $_SERVER['PHP_SELF']; ?>?page=acp&amp;section=ban&amp;action=revise_ban&amp;id=<?php echo $user_data['id']; ?>" method="post">
 		<div>
			<input type="text" name="username" value="<?php echo $user_data['username']; ?>" readonly="readonly" /><br />
			<small>Username</small><br />
			<br />
			<select name="ban_reason">
			<?php foreach($ban_reason as $reason): ?>
				<?php if($reason['code'] == $user_data['ban_reason']): ?>
					<option value="<?php echo $reason['code']; ?>" selected="selected"><?php echo $reason['text']; ?></option>
				<?php else: ?>
					<option value="<?php echo $reason['code']; ?>"><?php echo $reason['text']; ?></option>
				<?php endif; ?>
			<?php endforeach; ?>
			</select><br />
			<small>Ban Reason</small><br />
			<br />
			<?php hour_selector($user_data['hour']); minsec_selector("minute", $user_data['minute']); minsec_selector("seconds", $user_data['seconds']); meridiem_selector($user_data['hour']); ?><br />
			<small>Time of Ban Expire</small><br />
			<br />
			<script type="text/javascript" src="acp/calendarDateInput.js">
		
			/***********************************************
			* Jason's Date Input Calendar- By Jason Moon http://calendar.moonscript.com/dateinput.cfm
			* Script featured on and available at http://www.dynamicdrive.com
			* Keep this notice intact for use.
			***********************************************/
		
			</script>
			<script type="text/javascript">DateInput('ban_expire', true, 'YYYY-MM-DD', '<?php echo $user_data['ban_expire']; ?>')</script>
			<small>Date of Ban Expire</small><br />
			<br />
			<input type="submit" name="submit" value="Submit" />
			</div>
		</form>	
	<?php elseif($complete): ?>
		<p>The ban has been revised.<br />
	<?php endif; ?>

<?php elseif($action == 'remove_ban'): ?>
	<?php if(!$complete): ?>
		<p>Remove Ban</p>	
		<form action="<?php echo $_SERVER['PHP_SELF']; ?>?page=acp&amp;section=ban&amp;action=remove_ban&amp;id=<?php echo $_GET['id']; ?>" method="post">
		<div>
		Are you sure you want to remove the ban for this user?<br />
		<input type="submit" name="submit" value="Yes" /> <input type="button" value="No" onclick="history.go(-1)" />
		</div>
		</form>
	<?php elseif($complete): ?>
		<p>The ban has been removed.<br />
	<?php endif; ?>

<?php endif; ?>

<?php if ($complete): ?>
	<a href="<?php echo $_SERVER['PHP_SELF']; ?>?page=acp&amp;section=ban">Click here to continue</a></p>
<?php endif; ?>