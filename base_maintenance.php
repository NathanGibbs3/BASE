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
** Purpose: status and event/dns/whois cache maintenance 
********************************************************************************
** Authors:
********************************************************************************
** Kevin Johnson <kjohnson@secureideas.net
**
********************************************************************************
*/

  include("base_conf.php");
  include("$BASE_path/includes/base_constants.inc.php");
  include("$BASE_path/includes/base_include.inc.php");
  include_once("$BASE_path/base_db_common.php");
  include_once("$BASE_path/base_common.php");
  include_once("$BASE_path/base_stat_common.php");
  include_once("$BASE_path/setup/setup_db.inc.php");

  $et = new EventTiming($debug_time_mode);
  $cs = new CriteriaState("base_maintenance.php");
  $cs->ReadState();

   // Check role out and redirect if needed -- Kevin
  $roleneeded = 10000;
  $BUser = new BaseUser();
   if ($Use_Auth_System == 1)
   {
      if ($_POST['standalone'] == "yes")
      {
         $usrrole = $BUser->AuthenticateNoCookie(filterSql($_POST['user']), filterSql($_POST['pwd']));
         if ($usrrole == "Failed")
            base_header('HTTP/1.0 401');
         if ($usrrole > $roleneeded)
            base_header('HTTP/1.0 403');
      }
      elseif (($BUser->hasRole($roleneeded) == 0))
        base_header("Location: ". $BASE_urlpath . "/index.php");
   }

  $page_title = _MAINTTITLE;
  PrintBASESubHeader($page_title, $page_title, $cs->GetBackLink(), $refresh_all_pages);

  $submit = ImportHTTPVar("submit", VAR_ALPHA | VAR_SPACE);
?>
<br>

<FORM METHOD="POST" ACTION="base_maintenance.php">

