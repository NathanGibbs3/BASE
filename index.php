<?php
/*******************************************************************************
** Basic Analysis and Security Engine (BASE)
** Copyright (C) 2004 BASE Project Team
** Copyright (C) 2000 Carnegie Mellon University
**
** (see the file 'base_main.php' for license details)
**
** Project Leads: Kevin Johnson <kjohnson@secureideas.net>
**                Sean Muller <samwise_diver@users.sourceforge.net>
** Built upon work by Roman Danyliw <rdd@cert.org>, <roman@danyliw.com>
**
** Purpose: Determines if a login is needed.  If not, will redirect you
**  to base_main.php
********************************************************************************
** Authors:
********************************************************************************
** Kevin Johnson <kjohnson@secureideas.net
**
********************************************************************************
*/

/**
 *  Check to see if the base_conf.php file exists and is big enough...
 *  if not redirect to the setup/index.php page
*/
if (!file_exists('base_conf.php') || filesize('base_conf.php') < 10) {
    header( 'Location: setup/index.php' );
    die();
    }

require("base_conf.php");
include("$BASE_path/includes/base_include.inc.php");
include_once("$BASE_path/base_db_common.php");
include_once("$BASE_path/base_common.php");

$errorMsg      = "";
$displayError  = 0;
$noDisplayMenu = 1;

// Redirect to base_main.php if auth system is off
if ( $Use_Auth_System == 0 ) {
    base_header("Location: base_main.php");
}
$LoginDesc = _FRMLOGIN;
$PWDesc = _FRMPWD;
if (isset($_POST['submit'])) {
    $debug_mode = 0; // wont login with debug_mode
    $BASEUSER   = new BaseUser();
    $user       = filterSql($_POST['login']);
    $pwd        = filterSql($_POST['password']);

    if (($BASEUSER->Authenticate($user, $pwd)) == 0) {
		base_header("Location: base_main.php");
	}
    $displayError = 1;
    $errorMsg     = _LOGINERROR;
}
PageStart();
include("$BASE_path/base_hdr1.php");
if ($displayError == 1) {
	NLIO ("<div class='errorMsg' align='center'>$errorMsg</div>",2);
}
NLIO ("<form action='index.php' method='post' name='loginform'>",2);
NLIO ("<table width='75%' style='border:0;padding:0;margin:auto;'>",3);
NLIO ('<tr>',4);
NLIO ("<td align='right' width='50%'>$LoginDesc:&nbsp;</td>",5);
NLIO ("<td align='left' width='50%'>",5);
NLIO ("<input type='text' name='login' autofocus='autofocus' />",6);
NLIO ('</td>',5);
NLIO ('</tr><tr>',4);
NLIO ("<td align='right'>$PWDesc:&nbsp;</td>",5);
NLIO ("<td align='left'>",5);
NLIO ("<input type='password' name='password' />",6);
NLIO ('</td>',5);
NLIO ('</tr><tr>',4);
NLIO ("<td colspan='2' align='center'>",5);
NLIO ("<input type='submit' name='submit' value='Login' />",6);
NLIO ("<input type='reset' name='reset' />",6);
NLIO ('</td>',5);
NLIO ('</tr>',4);
NLIO ('</table>',3);
NLIO ('</form>',2);
include("$BASE_path/base_footer.php");
PageEnd();
?>
