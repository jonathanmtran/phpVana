<?php if ($news): ?>
	<?php foreach($news_data as $news): ?>
		<h2><?php echo $news['title']; ?></h2>
		<p class="post-by">posted by: <?php echo $news['owner']; ?> on <?php echo $news['date']; ?> @ <?php echo $news['time']; ?></p>
		<p><?php echo $news['content']; ?></p>
		<br />
		<p class="post-footer">
			<a href="<?php echo $_SERVER['PHP_SELF']; ?>?page=news&amp;id=<?php echo $news['id']; ?>" class="comments">Comments (<?php echo $news['num_comments']; ?>)</a> |
			<span class="date"><?php echo $news['date']; ?></span>
		</p>
		<br />
	<?php endforeach; ?>
<?php elseif (!$news): ?>
	<p>There are no news articles to display</p>
<?php endif; ?>