<?php
  /* Connect to the Alert database */
  $db = NewBASEDBConnection($DBlib_path, $DBtype);
  $db->baseDBConnect($db_connect_method,
                     $alert_dbname, $alert_host, $alert_port, $alert_user, $alert_password);

  if ( $debug_mode > 0 )
     echo "submit = '$submit'<P>";

  if ( ini_get("safe_mode") != true )
     set_time_limit($max_script_runtime);

  $repair_output = NULL;
  if ( $submit == "Update Alert Cache" )
  {
     UpdateAlertCache($db);     
  }
  else if ( $submit == "Rebuild Alert Cache" )
  {
     DropAlertCache($db);
     UpdateAlertCache($db);
  }
  else if ( $submit == "Update IP Cache" )
  {
     UpdateDNSCache($db);     
  }
  else if ( $submit == "Rebuild IP Cache" )
  {
     DropDNSCache($db);
     UpdateDNSCache($db);
  }
  else if ( $submit == "Update Whois Cache" )
  {
     UpdateWhoisCache($db);     
  }
  else if ( $submit == "Rebuild Whois Cache" )
  {
     DropWhoisCache($db);
     UpdateWhoisCache($db);
  }
  else if ( $submit == "Repair Tables")
  {
     //$repair_output = RepairDBTables($db);
     CreateBASEAG($db);
  }
  else if ( $submit == "Clear Data Tables")
  {
     ClearDataTables($db);
  }

  echo '<TABLE WIDTH="100%" CELLSPACING=0 CELLPADDING=2 BORDER=0 BGCOLOR="#669999">
         <TR><TD> 
           <TABLE WIDTH="100%" CELLSPACING=0 CELLPADDING=2 BORDER=0 BGCOLOR="#FFFFFF">
              <TR><TD class="sectiontitle">'._MNTPHP.'</TD></TR>
              <TR><TD>
         <B>'._MNTCLIENT.'</B> '.XSSPrintSafe($_SERVER['HTTP_USER_AGENT']).'<BR>
         <B>'._MNTSERVER.'</B> '.XSSPrintSafe($_SERVER['SERVER_SOFTWARE']).'<BR> 
         <B>'._MNTSERVERHW.'</B> '.php_uname().'<BR>
         <B>'._MNTPHPVER.'</B> '.phpversion().'<BR>
         <B>PHP API:</B> '.php_sapi_name().'<BR>';

  $tmp_error_reporting_str = "";

  if ( (ini_get("error_reporting") & E_ERROR) > 0 )
     $tmp_error_reporting_str .= " [E_ERROR] ";

  if ( (ini_get("error_reporting") & E_WARNING) > 0 )
     $tmp_error_reporting_str .= " [E_WARNING] ";

  if ( (ini_get("error_reporting") & E_PARSE) > 0 )
     $tmp_error_reporting_str .= " [E_PARSE] ";

  if ( (ini_get("error_reporting") & E_NOTICE) > 0 )
     $tmp_error_reporting_str .= " [E_NOTICE] ";

  if ( (ini_get("error_reporting") & E_CORE_WARNING) > 0 )
     $tmp_error_reporting_str .= " [E_CORE_WARNING] ";

  if ( (ini_get("error_reporting") & E_CORE_ERROR) > 0 )
     $tmp_error_reporting_str .= " [E_CORE_ERROR] ";

  if ( (ini_get("error_reporting") & E_COMPILE_ERROR) > 0 )
     $tmp_error_reporting_str .= " [E_COMPILE_ERROR] ";

  if ( (ini_get("error_reporting") & E_COMPILE_WARNING) > 0 )
     $tmp_error_reporting_str .= " [E_COMPILE_WARNING] ";

  echo ' <B>'._MNTPHPLOGLVL.' </B> ('.ini_get("error_reporting").') '.$tmp_error_reporting_str.'<BR>
         <B>'._MNTPHPMODS.' </B> ';

         $module_lst = get_loaded_extensions();
         for ( $i = 0; $i < count($module_lst); $i++)
             echo " [ ".$module_lst[$i]." ]";

  echo '      </TD></TR>
           </TABLE>
         </TD></TR>
        </TABLE><P>';

  echo '<TABLE WIDTH="100%" CELLSPACING=0 CELLPADDING=2 BORDER=0 BGCOLOR="#669999">
         <TR><TD> 
           <TABLE WIDTH="100%" CELLSPACING=0 CELLPADDING=2 BORDER=0 BGCOLOR="#FFFFFF">
              <TR><TD class="sectiontitle">'._DATABASE.'</TD></TR>
              <TR><TD>';
     
  GLOBAL $ADODB_vers;

  echo "<B>"._MNTDBTYPE."</B> $DBtype <BR>  
        <B>"._MNTDBALV."</B> $ADODB_vers <BR>
        <B>"._MNTDBALERTNAME."</B> $alert_dbname <BR>
        <B>"._MNTDBARCHNAME."</B> $archive_dbname <BR>

        <INPUT TYPE=\"submit\" NAME=\"submit\" VALUE=\"Repair Tables\">
        <INPUT TYPE=\"submit\" NAME=\"submit\" VALUE=\"Clear Data Tables\">";

  echo $repair_output;

   echo '
             </TD></TR>
           </TABLE>
         </TD></TR>
        </TABLE><P>';

  echo '<TABLE WIDTH="100%" CELLSPACING=0 CELLPADDING=2 BORDER=0 BGCOLOR="#669999">
         <TR><TD> 
           <TABLE WIDTH="100%" CELLSPACING=0 CELLPADDING=2 BORDER=0 BGCOLOR="#FFFFFF">
              <TR><TD class="sectiontitle">'._MNTAIC.'</TD></TR>
              <TR><TD>';

  $event_cnt_lst = $db->baseExecute("SELECT COUNT(*) FROM event");
  $event_cnt_row = $event_cnt_lst->baseFetchRow();
  $event_cnt = $event_cnt_row[0];
  $event_cnt_lst->baseFreeRows();

  $cache_event_cnt_lst = $db->baseExecute("SELECT COUNT(*) FROM acid_event");
  $cache_event_cnt_row = $cache_event_cnt_lst->baseFetchRow();
  $cache_event_cnt = $cache_event_cnt_row[0];
  $cache_event_cnt_lst->baseFreeRows();

  echo '<B>'._MNTAICTE.'</B> '.$event_cnt.'&nbsp&nbsp
        <B>'._MNTAICCE.'</B> '.$cache_event_cnt.'
        &nbsp;&nbsp;
        <INPUT TYPE="submit" NAME="submit" VALUE="Update Alert Cache">
        &nbsp;&nbsp;
        <INPUT TYPE="submit" NAME="submit" VALUE="Rebuild Alert Cache">';

  echo '   </TD></TR>
           </TABLE>
         </TD></TR>
        </TABLE><P>';

  echo '<TABLE WIDTH="100%" CELLSPACING=0 CELLPADDING=2 BORDER=0 BGCOLOR="#669999">
         <TR><TD> 
           <TABLE WIDTH="100%" CELLSPACING=0 CELLPADDING=2 BORDER=0 BGCOLOR="#FFFFFF">
              <TR><TD class="sectiontitle">'._MNTIPAC.'</TD></TR>
              <TR><TD>';

  $uncached_sip_cnt = UniqueSrcIPCnt($db);
  $uncached_dip_cnt = UniqueDstIPCnt($db);
  
  $ip_result = $db->baseExecute("SELECT COUNT(DISTINCT ip_src) FROM acid_event ".
                                "INNER JOIN acid_ip_cache ON ipc_ip = ip_src ".
                                "WHERE ipc_fqdn is not NULL");
  $row = $ip_result->baseFetchRow();
  $ip_result->baseFreeRows();
  $cached_sip_cnt = $row[0];

  $ip_result = $db->baseExecute("SELECT COUNT(DISTINCT ip_dst) FROM acid_event ".
                                "INNER JOIN acid_ip_cache ON ipc_ip = ip_dst ".
                                "WHERE ipc_fqdn is not NULL");
  $row = $ip_result->baseFetchRow();
  $ip_result->baseFreeRows();
  $cached_dip_cnt = $row[0];

  $ip_result = $db->baseExecute("SELECT COUNT(DISTINCT ip_src) FROM acid_event ".
                                "INNER JOIN acid_ip_cache ON ipc_ip = ip_src ".
                                "WHERE ipc_whois is not NULL");
  $row = $ip_result->baseFetchRow();
  $ip_result->baseFreeRows();
  $cached_swhois_cnt = $row[0];

  $ip_result = $db->baseExecute("SELECT COUNT(DISTINCT ip_dst) FROM acid_event ".
                                "INNER JOIN acid_ip_cache ON ipc_ip = ip_dst ".
                                "WHERE ipc_whois is not NULL");
  $row = $ip_result->baseFetchRow();
  $ip_result->baseFreeRows();
  $cached_dwhois_cnt = $row[0];

  echo '<B>'._MNTIPACUSIP.'</B> '.$uncached_sip_cnt.'&nbsp;&nbsp&nbsp;'.
       '<B>'._MNTIPACDNSC.'</B> '.$cached_sip_cnt.'&nbsp;&nbsp;&nbsp;'.
       '<B>'._MNTIPACWC.'</B> '.$cached_swhois_cnt.'<BR>'.
       '<B>'._MNTIPACUDIP.'</B> '.$uncached_dip_cnt.'&nbsp;&nbsp&nbsp;'.
       '<B>'._MNTIPACDNSC.'</B> '.$cached_dip_cnt.'&nbsp;&nbsp;&nbsp;'.
       '<B>'._MNTIPACWC.'</B> '.$cached_dwhois_cnt.'<BR>
        <INPUT TYPE="submit" NAME="submit" VALUE="Update IP Cache">&nbsp;
        <INPUT TYPE="submit" NAME="submit" VALUE="Update Whois Cache"><BR>
        <INPUT TYPE="submit" NAME="submit" VALUE="Rebuild IP Cache">&nbsp;
        <INPUT TYPE="submit" NAME="submit" VALUE="Rebuild Whois Cache"><BR>';
       
  echo '   </TD></TR>
           </TABLE>
         </TD></TR>
        </TABLE><P>';

  echo "\n</FORM>\n";
  
  PrintBASESubFooter();

  $et->PrintTiming();
  echo "</body>\r\n</html>";
?>
