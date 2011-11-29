<?php if(!$id): ?>
<?php foreach ($news_data as $data): ?>
	<h2><?php echo $data['title']; ?></h2>
	<p class="post-by">posted by: <?php echo $data['owner']; ?></p>
	<p><?php echo $data['content']; ?></p>
	<p class="post-footer">
		<a href="<?php echo $_SERVER['PHP_SELF']; ?>?page=news&amp;id=<?php echo $data['id']; ?>" class="comments">Comments (<?php echo $data['num_comments']; ?>)</a> |
		<span class="date"><?php echo $data['date']; ?></span>
	</p>
<?php endforeach; ?>

<?php elseif($id): ?>
	<h2><?php echo $news_data['title']; ?></h2>
	<p class="post-info">Posted by <?php echo $news_data['owner']; ?></p>
	<p><?php echo $news_data['content']; ?></p>
	
	<p class="post-footer">		
	Comments: <?php echo $news_data['num_comments']; ?> |
	<span class="date"><?php echo $news_data['date']; ?></span>
	</p>
<?php if ($news_data['num_comments'] > 0): ?>
	<ol class="commentlist">
<?php $i = 0; ?>
<?php foreach ($news_data['comments'] as $comment): ?>
<?php $i++; ?>
		<a name="comment-<?php echo $i; ?>" />
		<li class="alt" id="comment">
			<cite>
				<?php echo $comment['username']; ?> Says: <br />
				<span class="comment-data"><a href="#comment-<?php echo $i; ?>" title=""><?php echo $comment['date']; ?> at <?php echo $comment['time']; ?></a></span>
				<?php if (isset($_SESSION['nav_level']) && $_SESSION['nav_level'] > 1): ?>
					<span class="comment-data"><a href="<?php echo $_SERVER['PHP_SELF']; ?>?page=news&amp;id=<?php echo $news_data['id']; ?>&amp;do=delete_comment&amp;comment_id=<?php echo $comment['id']; ?>">[x]</a></span>
				<?php endif; ?>
			</cite>
			<div class="comment-text">
				<p><?php echo $comment['comment']; ?></p>
			</div>		
		</li>
<?php endforeach; ?>
	</ol>
<?php endif; ?>

	<h3 id="respond">Leave a comment!</h3>	
<?php if(isset($_SESSION['user_id'])): ?>
	<form action="<?php echo $_SERVER['PHP_SELF']; ?>?page=news&amp;id=<?php echo $news_data['id']; ?>&amp;do=post_comment" method="post" id="commentform">
		<p>
			<label for="message">Your Message:</label><br />
			<textarea id="message" name="message" rows="10" cols="70" tabindex="4"></textarea>
		</p>

		<p class="no-border">
			<input class="button" type="submit" value="Submit" tabindex="5" /></p>
	</form>
<?php else: ?>
	<p>You must be logged in to reply to this post</p>
<?php endif; ?>

<?php endif; ?>