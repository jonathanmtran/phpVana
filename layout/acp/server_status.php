			<p class="page-header">Server Status</p>
<?php if (!isset($action)): ?>
<?php if($loginserver): ?>
			<table>
<?php foreach($server_list as $server): ?>
<?php if($server['type'] == 0): ?>
				<tr>
					<td><strong>Login Server</strong></td>
					<td><a href="<?php echo $_SERVER['PHP_SELF'];?>?page=acp&amp;section=server_status&amp;action=edit_loginserver&amp;id=<?php echo $server['id']; ?>">Edit</a></td>
				</tr>
				<tr>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
				</tr>
<?php elseif($server['type'] == 1): ?>
				<tr>
					<td>
						<strong><?php echo $server['name']; ?></strong><br />
						EXP Rate: <?php echo $server['exp_rate']; ?> | Quest Rate: <?php echo $server['quest_rate']; ?> | Meso Rate: <?php echo $server['meso_rate']; ?> | Drop Rate: <?php echo $server['drop_rate']; ?><br />
						<br />
						<table>
<?php if ($server['cash_server'] == null): ?>
							<tr>
                            <td>There's no cashserver added.</td> 
                            <td><a href="<?php echo $_SERVER['PHP_SELF'];?>?page=acp&amp;section=server_status&amp;action=create_cashserver&amp;parent=<?php echo $server['id']; ?>">Create</a></td></tr>
<?php else: ?>
							<tr>
								<td>Cashserver:</td>
							 	<td><a href="<?php echo $_SERVER['PHP_SELF'];?>?page=acp&amp;section=server_status&amp;action=edit_cashserver&amp;id=<?php echo $server['cash_server']['id']; ?>">Edit</a> | <a href="<?php echo $_SERVER['PHP_SELF'];?>?page=acp&amp;section=server_status&amp;action=delete_cashserver&amp;id=<?php echo $server['cash_server']['id']; ?>">Delete</a></td>
							</tr>
<?php endif; ?>
<?php if (count($server['channel_server']) == 0): ?>
							<tr><td>There are no channels added.</td></tr>
<?php else: ?>
<?php foreach($server['channel_server'] as $channel_server): ?>
							<tr>
								<td>Channel <?php echo $channel_server['channel']; ?>:</td>
							 	<td><a href="<?php echo $_SERVER['PHP_SELF'];?>?page=acp&amp;section=server_status&amp;action=edit_channelserver&amp;id=<?php echo $channel_server['id']; ?>">Edit</a> | <a href="<?php echo $_SERVER['PHP_SELF'];?>?page=acp&amp;section=server_status&amp;action=delete_channelserver&amp;id=<?php echo $channel_server['id']; ?>">Delete</a></td>
							</tr>
<?php endforeach; ?>
<?php endif; ?>
						</table>
<?php if (count($server['channel_server']) < 20): ?>
						<a href="<?php echo $_SERVER['PHP_SELF']; ?>?page=acp&amp;section=server_status&amp;action=add_channelserver&amp;parent=<?php echo $server['id']; ?>">Add a channel server to the database.</a>
<?php endif; ?>
					</td>
					<td style="vertical-align:top">
						<a href="<?php echo $_SERVER['PHP_SELF'];?>?page=acp&amp;section=server_status&amp;action=edit_worldserver&amp;id=<?php echo $server['id']; ?>">Edit</a> | <a href="<?php echo $_SERVER['PHP_SELF'];?>?page=acp&amp;section=server_status&amp;action=delete_worldserver&amp;id=<?php echo $server['id']; ?>">Delete</a>
					</td>
				</tr>
				<tr>
					<td>&nbsp;</td>
					<td>&nbsp;</td>
				</tr>
<?php endif; ?>
<?php endforeach; ?>
			</table>
			<p><a href="<?php echo $_SERVER['PHP_SELF']; ?>?page=acp&amp;section=server_status&amp;action=add_worldserver">Add a world server to the database.</a></p>
