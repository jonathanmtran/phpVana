<p class="page-header">Ranking Settings</p>
<?php if(!$submit): ?>
<form action="<?php echo $_SERVER['PHP_SELF']; ?>?page=acp&amp;section=ranking" method="post">
	<div>
	<table>
		<tr>
			<td style="width:75%">Ranking Module Limit<br /><span class="small">This setting limits how many people are shown in the ranking module if it is included in the template.</span></td>
			<td><input type="text" name="ranking_module_limit" value="<?php echo $phpVana_config['ranking_module_limit']; ?>" /></td>
		</tr>
		<tr>
			<td style="width:75%">Ranking Page Limit<br /><span class="small">This setting limits how many people are shown per page on the ranking page.</span></td>
			<td><input type="text" name="ranking_page_limit" value="<?php echo $phpVana_config['ranking_page_limit']; ?>" /></td>
		</tr>
		<tr>
			<td style="width:75%">Show GM Users<br /><span class="small">This setting defines if gm users are shown on the rankings.</span></td>
			<td>
				<select name="show_gm_user">
					<option value="0" <?php echo $ranking_show_gm_user_disabled; ?>>Disabled</option>
					<option value="1" <?php echo $ranking_show_gm_user_enabled; ?>>Enabled</option>
				</select>
			</td>
		</tr>
		<tr>
			<td style="width:75%">Show GM Characters<br /><span class="small">This setting defines if gm characters [job 900 and 910] are shown in the ranking.</span></td>
			<td>
				<select name="show_gm_character">
					<option value="0" <?php echo $ranking_show_gm_character_disabled; ?>>Disabled</option>
					<option value="1" <?php echo $ranking_show_gm_character_enabled; ?>>Enabled</option>
				</select>
			</td>
		</tr>	
	</table>
	<input type="submit" name="submit" value="Submit" />
	</div>
	</form>

<?php elseif($submit): ?>

<p>Settings have been updated.<br />
Click <a href="<?php echo $_SERVER['PHP_SELF']; ?>?page=acp&amp;section=ranking">here</a> to continue.</p>

<?php endif; ?>