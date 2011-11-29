<h1>Database Update</h1>
<p>
<?php if(isset($database_info)): ?>
	At this page, you can update you database by clicking the links below<br />
	If you cannot click the link, you have executed that SQL file already.<br />
	<br />
	<?php foreach($database_info as $row): ?>
		<?php if(!$row['executed']): ?>
		- <a href="<?php echo $_SERVER['PHP_SELF']; ?>?page=acp&amp;section=database_update&amp;file=<?php echo $row['file']; ?>"><?php echo $row['file']; ?></a>
		<?php elseif($row['executed']): ?>
		- <?php echo $row['file']; ?>
		<?php endif; ?>
		<br />
	<?php endforeach; ?>
<?php else: ?>
	<?php if ($execute_result): ?>
		<?php echo $sql_file; ?> has been executed successfully.<br />
		<?php if($sql_message): ?>
		Message: <?php echo $sql_message; ?>
		<?php endif; ?>		
	<?php else: ?>
		<?php echo $sql_file; ?> has been executed already.
	<?php endif; ?>
<?php endif; ?>
</p>