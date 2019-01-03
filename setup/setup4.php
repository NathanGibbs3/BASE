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
** Purpose: Setup step 4, create the database stuff
********************************************************************************
** Authors:
********************************************************************************
** Kevin Johnson <kjohnson@secureideas.net>
** Joel Esler <eslerj@gmail.com>
**
********************************************************************************
*/
   session_start();

   $BASE_path = "..";  // Set this since we don't have a base_conf.php
   define( "_BASE_INC", 1 );
   $BASE_InstallID = 1;
   
  include("../languages/english.lang.php");
  include("../includes/base_constants.inc.php");
  include("../includes/base_include.inc.php");
  include_once("../base_db_common.php");
  include_once("../base_common.php");
  include_once("setup_db.inc.php");
  
if (file_exists('../base_conf.php'))
	die ("If you wish to re-run the setup routine, please either move OR delete your previous base_conf file first.");
   
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
      <TD class="mainheader">&nbsp;</TD>
      <TD class="mainheadertitle">
         Basic Analysis and Security Engine (BASE) Setup Program
      </TD>
    </TR>
</TABLE>
<br>
<P>
<FORM METHOD="POST" ACTION="setup4.php">

<?php
  GLOBAL $debug_mode;
  $submit = ImportHTTPVar("submit", VAR_ALPHA | VAR_SPACE);

  // Grab the variables that have been put into Session
  $alert_dbname = $_SESSION['dbname'];
  $alert_host =  $_SESSION['dbhost'];
  $alert_port =  $_SESSION['dbport'];
  $alert_user = $_SESSION['dbusername'];
  $alert_password = $_SESSION['dbpasswd'];
  $DBlib_path = $_SESSION['adodbpath'];
  $DBtype = $_SESSION['dbtype'];

  /* Archive database */ 
  if ( $submit == "Create BASE AG" ) 
  {
    if ($_SESSION['usearchive'] == 1)
    {
      if ($_SESSION['arcdbexists'])
      {
        $archive_dbname = $_SESSION['arcdbname'];
        $archive_host =  $_SESSION['arcdbhost'];
        $archive_port =  $_SESSION['arcdbport'];
        $archive_user = $_SESSION['arcdbusername'];
        $archive_password = $_SESSION['arcdbpasswd'];
        $archive = NewBASEDBConnection($DBlib_path, $DBtype);
        $archive->baseDBConnect(1, $archive_dbname, $archive_host, $archive_port, $archive_user, $archive_password, 1);
        $archive_result = CreateBASEAG($archive);
        if ($archive_result != 1)					 
        {
          error_message("ERROR: Trying to add BASE specific tables to the archive database has failed. <BR>\n");
          if ( $archive->baseErrorMessage() != "" )
          {
            ErrorMessage($archive->baseErrorMessage());
          }
        } 
        else if ($debug_mode > 0)
        {
          echo "<BR>";
          ErrorMessage("BASE tables added to $archive->DB_name.");
          echo "<BR>\n";
        }
			}
    }
  }

  /* Connect to the Alert database */
  $db = NewBASEDBConnection($DBlib_path, $DBtype);
  $db->baseDBConnect(1,$alert_dbname, $alert_host, $alert_port, $alert_user, $alert_password, 1);

  if ( $submit == "Create BASE AG" ) {		
    $result = CreateBASEAG($db);
    if (($debug_mode > 0) && ($result == 1))
    {
      echo "<BR>";
      ErrorMessage("BASE tables added to $db->DB_name.");
      echo "<BR>\n";
    }
	}
  
  echo '<HR><P>'; 

  echo '
  <TABLE WIDTH="100%">
   <tr><td colspan=3 align="center" class="plfieldhdr">Step 4 of 5</td><tr>
     <TR><TD CLASS="plfieldhdr">Operation</TD>
         <TD CLASS="plfieldhdr">Description</TD>
         <TD CLASS="plfieldhdr">Status</TD>
     </TR>
     <TR><TD VALIGN=TOP><B>BASE tables</B></TD>
         <TD VALIGN=TOP>Adds tables to extend the Snort DB to support the BASE functionality<BR>
         <UL>';

  if ($alert_dbname != "")
  {
    echo '<LI>' . $alert_dbname . '<BR>';
  }

  if ($_SESSION['usearchive'] == 1)
  {
    if ($_SESSION['arcdbname'] != "")
    {
      echo '<LI>' . $_SESSION['arcdbname'] . '<BR>';
    }
  }

  echo ' 
         </UL>
         </TD>
         <TD VALIGN=TOP>';

  if ($result == 1) {

     ErrorMessage("&nbsp;DONE&nbsp;");
     if ($_SESSION['useuserauth'] == 1) {
            $user = filterSql($_SESSION['usrlogin'], 1);
            $pwd = md5($_SESSION['usrpasswd']);
            $name = filterSql($_SESSION['usrname'], 1);
            $sql = "SELECT COUNT(*) FROM base_users WHERE usr_login = '".$user."'";
            $rs_del_cnt = $db->baseExecute($sql);
            $userdelcnt = $rs_del_cnt->baseFetchRow();
            if ($userdelcnt[0] > 0 ) {
                $sql = "DELETE FROM base_users WHERE usr_login = '".$user."'";
                $db->baseExecute($sql);
            }
            $sql = "SELECT MAX(usr_id) FROM base_users;";
            $usercount = $db->baseExecute($sql);
            $usercnt = $usercount->baseFetchRow();
            $userid = $usercnt[0] + 1;
            $sql ="INSERT INTO base_users (usr_id, usr_login, usr_pwd, role_id, usr_name, usr_enabled)";
            $sql = $sql. " VALUES (".$userid .", '".$user."','".$pwd."', 1,'".$name."', 1);";
            $db->baseExecute($sql);
             if ( $db->baseErrorMessage() != "" )
                ErrorMessage("Unable to CREATE User: ".
                             $db->baseErrorMessage());
             else
                ErrorMessage("Successfully created user.");
             if ($userdelcnt[0] > 0 )
                ErrorMessage("$userdelcnt[0] user(s) matching '$user' was(were) deleted.");
         }
  } else
     echo '<INPUT TYPE="submit" NAME="submit" VALUE="Create BASE AG">'; 

  echo '
  </TABLE>';
  
  if ($result == 1)
     echo '<P>
           The underlying Alert DB is configured for usage with BASE.
           <P>
           <B>Additional DB permissions</B><BR>
           In order to support Alert purging (the selective ability to permanently delete
           alerts from the database) and DNS/whois lookup caching, 
           the DB user "'.$alert_user.'" must have the DELETE and UPDATE privilege
           on the database "'.$alert_dbname.'@'.$alert_host.'" 
           <P>
           <center>Now continue to <a href="setup5.php">step 5</a>...</center>'; 

  //echo "\n</FORM>\n";
?>
</FORM>
</BODY>
</HTML>
