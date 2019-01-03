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
** Purpose: Setup Help page
********************************************************************************
** Authors:
********************************************************************************
** Kevin Johnson <kjohnson@secureideas.net
**
********************************************************************************
*/

if (file_exists('base_conf.php'))
{
  include("base_conf.php");
}
elseif (file_exists('../base_conf.php'))
{
  include("../base_conf.php");
}
elseif (file_exists("$BASE_path/base_conf.php"))
{
  include("$BASE_path/base_conf.php");
}
else
{
  include ("../base_conf.php.dist");
}




echo '<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">' . "\n\n" .
     '<!-- Basic Analysis and Security Engine (BASE) ' . $BASE_VERSION . ' -->' . "\n" . 
     '<!-- BASE_path = ' . $BASE_path . "\n" .
     '     BASE_urlpath = ' . $BASE_urlpath .  "\n" .
     '     BASE_VERSION = ' . $BASE_VERSION .  "\n" .
     '-->' . "\n" .
     '<HTML> ' . "\n" .
     '  <HEAD> ' . "\n" .
     '  <META name="Author" content="Kevin Johnson"> ' . "\n" .
     '    <TITLE>BASE: ' . $BASE_VERSION . '</TITLE> ' . "\n" .
     '    <LINK rel="stylesheet" type="text/css" HREF="../styles/base_style.css">' . "\n" ;
echo '  </HEAD> ' ;

?>

<BODY>
<a name="language"><b>Language Selection:</b><br>
This is the language that the program will be displayed in.  Currently this is a global setting.<hr>
<br><a name="adodb"><b>Path to ADODB:</b><br>
Path to the DB abstraction library 
  (Note: DO NOT include a trailing backslash after the directory)
   e.g. <ul><li>"/tmp"      [OK]
        <li>"/tmp/"     [WRONG]
        <li>"c:\tmp"    [OK]
        <li>"c:\tmp\"   [WRONG]</ul><hr>
<br><a name="chartpath"><b>Path to the Chart Library:</b><br>
Path to the graphing library <br>
(Note: DO NOT include a trailing backslash after the directory)<hr>
<br>
<br><a name="dbtype"><b>Database Type:</b><br>
Please select the type of Database that Snort is logging its alerts too.
<br><a name="usearchive"><b>Use an Archive Database:</b><br>
If you would like the ability to archive alerts from your active database, sleect this box.
If so, you must also answer the questions below.
<br><a name="useauth"><b>Use the User Authentication System:</b><br>
This check box enables you to set up a user authentication system for BASE.
If you do not want to have people log in before they can view BASE, do not select this.
</BODY>

</HTML>
