<p class="page-header">Cashshop Coupon Management</p>

<div>
<p>
<?php if ($mode == "home"): ?>
<a href="<?php echo $_SERVER['PHP_SELF']; ?>?page=acp&amp;section=coupons&amp;mode=add">Add new coupon</a>
<table border="1">
  <tr>
    <th width="212">Serial</th>
    <th width="63">Used</th>
    <th width="91">Items assigned</th>
    <th width="96">Options</th>
  </tr>
<?php foreach ($coupons as $coupon): ?>
  <tr>
    <td><?php echo $coupon['serial']; ?></td>
    <td><?php echo ($coupon['used'] ? "Yes" : "No"); ?></td>
    <td><?php echo $coupon['items_assigned']; ?></td>
    <td><a href="<?php echo $_SERVER['PHP_SELF']; ?>?page=acp&amp;section=coupons&amp;mode=delete&amp;serial=<?php echo $coupon['serial']; ?>">Delete</a> | <a href="<?php echo $_SERVER['PHP_SELF']; ?>?page=acp&amp;section=coupons&amp;mode=change&amp;serial=<?php echo $coupon['serial']; ?>">Change</a></td>
  </tr>
<?php endforeach; ?>
</table>
<?php elseif ($mode == "add"): ?>
<?php if (isset($added)): ?>
The coupon is created!<br />
<input type="button" value="Go to the coupon list" onclick="parent.location='<?php echo $_SERVER['PHP_SELF']; ?>?page=acp&amp;section=coupons'" /> or 
<input type="button" value="Change the newly created coupon" onclick="parent.location='<?php echo $_SERVER['PHP_SELF']; ?>?page=acp&amp;section=coupons&amp;mode=change&amp;serial=<?php echo $serial; ?>'" /> or
<input type="button" value="Add items to the newly created coupon" onclick="parent.location='<?php echo $_SERVER['PHP_SELF']; ?>?page=acp&amp;section=coupons&amp;mode=addreward&amp;serial=<?php echo $serial; ?>'" />
<?php else: ?>
<?php if (isset($error)): ?>
<strong>Error: A coupon with this serial already exists!</strong><br />
<?php endif; ?>
<form action="" method="post">
	Coupon data:
  <table width="100%" border="1">
    <tr>
      <th width="50%" scope="col">Name</th>
      <th width="50%" scope="col">Value</th>
    </tr>
    <tr>
      <td>Serial</td>
      <td><input name="serial" type="text" id="textfield" size="40" maxlength="22" value="<?php echo (isset($_POST['serial']) ? $_POST['serial'] : ''); ?>" /></td>
    </tr>
    <tr>
      <td>Used</td>
      <td><label>
        <input type="checkbox" name="used" id="used"<?php echo (isset($_POST['used']) ? 'checked="checked"' : ''); ?> />
      </label></td>
    </tr>
  </table>
  Reward data:
  <table width="100%" border="1">
    <tr>
      <th width="50%" scope="col">Name</th>
      <th width="50%" scope="col">Value</th>
    </tr>
    <tr>
      <td width="50%">Maplepoints</td>
      <td width="50%"><input name="maplepoints" type="text" id="textfield" size="40" maxlength="10" value="<?php echo (isset($_POST['maplepoints']) ? $_POST['maplepoints'] : ''); ?>" /></td>
    </tr>
    <tr>
      <td width="50%">NX Credit</td>
      <td width="50%"><input name="nxcredit" type="text" id="textfield" size="40" maxlength="10" value="<?php echo (isset($_POST['nxcredit']) ? $_POST['nxcredit'] : ''); ?>" /></td>
    </tr>
    <tr>
      <td width="50%">NX Prepaid</td>
      <td width="50%"><input name="nxprepaid" type="text" id="textfield" size="40" maxlength="10" value="<?php echo (isset($_POST['nxprepaid']) ? $_POST['nxprepaid'] : ''); ?>" /></td>
    </tr>
    <tr>
      <td width="50%">Mesos</td>
      <td width="50%"><input name="mesos" type="text" id="textfield" size="40" maxlength="10" value="<?php echo (isset($_POST['mesos']) ? $_POST['mesos'] : ''); ?>" /></td>
    </tr>
  </table>
  
  <strong>Item rewards can be added after you created the coupon.</strong><br />


	<input type="submit" value="Add the coupon!" />
	<input type="button" value="No thanks..." onclick="parent.location='<?php echo $_SERVER['PHP_SELF']; ?>?page=acp&amp;section=coupons'" />
</form>
<?php endif; ?>
<?php elseif ($mode == "delete"): ?>
<?php if ($state == "deleted"): ?>
This coupon is now deleted!<br />
<button onclick="parent.location='<?php echo $_SERVER['PHP_SELF']; ?>?page=acp&amp;section=coupons'">Go back to the list!</button>
<?php else: ?>
<form method="post">
	Are you sure you want to delete this coupon?<br />
	<input type="submit" value="Delete!" />
	<input type="button" value="No thanks..." onclick="parent.location='<?php echo $_SERVER['PHP_SELF']; ?>?page=acp&amp;section=coupons'" />
