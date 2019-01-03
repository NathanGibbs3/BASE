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
** Purpose: This file is the third step in the setup program
********************************************************************************
** Authors:
********************************************************************************
** Kevin Johnson <kjohnson@secureideas.net
**
********************************************************************************
*/
session_start();

include("../includes/base_setup.inc.php");

if (file_exists('../base_conf.php'))
	die ("If you wish to re-run the setup routine, please either move OR delete your previous base_conf file first.");

$errorMsg = '';

if (@$_GET['action'] == "check")
{
   // form was submitted do the checks!
   if ($_POST['useuserauth'] == "on" && ($_POST['usrlogin'] == "" || $_POST['usrpasswd'] == "" || $_POST['usrname'] == ""))
   {
      $errorMsg = "You must fill in all of the fields or uncheck \"Use Authentication System\"!";
      $error = 1;
   }
   $_SESSION['useuserauth'] = ($_POST['useuserauth'] == "on") ? 1 : 0;
   $_SESSION['usrlogin'] = $_POST['usrlogin'];   // filtred in setup4.php with filterSql()
   $_SESSION['usrpasswd'] = $_POST['usrpasswd']; // no need to filter. will be taken only md5 hash
   $_SESSION['usrname'] = $_POST['usrname'];     // filtred in setup4.php with filterSql()
   
   if ($error != 1)
   {
      header("Location: setup4.php");
      exit();
   }

}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<!-- Basic Analysis and Security Engine (BASE) -->
<HTML>

<HEAD>
  <META HTTP-EQUIV="pragma" CONTENT="no-cache">
  <TITLE>Basic Analysis and Security Engine (BASE)</TITLE>
  <LINK rel="stylesheet" type="text/css" HREF="../styles/base_style.css">
</HEAD>
<BODY>
<TABLE WIDTH="100%" BORDER=0 CELLSPACING=0 CELLPADDING=5>
    <TR>
      <TD class="mainheader"> &nbsp </TD>
      <TD class="mainheadertitle">
         Basic Analysis and Security Engine (BASE) Setup Program
      </TD>
    </TR>
</TABLE>
<br>
<P>
<?php echo("<div class='errorMsg' align='center'>".$errorMsg."</div>"); ?>
<form action=setup3.php?action=check method="POST">
<center><table width="50%" border=1 class ="query">
<tr><td colspan=2 align="center" class="setupTitle">Step 3 of 5</td><tr>
<tr><td colspan=2 align="center"><input type="checkbox" name="useuserauth" <?php if ($_SESSION['useuserauth']) echo "checked";?>>Use Authentication System [<a href="../help/base_setup_help.php#useauth" onClick="javascript:window.open('../help/base_setup_help.php#useauth','helpscreen','width=300,height=300');">?</a>]</td></tr>
<tr><td class="setupKey">Admin User Name:</td><td class="setupValue"><input type="text" name="usrlogin" value="<?php echo $_SESSION['usrlogin'];?>"></td></tr>
<tr><td class="setupKey">Password:</td><td class="setupValue"><input type="password" name="usrpasswd" value="<?php echo $_SESSION['usrpasswd'];?>"></td></tr>
<tr><td class="setupKey">Full Name:</td><td class="setupValue"><input type="text" name="usrname" value="<?php echo $_SESSION['usrname'];?>"></td></tr>
<tr><td colspan=2 align="center"><input type="submit" value="Continue"></td></tr>
</table></center></form>
</BODY>
</HTML>
