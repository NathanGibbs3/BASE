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
** Purpose: This file is the intro to the setup program
**          It will display the introduction help text and link to
**          setup1.php
********************************************************************************
** Authors:
********************************************************************************
** Kevin Johnson <kjohnson@secureideas.net
**
********************************************************************************
*/

include("../includes/base_setup.inc.php");

session_start();
unset( $_SESSION['language'] );
unset( $_SESSION['adodbpath'] );
unset( $_SESSION['dbtype'] );
unset( $_SESSION['dbhost'] );
unset( $_SESSION['dbport'] );
unset( $_SESSION['dbname'] );
unset( $_SESSION['dbusername'] );
unset( $_SESSION['dbpasswd'] );
unset( $_SESSION['usearchive'] );
unset( $_SESSION['arcdbexists'] );
unset( $_SESSION['arcdbhost'] );
unset( $_SESSION['arcdbport'] );
unset( $_SESSION['arcdbname'] );
unset( $_SESSION['arcdbusername'] );
unset( $_SESSION['useuserauth'] );
unset( $_SESSION['usrlogin'] );
unset( $_SESSION['usrpasswd'] );
unset( $_SESSION['usrname'] );
$writeable = (is_writable("..")) ?  "Yes" : "No";
$writeColor = ($writeable == "Yes") ? "#336600" : "#FF0000";


  /* Check PHP version >= 4.0.4 */
  $current_php_version = phpversion();
  $version = explode(".", $current_php_version);

  /* account for x.x.xXX subversions possibly having text like 4.0.4pl1 */
  if ( is_numeric(substr($version[2], 1, 1)) ) 
     $version[2] = substr($version[2], 0, 2);
  else
     $version[2] = substr($version[2], 0, 1);

  /* only version PHP 4.0.4+ or 4.1+.* are valid */ 
  if ( !( ($version[0] >= 4) && ( ( ($version[1] == 0) && ($version[2] >= 4) ) ||
          ($version[1] > 0) || ($version[0] > 4) ) ) )
  {
    $phpColor = "#ff0000";
    $phpVer = 0;
  } else {
    $phpColor = "#336600";
    $phpVer = 1;
  }

  /* Check for PHP Logging Level. There should not be NOTICE. */
  if ( (ini_get('error_reporting') & E_NOTICE) > 0 ) {
     $error_reporting_str = '<font color="#ff0000">[NOTICE]</font><font color="#336600">';
     $phpLogLevel = 0;
  } else {
     $error_reporting_str = '<font color="#336600">';
     $phpLogLevel = 1;
  }
  if ( (ini_get('error_reporting') & E_ERROR) > 0 )
     $error_reporting_str .= '[ERROR]';
  if ( (ini_get('error_reporting') & E_WARNING) > 0 )
     $error_reporting_str .= '[WARNING]';
  if ( (ini_get('error_reporting') & E_PARSE) > 0 )
     $error_reporting_str .= '[PARSE]';
  $error_reporting_str .= '</font>&nbsp;';

?>
<!doctype html public "-//w3c//dtd html 4.0 transitional//en">
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
<div align="center">The following pages will prompt you for set up information to finish the install of BASE.<br>
If any of the options below are red, there will be a description of what you need to do below the chart.
</div>
<hr><br>
<center><table width="50%" border=1 class ="query">
<tr><td colspan=2 align="center" class="setupTitle">Settings</td><tr>
<tr><td class="setupKey" width="50%">Config Writeable:</td><td class="setupValue"><?php echo("<font color='".$writeColor."'>".$writeable."</font>"); ?></td</tr>
<tr><td class="setupKey" width="50%">PHP Version:</td><td class="setupValue"><?php echo("<font color='".$phpColor."'>". phpversion() ."</font>"); ?></td</tr>
<tr><td class="setupKey" width="50%">PHP Logging Level:</td><td class="setupValue"><?php echo($error_reporting_str); ?></td</tr>
</table>
<?php
    /* error messages */
    if ($phpVer == 0) {
        echo("<br><font color=\"red\"><b>Your version of PHP is to low too handle the running of BASE!");
        echo("<br>Please upgrade your install of PHP!</b></font>");
    }

    if ($phpLogLevel == 0) {
        echo("<br><font color=\"red\"><b>Your PHP Logging Level is too high to handle the running of BASE!");
        echo("<br>Please set the 'error_reporting' variable to at least 'E_ALL & ~E_NOTICE' in your php.ini!</b></font>");
    }
    
    if ($writeable == "No") {
        $msg = "<br>The directory where BASE is installed does not allow the web server to write.<br>This will prevent the setup";
        $msg = $msg." progam from creating the base_conf.php file.  You have two choices.<br>";
        $msg = $msg."1. Make the directory writeable for the web server user.<br>";
        $msg = $msg."2. When the set up is done, copy the information displayed to the screen and use it to create a base_conf.php.";
        
        echo $msg;
    }
?>
<br><a href="setup1.php">Continue</a></center>
</BODY>
</HTML>