</form>
<?php endif; ?>
<?php elseif ($mode == "change"): ?>
<?php if (!isset($coupon)): ?>
This coupon doesn't exist.<br />
<button onclick="parent.location='<?php echo $_SERVER['PHP_SELF']; ?>?page=acp&amp;section=coupons'">Go back to the list!</button>
<?php else: ?>
<?php if (isset($saved)): ?>
<p><strong>Saved!</strong><br />
<button onclick="parent.location='<?php echo $_SERVER['PHP_SELF']; ?>?page=acp&amp;section=coupons'">Go back to the list!</button></p>
<?php endif; ?>
<script type="text/javascript" src="includes/js/jquery.js"></script>
<script type="text/javascript" src="includes/js/row_adding_removing.js"></script>
<form action="" method="post">
	Coupon data:
  <table width="100%" border="1">
    <tr>
      <th width="50%" scope="col">Name</th>
      <th width="50%" scope="col">Value</th>
    </tr>
    <tr>
      <td>Serial</td>
      <td><input name="serial" type="text" id="textfield" size="40" maxlength="22" value="<?php echo $coupon['serial']; ?>" readonly="readonly" /></td>
    </tr>
    <tr>
      <td>Used</td>
      <td><label>
        <input type="checkbox" name="used" id="used" <?php echo $coupon['used'] == 1 ? 'checked="checked"' : ''; ?> />
      </label></td>
    </tr>
  </table>
  Reward data:
  <table width="100%" border="1">
    <tr>
      <th width="50%" scope="col">Name</th>
      <th width="50%" scope="col">Value</th>
    </tr>
    <tr>
      <td width="50%">Maplepoints</td>
      <td width="50%"><input name="maplepoints" type="text" id="textfield" size="40" maxlength="10" value="<?php echo $coupon['maplepoints']; ?>" /></td>
    </tr>
    <tr>
      <td width="50%">NX Credit</td>
      <td width="50%"><input name="nxcredit" type="text" id="textfield" size="40" maxlength="10" value="<?php echo $coupon['nxcredit']; ?>" /></td>
    </tr>
    <tr>
      <td width="50%">NX Prepaid</td>
      <td width="50%"><input name="nxprepaid" type="text" id="textfield" size="40" maxlength="10" value="<?php echo $coupon['nxprepaid']; ?>" /></td>
    </tr>
    <tr>
      <td width="50%">Mesos</td>
      <td width="50%"><input name="mesos" type="text" id="textfield" size="40" maxlength="10" value="<?php echo $coupon['mesos']; ?>" /></td>
    </tr>
  </table>
  
  Item rewards:<br />
  (Changes can be made here and will be saved to the database.)
  <table id="itemRewards" width="100%" border="1">
    <tr>
      <th scope="col">Item ID</th>
      <th scope="col">Amount</th>
      <th scope="col">Days usable</th>
      <th scope="col">Options</th>
    </tr>
<?php if (!isset($rewards)): ?>
    <tr>
      <td colspan="4">No item rewards added yet.</td>
    </tr>
<?php else: ?>
<?php $i = 0; ?>
<?php foreach ($rewards as $reward): ?>
    <tr id="row_<?php echo $i; ?>">
      <td><input name="rewards_itemid[]" type="text" id="textfield" size="13" maxlength="10" value="<?php echo $reward['itemid']; ?>" /></td>
      <td><input name="rewards_amount[]" type="text" id="textfield" size="5" maxlength="10" value="<?php echo $reward['amount']; ?>" /></td>
      <td><input name="rewards_daysusable[]" type="text" id="textfield" size="5" maxlength="10" value="<?php echo $reward['days_usable']; ?>" /></td>
      <td><a href="#endtable" onclick="removeRow('#row_<?php echo $i; ?>');">Remove</a></td>
    </tr>
<?php $i++; ?>
<?php endforeach; ?>
<?php endif; ?>
  </table>
	<a target="endtable"></a>
	<a href="<?php echo $_SERVER['PHP_SELF']; ?>?page=acp&amp;section=coupons&amp;mode=addreward&serial=<?php echo $coupon['serial']; ?>">Add item reward!</a><br />

    <input name="" type="submit" value="Save changes" />
</form>
<?php endif; ?>
<?php elseif ($mode == "addreward"): ?>
<?php if (isset($added)): ?>
Reward added! (<?php echo $added; ?>)<br />
<button onclick="parent.location='<?php echo $_SERVER['PHP_SELF']; ?>?page=acp&amp;section=coupons'">Go back to the list!</button> or 
<button onclick="parent.location='<?php echo $_SERVER['PHP_SELF']; ?>?page=acp&amp;section=coupons&amp;mode=change&amp;serial=<?php echo $serial; ?>'">Go back to the coupon!</button> or 
<button onclick="parent.location='<?php echo $_SERVER['PHP_SELF']; ?>?page=acp&amp;section=coupons&amp;mode=addreward&amp;serial=<?php echo $serial; ?>'">Add another item!</button>
<?php else: ?>
<?php if (!isset($serial)): ?>
This coupon doesn't exist.<br />
<button onclick="parent.location='<?php echo $_SERVER['PHP_SELF']; ?>?page=acp&amp;section=coupons'">Go back to the list!</button>
<?php else: ?>
<form action="" method="post">
  <table id="itemRewards" width="100%" border="1">
    <tr>
      <th scope="col">Item ID</th>
      <th scope="col">Amount</th>
      <th scope="col">Days usable</th>
    </tr>	
    <tr>	
      <td><input name="itemid" type="text" id="textfield" size="13" maxlength="10" /></td>
      <td><input name="amount" type="text" id="textfield" size="5" maxlength="10" /></td>
      <td><input name="daysusable" type="text" id="textfield" size="5" maxlength="10" /></td>
    </tr>
  </table>
    <input name="" type="submit" value="Add item!" />
</form>
<button onclick="parent.location='<?php echo $_SERVER['PHP_SELF']; ?>?page=acp&amp;section=coupons'">Go back to the list!</button> or 
<button onclick="parent.location='<?php echo $_SERVER['PHP_SELF']; ?>?page=acp&amp;section=coupons&amp;mode=change&amp;serial=<?php echo $serial; ?>'">Go back to the coupon!</button>
<?php endif; ?>
<?php endif; ?>
<?php endif; ?>
</p>
</div>