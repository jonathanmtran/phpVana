				<strong>Mini Ranking</strong><br />
				<table style="margin-left: auto; margin-right: auto;">
<?php foreach ($ranking_data as $data): ?>
					<tr>
						<td><?php echo $data['rank']; ?></td>
						<td><?php echo $data['character_name']; ?></td>
					</tr>
<?php endforeach; ?>
				</table>