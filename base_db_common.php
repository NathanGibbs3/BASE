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
** Purpose: database schema manipulation
********************************************************************************
** Authors:
********************************************************************************
** Kevin Johnson <kjohnson@secureideas.net
**
********************************************************************************
*/

function createDBIndex($db, $table, $field, $index_name)
{
   $sql = 'CREATE INDEX '.$index_name.' ON '.$table.' ('.$field.')';

   $db->baseExecute($sql, -1, -1, false);
   if ( $db->baseErrorMessage() != "" )
      ErrorMessage(_ERRDBINDEXCREATE." '".$field."' : ".$db->baseErrorMessage());
   else
      ErrorMessage(_DBINDEXCREATE." '".$field."'");
}

function verify_db($db, $alert_dbname, $alert_host)
{
  $msg = '<B>'._ERRSNORTVER1.' '.$alert_dbname.'@'.$alert_host.' '._ERRSNORTVER2.'</B>';

  $sql = "SELECT ip_src FROM iphdr";
  $result = $db->baseExecute($sql, 0, 1, false);

  if ( $db->baseErrorMessage() != "" )
     return $msg.'<BR>'.$db->baseErrorMessage().'
            <P>'._ERRSNORTVER;

  $base_table = array ("acid_ag",
                       "acid_ag_alert",
                       "acid_ip_cache",
                       "acid_event",
                       "base_users",
                       "base_roles");

  for ( $i = 0; $i < count($base_table); $i++)
  { 
     if ( !$db->baseTableExists($base_table[$i]) )
       return $msg.'.  <P>'._ERRDBSTRUCT1.' 
              (table: '.$base_table[$i].')'._ERRDBSTRUCT2;
  }
  
  return "";
}

function verify_php_build($DBtype)
/* Checks whether the necessary libraries is built into PHP */
{
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
     return "<FONT COLOR=\"#FF0000\">"._ERRPHPERROR."</FONT>: ".
            "<B>"._ERRPHPERROR1."</B>: <FONT>"._ERRVERSION." ".$current_php_version.
            " "._ERRPHPERROR2."</FONT>";
  }

  if ( ($DBtype == "mysql") || ($DBtype == "mysqlt") )
  {
     if ( !(function_exists("mysql_connect")) )
     {
        return "<FONT COLOR=\"#FF0000\">"._ERRPHPERROR."</FONT>: "._ERRPHPMYSQLSUP;
     }
  }
  else if ( $DBtype == "postgres" )
  {
     if ( !(function_exists("pg_connect")) )
     {
        return "<FONT COLOR=\"#FF0000\">"._ERRPHPERROR."</FONT>: "._ERRPHPPOSTGRESSUP;
     }
  }
  else if ( $DBtype == "mssql" )
  {
      if ( !(function_exists("mssql_connect")) )
       {
            return "<FONT COLOR=\"#FF0000\">"._ERRPHPERROR."</FONT>: "._ERRPHPMSSQLSUP;
       }
  }
  else if ( $DBtype == "oci8" )
  {
      if ( !(function_exists("ocilogon")) )
       {
            return "<FONT COLOR=\"#FF0000\">"._ERRPHPERROR."</FONT>: "._ERRPHPORACLESUP;
       }
  }
  else
     return "<B>"._ERRSQLDBTYPE."</B>: "._ERRSQLDBTYPEINFO1."'$DBtype'.". _ERRSQLDBTYPEINFO2;
  return "";
}

/* ******************* DB Query Routines ************************************ */
function EventsByAddr($db, $i, $ip)
{
   $ip32 = baseIP2long($ip);

   $result = $db->baseExecute("SELECT signature FROM acid_event (ip_src='$ip32') OR (ip_dst='$ip32')");

   while ( $myrow = $result->baseFetchRow() ) 
      $sig[] = $myrow[0];

   $result->baseFreeRows();

   return $sig[$i];
}

function EventCntByAddr($db, $ip)
{
   $ip32 = baseIP2long($ip);

   $result = $db->baseExecute("SELECT count(ip_src) FROM acid_event WHERE ".
                  "(ip_src='$ip32') OR (ip_dst='$ip32')");

   $myrow = $result->baseFetchRow();
   $event_cnt = $myrow[0];
   $result->baseFreeRows();

   return $event_cnt;
}

function UniqueEventsByAddr($db, $i, $ip)
{
     $ip32 = baseIP2long($ip);
     $result = $db->baseExecute("SELECT DISTINCT signature FROM acid_event WHERE ".
                  "(ip_src='$ip32') OR (ip_dst='$ip32')");

   while ($myrow = $result->baseFetchRow())
      $sig[] = $myrow[0];

   $result->baseFreeRows();

   return $sig[$i];
}

function UniqueEventCntByAddr($db, $ip)
{
     $ip32 = baseIP2long($ip);
     $result = $db->baseExecute("SELECT DISTINCT signature FROM acid_event WHERE ".
                  "(ip_src='$ip32') OR (ip_dst='$ip32')");

   while ($myrow = $result->baseFetchRow())
      $sig[] = $myrow[0];

   $result->baseFreeRows();

   return $sig;
}

function UniqueEventTotalsByAddr($db, $ip, $current_event)
{
   $ip32 = baseIP2long($ip);
   $result = $db->baseExecute("SELECT count(signature) FROM acid_event WHERE ".
                  "( (ip_src='$ip32' OR ip_dst='$ip32') AND signature='$current_event')"); 

   $myrow = $result->baseFetchRow();
   $tmp = $myrow[0];
   
   $result->baseFreeRows();
   return $tmp;
}

function UniqueSensorCntByAddr($db, $ip, $current_event)
{
   $ip32 = baseIP2long($ip);
   $result = $db->baseExecute("SELECT DISTINCT sid FROM acid_event WHERE ".
                  "( (ip_src='$ip32' OR ip_dst='$ip32') AND signature='$current_event')");

   while ($myrow = $result->baseFetchRow())
      $sid[] = $myrow[0];

   $count = count($sid);
   $result->baseFreeRows();

   return $count;
}

function StartTimeForUniqueEventByAddr($db, $ip, $current_event)
{
   $ip32 = baseIP2long($ip);
   $result = $db->baseExecute("SELECT min(timestamp) FROM acid_event WHERE ".
                  "((ip_src='$ip32' OR ip_dst='$ip32') AND signature = '$current_event');");
   $myrow = $result->baseFetchRow();
   $start_time = $myrow[0];

   $result->baseFreeRows();
   return $start_time;
}

function StopTimeForUniqueEventByAddr($db, $ip, $current_event)
{
   $ip32 = baseIP2long($ip);
   $result = $db->baseExecute("SELECT max(timestamp) FROM acid_event WHERE ".
                  "((ip_src='$ip32' OR ip_dst='$ip32') AND signature = '$current_event');");

   $myrow = $result->baseFetchRow();
   $stop_time = $myrow[0];

   $result->baseFreeRows();
   return $stop_time;
}

?>
