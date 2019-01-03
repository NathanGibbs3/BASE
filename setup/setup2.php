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
** Purpose: This file is the second step in the setup program
********************************************************************************
** Authors:
********************************************************************************
** Kevin Johnson <kjohnson@secureideas.net
**
********************************************************************************
*/
session_start();

define( "_BASE_INC", 1 );
include("../includes/base_setup.inc.php");
if (!array_key_exists("adodbpath", $_SESSION))
{
  $errorMsg = "ERROR: The php session does not contain the array key \"adodbpath\". This is typically caused by not having allowed cookies. Exiting.";
  print $errorMsg;
  exit();
}
$ado_inc_php = $_SESSION['adodbpath'] . "/adodb.inc.php";
if ($ado_inc_php == "")
{
  print __FILE__ . ":" . __LINE__ . ": ERROR: The variable \$ado_inc_php is empty. Exiting.";
  exit();
}
else if (!file_exists($ado_inc_php))
{
  print "ERROR: The file \"" . $ado_inc_php . "\" does not exist. Exiting.";
  exit();
}
else if (!is_readable($ado_inc_php))
{
  print "ERROR: " . $ado_inc_php . " does exist, but is not readable. Typically a permission problem. Exiting.";
  exit();
}
include($ado_inc_php);
include("../includes/base_state_common.inc.php");
include("../includes/base_constants.inc.php");

if (file_exists('../base_conf.php'))
	die ("If you wish to re-run the setup routine, please either move OR delete your previous base_conf file first.");

$errorMsg = '';

