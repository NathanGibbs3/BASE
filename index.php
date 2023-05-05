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

$sc = DIRECTORY_SEPARATOR;
require_once("includes$sc" . 'base_krnl.php');
include_once("$BASE_path/includes/base_include.inc.php");
include_once("$BASE_path/base_db_common.php");

$errorMsg      = '';
$displayError  = 0;

// Redirect to base_main.php if auth system is off.
if( $Use_Auth_System == 0 ){
	HTTP_header('Location: base_main.php');
}
$LoginDesc = _FRMLOGIN;
$PWDesc = _FRMPWD;
if( isset($_POST['submit']) ){
    $BASEUSER   = new BaseUser();
    $user       = filterSql($_POST['login']);
    $pwd        = filterSql($_POST['password']);

	if ( $BASEUSER->Authenticate($user, $pwd) == 0 ){
		HTTP_header('Location: base_main.php');
	}
    $displayError = 1;
    $errorMsg     = _LOGINERROR;
}

PrintBASESubHeader();
$tmp_str = verify_php_build($DBtype); // Check that PHP was built correctly.
if( $tmp_str != '' ){
	BuildError($tmp_str);
}else{
	if( $displayError == 1 ){
		DivErrorMessage($errorMsg, 2);
	}
	$ipt = "<input type='";
	NLIO("<form action='index.php' method='post' name='loginform'>", 2);
	NLIO("<table width='75%' style='border:0;padding:0;margin:auto;'>", 3);
	NLIO('<tr>', 4);
	NLIO("<td align='right' width='50%'>$LoginDesc:&nbsp;</td>", 5);
	NLIO("<td align='left' width='50%'>", 5);
	NLIO($ipt . "text' name='login' autofocus='autofocus' />", 6);
	PrintTblNewRow(1, 'right', 5);
	NLIO("$PWDesc:&nbsp;</td>", 5);
	NLIO("<td align='left'>", 5);
	NLIO($ipt . "password' name='password' />", 6);
	PrintTblNewRow(0, '', 5);
	NLIO("<td colspan='2' align='center'>", 5);
	NLIO($ipt."submit' name='submit' value='Login' />", 6);
	NLIO($ipt."reset' name='reset' />", 6);
	PrintFramedBoxFooter(1, 3);
	NLIO('</form>', 2);
}
PrintBASESubFooter();
?>
