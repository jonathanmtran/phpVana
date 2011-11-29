<?php $sorting = isset($_GET["sort"]) ? $_GET["sort"] : ""; ?>
			<p class="page-header">Ranking</p>
			<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="get">
				<div>
					<input type="hidden" name="page" value="ranking" />
					<strong>Sort by:</strong><br />
					<select name="sort"<?php echo ($auto_sub_goto == "yes" ? ' onchange="this.form.submit();"' : ''); ?>>
						<optgroup label="Basic sorting:">
							<option value=""<?php echo getSelected($sorting, ""); ?>>Total</option>
							<option value="fame"<?php echo getSelected($sorting, "fame"); ?>>Fame</option>
						</optgroup>
						<optgroup label="Explorer jobs:">
							<option value="beginner"<?php echo getSelected($sorting, "beginner"); ?>>Beginner</option>
							<option value="bowman"<?php echo getSelected($sorting, "bowman"); ?>>Bowman</option>
							<option value="magician"<?php echo getSelected($sorting, "magician"); ?>>Magician</option>
							<option value="thief"<?php echo getSelected($sorting, "thief"); ?>>Thief</option>
							<option value="warrior"<?php echo getSelected($sorting, "warrior"); ?>>Warrior</option>
							<option value="pirate"<?php echo getSelected($sorting, "pirate"); ?>>Pirate</option>
						</optgroup>
						<optgroup label="Knights of Cygnus jobs:">
							<option value="noblesse"<?php echo getSelected($sorting, "noblesse"); ?>>Noblesse</option>
							<option value="windarcher"<?php echo getSelected($sorting, "windarcher"); ?>>Wind Archer</option>
							<option value="blazewizard"<?php echo getSelected($sorting, "blazewizard"); ?>>Blaze Wizard</option>
							<option value="nightwalker"<?php echo getSelected($sorting, "nightwalker"); ?>>Night Walker</option>
							<option value="dawnwarrior"<?php echo getSelected($sorting, "dawnwarrior"); ?>>Dawn Warrior</option>
							<option value="thunderdreaker"<?php echo getSelected($sorting, "thunderbreaker"); ?>>Thunder Breaker</option>
						</optgroup>
<?php if ($show_gms): ?>
						<optgroup label="Special sorting:">
							<option value="gmjob"<?php echo getSelected($sorting, "gmjob"); ?>>Game Master (job)</option>
							<option value="gmstat"<?php echo getSelected($sorting, "gmstat"); ?>>Game Master (status)</option>
						</optgroup>
<?php endif; ?>
					</select>
					<input type="submit" value="Go" />
					<br />
					<strong>Search character:</strong><br />
					<input name="char_name" type="text" size="30" maxlength="12" /> <input type="submit" value="Search!" />
<?php if (isset($found_char_rank) && $found_char_rank == -1): ?>
					<br />
					<strong>Error: Character not found</strong>
<?php endif; ?>
				</div>
			</form>
			<br />

<?php if ($num_records != 0): ?>
			<table>
				<tr>
					<th style="width:48px"><div style="text-align:center">Rank</div></td>
					<th style="width:96px"><div style="text-align:center">Character</div></td>
					<th style="width:104px"><div style="text-align:center">World</div></td>
					<th style="width:85px"><div style="text-align:center">Job</div></td>
<?php if($sort == "fame"): ?>
					<th style="width:120px"><div style="text-align:center">Fame / Move</div></td>
<?php else: ?>
					<th style="width:120px"><div style="text-align:center">Level / Move</div></td>
<?php endif; ?>
				</tr>
<?php foreach ($ranking_data as $character): ?>
<?php if (isset($found_char_rank) && $found_char_rank == $character['id']): ?>
				<tr style="border:double" bordercolor="#000000">
<?php else: ?>
				<tr>
<?php endif; ?>
					<td>
						<div class="content" style="text-align:center"><?php echo $character['rank']; ?></div>
					</td>
					<td>
						<div class="content" style="text-align:center"><img src="character_image.php?id=<?php echo $character['id']; ?>" alt="<?php echo $character['name']; ?>" /></div>
					</td>
					<td>
						<div class="content" style="text-align:center"><?php echo get_world($character['world_id']); ?><br /><?php echo $worldnames[$character['world_id']]; ?></div>
					</td>
					<td>
						<div class="content" style="text-align:center"><img src="images/job_<?php echo $character['job_class']; ?>.gif" alt="<?php echo $character['job_name']; ?>" /><br /><?php echo $character['job_name']; ?></div>
					</td>
					<td>
						<div class="content" style="text-align:center">
							<strong><?php echo $sort == "fame" ? $character['fame'] : $character['level']; ?></strong><br />
							<span style="font-family:ARIAL; font-size:11px; letter-spacing:0px; color:#666666; line-height: 12px;">(<?php echo number_format($character['exp'], 0); ?>)</span>
							<hr />
<?php if ($character['move'] == 0): ?>
							-
<?php elseif ($character['move'] > 0): ?>
							<img style="vertical-align: middle;" border="0" src="images/up.png" alt="Up" />&nbsp;<font style="font-weight: bold;" color="red"><?php echo abs($character['move']); ?></font>
<?php elseif ($character['move'] < 0): ?>
							<img style="vertical-align: middle;" border="0" src="images/down.png" alt="Down" />&nbsp;<font style="font-weight: bold;" color="blue"><?php echo abs($character['move']); ?></span>
<?php endif; ?>
						</div>
					</td>
				</tr>
	<?php endforeach; ?>
			</table>

			<p>
<?php if($paging_data['previous']): ?>
				<a href="<?php echo $_SERVER['PHP_SELF']; ?>?page=ranking&amp;trang=<?php echo $paging_data['previous']; ?>&amp;sort=<?php echo $paging_data['sort']; ?>">[Previous]</a>
<?php else: ?>
				<strong>[Previous] </strong>
<?php endif; ?>
				Showing page <strong><?php echo $paging_data['current']; ?></strong> of <strong><?php echo $paging_data['pages']; ?></strong>
<?php if($paging_data['next']): ?>
				<a href="<?php echo $_SERVER['PHP_SELF']; ?>?page=ranking&amp;trang=<?php echo $paging_data['next']; ?>&amp;sort=<?php echo $paging_data['sort']; ?>">[Next]</a>
<?php else: ?>
				<strong> [Next]</strong>
<?php endif; ?>
				<br />
				Go to page: <?php echo $pages_list; ?>
			</p>

<?php else: ?>
			<p>No ranking data to display</p>
<?php endif; ?>