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

$UIL = new UILang($BASE_Language); // Create UI Language Abstraction Object.
$LoginDesc = $UIL->ADA['DescUN'];
$PWDesc = $UIL->ADA['DescPW'];
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
	print "\n".str_repeat ( "\t",3 )."<div class='errorMsg' align='center'>";
	print "\n".str_repeat ( "\t",4 ). $errorMsg;
	print "\n".str_repeat ( "\t",3 ).'</div>';
}
print "\n".str_repeat ( "\t",3 ).'<form';
print "\n".str_repeat ( "\t",4 ).'action="index.php"';
print "\n".str_repeat ( "\t",4 ).'method="post"';
print "\n".str_repeat ( "\t",4 ).'name="loginform"';
print "\n".str_repeat ( "\t",3 ).'><table';
print "\n".str_repeat ( "\t",4 ).'width="75%"';
print "\n".str_repeat ( "\t",4 )."style='border:0;padding:0;margin:auto;'";
print "\n".str_repeat ( "\t",3 ).'><tr><td align="right" width="50%">';
print "\n".str_repeat ( "\t",4 )."$LoginDesc:&nbsp;";
print "\n".str_repeat ( "\t",3 ).'</td><td align="left" width="50%">';
print "\n".str_repeat ( "\t",4 ).'<input type="text" name="login"';
print "\n".str_repeat ( "\t",4 ).'autofocus="autofocus" />';
print "\n".str_repeat ( "\t",3 ).'</td></tr><tr><td align="right">';
print "\n".str_repeat ( "\t",4 )."$PWDesc:&nbsp;";
print "\n".str_repeat ( "\t",3 ).'</td><td align="left">';
print "\n".str_repeat ( "\t",4 ).'<input type="password" name="password" />';
print "\n".str_repeat ( "\t",3 ).'</td></tr><tr>';
print "\n".str_repeat ( "\t",4 ).'<td colspan="2" align="center">';
print "\n".str_repeat ( "\t",5 ).'<input';
print "\n".str_repeat ( "\t",6 ).'type="submit" name="submit" value="Login"';
print "\n".str_repeat ( "\t",5 ).'/>';
print "\n".str_repeat ( "\t",5 ).'<input type="reset" name="reset" />';
print "\n".str_repeat ( "\t",4 ).'</td></tr>';
print "\n".str_repeat ( "\t",3 ).'</table></form>';
include("$BASE_path/base_footer.php");
PageEnd();
?>