<?php elseif(!$loginserver): ?>
			<p>You don't have a login server in the database yet.<br />
			Click <a href="<?php echo $_SERVER['PHP_SELF']; ?>?page=acp&amp;section=server_status&amp;action=add_loginserver">here</a> to add a login server.</p>
<?php endif; ?>
<?php elseif ($action == "addloginserver"): ?>
<?php if (!isset($complete)): ?>
				<p>Add Login Server</p>
				<p>Fill out the information for your login server. Assumed values are filled in for you.</p>
				<form action="<?php echo $_SERVER['PHP_SELF']; ?>?page=acp&amp;section=server_status&amp;action=add_loginserver" method="post">
					<div>
						<table width="92%" border="0">
							<tr>
								<td>IP:</td>
								<td><input type="text" name="host" value="127.0.0.1" /></td>
							</tr>
							<tr>
								<td>Port:</td>
								<td><input type="text" name = "port" value="8484" /></td>
							</tr>
						</table>
						<input type="submit" name="submit" value="Submit" />
					</div>
				</form>
<?php elseif (isset($complete) && $result): ?>
				<p>The login server has been added.<br />
<?php else: ?>
				<p>Failed to add login server.<br />
<?php endif; ?>
<?php elseif ($action == "editloginserver"): ?>
<?php if (!isset($complete)): ?>
				<p>Edit Login Server</p>
				<form action="<?php echo $_SERVER['PHP_SELF']; ?>?page=acp&amp;section=server_status&amp;action=edit_loginserver&amp;id=<?php echo $_GET['id']; ?>" method="post">
					<div>
						<table width="92%" border="0">
							<tr>
								<td>IP:</td>
								<td><input type="text" name="host" value="<?php echo $loginserver_data['host']; ?>" /></td>
							</tr>
							<tr>
								<td>Port:</td>
								<td><input type="text" name = "port" value="<?php echo $loginserver_data['port']; ?>" /></td>
							</tr>
						</table>
						<input type="submit" name="submit" value="Submit" />
					</div>
				</form>	
<?php elseif (isset($complete)): ?>
				<p>The login server has been updated.<br />
<?php endif; ?>
<?php elseif ($action == "addworldserver"): ?>
<?php if (!isset($complete)): ?>
				<p>Add World Server</p>
				<p>Fill out the information for your world server. Some values were assumed and filled in for you.</p>
				<form action="<?php echo $_SERVER['PHP_SELF']; ?>?page=acp&amp;section=server_status&amp;action=add_worldserver" method="post">
					<div>
						<table width="92%" border="0">
							<tr>
								<td>ID:<br />
								<span class="small">Found in worlds.lua. (i.e. world<strong>0</strong>_name -&gt; 0)</span></td>
								<td><input type="text" name="world_id" value="<?php echo $id; ?>" /></td>
							</tr>
							<tr>
								<td>Name:</td>
								<td><input type="text" name="name" value="<?php echo getWorldName($id); ?>" /></td>
							</tr>
							<tr>
								<td>IP:</td>
								<td><input type="text" name="host" value="127.0.0.1" /></td>
							</tr>
							<tr>
								<td>Port:</td>
								<td><input type="text" name = "port" value="<?php echo $port; ?>" /></td>
							</tr>
							<tr>
								<td>EXP Rate</td>
								<td><input type="text" name = "exp_rate" value="1" /></td>
							</tr>
							<tr>
								<td>Quest EXP Rate</td>
								<td><input type="text" name = "quest_rate" value="1" /></td>
							</tr>
							<tr>
								<td>Meso Rate</td>
								<td><input type="text" name = "meso_rate" value="1" /></td>
							</tr>
							<tr>
								<td>Drop Rate</td>
								<td><input type="text" name = "drop_rate" value="1" /></td>
							</tr>
						</table>
						<input type="submit" name="submit" value="Submit" />
					</div>
				</form>
<?php elseif (isset($complete)): ?>
				<p>The world server has been added.<br />
