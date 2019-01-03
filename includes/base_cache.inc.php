<?php
/*******************************************************************************
** Basic Analysis and Security Engine (BASE)
** Copyright (C) 2004 BASE Project Team
** Copyright (C) 2000 Carnegie Mellon University
**
** (see the file 'base_main.php' for license details)
**
** Project Lead: Kevin Johnson <kjohnson@secureideas.net>
**                Sean Muller <samwise_diver@users.sourceforge.net>
** Built upon work by Roman Danyliw <rdd@cert.org>, <roman@danyliw.com>
**
** Purpose: IP DNS, whois, event cache library   
********************************************************************************
** Authors:
********************************************************************************
** Kevin Johnson <kjohnson@secureideas.net
**
********************************************************************************
*/
/** The below check is to make sure that the conf file has been loaded before this one....
 **  This should prevent someone from accessing the page directly. -- Kevin
 **/
defined( '_BASE_INC' ) or die( 'Accessing this file directly is not allowed.' );

include_once("$BASE_path/base_stat_common.php");
include_once("$BASE_path/includes/base_log_error.inc.php");

function UpdateDNSCache($db)
{
  GLOBAL $debug_mode, $dns_cache_lifetime;

  $cnt = 0;

  $ip_result = $db->baseExecute("SELECT DISTINCT ip_src FROM acid_event ".
                                "LEFT JOIN acid_ip_cache ON ipc_ip = ip_src ".
                                "WHERE ipc_fqdn IS NULL");

  while ( ($row = $ip_result->baseFetchRow()) != "")
  {
     if ( $debug_mode > 0 )  
        echo $row[0]." - ".baseLong2IP($row[0])."<BR>";
     baseGetHostByAddr(baseLong2IP($row[0]), $db, $dns_cache_lifetime);
     ++$cnt;
  }
  $ip_result->baseFreeRows();

  $ip_result = $db->baseExecute("SELECT DISTINCT ip_dst FROM acid_event ".
                                "LEFT JOIN acid_ip_cache ON ipc_ip = ip_dst ".
                                "WHERE ipc_fqdn IS NULL");
  while ( ($row = $ip_result->baseFetchRow()) != "")
  {
     if ( $debug_mode > 0 )  
        echo $row[0]." - ".baseLong2IP($row[0])."<BR>";  
     baseGetHostByAddr(baseLong2IP($row[0]), $db, $dns_cache_lifetime);
     ++$cnt;
  }
  $ip_result->baseFreeRows();

  ErrorMessage(_ADDED.$cnt._HOSTNAMESDNS);
}

function UpdateWhoisCache($db)
{
  GLOBAL $debug_mode, $whois_cache_lifetime;

  $cnt = 0;

  $ip_result = $db->baseExecute("SELECT DISTINCT ip_src FROM acid_event ".
                                "LEFT JOIN acid_ip_cache ON ipc_ip = ip_src ".
                                "WHERE ipc_whois IS NULL");

  while ( ($row = $ip_result->baseFetchRow()) != "")
  {
     if ( $debug_mode > 0 )  echo $row[0]." - ".baseLong2IP($row[0])."<BR>";
     baseGetWhois(baseLong2IP($row[0]), $db, $whois_cache_lifetime);
     ++$cnt;
  }
  $ip_result->baseFreeRows();

  $ip_result = $db->baseExecute("SELECT DISTINCT ip_dst FROM acid_event ".
                                "LEFT JOIN acid_ip_cache ON ipc_ip = ip_dst ".
                                "WHERE ipc_whois IS NULL");

  while ( ($row = $ip_result->baseFetchRow()) != "")
  {
     if ( $debug_mode > 0 )  echo $row[0]." - ".baseLong2IP($row[0])."<BR>";  
     baseGetWhois(baseLong2IP($row[0]), $db, $whois_cache_lifetime);
     ++$cnt;
  }
  $ip_result->baseFreeRows();

  ErrorMessage(_ADDED.$cnt._HOSTNAMESWHOIS);
}

