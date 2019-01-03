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
** Purpose: Displays stats on an individual IP address   
**
** Input GET/POST variables
**   - action:
**   - submit:
**   - ip:
**   - netmask:
********************************************************************************
** Authors:
********************************************************************************
** Kevin Johnson <kjohnson@secureideas.net
**
********************************************************************************
*/

  $start = time();
  $sig   = array();
  
  include("base_conf.php");
  include("$BASE_path/includes/base_constants.inc.php");
  include("$BASE_path/includes/base_include.inc.php");
  include_once("$BASE_path/base_db_common.php");
  include_once("$BASE_path/base_common.php");

  $et = new EventTiming($debug_time_mode);
  $cs = new CriteriaState("base_stat_ipaddr.php");
  $cs->ReadState();

  $ip = ImportHTTPVar("ip", VAR_DIGIT | VAR_PERIOD);
  $netmask = ImportHTTPVar("netmask", VAR_DIGIT);
  $action = ImportHTTPVar("action", VAR_ALPHA);
  $submit = ImportHTTPVar("submit", VAR_ALPHA | VAR_SPACE);  

   // Check role out and redirect if needed -- Kevin
  $roleneeded = 10000;
  $BUser = new BaseUser();
  if (($BUser->hasRole($roleneeded) == 0) && ($Use_Auth_System == 1))
    base_header("Location: ". $BASE_urlpath . "/index.php");

  $page_title = $ip.'/'.$netmask;
  PrintBASESubHeader($page_title, $page_title, $cs->GetBackLink(), 1);

  if (!isset($ip))
  {
    ErrorMessage(__FILE__ . ":" . __LINE__ . ": \$ip has NOT been defined. Ignoring.");
    $debug_str = "<BR><PRE>\n\n" . debug_print_backtrace() . "\n\n</PRE><BR>\n";
    ErrorMessage($debug_str);
  }
  elseif (empty($ip))
  {
    ErrorMessage(__FILE__ . ":" . __LINE__ . ": \$ip has been defined, but it is empty. Ignoring."); 
    $debug_str = "<BR><PRE>\n\n" . debug_print_backtrace() . "\n\n</PRE><BR>\n";
    ErrorMessage($debug_str);
  }