<?php endif; ?>
<?php elseif ($action == "editworldserver"): ?>
<?php if (!isset($complete)): ?>
				<p>Edit World Server</p>
				<form action="<?php echo $_SERVER['PHP_SELF']; ?>?page=acp&amp;section=server_status&amp;action=edit_worldserver&amp;id=<?php echo $worldserver_data['id']; ?>" method="post">
					<div>
						<table width="92%" border="0">
							<tr>
								<td>ID:<br />
								<span class="small">Found in worlds.lua. (i.e. world<strong>0</strong>_name -&gt; 0)</span></td>
								<td><input type="text" name="world_id" value="<?php echo $worldserver_data['world_id']; ?>" /></td>
							</tr>
							<tr>
								<td>Name:</td>
								<td><input type="text" name="name" value="<?php echo $worldserver_data['name']; ?>" /></td>
							</tr>
							<tr>
								<td>IP:</td>
								<td><input type="text" name="host" value="<?php echo $worldserver_data['host']; ?>" /></td>
							</tr>
							<tr>
								<td>Port:</td>
								<td><input type="text" name = "port" value="<?php echo $worldserver_data['port']; ?>" /></td>
							</tr>
							<tr>
								<td>EXP Rate</td>
								<td><input type="text" name = "exp_rate" value="<?php echo $worldserver_data['exp_rate']; ?>" /></td>
							</tr>
							<tr>
								<td>Quest EXP Rate</td>
								<td><input type="text" name = "quest_rate" value="<?php echo $worldserver_data['quest_rate']; ?>" /></td>
							</tr>
							<tr>
								<td>Meso Rate</td>
								<td><input type="text" name = "meso_rate" value="<?php echo $worldserver_data['meso_rate']; ?>" /></td>
							</tr>
							<tr>
								<td>Drop Rate</td>
								<td><input type="text" name = "drop_rate" value="<?php echo $worldserver_data['drop_rate']; ?>" /></td>
							</tr>
						</table>
<br />
						<br />
						<input type="submit" name="submit" value="Submit" />
					</div>
				</form>
<?php elseif (isset($complete)): ?>
				<p>The world server has been updated.<br />
<?php endif; ?>
<?php elseif ($action == "deleteworldserver"): ?>
<?php if (!isset($complete)): ?>
				<p>Delete World Server</p>
				<form action="<?php echo $_SERVER['PHP_SELF']; ?>?page=acp&amp;section=server_status&amp;action=delete_worldserver&amp;id=<?php echo $id; ?>" method="post">
					<div>
						<p>Are you sure you want to delete this world server and it's channels?</p>
						<input type="submit" name="submit" value="Yes" /> <input type="button" value="No" onclick="history.go(-1)" />
					</div>
				</form>	
<?php elseif (isset($complete)): ?>
				<p>The world server and it's channels have been deleted.<br />
<?php endif; ?>
<?php elseif ($action == "addchannelserver"): ?>
<?php 	if (!isset($complete)): ?>
				<p>Add Channel Server</p>
				<p>Fill out the information for your channel server. Assumed values are filled in for you.</p>
				<form action="<?php echo $_SERVER['PHP_SELF']; ?>?page=acp&amp;section=server_status&amp;action=add_channelserver&amp;parent=<?php echo $_GET['parent']; ?>" method="post">
					<div>
						<table width="92%" border="0">
							<tr>
								<td>IP:</td>
								<td><input type="text" name="host" value="127.0.0.1" /></td>
							</tr>
							<tr>
								<td>Port:</td>
								<td><input type="text" name = "port" value="<?php echo $port; ?>" /></td>
							</tr>
						</table>
						<br />
						<input type="submit" name="submit" value="Submit" />
					</div>
				</form>	
<?php elseif (isset($complete)): ?>
				<p>The channel server has been added.<br />