function CacheAlert($sid, $cid, $db)
{
  $signature = $timestamp = $ip_src = $ip_dst = null;
  $ip_proto = $layer4_sport = $layer4_dport = $sig_name = null;
  $sig_class_id = $sig_priority = null;

  $sql = "SELECT signature, timestamp, ip_src, ip_dst, ip_proto FROM event ".
         "LEFT JOIN iphdr ON (event.sid=iphdr.sid AND event.cid = iphdr.cid) ".
         "WHERE (event.sid='".$sid."' AND event.cid='".$cid."') ORDER BY event.cid";

  $result = $db->baseExecute($sql);

  $row = $result->baseFetchRow();
  if ( $row )
  {
     $signature = $row[0];
     $timestamp = $row[1];
     $ip_src    = $row[2];
     $ip_dst    = $row[3];
     $ip_proto  = $row[4];
     $result->baseFreeRows();

     if ( $ip_proto == TCP )
     {
        $result = $db->baseExecute("SELECT tcp_sport, tcp_dport FROM
                                    tcphdr WHERE sid='".$sid."' AND cid='".$cid."'");
        $row = $result->baseFetchRow();
        if ( $row )
        {
           $layer4_sport = $row[0];
           $layer4_dport = $row[1];
           $result->baseFreeRows();
        }
     }

     else if ( $ip_proto == UDP )
     {
        $result = $db->baseExecute("SELECT udp_sport, udp_dport FROM
                                    udphdr WHERE sid='".$sid."' AND cid='".$cid."'");
        $row = $result->baseFetchRow();
        if ( $row )
        {
           $layer4_sport = $row[0];
           $layer4_dport = $row[1];
           $result->baseFreeRows();
        }
     }

     if ( $db->baseGetDBversion() >= 100 )
     {
        if ( $db->baseGetDBversion() >= 103 )
           $result = $db->baseExecute("SELECT sig_name, sig_class_id, sig_priority ".
                                      " FROM signature ".
                                      "WHERE sig_id = '".$signature."'");
        else
           $result = $db->baseExecute("SELECT sig_name FROM signature ".
                                      "WHERE sig_id = '".$signature."'");
        $row = $result->baseFetchRow();
        if ( $row )
        {
           $sig_name = $row[0];
           if ( $db->baseGetDBversion() >= 103 )
           {
              $sig_class_id = $row[1];
              $sig_priority = $row[2];
           }
           $result->baseFreeRows();
        } 
     }
  }
  else
  {
    ErrorMessage(_ERRCACHENULL);
    echo "<PRE>".$sql."</PRE>";
  }

  /* There can be events without certain attributes */
  if ($sig_priority=='') $sig_priority='NULL';
  if ($ip_src=='') $ip_src='NULL';
  if ($ip_dst=='') $ip_dst='NULL';
  if ($ip_proto=='') $ip_proto='NULL';
  if ($layer4_sport=='') $layer4_sport='NULL';
  if ($layer4_dport=='') $layer4_dport='NULL';
  if ($sig_class_id == '') $sig_class_id = 'NULL';
  if ($sig_priority == '') $sig_priority = 'NULL';

  if ( $db->baseGetDBversion() >= 100 ) {
      $sql = "INSERT INTO acid_event (sid, cid, signature, sig_name, sig_class_id, sig_priority, ";
      $sql.= "timestamp, ip_src, ip_dst, ip_proto, layer4_sport, layer4_dport) ";
      $sql.= "VALUES ($sid, $cid, $signature, '" . addslashes($sig_name) . "', $sig_class_id, $sig_priority,";
      $sql.= "'$timestamp', $ip_src, $ip_dst, $ip_proto, $layer4_sport, $layer4_dport)";
  } else {
      $sql = "INSERT INTO acid_event (sid, cid, signature, timestamp, ip_src, ";
      $sql.= "ip_dst, ip_proto, layer4_sport,layer4_dport) ";
      $sql.= "VALUES ($sid, $cid, '$signature', '$timestamp', $ip_src, $ip_dst, ";
      $sql.= "$ip_proto, $layer4_sport, $layer4_dport)";
  }

  $db->baseExecute($sql); 

  if ( $db->baseErrorMessage() != "" )
     return 0;
  else 
     return 1;
}

function CacheSensor($sid, $cid, $db)
/*
  Caches all alerts for sensor $sid newer than the event $cid
 */
{
  GLOBAL $debug_mode;


  $schema_specific = array(2);

  $schema_specific[0] = "";
  $schema_specific[1] = "";
  $schema_specific[2] = "";

  if ( $db->baseGetDBversion() >= 100 ) 
  {
     $schema_specific[1] = ", sig_name"; 
     $schema_specific[2] = " INNER JOIN signature ON (signature = signature.sig_id) ";
  }

  if ( $db->baseGetDBversion() >= 103 )
  {
     $schema_specific[0] = $schema_specific[0].", sig_priority, sig_class_id ";
     $schema_specific[1] = $schema_specific[1].", sig_priority, sig_class_id "; 
     $schema_specific[2] = $schema_specific[2]."";
  }

  if ( $db->baseGetDBversion() < 100 )
     $schema_specific[1] = $schema_specific[1].", signature ";

  $update_sql = array(4);

  /* Preprocessor events only */
  # The original "(sig_name LIKE '(spp_%')" is too limited. Cf.
  # /usr/local/src/snort-2.8.3.1_unpatched/etc/gen-msg.map
  # /usr/local/src/snort-2.8.3.1_unpatched/src/generators.h
  # Currently I have included all the names that I have found in 
  # these files.
  # Note: Do always add '%' in LIKE-statements. Otherwise the entries
  #       won't match.
  if ( $db->baseGetDBversion() >= 100 ) {
    $schema_specific[3] = " ( " . 
                          "(sig_name LIKE '(spp_%') OR " . 
                          "(sig_name LIKE '(spo_%') OR " . 
                          "(sig_name LIKE '(snort_decoder)%') OR " .
                          "(sig_name LIKE '(http_decode)%') OR " . 
                          "(sig_name LIKE '(http_inspect)%') OR " . 
                          "(sig_name LIKE '(portscan)%') OR " . 
                          "(sig_name LIKE '(flow-portscan)%') OR " . 
                          "(sig_name LIKE '(frag3)%') OR " . 
                          "(sig_name LIKE '(smtp)%') OR " .
                          "(sig_name LIKE '(ftp_pp)%') OR " . 
                          "(sig_name LIKE '(telnet_pp)%') OR " .
                          "(sig_name LIKE '(ssh)%') OR " .
                          "(sig_name LIKE '(stream5)%') OR " . 
                          "(sig_name LIKE '(dcerpc)%') OR " .
                          "(sig_name LIKE '(dns)%') OR " . 
                          "(sig_name LIKE '(ppm)%') " .
                          " ) ";
  }
  else {
    $schema_specific[3] = " (signature LIKE '(spp_%') ";
  }

  
  /* TCP events */
  if( $db->DB_type == 'oci8' ) {
  $update_sql[0] =
    "INSERT INTO acid_event (sid,cid,signature,timestamp,
                             ip_src,ip_dst,ip_proto,
                             layer4_sport,layer4_dport,
                             sig_name".
                             $schema_specific[0].")
     SELECT a.sid as sid, a.cid as cid, a.signature, a.timestamp,
            b.ip_src, ip_dst, ip_proto,
            tcp_sport as layer4_sport, tcp_dport as layer4_dport".
            $schema_specific[1]."
    FROM event a
    ".$schema_specific[2]." 
    INNER JOIN iphdr b ON (a.sid=b.sid AND a.cid=b.cid) 
    LEFT JOIN tcphdr c ON (a.sid=c.sid AND a.cid=c.cid)
    WHERE (a.sid = $sid AND a.cid > $cid) AND ip_proto = 6
    AND ( NOT ".$schema_specific[3].")";
  }
  else {
  $update_sql[0] =
    "INSERT INTO acid_event (sid,cid,signature,timestamp,
                             ip_src,ip_dst,ip_proto,
                             layer4_sport,layer4_dport,
                             sig_name".
                             $schema_specific[0].")
     SELECT event.sid as sid, event.cid as cid, signature, timestamp, 
            ip_src, ip_dst, ip_proto,
            tcp_sport as layer4_sport, tcp_dport as layer4_dport".
            $schema_specific[1]."
    FROM event
    ".$schema_specific[2]." 
    INNER JOIN iphdr ON (event.sid=iphdr.sid AND event.cid=iphdr.cid) 
    LEFT JOIN tcphdr ON (event.sid=tcphdr.sid AND event.cid=tcphdr.cid)
    WHERE (event.sid = $sid AND event.cid > $cid) AND ip_proto = 6
    AND ( NOT ".$schema_specific[3].")";
  }

  /* UDP events */
  if( $db->DB_type == 'oci8' ) {
  $update_sql[1] = 
    "INSERT INTO acid_event (sid,cid,signature,timestamp,
                             ip_src,ip_dst,ip_proto,
                             layer4_sport,layer4_dport,
                             sig_name".
                             $schema_specific[0].")
     SELECT a.sid as sid, a.cid as cid, signature, a.timestamp,
            ip_src, ip_dst, ip_proto,
            udp_sport as layer4_sport, udp_dport as layer4_dport".
            $schema_specific[1]."
     FROM event a
     ".$schema_specific[2]."
     INNER JOIN iphdr b ON (a.sid=b.sid AND a.cid=b.cid)
     LEFT JOIN udphdr c ON (a.sid=c.sid AND a.cid=c.cid)
     WHERE (a.sid = $sid AND a.cid > $cid) AND ip_proto = 17
     AND ( NOT ".$schema_specific[3].")";
  }
  else {
  $update_sql[1] = 
    "INSERT INTO acid_event (sid,cid,signature,timestamp,
                             ip_src,ip_dst,ip_proto,
                             layer4_sport,layer4_dport,
                             sig_name".
                             $schema_specific[0].")
     SELECT event.sid as sid, event.cid as cid, signature, timestamp,
            ip_src, ip_dst, ip_proto,
            udp_sport as layer4_sport, udp_dport as layer4_dport".
            $schema_specific[1]."
     FROM event
     ".$schema_specific[2]."
     INNER JOIN iphdr ON (event.sid=iphdr.sid AND event.cid=iphdr.cid)
     LEFT JOIN udphdr ON (event.sid=udphdr.sid AND event.cid=udphdr.cid)
     WHERE (event.sid = $sid AND event.cid > $cid) AND ip_proto = 17
     AND ( NOT ".$schema_specific[3].")";
  }

  /* ICMP events */
  if( $db->DB_type == 'oci8' ) {
    $update_sql[2] = 
     "INSERT INTO acid_event (sid,cid,signature,timestamp,
                              ip_src,ip_dst,ip_proto,
                              sig_name".
                              $schema_specific[0].")
      SELECT a.sid as sid, a.cid as cid, signature, a.timestamp,
             ip_src, ip_dst, ip_proto".
             $schema_specific[1]."
      FROM event a
      ".$schema_specific[2]."
      INNER JOIN iphdr b ON (a.sid=b.sid AND a.cid=b.cid)
      LEFT JOIN icmphdr c ON (a.sid=c.sid AND a.cid=c.cid)
      WHERE (a.sid = $sid AND a.cid > $cid) and ip_proto = 1
      AND ( NOT ".$schema_specific[3].")";
  }
  else 
  {
    $update_sql[2] = 
     "INSERT INTO acid_event (sid,cid,signature,timestamp,
                              ip_src,ip_dst,ip_proto,
                              sig_name".
                              $schema_specific[0].")
      SELECT event.sid as sid, event.cid as cid, signature, timestamp,
             ip_src, ip_dst, ip_proto".
             $schema_specific[1]."
      FROM event
      ".$schema_specific[2]."
      INNER JOIN iphdr ON (event.sid=iphdr.sid AND event.cid=iphdr.cid)
      LEFT JOIN icmphdr ON (event.sid=icmphdr.sid AND event.cid=icmphdr.cid)
      WHERE (event.sid = $sid AND event.cid > $cid) and ip_proto = 1
      AND ( NOT ".$schema_specific[3].")";
  }

  /* IP based protocols that are neither ICMP nor TCP nor UDP nor
     preprocessor generated */
  if( $db->DB_type == 'oci8' ) {
    $update_sql[3] = 
     "INSERT INTO acid_event (sid,cid,signature,timestamp,
                              ip_src,ip_dst,ip_proto,
                              sig_name".
                              $schema_specific[0].")
      SELECT a.sid as sid, a.cid as cid, signature, a.timestamp,
             ip_src, ip_dst, ip_proto".
             $schema_specific[1]."
      FROM event a
      ".$schema_specific[2]."
      LEFT JOIN iphdr b ON (a.sid=b.sid AND a.cid=b.cid)
      WHERE (NOT (ip_proto IN (1, 6, 17))) AND ".
            " ( NOT ".$schema_specific[3].") AND
            (a.sid = $sid AND a.cid > $cid)";
  }
  else 
  {
    $update_sql[3] = 
     "INSERT INTO acid_event (sid,cid,signature,timestamp,
                              ip_src,ip_dst,ip_proto,
                              sig_name".
                              $schema_specific[0].")
      SELECT event.sid as sid, event.cid as cid, signature, timestamp,
             ip_src, ip_dst, ip_proto".
             $schema_specific[1]."
      FROM event
      ".$schema_specific[2]."
      LEFT JOIN iphdr ON (event.sid=iphdr.sid AND event.cid=iphdr.cid)
      WHERE (NOT (ip_proto IN (1, 6, 17))) AND ".
            " ( NOT ".$schema_specific[3].") AND
            (event.sid = $sid AND event.cid > $cid)";
  }



  /* Event only -- pre-processor alerts */
  if( $db->DB_type == 'oci8' ) {
     $update_sql[4] = 
       "INSERT INTO acid_event (sid,cid,signature,timestamp,
                                ip_src,ip_dst,ip_proto,
                                sig_name".
                                $schema_specific[0].")
        SELECT a.sid as sid, a.cid as cid, signature, a.timestamp,
               ip_src, ip_dst, ip_proto".
               $schema_specific[1]."
        FROM event a
        ".$schema_specific[2]."
        LEFT JOIN iphdr b ON (a.sid=b.sid AND a.cid=b.cid)
        WHERE ".$schema_specific[3]." AND 
        (a.sid = $sid AND a.cid > $cid)";
  }
  else 
  {
    $update_sql[4] = 
       "INSERT INTO acid_event (sid,cid,signature,timestamp,
                                ip_src,ip_dst,ip_proto,
                                sig_name".
                                $schema_specific[0].")
        SELECT event.sid as sid, event.cid as cid, signature, timestamp,
               ip_src, ip_dst, ip_proto".
               $schema_specific[1]."
        FROM event
        ".$schema_specific[2]."
        LEFT JOIN iphdr ON (event.sid=iphdr.sid AND event.cid=iphdr.cid)
        WHERE ".$schema_specific[3]." AND 
        (event.sid = $sid AND event.cid > $cid)";
  }


  // Some checks for unexpected errors
  $update_cnt = count($update_sql);
  if (!isset($update_cnt)) 
  {
    $mystr = '<BR>' . __FILE__ . ':' . __LINE__ . ": WARNING: \$update_cnt has not been set. sid = $sid, cid = $cid<BR>";
    echo $mystr; 
  }
  else if ((integer)$update_cnt == 0) 
  {
    $mystr = '<BR>' . __FILE__ . ':' . __LINE__ . ": WARNING: \$update_cnt = 0 with sid = $sid, cid = $cid<BR>";
    echo $mystr; 
  }
  else if (!isset($update_sql[0]) && !isset($update_sql[1]) && !isset($update_sql[2]) && !isset($update_sql[3])) 
  {
    $mystr = '<BR>' . __FILE__ . ':' . __LINE__ . ": WARNING: \$update_sql[] has only empty elements with sid = $sid, cid = $cid<BR>";
    echo $mystr;
  } 
  else if ($update_sql[0] == "" && $update_sql[1] == "" && $update_sql[2] == "" && $update_sql[3] == "") 
  {
    $mystr = '<BR>' . __FILE__ . ':' . __LINE__ . ": WARNING: \$update_sql[] has only empty elements with sid = $sid, cid = $cid<BR>";
    echo $mystr;
  }



  // Now commit all those SQL commands
  for ( $i = 0; $i < $update_cnt; $i++ )
  {
    if ($debug_mode > 0)
    {
      $mystr = '<BR>' . __FILE__ . ':' . __LINE__ . ": <BR>\n$update_sql[$i] <BR><BR>\n\n";
      echo $mystr;
    }


    $db->baseExecute($update_sql[$i]); 

    if ( $db->baseErrorMessage() != "" )
       ErrorMessage(_ERRCACHEERROR." ["._SENSOR." #$sid]["._EVENTTYPE." $i]".
                      " "._ERRCACHEUPDATE);

  }
}


// This is an auxiliary function for problems with updating acid_event
function dump_missing_events($db, $sid, $start_cid, $end_cid)
{
  GLOBAL $debug_mode;
  GLOBAL $archive_exists;
  GLOBAL $DBlib_path, $DBtype, 
         $archive_dbname, $archive_host, $archive_port,
         $archive_user, $archive_password;


  for ($n = (integer)$start_cid; $n <= (integer)$end_cid; $n++)
  {
    // Does this particular really exist in the event table?
    $event_list = $db->baseExecute( "SELECT count(*) FROM event WHERE sid='" . $sid . "' AND cid='" . $n. "'" );
    $event_row = $event_list->baseFetchRow();
    $event_value = $event_row[0];
    $event_list->baseFreeRows();
    if ((integer)$event_value == 1) {
      // Yes, it does.
      // So let's try and find it in acid event.
      $acid_event_list = $db->baseExecute( "SELECT count(*) FROM acid_event WHERE sid='" . $sid . "' AND cid='" . $n. "'" );
      $acid_event_row = $acid_event_list->baseFetchRow();
      $acid_event_element = $acid_event_row[0];
      $acid_event_list->baseFreeRows();
      if ((integer)$acid_event_element == 0) 
      {
        echo '<BR>' . __FILE__ . ':' . __LINE__ . ": ERROR: Alert \"$sid - $n\" could NOT be found in acid_event.<BR>";
      }
    }
  }
}



function UpdateAlertCache($db)
{
  GLOBAL $debug_mode;
  GLOBAL $archive_exists;
  GLOBAL $DBlib_path, $DBtype, 
         $archive_dbname, $archive_host, $archive_port,
         $archive_user, $archive_password;

  $batch_sql = "";
  $batch_cnt = 0;

  $updated_cache_cnt = 0;

  // How many sensors do we have?
  $number_sensors_lst = $db->baseExecute("SELECT count(*) FROM sensor");
  $number_sensors_array = $number_sensors_lst->baseFetchRow();
  $number_sensors_lst->baseFreeRows();

  if (!isset($number_sensors_array))
  {
    $mystr = '<BR>' . __FILE__ . ':' . __LINE__ . ": ERROR: \$number_sensors_array has not been set at all!<BR>";
    ErrorMessage($mystr);
    $number_sensors = 0;
  }

  if (!is_array($number_sensors_array))
  {
    $mystr = '<BR>' . __FILE__ . ':' . __LINE__ . ": ERROR: \$number_sensors_array is NOT an array!<BR>";
    ErrorMessage($mystr);
    
    $number_sensors = 0;
  }

  if ($number_sensors_array == NULL || $number_sensors_array == "")
  {
    $mystr = '<BR>' . __FILE__ . ':' . __LINE__ . ": ERROR: \$number_sensors_array is either NULL or empty!<BR>";
    ErrorMessage($mystr);

    $number_sensors = 0;
  } 
  else
  {
    $number_sensors = $number_sensors_array[0];
  }

  if ($debug_mode > 1)
  {
    echo '$number_sensors = ' . $number_sensors . '<BR><BR>';
  }

  
  if (($debug_mode > 0) && ($number_sensors < 1))
  {
    ErrorMessage("WARNING: Number of sensors = " . $number_sensors);
    echo '<HR>';
    echo '<BR>number_sensors_array:<BR>';
    echo '<PRE>';
    var_dump($number_sensors_array);
    echo '</PRE>';
    echo '<HR>';    
  }
  
  
  /* Iterate through all sensors in the SENSOR table */
  $sensor_lst = $db->baseExecute("SELECT sid FROM sensor");
  if (($debug_mode > 0) && ($number_sensors < 1))
  {
    echo '<HR>';
    echo '<BR>sensor_lst:<BR>';
    echo '<PRE>';
    var_dump($sensor_lst);
    echo '</PRE>';
    echo '<HR>';
  }

  for ($n = 0; $n < $number_sensors; $n++)
  {
    $sid_row = $sensor_lst->baseFetchRow();
    if (!isset($sid_row) || $sid_row == "" || $sid_row == NULL)
    {
      if ($n >= $number_sensors)
      {
        break;
      }
      else
      {
        next;
      }
    }

    $sid = $sid_row[0];
    /* Get highest CID for a given sensor */
    $cid_lst = $db->baseExecute("SELECT MAX(cid) FROM event WHERE sid='".$sid."'");
    $cid_row = $cid_lst->baseFetchRow();
    if (
         (!isset($cid_row)) ||
         ($cid_row == NULL) ||
         ($cid_row == "")
       )
    {
      /* NULL is in conflict with snort-2.8.0.1/schemas/create_mysql:
       * CREATE TABLE event  ( sid         INT      UNSIGNED NOT NULL,
                               cid         INT      UNSIGNED NOT NULL,
                               signature   INT      UNSIGNED NOT NULL, 
                               timestamp            DATETIME NOT NULL,
                               PRIMARY KEY (sid,cid),
                               INDEX       sig (signature),
                               INDEX       time (timestamp));
       */
      $cid = 0;
    }
    else
    {
      $cid = $cid_row[0];
    }
    if ( $cid == NULL ) $cid = 0;

    /* Get highest CID for a given sensor in the cache */
    $ccid_lst = $db->baseExecute("SELECT MAX(cid) FROM acid_event WHERE sid='".$sid."'");
    $ccid_row = $ccid_lst->baseFetchRow();
    if (
         (!isset($ccid_row)) ||
         ($ccid_row == NULL) ||
         ($ccid_row == "")
       )
    {
      /* NULL is in conflict with base-php4/sql/create_base_tbls_mysql.sql:
         CREATE TABLE acid_event   ( sid                 INT UNSIGNED NOT NULL,
                                      cid                 INT UNSIGNED NOT NULL,     
         (...)
       */
      $ccid = 0;
    }
    else
    {
      $ccid = $ccid_row[0];
    }
    if ( $ccid == NULL ) $ccid = 0;

    if ( $debug_mode > 0 )
      echo "sensor #$sid: event.cid = $cid, acid_event.cid = $ccid";

    /* if the CID in the cache < the CID in the event table 
     *  then there are events which have NOT been added to the cache 
     */
    if ( $cid > $ccid )
    {
      $expected_addition = (integer)($cid - $ccid);

      $before_cnt = EventCntBySensor($sid, $db);        
      CacheSensor($sid, $ccid, $db);
      $updated_cache_cnt += EventCntBySensor($sid, $db) - $before_cnt;
    }

    if ( $debug_mode > 0 )
      echo "<BR>";

    if ($cid_row != NULL)
    {
      $cid_lst->baseFreeRows();
    }

    if ($ccid_row != NULL)
    {
      $ccid_lst->baseFreeRows();
    }
 
    /* BEGIN LOCAL FIX */
 
    /* If there's an archive database, and this isn't it, get the MAX(cid) from there */
    if ( ($archive_exists == 1) && (@$_COOKIE['archive'] != 1) ) { 
      $db2 = NewBASEDBConnection($DBlib_path, $DBtype);
      $db2->baseConnect($archive_dbname, $archive_host, $archive_port,
                        $archive_user, $archive_password);
      $archive_ccid_lst = $db2->baseExecute("SELECT MAX(cid) FROM acid_event WHERE sid='".$sid."'"); 
      $archive_ccid_row = $archive_ccid_lst->baseFetchRow();
      $archive_ccid = $archive_ccid_row[0];
      $archive_ccid_lst->baseFreeRows();
      $db2->baseClose();
      if ( $archive_ccid == NULL ) $archive_ccid = 0;
    } else {
      $archive_ccid = 0; 
    }
 
    if ( $archive_ccid > $ccid ) {
      $max_ccid = $archive_ccid;
    } else {
      $max_ccid = $ccid;
    }
 
    /* Fix the last_cid value for the sensor */
    $db->baseExecute("UPDATE sensor SET last_cid=$max_ccid WHERE sid=$sid"); 

    /* END LOCAL FIX */


    ####### Has every alert in the event table found its way into
    ####### acid_event?
    if (isset($ccid)) {

      if ($debug_mode > 1)
      {
        echo '<BR><BR>' . __FILE__ . ':' . __LINE__ . ": <BR>\nSensor no. $sid:<BR>\n<PRE>\n";
        echo "Old max cid in acid_event: $ccid<BR>";    
      }

      $debug_new_ccid_lst = $db->baseExecute("SELECT MAX(cid) FROM acid_event WHERE sid='".$sid."'");
      $debug_new_ccid_row = $debug_new_ccid_lst->baseFetchRow();
      $debug_new_ccid_lst->baseFreeRows();
      if (isset($debug_new_ccid_row[0])) 
      {
        $new_ccid = (integer) $debug_new_ccid_row[0];
      }
      else
      {
        $new_ccid = 0;
      }

      
      $real_addition = (integer)($new_ccid - (integer)$ccid);

      if ($debug_mode > 1)
      {
        echo "New max cid in acid_event: $new_ccid<BR>";
        echo "This many events HAVE been added to acid_event: $real_addition<BR><BR>";
    
        echo "Max cid in event: $cid<BR>";
      }

      if ($real_addition >= 0) 
      {


        if (!isset($expected_addition)) 
        {
          $expected_addition = 0;
        }

        if ($debug_mode > 1)
        {
          echo "This many events SHOULD have been added to acid_event: $expected_addition<BR>";
        }

        if ($real_addition > 0 && $expected_addition > 0) 
        {
          if ($expected_addition - $real_addition > 0) 
          {
            $mystr = '<BR>' . __FILE__ . ':' . __LINE__ . ": ERROR: <BR>" . (integer)((integer)$expected_addition - (integer)$real_addition) . " alerts have NOT found their way into acid_event with sid = $sid<BR>";
            errorMessage($mystr);


            dump_missing_events($db, $sid, $ccid, $new_ccid);
          }
        }
      }
      else
      {
        if ($debug_mode > 1)
        {
          echo "$real_addition is negative. \$new_ccid could not be retrieved. This is apparently not a situation where this sanity check would be applicable.\n";
        }
      }

      if ($debug_mode > 1) 
      {
        echo "\n---------------<BR><PRE>\n";
      }
    }
  } // for ($n = 0; $n < $number_sensors; $n++)
  
  $sensor_lst->baseFreeRows();


  if ( $updated_cache_cnt != 0 )
  {
    if ( preg_match("/base_main.php/", $_SERVER['SCRIPT_NAME']) )
         ErrorMessage(_ADDED.$updated_cache_cnt._ALERTSCACHE, "yellow");
    else
         ErrorMessage(_ADDED.$updated_cache_cnt._ALERTSCACHE);
  }
}

function DropAlertCache($db)
{
  $db->baseExecute("DELETE FROM acid_event");
}

function DropDNSCache($db)
{
  $db->baseExecute("UPDATE acid_ip_cache SET ipc_fqdn = NULL, ipc_dns_timestamp = NULL");
}

function DropWhoisCache($db)
{
  $db->baseExecute("UPDATE acid_ip_cache SET ipc_whois = NULL, ipc_whois_timestamp = NULL");
}
// vim:tabstop=2:shiftwidth=2:expandtab
?>