function PrintPortscanEvents($db, $ip)
{
  GLOBAL $portscan_file;

  if (!$portscan_file || !isset($portscan_file) || empty($portscan_file))
  {
     ErrorMessage(_PSEVENTERR._PSEVENTERRNOFILE);
     return;
  }

  if (!is_file($portscan_file))
  {
     ErrorMessage(_PSEVENTERR._PSEVENTERROPENFILE . " '" . $portscan_file . "': This file could not be found. Maybe a wrong path or a wrong filename?");
    return;    
  }

  if (!is_readable($portscan_file))
  {
    ErrorMessage(_PSEVENTERR._PSEVENTERROPENFILE . " '" . $portscan_file . "' Maybe a permission problem?");
    return;
  }

  if (!isset($ip))
  {
    ErrorMessage(__FILE__ . ":" . __LINE__ . ": \$ip has NOT been defined. Returning.");
    return;
  }

  if (empty($ip))
  {
    ErrorMessage(__FILE__ . ":" . __LINE__ . ": \$ip has been defined, but it is empty. Returning.");
    return;
  }

  $fp = fopen($portscan_file,"r");
  if ( !$fp )
  {
     ErrorMessage(_PSEVENTERR._PSEVENTERROPENFILE." '".$portscan_file."'");
     return;
  }

  echo '<TABLE border="1" width="100%" cellspacing="0" cellpadding="5">
        <TR>
        <TD CLASS="plfieldhdr">IP addresses</TD>
        <TD CLASS="plfieldhdr">Type</TD>
        <TD CLASS="plfieldhdr">Details</TD>';
        /*
           <TD CLASS="plfieldhdr">'._PSSRCIP.'</TD>
           <TD CLASS="plfieldhdr">'._PSSRCPORT.'</TD>
           <TD CLASS="plfieldhdr">'._PSDSTIP.'</TD>
           <TD CLASS="plfieldhdr">'._PSDSTPORT.'</TD>
           <TD CLASS="plfieldhdr">'._PSTCPFLAGS.'</TD>
        */
  echo '</TR>';

  $total = 0;

  //print "\$ip = \"$ip\"<BR>\n";

  while ( !feof($fp) )
  {
   $contents = fgets($fp, 255);   

   /*
   print "<BR>\n<PRE>";
   var_dump($contents);
   print "</PRE><BR>\n";
   */

   if(ereg($ip, $contents)) {
     $total++;
     if( $total % 2 == 0 ) {
        $color="DDDDDD";
     }else{
        $color="FFFFFF";
     }

     $contents = ereg_replace("  ", " ", $contents);
     $elements = explode(" ", $contents);

     echo '<tr bgcolor="'.$color.'"><td align="center">'.
       $elements[0].' '.$elements[1].' '.$elements[2].'</td>';

     echo "<TD ALIGN=center>";
     $i = 4;
     while (isset($elements[$i]) && !empty($elements[$i]))
     {
       print $elements[$i] . " ";
       $i++;
       if ($i > 10)
       {
         break;
       }
     }
     echo "</TD>";

     $whole_entry = "";
     while($contents = fgets($fp, 255))
     {
       if (feof($fp))
       {
         break;
       }

       if (!isset($contents) || empty($contents) || $contents == "\n" || $contents == "\r\n" || $contents == "\n\r" || strlen($contents) < 3)
       {
         break;
       }

       $whole_entry .= $contents;
     }

     print "<TD ALIGN=center><PRE>";
     print $whole_entry;
     print "</PRE></TD>";

     /*
     ereg("([0-9]*\.[0-9]*\.[0-9]*\.[0-9]*):([0-9]*)",$elements[3],$store);
     $source_ip = $store[1];
     $source_port = $store[2];
     if (empty($source_ip))
     {
       $source_ip = "n/a";
     }
     if (empty($source_port))
     {
       $source_port = "n/a";
     }
     echo '<td align="center">' . $source_ip  . '</td>';
     echo '<td align="center">' . $source_port . '</td>';

     ereg("([0-9]*\.[0-9]*\.[0-9]*\.[0-9]*):([0-9]*)",$elements[5],$store);
     $destination_ip = $store[1];
     $destination_port = $store[2];
     if (empty($destination_ip))
     {
       $destination_ip = "n/a";
     }
     if (empty($destination_port))
     {
       $destination_port = "n/a";
     }
     echo '<td align="center">' . $destination_ip . '</td>';
     echo '<td align="center">' . $destination_port . '</td>';

     $tcp_flags = $elements[7];
     if (empty($tcp_flags))
     {
       $tcp_flags = "n/a";
     }
     echo '<td align="center">' . $tcp_flags . '</td></tr>';
     */
   }
  }

  fclose($fp);

  echo '<TR>
         <TD CLASS="plfieldhdr" align="left">'._PSTOTALHOSTS.'</TD>
         <TD CLASS="plfieldhdr">'.$total.'</TD>
         <TD CLASS="plfieldhdr" colspan="4">&nbsp;</TD>
        </TR>
        </TABLE>';
}



