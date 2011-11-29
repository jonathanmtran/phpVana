<p class="page-header">News Administration</p>

<?php if(!isset($action)): ?>
	<p><a href="<?php echo $_SERVER['PHP_SELF']; ?>?page=acp&amp;section=news&amp;action=new">Add News Article</a></p>
	<?php if (!empty($news_data)): ?>
		<table>
			<tr>
				<td style="width:151"><strong>Title</strong></td>
				<td style="width:106"><strong>Date</strong></td>
				<td style="width:50"><strong>Owner</strong></td>
				<td style="width:74"><strong>Actions</strong></td>
			</tr>
		<?php foreach($news_data as $news): ?>
			<tr>
				<td><?php echo $news['title']; ?></td>
				<td><?php echo $news['date']; ?> @ <?php echo $news['time']; ?></td>
				<td><?php echo $news['owner']; ?></td>
				<td><a href="<?php echo $_SERVER['PHP_SELF']; ?>?page=acp&amp;section=news&amp;action=edit&amp;id=<?php echo $news['id']; ?>">Edit</a> | <a href="<?php echo $_SERVER['PHP_SELF']; ?>?page=acp&amp;section=news&amp;action=delete&amp;id=<?php echo $news['id']; ?>">Delete</a></td>
			</tr>
		<?php endforeach; ?>
		</table>		
	<?php else: ?>
		<p>There are no news articles to display.</p>
	<?php endif; ?>

<?php elseif($action == "new"): ?>
	<?php if(!$complete): ?>
		<p>Add News Article</p>
		<br />
		<form action="<?php echo $_SERVER['PHP_SELF']; ?>?page=acp&amp;section=news&amp;action=new" method="post">
			Title:<br />
			<input type="text" name="title"><br />
			<br />
			Content:<br />
			<textarea name="content" cols="40" rows="8"></textarea>
			<input type="submit" name="submit" value="Submit">
		</form>
	<?php elseif($complete): ?>
		<p>Your news article has been added.<br />
	<?php endif; ?>
	
<?php elseif($action == "edit"): ?>
	<?php if(!$complete): ?>
		<p>Edit News Article</p>
		<form action="<?php echo $_SERVER['PHP_SELF']; ?>?page=acp&amp;section=news&amp;action=edit&amp;id=<?php echo $news_data['id']; ?>" method="post">
			<small>Title</small><br />
			<input type="text" name="title" value="<?php echo $news_data['title']; ?>"><br />
			<br />
			<small>Content</small><br />
			<textarea name="content" cols="40" rows="8"><?php echo $news_data['content']; ?></textarea>
			<input type="submit" name="submit" value="Submit">
		</form>	
	<?php elseif($complete): ?>
		<p>Your news article has been updated.<br />
	<?php endif; ?>

<?php elseif($action == "delete"): ?>
	<?php if(!$complete): ?>
		<p>Delete News Article</p>
		<p><form action="<?php echo $_SERVER['PHP_SELF']; ?>?page=acp&amp;section=news&amp;action=delete&amp;id=<?php echo $id; ?>" method="post">
		Are you sure you want to delete this news article?<br />
		<input type="submit" name="submit" value="Yes"> <input type="button" value="No" onclick="history.go(-1)"
>
		</form></p>
	<?php elseif($complete): ?>
		<p>Your news article has been deleted.<br />
	<?php endif; ?>
	
<?php endif; ?>

<?php if ($complete): ?>
	<a href="<?php echo $_SERVER['PHP_SELF']; ?>?page=acp&amp;section=news">Click here to continue</a></p>
<?php endif; ?>