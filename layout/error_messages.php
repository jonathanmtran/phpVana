<?php if($code == "nli"): ?>
You must be logged in to access the Player CP.

<?php elseif($code == "invalid"): ?>
Invalid username/password combination.

<?php elseif($code == "nu"): ?>
You did not enter a username.

<?php elseif($code == "np"): ?>
You did not enter a password.

<?php elseif($code == "nanu"): ?>
You used non alphanumeric/numeric characters in your username.

<?php elseif($code == "nanp"): ?>
You used non alphanumeric/numeric characters in your password.

<?php elseif($code == "lu"): ?>
The length of the username is invalid. 6 characters minimum. 12 characters maximum.

<?php elseif($code == "lp"): ?>
The length of the password is invalid. 6 characters minimum. 12 characters maximum.

<?php elseif($code == "ue"): ?>
The username you chose has been taken by another user. Please select a different username.

<?php elseif($code == "wp"): ?>
Incorrect password.

<?php elseif($code == "pdm"): ?>
Passwords don't match.

<?php elseif($code == "cdbns"): ?>
You did not fill in your birthdate.

<?php elseif($code == "cdpns"): ?>
You did not enter a character delete passsword.

<?php elseif(($code == "cdbnn") || ($code == "cdbl")): ?>
You did not enter a valid birthdate.

<?php elseif(($code == "cdpnn") || ($code == "cdpl")): ?>
You did not enter a valid character delete password.

<?php elseif($code == "capt"): ?>
You entered the wrong reCAPTCHA answer.

<?php elseif($code == "unk"): ?>
An unkown error occurred.

<?php else: ?>
Hacking attempt

<?php endif; ?>