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
include_once(BASE_IPath . 'base_include.inc.php');
include_once("$BASE_path/base_db_common.php");

if( ARC(10000) ){ // Redirect to base_main.php if user is authenticated.
	HTTP_header('Location: base_main.php');
}else{
	$errorMsg = '';
	$Authfail = false;
	if( isset($_POST['submit']) ){
		$user = $_POST['login']; // Input sanitization happens at the
		$pswd = $_POST['password']; // Authentication level.
		$BASEUSER = new BaseUser();
		if ( $BASEUSER->Authenticate($user, $pswd) == 0 ){
			HTTP_header('Location: base_main.php');
		}
		$Authfail = true;
	}
	PrintBASESubHeader();
	$tmp_str = verify_php_build($DBtype); // Is PHP built correctly.
	if( $tmp_str != '' ){
		BuildError($tmp_str);
	}else{
		NLIO("<div style='height: 64px;'>", 3);
		$LoginDesc = _FRMLOGIN;
		$PWDesc = _FRMPWD;
		$ipt = "<input type='";
		NLIO(
			"<form class='login' action='index.php' method='post' "
			. "name='loginform'>", 4
		);
		NLIO('<div>', 5);
		printIcon('user', $LoginDesc, 6);
		NLIO("$LoginDesc:", 6);
		NLIO($ipt . "text' name='login' autofocus='autofocus' />", 6);
		NLIO('<br/>', 6);
		printIcon('password', $PWDesc, 6);
		NLIO("$PWDesc:", 6);
		NLIO($ipt . "password' name='password' />", 6);
		NLIO('</div>', 5);
		NLIO('<div>', 5);
		$ipt = "<input class='login' type='";
		NLIO($ipt . "submit' name='submit' value='$LoginDesc' />", 6);
		NLIO('<br/>', 6);
		NLIO($ipt . "reset' name='reset' />", 6);
		NLIO('</div>', 5);
		NLIO('</form>', 4);
		if( $Authfail ){
			$eMsg = _LOGINERROR;
			NLIO(
				"<div class='errorMsg' style='padding: 10px;'>$eMsg</div>", 3)
			;
		}
		NLIO('</div>', 3);
	}
	PrintBASESubFooter();
}
?>
