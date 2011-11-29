<p class="page-header">Rebirthing</p>

<?php if (!isset($id)): ?>
	<p>This page will revert your character back to level 1 and job to beginner.<br />
	All your characters that are level 200 or above will be shown below.<br />
    <?php if ($rebirth_cost != 0): ?>
    The cost to rebirth is: <strong><?php echo number_format($rebirth_cost); ?></strong>
    <?php else: ?>
    There's no cost for rebirthing.
    <?php endif; ?>
    </p>
	<?php if (count($eligible_characters) != 0): ?>
		<ul>
			<?php foreach ($eligible_characters as $character): ?>
				<li>
                <img src="character_image.php?id=<?php echo $character['id']; ?>" alt="<?php echo $character['name']; ?>" /><br />
                <?php if ($character["mesos"] < $rebirth_cost): ?>
                <strong><?php echo $character['name']; ?></strong><br />
				<i>Not enough mesos to rebirth. Amount of mesos remaining: <?php echo number_format($rebirth_cost - $character["mesos"]); ?>.</i>
                <?php else: ?>
                <a href="<?php echo $_SERVER['PHP_SELF']; ?>?page=ucp&amp;section=rebirth&amp;character=<?php echo $character['id']; ?>"><?php echo $character['name']; ?></a>
                <?php endif; ?>
                </li>
			<?php endforeach; ?>
		</ul>
	<?php else: ?>
		<p>None of your characters are eligible for rebirth...</p>
	<?php endif; ?>
<?php else: ?>
	<?php if ($online): ?>
		<p>Log out of the game first then refresh this page.</p>
	<?php elseif (!$mesos): ?>
		<p>This character doesn't have the required amount of mesos (<?php echo number_format($rebirth_cost); ?>) to rebirth...<br />
		You have <?php echo number_format($character_mesos); ?> mesos.</p>
	<?php else: ?>
		<p>When you log on again your character will be reborn.</p>
	<?php endif; ?>
	
	<p><a href="<?php echo $_SERVER['PHP_SELF']; ?>?page=ucp&amp;section=rebirth">Click here to continue.</a></p>

<?php endif; ?>