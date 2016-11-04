<?php
$lang = array();
$lang['error'][0] = "LANG_KEY_ERROR";
$lang['error'][1] = "User Id contain invalid character";
$lang['error'][2] = "The length of user id must be between %s and %s";
$lang['error'][3] = "User id [%s] has already been choosed by another member, please choose another one";
$lang['error'][4] = "Email address [%s] already exist in our database, please choose  another one";
$lang['error'][5] = "Password is not valid";
$lang['error'][6] = "The length of your password is not valid, must be between %s and %s";
$lang['error'][7] = "First name contain invalid character";
$lang['error'][8] = "Middle name contain invalid character";
$lang['error'][9] = "Last name contain invalid character";
$lang['error'][10]  = "Email address is not valid";
$lang['error'][11] = "System error has occurred, your account has not be create, please try later";
$lang['error'][12] = "Operation failed: the system can not send mail, please try later";
$lang['error'][13] = "Unable to validate data";
$lang['error'][14] = "Password must be of the same length";
$lang['error'][15] = "Your userid and password must not be the same or match";
$lang['error'][16] = "Activation code is not valid";
$lang['error'][17] = "Account is already active, if you are having problem login, visit the password <a href='reset.php'>Reset Page</a>";
$lang['error'][18] = "The system can not activate your account for now, please try later";

$lang['error'][19] = "Spamming is banned on this website, please follow due process when sending request to the server";
$lang['error'][20] = "Invalid request: security token is not set";
$lang['error'][21] = "The security code you entered was wrong. Try again.";
$lang['error'][22] = "Bad request: the system can not handle your request due to bad input format";
$lang['error'][23] = "LOGIN ERROR: Your username or password is wrong";
$lang['error'][24] = "ACCOUNT ERROR: your account is not yet activated. Check your email for activation instructions";
$lang['error'][25] = "ACCOUNT ERROR: this account has been suspended";
$lang['error'][26] = "ACCOUNT ERROR: this account has been closed by the admin";
$lang['error'][27] = "ACCOUNT ERROR: bad status, please contact the administrator";
$lang['error'][28] = "SYSTEM ERROR: the system has reach an error, please try login later";
$lang['error'][29] = "Attempt to use expired session.";
$lang['error'][30] = "No session started";
$lang['error'][31] = "IP Address mixmatch (possible session hijacking attempt)";
$lang['error'][32] = "Useragent mixmatch (possible session hijacking attempt)";
$lang['error'][33] = "Attempted to log in user that does not exist";
$lang['error'][32] = "Either the Email address or Activation code is/are invalid";

$lang['error'][33] = "Account did not exists on our server";
$lang['error'][34] = "Request failed: email address is not set";
$lang['error'][35] = "The Security code you entered was wrong! Try again...";
$lang['error'][36] = "ERROR: Security image is not set";

$lang['email'][1] = <<<EOD
Dear %userid%

Thank you for taking out time to complete the registration process on our website.
Your account has been created and activated, you can now login to your account

Your login information are:

User id: %userid%
Password: %userpass%
To login visit http://www.exood.com/userlogin.php

Registration email: %email% , this email is very important as you will need it to perform some action/activity on your account/on our website.

Do not reply to this message, this is an automated message from Exood.com

For more information, goto http://www.exood.com

Thank you.

-------------------------------------------------------------
Exood Team
EOD;

$lang['email'][2] = <<<EOD
Dear %userid%

Thank you for taking out time to complete the registration process on our website.
Your account has been created and awaiting activation.

To activate your account, please follow the below instructions.

OPTION 1:
click  : %activation_url%

OR

OPTION 2:
copy this code: %activation_code% and goto %activation_page% the complete the process on that page

If you are having problems, send an email to activation.help@exood.com

Registration email: %email% , this email is very important as you will need it to perform some action/activity on your account/on our website.

Do not reply to this message, this is an automated message from Exood.com

For more information, goto http://www.exood.com

Thank you.

-----------------------------------------------
Exood Team
EOD;

$lang['email'][3] = <<<EOD
Hello Admin,
New user account was created some few minute ago, find basic information below

First name: %fname%
Last name: %lname%
Email: %email%
User id: %userid%

Password was not sent with the mail

------------------------------------------------------------
Administrator
EOD;

$lang['email'][4] = <<<EOD
Welcome to Exood.com, your account has been activated, you can now login with the
userid and password use registered with.

If you are have any problem, send an email to help@exood.com.

Login login details was sent along side the activation link, so you can check the first
email you received from us.

If you forget your password you can retrive it using the password reset wizard.

Please do not reply to this email.

Thank you
--------------------------
Exood Team
EOD;



$lang['email'][5] = <<<EOD
To initiate the password reset process for your
%email% Exood Account, click the link below:

%pwd_reset_url%

If clicking the link above doesn't work, please copy and paste the URL in a
new browser window instead.

If you've received this mail in error, it's likely that another user entered
your email address by mistake while trying to reset a password. If you
didn't
initiate the request, you don't need to take any further action and can
safely
disregard this email.

Thank you for using Exood.

For questions or concerns about your account, please visit the Google
Accounts
Help Center at http://www.exood.com/useraccounts/accounthelp.php


This is a post-only mailing. Replies to this message are not monitored
or answered.
EOD;

$lang['email'][6] = <<<EOD
Your password has been Reset. Your new password is

PASSWORD: %userpass%

Thank you for using Exood.

For questions or concerns about your account, please visit your user area
Help Center at http://www.exood.com/useraccounts/accounthelp.php


This is a post-only mailing. Replies to this message are not monitored
or answered.
EOD;
/**
 * Get error string from the language array
 */
function get_lang_error($key) {
	global $lang;
	if(!array_key_exists($key, $lang['error'])) {
		return $lang['error'][0];
	}
	return $lang['error'][$key];
}

/**
 * Get email message string from the language array
 */
function get_lang_email($key) {
	global $lang;
	if(!array_key_exists($key, $lang['email'])) {
		return $lang['email'][0];
	}
	return $lang['email'][$key];
}

?>