<?php endif; ?>
<?php elseif ($action == "editchannelserver"): ?>
<?php if (!isset($complete)): ?>
				<p>Edit Channel Server</p>
				<form action="<?php echo $_SERVER['PHP_SELF']; ?>?page=acp&amp;section=server_status&amp;action=edit_channelserver&amp;id=<?php echo $channelserver_data['id']; ?>" method="post">
					<div>
						<table width="92%" border="0">
							<tr>
								<td>IP:</td>
								<td><input type="text" name="host" value="<?php echo $channelserver_data['host']; ?>" /></td>
							</tr>
							<tr>
								<td>Port:</td>
								<td><input type="text" name = "port" value="<?php echo $channelserver_data['port']; ?>" /></td>
							</tr>
						</table>
						<input type="submit" name="submit" value="Submit" />
					</div>
				</form>
<?php elseif (isset($complete)): ?>
				<p>The channel server has been updated.<br />
<?php endif; ?>
<?php elseif ($action == "deletechannelserver"): ?>
<?php if (!isset($complete)): ?>
				<p>Delete Channel Server</p>
				<form action="<?php echo $_SERVER['PHP_SELF']; ?>?page=acp&amp;section=server_status&amp;action=delete_channelserver&amp;id=<?php echo $id; ?>" method="post">
					<div>
						<p>Are you sure you want to delete this channel server?</p>
						<input type="submit" name="submit" value="Yes" /> <input type="button" value="No" onclick="history.go(-1)" />
					</div>
				</form>	
<?php elseif (isset($complete)): ?>
				<p>The channel server has been deleted.<br />
<?php endif; ?>
<?php elseif ($action == "addcashserver"): ?>
<?php if (!isset($complete)): ?>
				<p>Add Cash Server</p>
				<p>Fill out the information for your cash server. Assumed values are filled in for you.</p>
				<form action="<?php echo $_SERVER['PHP_SELF']; ?>?page=acp&amp;section=server_status&amp;action=create_cashserver&amp;parent=<?php echo $_GET['parent']; ?>" method="post">
					<div>
						<table width="92%" border="0">
							<tr>
								<td>IP:</td>
								<td><input type="text" name="host" value="127.0.0.1" /></td>
							</tr>
							<tr>
								<td>Port:</td>
								<td><input type="text" name = "port" value="<?php echo $port; ?>" /></td>
							</tr>
						</table>
						<br />
						<input type="submit" name="submit" value="Submit" />
					</div>
				</form>	
<?php elseif (isset($complete)): ?>
				<p>The cash server has been added.<br />
<?php endif; ?>
<?php elseif ($action == "editcashserver"): ?>
<?php if (!isset($complete)): ?>
				<p>Edit Cash Server</p>
				<form action="<?php echo $_SERVER['PHP_SELF']; ?>?page=acp&amp;section=server_status&amp;action=edit_cashserver&amp;id=<?php echo $cashserver_data['id']; ?>" method="post">
					<div>
						<table width="92%" border="0">
							<tr>
								<td>IP:</td>
								<td><input type="text" name="host" value="<?php echo $cashserver_data['host']; ?>" /></td>
							</tr>
							<tr>
								<td>Port:</td>
								<td><input type="text" name = "port" value="<?php echo $cashserver_data['port']; ?>" /></td>
							</tr>
						</table>
						<input type="submit" name="submit" value="Submit" />
					</div>
				</form>
<?php elseif (isset($complete)): ?>
				<p>The cash server has been updated.<br />
<?php endif; ?>
<?php elseif ($action == "deletecashserver"): ?>
<?php if (!isset($complete)): ?>
				<p>Delete Cash Server</p>
				<form action="<?php echo $_SERVER['PHP_SELF']; ?>?page=acp&amp;section=server_status&amp;action=delete_cashserver&amp;id=<?php echo $id; ?>" method="post">
					<div>
						<p>Are you sure you want to delete the cash server?</p>
						<input type="submit" name="submit" value="Yes" /> <input type="button" value="No" onclick="history.go(-1)" />
					</div>
				</form>	
<?php elseif (isset($complete)): ?>
				<p>The cash server has been deleted.<br />
<?php endif; ?>
<?php endif; ?>
<?php if (isset($action) && isset($complete)): ?>
			Click <a href="<?php echo $_SERVER['PHP_SELF']; ?>?page=acp&amp;section=server_status">here</a> to continue.</p>
<?php endif; ?>