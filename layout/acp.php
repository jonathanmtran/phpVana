			<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="get">
				<div>
					<p>
						<input type="hidden" name="page" value="acp" />
						<strong>Go to:</strong>
						<select name="section"<?php echo ($auto_sub_goto == "yes" ? ' onchange="this.form.submit();"' : ''); ?>>
							<option value="home">Home</option>
<?php foreach ($navigation as $nav): ?>
							<option value="<?php echo $nav['page']; ?>"<?php echo ((isset($_GET["section"]) && $_GET["section"] == $nav['page']) ? 'selected="selected"' : ""); ?>><?php echo $nav['title']; ?></option>
<?php endforeach; ?>
						</select>
						<input type="submit" value="Go" />
					</p>
				</div>
			</form>
<?php echo $section;?>