function PrintEventsByIP($db, $ip)
{
  GLOBAL $debug_mode;

  if (!isset($ip))
  {
    ErrorMessage(__FILE__ . ":" . __LINE__ . ": \$ip has NOT been defined. Returning.");
    return;
  }

  if (empty($ip))
  {
    ErrorMessage(__FILE__ . ":" . __LINE__ . ": \$ip has been defined, but it is empty. Returning.");
    return;
  }


  $count = 0;
  /* Jeffs stuff */
  /* Count total events for the given address */
  $event_cnt = EventCntByAddr($db, $ip);

  /* Grab unique alerts and count them */
  $unique_events = UniqueEventCntByAddr($db, $ip, $count);
  $unique_event_cnt = count($unique_events);

 printf ("<B>"._PSDETECTAMONG."/32</B><BR>", $unique_event_cnt,$event_cnt,$ip);
   
  /* Print the Statistics on Each of the Unique Alerts */
  echo '<TABLE BORDER=1>
        <TR>
           <TD CLASS="plfieldhdr">'._PSTCPFLAGS.'</TD>
           <TD CLASS="plfieldhdr">'._PSTOTALOCC.'</TD>
           <TD CLASS="plfieldhdr">'._PSNUMSENSORS.'</TD>
           <TD CLASS="plfieldhdr">'._PSFIRSTOCC.'</TD>
           <TD CLASS="plfieldhdr">'._PSLASTOCC.'</TD>
        </TR>';

 for ( $i = 0; $i < $unique_event_cnt; $i++ ) 
 {
   $current_event = $unique_events[$i];

   $total = UniqueEventTotalsByAddr($db, $ip, $current_event);
   $num_sensors = UniqueSensorCntByAddr($db, $ip, $current_event);
   $start_time = StartTimeForUniqueEventByAddr($db, $ip, $current_event);
   $stop_time = StopTimeForUniqueEventByAddr($db, $ip, $current_event);

   /* Print out */ 
   echo '<TR>';
   if ($debug_mode > 1)
   {
     SQLTraceLog(__FILE__ . ":" . __LINE__ . ":" . __FUNCTION__ . ": Before BuildSigByID()");
   }
   echo "  <TD ALIGN='center'> ".BuildSigByID($unique_events[$i], $db);
   if ($debug_mode > 1)
   {
     SQLTraceLog(__FILE__ . ":" . __LINE__ . ":" . __FUNCTION__ . ": After BuildSigByID()");
   }
   $tmp_iplookup = 'base_qry_main.php?new=1'.
                   '&amp;sig%5B0%5D=%3D&amp;sig%5B1%5D='.(rawurlencode(GetSignatureName($unique_events[$i], $db))).
                   '&amp;num_result_rows=-1'.
                   '&amp;submit='._QUERYDBP.'&amp;current_view=-1&amp;ip_addr_cnt=2'.
                   BuildIPFormVars($ip);

   $tmp_sensor_lookup = 'base_stat_sensor.php?'.
                        'sig%5B0%5D=%3D&amp;sig%5B1%5D='.
                        (rawurlencode($unique_events[$i])).
                        '&amp;ip_addr_cnt=2'.BuildIPFormVars($ip);

   echo "  <TD align='center'> <A HREF=\"$tmp_iplookup\">$total</A> ";
   echo "  <TD align='center'> <A HREF=\"$tmp_sensor_lookup\">$num_sensors</A> ";
   echo "  <TD align='center'> $start_time";
   echo "  <TD align='center' valign='middle'> $stop_time";
   echo '</TR>';
 }

 echo "</TABLE>\n";
}

  /* Connect to the Alert database */
  $db = NewBASEDBConnection($DBlib_path, $DBtype);
  $db->baseDBConnect($db_connect_method,
                     $alert_dbname, $alert_host, $alert_port, $alert_user, $alert_password);

  if ( $event_cache_auto_update == 1 )  UpdateAlertCache($db);

  if ( sizeof($sig) != 0 && strstr($sig[1], "spp_portscan") )
     $sig[1] = "";

  /*  Build new link for criteria-based sensor page 
   *                    -- ALS <aschroll@mitre.org>
   */
   $tmp_sensor_lookup = 'base_stat_sensor.php?ip_addr_cnt=2'.
                        BuildIPFormVars($ip);


   $tmp_srcdst_iplookup = 'base_qry_main.php?new=2'.
                          '&amp;num_result_rows=-1'.
                          '&amp;submit='._QUERYDBP.'&amp;current_view=-1&amp;ip_addr_cnt=2'.
                          BuildIPFormVars($ip);

   $tmp_src_iplookup    = 'base_qry_main.php?new=2'.
                          '&amp;num_result_rows=-1'.
                          '&amp;submit='._QUERYDBP.'&amp;current_view=-1&amp;ip_addr_cnt=1'.
                          BuildSrcIPFormVars($ip);

   $tmp_dst_iplookup    = 'base_qry_main.php?new=2'.
                          '&amp;num_result_rows=-1'.
                          '&amp;submit='._QUERYDBP.'&amp;current_view=-1&amp;ip_addr_cnt=1'.
                          BuildDstIPFormVars($ip);
  echo '<CENTER>';
  printf ("<FONT>"._PSALLALERTSAS.":</FONT>",$ip,$netmask); 
  echo '
 <A HREF="'.$tmp_src_iplookup.'">'._SCSOURCE.'</A> | 
 <A HREF="'.$tmp_dst_iplookup.'">'._SCDEST.'</A> | 
 <A HREF="'.$tmp_srcdst_iplookup.'">'._SCSOURCE.'/'._SCDEST.'</A><BR>';

 echo _PSSHOW.':
       <A HREF="base_stat_ipaddr.php?ip='.$ip.'&amp;netmask='.$netmask.'&amp;action=events">'._PSUNIALERTS.'</A>
       &nbsp; | &nbsp;
       <A HREF="base_stat_ipaddr.php?ip='.$ip.'&amp;netmask='.$netmask.'&amp;action=portscan">'._PSPORTSCANEVE.'</A>
       <BR>';

 echo '<FONT>'._PSREGWHOIS.': ';
    echo '
       <A HREF="http://ws.arin.net/cgi-bin/whois.pl?queryinput='.$ip.'" target="_NEW">ARIN</A> |
       <A HREF="http://www.db.ripe.net/whois?query='.$ip.'" target="_NEW">RIPE</A> |
       <A HREF="http://wq.apnic.net/apnic-bin/whois.pl?do_search=Search&amp;searchtext='.$ip.'" target="_NEW">APNIC</A> |
       <A HREF="http://lacnic.net/cgi-bin/lacnic/whois?lg=EN&amp;query='.$ip.'" target="_NEW">LACNIC</A><BR></FONT>';

 $octet=preg_split("/\./", $ip);
 $classc=sprintf("%03s.%03s.%03s",$octet[0],$octet[1],$octet[2]);

 echo '<FONT>'._PSEXTERNAL.': '.
      '<A HREF="'.$external_dns_link.$ip.'" target="_NEW">DNS</A> | '.
      '<A HREF="'.$external_whois_link.$ip.'" target="_NEW">whois</A> | '.
      '<A HREF="'.$external_all_link.$ip.'" target="_NEW">Extended whois</A> | '.
      '<A HREF="http://www.dshield.org/ipinfo.php?ip='.$ip.'&amp;Submit=Submit" target="_NEW">DShield.org IP Info</A> | '.
      '<A HREF="http://www.trustedsource.org/query.php?q='.$ip.'" target="_NEW">TrustedSource.org IP Info</A> | '.
      '<A HREF="http://isc.sans.org/ipinfo.html?ip='.$ip.'" target="_NEW">ISC Source/Subnet Report</A><BR> </FONT>';

  
  echo '</CENTER>';
  echo '<HR>';

  echo '<FORM METHOD="POST" ACTION="base_stat_ipaddr.php">';
  
  if ( $debug_mode >= 1 )
     echo '<TABLE BORDER=1>
             <TR><TD>action</TD><TD>submit</TD><TD>ip</TD><TD>netmask</TD></TR>
             <TR><TD>'.$action.'</TD><TD>'.$submit.'</TD>
                 <TD>'.$ip.'</TD><TD>'.$netmask.'</TD></TR>
           </TABLE>';

  /* Print the Statistics the IP address */
  echo '<CENTER><B>'.$ip.'</B><BR>FQDN: <B>';
  
  if ( $resolve_IP == 0 )
     echo '  ('._PSNODNS.')';
  else
  {
    if ( $ip != "255.255.255.255" )
        echo baseGetHostByAddr($ip, $db, $dns_cache_lifetime);
     else
        echo $ip.' (Broadcast)';
  } 

  if ( VerifySocketSupport() )
     echo '&nbsp;&nbsp;( <A HREF="base_stat_ipaddr.php?ip='.$ip.'&amp;netmask='.$netmask.'&amp;action=whois">local whois</A> )';
 
  echo    '</B>
        <TABLE BORDER=1>
        <TR>
           <TD CLASS="plfieldhdr">'._PSNUMSENSORSBR.'</TD>
           <TD CLASS="plfieldhdr">'._PSOCCASSRC.'</TD>
           <TD CLASS="plfieldhdr">'._PSOCCASDST.'</TD>
           <TD CLASS="plfieldhdr">'._PSFIRSTOCC.'</TD>
           <TD CLASS="plfieldhdr">'._PSLASTOCC.'</TD>
        </TR>';

  $ip_src32 = baseIP2long($ip);
  $ip_dst32 = $ip_src32;

  /* Number of Sensors, First, and Last timestamp */
  $temp = "SELECT COUNT(DISTINCT sid), MIN(timestamp), MAX(timestamp) FROM acid_event ".  
          "WHERE (ip_src = '$ip_src32' OR ip_dst = '$ip_dst32' )";
  $result2 = $db->baseExecute($temp);
  $row2 = $result2->baseFetchRow();

  $num_sensors = $row2[0];
  $start_time = $row2[1]; 
  $stop_time = $row2[2];
  $result2->baseFreeRows();

  /* Unique instances as Source Address  */
  $temp = "SELECT COUNT(sid) from acid_event WHERE ip_src='$ip_src32'";  
  $result2 = $db->baseExecute($temp);
  $row2 = $result2->baseFetchRow();
  $num_src_ip = $row2[0];
  $result2->baseFreeRows(); 

  /* Unique instances Dest. Address  */
  $temp = "SELECT COUNT(sid) from acid_event WHERE ip_dst='$ip_dst32'";
  $result2 = $db->baseExecute($temp);
  $row2 = $result2->baseFetchRow();
  $num_dst_ip = $row2[0];
  $result2->baseFreeRows(); 

  /* Print out */ 
  echo '<TR>
         <TD ALIGN="center"><A HREF="'.$tmp_sensor_lookup.'">'.$num_sensors.'</A>';
  if ( $num_src_ip == 0 )
         echo '<TD ALIGN="center">'.$num_src_ip;
  else
         echo '<TD ALIGN="center"><A HREF="'.$tmp_src_iplookup.'">'.$num_src_ip.'</A>';
  if ( $num_dst_ip == 0 )         
         echo '<TD ALIGN="center">'.$num_dst_ip;
  else
         echo '<TD ALIGN="center"><A HREF="'.$tmp_dst_iplookup.'">'.$num_dst_ip.'</A>';
  echo '
         <TD align="center">'.$start_time.'
         <TD align="center" valign="middle">'.$stop_time.'
       </TR>
      </TABLE></CENTER>';

  if ( $action == "events" )
  {
     echo '<HR>
            <CENTER><P>';
     PrintEventsByIP($db, $ip);
     echo ' </CENTER>';	
  }
  else if ( $action == "whois" )
  {
     echo "\n<B>"._PSWHOISINFO."</B>".
          "<PRE>".baseGetWhois($ip, $db, $whois_cache_lifetime)."</PRE>";
  }
  else if ( $action == "portscan" )
  {
     echo '<HR>
            <CENTER><P>';
     PrintPortscanEvents($db, $ip);
     echo ' </CENTER>';	
  }  


  echo "\n</FORM>\n";
  
  PrintBASESubFooter();

  $et->PrintTiming();
  echo "</body>\r\n</html>";
?>