if (@$_GET['action'] == "check")
{
   // form was submitted do the checks!
   $dbtype = ImportHTTPVar("dbtype", VAR_ALPHA);
   $dbport = ImportHTTPVar("dbport", VAR_DIGIT);
   $dbhost = ImportHTTPVar("dbhost", VAR_ALPHA | VAR_PERIOD | VAR_SCORE);
   $dbusername = ImportHTTPVar("dbusername");
   $dbpasswd = ImportHTTPVar("dbpasswd");
   $dbname = ImportHTTPVar("dbname", VAR_ALPHA | VAR_SCORE | VAR_USCORE);
   $arcdbport = ImportHTTPVar("arcdbport", VAR_DIGIT);
   $arcdbhost = ImportHTTPVar("arcdbhost", VAR_ALPHA | VAR_PERIOD | VAR_SCORE);
   $arcdbusername = ImportHTTPVar("arcdbusername");
   $arcdbpasswd = ImportHTTPVar("arcdbpasswd");
   $arcdbname = ImportHTTPVar("arcdbname", VAR_ALPHA | VAR_SCORE | VAR_USCORE);

   $db = NewADOConnection($dbtype);
   $dbconnect = $db->Connect( ( ( $dbport == "") ? $dbhost : ($dbhost.":".$dbport) ),
                              $dbusername, $dbpasswd, $dbname);
   if ( !$dbconnect )
   {
      $errorMsg = $errorMsg . "Database connection failed!<br>Please try again!";
      $error = 1;
   }
   $_SESSION['dbtype'] = $dbtype;
   $_SESSION['dbhost'] = $dbhost;
   $_SESSION['dbport'] = $dbport;
   $_SESSION['dbname'] = $dbname;
   $_SESSION['dbusername'] = $dbusername;
   $_SESSION['dbpasswd'] = $dbpasswd;

   $_SESSION['usearchive'] = 0;
   if (@$_POST['usearchive'] == "on")
   {
       $_SESSION['usearchive'] = 1;
      $dbconnect = $db->Connect( ( ($arcdbport == "") ? $arcdbhost : ($arcdbhost.":".$arcdbport) ), 
                                 $arcdbusername, $arcdbpasswd, $arcdbname);
      if ( !$dbconnect )
      {
         $errorMsg = $errorMsg . "Archive Database connection failed!<br>Please try again!";
         $error = 1;
      }
      $_SESSION['arcdbexists'] = 1;
      $_SESSION['arcdbhost'] = $arcdbhost;
      $_SESSION['arcdbport'] = $arcdbport;
      $_SESSION['arcdbname'] = $arcdbname;
      $_SESSION['arcdbusername'] = $arcdbusername;
      $_SESSION['arcdbpasswd'] = $arcdbpasswd;
   }
   if ($error != 1)
   {
      header("Location: setup3.php");
      exit();
   } else {
      echo $errorMsg;
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
<form action=setup2.php?action=check method="POST">
<center><table width="50%" border=1 class ="query">
<tr><td colspan=2 align="center" class="setupTitle">Step 2 of 5</td><tr>
<tr><td class="setupKey" width="50%">Pick a Database type:</td><td class="setupValue"><select name="dbtype">
<option value="mysql" <?php if ($_SESSION['dbtype'] == 'mysql') echo "selected";?>>MySQL
<option value="postgres" <?php if ($_SESSION['dbtype'] == 'postgres') echo "selected";?>>PostgreSQL
<option value="mssql" <?php if ($_SESSION['dbtype'] == 'mssql') echo "selected";?>>Microsoft SQL Server
<option value="oci8" <?php if ($_SESSION['dbtype'] == 'oci8') echo "selected";?>>Oracle
</select>[<a href="../help/base_setup_help.php#dbtype" onClick="javascript:window.open('../help/base_setup_help.php#dbtype','helpscreen','width=300,height=300');">?</a>]</td</tr>
<tr><td colspan=2 align="center">&nbsp;</td></tr>
<tr><td class="setupKey">Database Name:</td><td class="setupValue"><input type="text" name="dbname" value="<?php echo $_SESSION['dbname'];?>"></td></tr>
<tr><td class="setupKey">Database Host:</td><td class="setupValue"><input type="text" name="dbhost" value="<?php echo $_SESSION['dbhost'];?>"></td></tr>
<tr><td class="setupKey">Database Port:<br>Leave blank for default!</td><td class="setupValue"><input type="text" name="dbport" value="<?php echo $_SESSION['dbport'];?>"></td></tr>
<tr><td class="setupKey">Database User Name:</td><td class="setupValue"><input type="text" name="dbusername" value="<?php echo $_SESSION['dbusername'];?>"></td></tr>
<tr><td class="setupKey">Database Password:</td><td class="setupValue"><input type="password" name="dbpasswd" value="<?php echo $_SESSION['dbpasswd'];?>"></td></tr>
<tr><td colspan=2 align="center">&nbsp;</td></tr>
<tr><td colspan=2 align="center"><input type="checkbox" name="usearchive" <?php if ($_SESSION['usearchive'] == 1 ) echo "checked";?>>Use Archive Database[<a href="../help/base_setup_help.php#usearchive" onClick="javascript:window.open('../help/base_setup_help.php#usearchive','helpscreen','width=300,height=300');">?</a>]</td></tr>
<tr><td class="setupKey">Archive Database Name:</td><td class="setupValue"><input type="text" name="arcdbname" value="<?php echo $_SESSION['arcdbname'];?>"></td></tr>
<tr><td class="setupKey">Archive Database Host:</td><td class="setupValue"><input type="text" name="arcdbhost" value="<?php echo $_SESSION['arcdbhost'];?>"></td></tr>
<tr><td class="setupKey">Archive Database Port:<br>Leave blank for default!</td><td class="setupValue"><input type="text" name="arcdbport" value="<?php echo $_SESSION['arcdbport'];?>"></td></tr>
<tr><td class="setupKey">Archive Database User Name:</td><td class="setupValue"><input type="text" name="arcdbusername" value="<?php echo $_SESSION['arcdbusername'];?>"></td></tr>
<tr><td class="setupKey">Archive Database Password:</td><td class="setupValue"><input type="password" name="arcdbpasswd" value="<?php echo $_SESSION['arcdbpasswd'];?>"></td></tr>
<tr><td colspan=2 align="center"><input type="submit" value="Continue"></td></tr>
</table></center></form>
</BODY>
</HTML>
