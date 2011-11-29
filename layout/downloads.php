<p class="page-header">Downloads</p>
<?php if($data): ?>

<p>
<?php foreach ($download_data as $data): ?>

<?php if($data['mirror'] == "parent"): ?>
	<strong><?php echo $data['title']; ?></strong><br />
	<i><?php echo $data['description']; ?></i><br />
	-<a href="<?php echo $data['address']; ?>"><?php echo $data['host']; ?></a><br />
<?php elseif($data['mirror'] == "mirror"): ?>
	-<a href="<?php echo $data['address']; ?>"><?php echo $data['host']; ?></a><br />
<?php elseif($data['mirror'] == "neither"): ?>
	<br />
<?php endif; ?>

<?php endforeach; ?>
</p>

<?php else: ?>

<p>No downloads to display.</p>

<?php endif; ?>