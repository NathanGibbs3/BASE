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

if (isset($_POST['submit'])) {
    $debug_mode = 0; // wont login with debug_mode
    $BASEUSER   = new BaseUser();
    $user       = filterSql($_POST['login']);
    $pwd        = filterSql($_POST['password']);

    if (($BASEUSER->Authenticate($user, $pwd)) == 0) {
        header("Location: base_main.php");
	exit();
    }

    $displayError = 1;
    $errorMsg     = _LOGINERROR;
} 
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<!-- <?php echo _TITLE . $BASE_VERSION; ?> -->
<html>

<head>
  <meta http-equiv="Content-Type" content="text/html; charset=<?php echo(_CHARSET); ?>" />
  <meta http-equiv="pragma" content="no-cache" />
  <title><?php echo(_TITLE . $BASE_VERSION); ?></title>
  <link rel="stylesheet" type="text/css" href="./styles/<?php echo($base_style); ?>" />
</head>
<body onload="javascript:document.loginform.login.focus();">
  <div class="mainheadertitle">&nbsp;
<?php echo _TITLE; ?>
  </div><br />
<?php
if ($displayError == 1)
{
    echo "<div class='errorMsg' align='center'>$errorMsg</div>";
}
?>
<form action="index.php" method="post" name="loginform">
  <table width="75%" style='border:0;padding:0;margin:auto;'>
    <tr>
      <td align="right" width="50%"><?php echo _FRMLOGIN; ?>&nbsp;</td>
      <td align="left" width="50%"><input type="text" name="login"></td>
    </tr>
    <tr>
      <td align="right"><?php echo _FRMPWD; ?>&nbsp;</td>
      <td align="left"><input type="password" name="password" /></td>
    </tr>
    <tr>
      <td colspan="2" align="center">
        <input type="submit" name="submit" value="Login" />
        <input type="reset" name="reset" />
      </td>
    </tr>
  </table>
</form>
<?php
  include("$BASE_path/base_footer.php");
?>
</body>
</html>
