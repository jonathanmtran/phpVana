<p class="page-header">Negative EXP Fix</p>
<?php if (!isset($character)): ?>
<p>This page will fix your character's negative experience points.</p>

<?php if ($num_negative_exp_character != 0): ?>
	<p>Select your character from the list below and the experience points of that character will be reset to zero.</p>
	<ul>
	<?php foreach ($negative_exp_characters as $user): ?>
	<li><a href="<?php echo $_SERVER['PHP_SELF']; ?>?page=ucp&amp;section=negative_exp&amp;character=<?php echo $user['id']; ?>"><?php echo $user['name']; ?></a></li><br />
	<?php endforeach; ?>
	</ul>
<?php else: ?>
	<p>None of your characters have negative experience points.</p>
<?php endif; ?>

<?php else: ?>
	<?php if ($hacking): ?>
	<p>Hacking attempt</p>
	<?php endif; ?>

	<?php if($logged_in): ?>
	<p>Log out of the game before fixing the negative experience points on your character.</p>
	<?php else: ?>
	<p>The experience points have been reset to zero.</p>
	<?php endif; ?>

<?php endif; ?>