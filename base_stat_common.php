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
** Purpose: summary statistics
********************************************************************************
** Authors:
********************************************************************************
** Kevin Johnson <kjohnson@secureideas.net
**
********************************************************************************
*/
// Ensure the conf file has been loaded. Prevent direct access to this file.
defined('_BASE_INC') or die('Accessing this file directly is not allowed.');

function SensorCnt( $db, $join = '', $where = '' ){
   if ( $join == "" && $where == "" )
      $result = $db->baseExecute("SELECT COUNT(DISTINCT acid_event.sid) FROM acid_event");
   else
      $result = $db->baseExecute("SELECT COUNT(DISTINCT acid_event.sid) FROM acid_event $join $where");
   $myrow = $result->baseFetchRow();
   $num = $myrow[0];
   $result->baseFreeRows();

	return $num;
}

function SensorTotal( $db ){
   $result = $db->baseExecute("SELECT COUNT(DISTINCT sensor.sid) FROM sensor");
   $myrow = $result->baseFetchRow();
   $num = $myrow[0];
   $result->baseFreeRows();

	return $num;
}

function EventCnt( $db, $join = '', $where = '' ){
   if ( $join == "" && $where == "" )
      $result = $db->baseExecute("SELECT count(*) FROM acid_event");
   else
      $result = $db->baseExecute("SELECT COUNT(acid_event.sid) FROM acid_event $join $where");  

   $myrow = $result->baseFetchRow();
   $num = $myrow[0];
   $result->baseFreeRows();

	return $num;
}

// Takes: Numeric sensor ID from the Sensor table (SID), and DB connection.
// Returns: The number of unique alert descriptions for the given sensor ID.
function UniqueCntBySensor( $sensorID, $db ){
	// Calculate the Unique Alerts.
  $query = "SELECT COUNT(DISTINCT signature) FROM acid_event WHERE sid = '" . $sensorID . "'";
  $result = $db->baseExecute($query);

  if ( $result ) 
  {
     $row = $result->baseFetchRow();
     $num = $row[0];
     $result->baseFreeRows();
  }
  else
     $num = 0;

	return $num;
}

// Takes: Numeric sensor ID from the Sensor table (SID), and DB connection.
// Returns: The total number of alerts for the given sensor ID.
function EventCntBySensor( $sensorID, $db ){
   $query = "SELECT count(*) FROM acid_event where sid = '" .$sensorID. "'";

   $result = $db->baseExecute($query);
   $myrow = $result->baseFetchRow();
   $num = $myrow[0];
   $result->baseFreeRows();

	return $num;
}

function MinDateBySensor( $sensorID, $db ){
   $query = "SELECT min(timestamp) FROM acid_event WHERE sid= '". $sensorID."'";

   $result = $db->baseExecute($query);
   $myrow = $result->baseFetchRow();
   $num = $myrow[0];
   $result->baseFreeRows();

	return $num;
}

function MaxDateBySensor( $sensorID, $db ){
   $query = "SELECT max(timestamp) FROM acid_event WHERE sid='".$sensorID."'";

   $result = $db->baseExecute($query);
   $myrow = $result->baseFetchRow();
   $num = $myrow[0];
   $result->baseFreeRows();

	return $num;
}

function UniqueDestAddrCntBySensor( $sensorID, $db ){
   $query = "SELECT COUNT(DISTINCT ip_dst) from acid_event WHERE sid='" . $sensorID . "'";

   $result = $db->baseExecute($query);
   $row = $result->baseFetchRow();
   $num = $row[0];
   $result->baseFreeRows();

	return $num;
}

function UniqueSrcAddrCntBySensor( $sensorID, $db ){
   $query = "SELECT COUNT(DISTINCT ip_src) from acid_event WHERE sid='" . $sensorID . "'";

   $result = $db->baseExecute($query);
   $row = $result->baseFetchRow();
   $num = $row[0];
   $result->baseFreeRows();

	return $num;
}

function TCPPktCnt( $db ){
   $result = $db->baseExecute("SELECT count(*) FROM acid_event WHERE ip_proto=6");
   $myrow = $result->baseFetchRow();
   $num = $myrow[0];
   $result->baseFreeRows();

	return $num;
}

function UDPPktCnt( $db ){
   $result = $db->baseExecute("SELECT count(*) FROM acid_event WHERE ip_proto=17");
   $myrow = $result->baseFetchRow();
   $num = $myrow[0];
   $result->baseFreeRows();

	return $num;
}

function ICMPPktCnt( $db ){
   $result = $db->baseExecute("SELECT count(*) FROM acid_event WHERE ip_proto=1");
   $myrow = $result->baseFetchRow();
   $num = $myrow[0];
   $result->baseFreeRows();

	return $num;
}

function PortscanPktCnt( $db ){
   $result = $db->baseExecute("SELECT count(*) FROM acid_event WHERE ip_proto=255");
   $myrow = $result->baseFetchRow();
   $num = $myrow[0];
   $result->baseFreeRows();

	return $num;
}

function UniqueSrcIPCnt( $db, $join = '', $where = '' ){
   if ( $join == "" && $where == "" )
     $result = $db->baseExecute("SELECT COUNT(DISTINCT acid_event.ip_src) FROM acid_event");
   else
     $result = $db->baseExecute("SELECT COUNT(DISTINCT acid_event.ip_src) FROM acid_event $join WHERE $where"); //.
                                //"WHERE acid_event.sid > 0 $where");

   $row = $result->baseFetchRow();
   $num = $row[0];
   $result->baseFreeRows();

	return $num;
}

function UniqueDstIPCnt( $db, $join = '', $where = '' ){
   if ( $join == "" && $where == "" )
     $result = $db->baseExecute("SELECT COUNT(DISTINCT acid_event.ip_dst) FROM acid_event");
   else
     $result = $db->baseExecute("SELECT COUNT(DISTINCT acid_event.ip_dst) FROM acid_event $join WHERE $where"); //.
                                //"WHERE acid_event.sid > 0 $where");

   $row = $result->baseFetchRow();
   $num = $row[0];
   $result->baseFreeRows();

	return $num;
}

function UniqueIPCnt( $db, $join = '', $where = '' ){
   $result = $db->baseExecute("SELECT COUNT(DISTINCT acid_event.ip_src), ".
                              "COUNT(DISTINCT acid_event.ip_dst) FROM acid_event $join $where");

   $row = $result->baseFetchRow();
   $num1 = $row[0];
   $num2 = $row[1];
   $result->baseFreeRows();

	return array($num1, $num2);
}

function StartStopTime( &$start_time, &$stop_time, $db ){
   $result = $db->baseExecute("SELECT (SELECT timestamp FROM acid_event ORDER BY timestamp ASC LIMIT 1), ".
                              "(SELECT timestamp FROM acid_event ORDER BY timestamp DESC LIMIT 1)");
   $myrow = $result->baseFetchRow();
   $start_time = $myrow[0];
   $stop_time = $myrow[1];
   $result->baseFreeRows();
}

function UniqueAlertCnt($db, $join = '', $where = '' ){
   $result = $db->baseExecute("SELECT COUNT(DISTINCT acid_event.signature) FROM acid_event $join ".
                                 "$where");    

   $row = $result->baseFetchRow();
   $num = $row[0];
   $result->baseFreeRows();

	return $num;
}

function UniquePortCnt( $db, $join = '', $where = '' ){
   if ( $join == "" && $where == "")
     $result = $db->baseExecute("SELECT COUNT(DISTINCT layer4_sport),  ".
                                "COUNT(DISTINCT layer4_dport) FROM acid_event");
   else
     $result = $db->baseExecute("SELECT COUNT(DISTINCT acid_event.layer4_sport),  ".
                                "COUNT(DISTINCT acid_event.layer4_dport) FROM acid_event $join ".
                                "$where");

   $row = $result->baseFetchRow();
   $result->baseFreeRows();

	return array($row[0], $row[1]);
}

function UniqueTCPPortCnt( $db, $join = '', $where = '' ){
   if ( $join == "" && $where == "")
     $result = $db->baseExecute("SELECT COUNT(DISTINCT acid_event.layer4_sport),  ".
                              "COUNT(DISTINCT acid_event.layer4_dport) FROM acid_event ".
                              "WHERE ip_proto='".TCP."'");
   else
     $result = $db->baseExecute("SELECT COUNT(DISTINCT acid_event.layer4_sport),  ".
                              "COUNT(DISTINCT acid_event.layer4_dport) FROM acid_event $join".
                              " $where AND ip_proto='".TCP."'");

   $row = $result->baseFetchRow();
   $result->baseFreeRows();

	return array($row[0], $row[1]);
}

function UniqueUDPPortCnt( $db, $join = '', $where = '' ){
   if ( $join == "" && $where == "")
     $result = $db->baseExecute("SELECT COUNT(DISTINCT acid_event.layer4_sport),  ".
                              "COUNT(DISTINCT acid_event.layer4_dport) FROM acid_event ".
                              "WHERE ip_proto='".UDP."'");
   else
     $result = $db->baseExecute("SELECT COUNT(DISTINCT acid_event.layer4_sport),  ".
                              "COUNT(DISTINCT acid_event.layer4_dport) FROM acid_event $join".
                              " $where AND ip_proto='".UDP."'");

   $row = $result->baseFetchRow();
   $result->baseFreeRows();

	return array($row[0], $row[1]);
}

function UniqueLinkCnt( $db, $join = '', $where = '' ){
   if (!stristr($where, "WHERE") && $where != "")
	$where = " WHERE $where ";

   if ( $db->DB_type == "mysql" )
   {
     if ( $join == "" && $where == "")
       $result = $db->baseExecute("SELECT COUNT(DISTINCT acid_event.ip_src, acid_event.ip_dst, acid_event.ip_proto) FROM acid_event");
     else
       $result = $db->baseExecute("SELECT COUNT(DISTINCT acid_event.ip_src, acid_event.ip_dst, acid_event.ip_proto) FROM acid_event $join $where");

     $row = $result->baseFetchRow();
     $result->baseFreeRows();
   }
   else
   {
     if ( $join == "" && $where == "")
       $result = $db->baseExecute("SELECT DISTINCT acid_event.ip_src, acid_event.ip_dst, acid_event.ip_proto FROM acid_event");
     else
       $result = $db->baseExecute("SELECT DISTINCT acid_event.ip_src, acid_event.ip_dst, acid_event.ip_proto FROM acid_event $join $where");
   
     $row[0] = $result->baseRecordCount();
     $result->baseFreeRows();     
   }

	return $row[0];
}

function PrintGeneralStats(
	$db, $compact, $show_stats, $join = '', $where = '',
	$show_total_events = false
){
	GLOBAL $et, $debug_mode;
	if ( $show_stats == 1 || $show_total_events ){
		$event_cnt = EventCnt($db, $join, $where);
		if ( $debug_mode > 1 && isset($et) && is_object($et) ){
			$et->Mark('Stats: Alert Count');
		}
	}
	if ( $show_stats == 1 ){
     $sensor_cnt = SensorCnt($db, $join, $where);
     $sensor_total = SensorTotal($db);
		if ( $debug_mode > 1 && isset($et) && is_object($et) ){
			$et->Mark('Stats: Sensor Data');
		}
     $unique_alert_cnt = UniqueAlertCnt($db, $join, $where);
		if ( $debug_mode > 1 && isset($et) && is_object($et) ){
			$et->Mark('Stats: Unique Alert');
		}
     $unique_ip_cnt = UniqueIPCnt($db, $join, $where);
		if ( $debug_mode > 1 && isset($et) && is_object($et) ){
			$et->Mark('Stats: Unique IP');
		}
     $unique_links_cnt = UniqueLinkCnt($db, $join, $where);
		if ( $debug_mode > 1 && isset($et) && is_object($et) ){
			$et->Mark('Stats: Unique IP Links');
		}
     $unique_port_cnt = UniquePortCnt($db, $join, $where);
		if ( $debug_mode > 1 && isset($et) && is_object($et) ){
			$et->Mark('Stats: Unique Ports ALL');
		}
     $unique_tcp_port_cnt = UniqueTCPPortCnt($db, $join, $where);
		if ( $debug_mode > 1 && isset($et) && is_object($et) ){
			$et->Mark('Stats: Unique Ports TCP');
		}
     $unique_udp_port_cnt = UniqueUDPPortCnt($db, $join, $where);
		if ( $debug_mode > 1 && isset($et) && is_object($et) ){
			$et->Mark('Stats: Unique Ports UDP');
		}
		if ( $debug_mode == 1 && isset($et) && is_object($et) ){
			$et->Mark("Stats: Unique Data");
		}
	}
	if ( $db->baseGetDBversion() >= 103 ){
		// Michael Stone 2005-03-09
		// This is an expensive calculation. Do it, only if we need it.
		if ( $show_stats == 1 ){
			$result = $db->baseExecute(
				"SELECT count(DISTINCT(sig_class_id)) FROM acid_event"
			);
			$myrow = $result->baseFetchRow();
			$class_cnt = $myrow[0];
			$result->baseFreeRows();
			if ( $debug_mode > 1 && isset($et) && is_object($et) ){
				$et->Mark("Stats: Classification Count");
			}
		}

      $class_cnt_info[0] = " <strong>"._SCCATEGORIES." </strong>";
      $class_cnt_info[1] = "<a href=\"base_stat_class.php?sort_order=class_a\">";
      $class_cnt_info[2] = "</a>";
	}

   $sensor_cnt_info[0] = "<strong>"._SCSENSORTOTAL."</strong>\n";
   $sensor_cnt_info[1] = "<a href=\"base_stat_sensor.php\">";
   $sensor_cnt_info[2] = "</a> / ";

   $unique_alert_cnt_info[0] = "<strong>"._UNIALERTS.":</strong>\n";
   $unique_alert_cnt_info[1] = "<a href=\"base_stat_alerts.php\">"; 
   $unique_alert_cnt_info[2] = "</a>";

   $event_cnt_info[0] = "<strong>"._SCTOTALNUMALERTS."</strong>\n";
   $event_cnt_info[1] = '<a href="base_qry_main.php?&amp;num_result_rows=-1'.
                        '&amp;submit='._QUERYDBP.'&amp;current_view=-1">';
   $event_cnt_info[2] = "</a>";

   $unique_src_ip_cnt_info[0] = _SCSRCIP;
   $unique_src_ip_cnt_info[1] = " ".BuildUniqueAddressLink(1);
   $unique_src_ip_cnt_info[2] = "</a>";
   $unique_dst_ip_cnt_info[0] = _SCDSTIP;
   $unique_dst_ip_cnt_info[1] = " ".BuildUniqueAddressLink(2);
   $unique_dst_ip_cnt_info[2] = "</a>";

   $unique_links_info[0] = _SCUNILINKS;
   $unique_links_info[1] = " <a href=\"base_stat_iplink.php\">";
   $unique_links_info[2] = "</a>";

   $unique_src_port_cnt_info[0] = _SCSRCPORTS; 
   $unique_src_port_cnt_info[1] = " <a href=\"base_stat_ports.php?port_type=1&amp;proto=-1\">";
   $unique_src_port_cnt_info[2] = "</a>";
   $unique_dst_port_cnt_info[0] = _SCDSTPORTS; 
   $unique_dst_port_cnt_info[1] = " <a href=\"base_stat_ports.php?port_type=2&amp;proto=-1\">";
   $unique_dst_port_cnt_info[2] = "</a>";

   $unique_tcp_src_port_cnt_info[0] = "TCP (";
   $unique_tcp_src_port_cnt_info[1] = " <a href=\"base_stat_ports.php?port_type=1&amp;proto=".TCP."\">";
   $unique_tcp_src_port_cnt_info[2] = "</a>)";
   $unique_tcp_dst_port_cnt_info[0] = "TCP (";
   $unique_tcp_dst_port_cnt_info[1] = " <a href=\"base_stat_ports.php?port_type=2&amp;proto=".TCP."\">";
   $unique_tcp_dst_port_cnt_info[2] = "</a>)";

   $unique_udp_src_port_cnt_info[0] = "UDP (";
   $unique_udp_src_port_cnt_info[1] = " <a href=\"base_stat_ports.php?port_type=1&amp;proto=".UDP."\">";
   $unique_udp_src_port_cnt_info[2] = "</a>)";
   $unique_udp_dst_port_cnt_info[0] = "UDP (";
   $unique_udp_dst_port_cnt_info[1] = " <a href=\"base_stat_ports.php?port_type=2&amp;proto=".UDP."\">";
   $unique_udp_dst_port_cnt_info[2] = "</a>)";

// Begin page output.
	if ( $show_stats == 1 ){
   echo $sensor_cnt_info[0].
        $sensor_cnt_info[1].
        $sensor_cnt.
        $sensor_cnt_info[2].
        $sensor_total;
		NLIO('<br/>');
   echo $unique_alert_cnt_info[0].
        $unique_alert_cnt_info[1].
        $unique_alert_cnt.
        $unique_alert_cnt_info[2];
		NLIO('<br/>');
		if ( $db->baseGetDBversion() >= 103 ){
			print $class_cnt_info[0] . $class_cnt_info[1] . $class_cnt
			. $class_cnt_info[2];
			NLIO('<br/>');
		}

   echo $event_cnt_info[0].
        $event_cnt_info[1].
        $event_cnt.
        $event_cnt_info[2];
		NLIO("<ul class='stats'>",2);
		NLIO('<li>');
		print $unique_src_ip_cnt_info[0] . $unique_src_ip_cnt_info[1]
			. $unique_ip_cnt[0] . $unique_src_ip_cnt_info[2];
		PrintLINext();
		print $unique_dst_ip_cnt_info[0] . $unique_dst_ip_cnt_info[1]
			. $unique_ip_cnt[1] . $unique_dst_ip_cnt_info[2];
		PrintLINext();
		print $unique_links_info[0] . $unique_links_info[1]
			. $unique_links_cnt . $unique_links_info[2];
		if ( $compact == 0 ){
			NLIO('</li></ul>');
			NLIO("<ul class='stats'><li>");
		}else{
			PrintLINext();
		}
		print $unique_src_port_cnt_info[0] . $unique_src_port_cnt_info[1]
			. $unique_port_cnt[0] . $unique_src_port_cnt_info[2];
		if ( $compact == 0 ){
			NLIO("<ul class='stats'><li>");
		}else{
			PrintLINext();
     echo "&nbsp;&nbsp;--&nbsp;&nbsp;";
		}
   echo $unique_tcp_src_port_cnt_info[0].
        $unique_tcp_src_port_cnt_info[1]. 
        $unique_tcp_port_cnt[0].
        $unique_tcp_src_port_cnt_info[2].
        "&nbsp;&nbsp;".
        $unique_udp_src_port_cnt_info[0].
        $unique_udp_src_port_cnt_info[1]. 
        $unique_udp_port_cnt[0].
        $unique_udp_src_port_cnt_info[2];
		if ( $compact == 0 ){
			NLIO('</li></ul>',4);
		}
		PrintLINext();
		print $unique_dst_port_cnt_info[0] . $unique_dst_port_cnt_info[1]
			. $unique_port_cnt[1] . $unique_dst_port_cnt_info[2];
		if ( $compact == 0 ){
			NLIO("<ul class='stats'><li>");
		}else{
			PrintLINext();
     echo "&nbsp;&nbsp;--&nbsp;&nbsp;";
		}
   echo $unique_tcp_dst_port_cnt_info[0].
        $unique_tcp_dst_port_cnt_info[1]. 
        $unique_tcp_port_cnt[1].
        $unique_tcp_dst_port_cnt_info[2].
        "&nbsp;&nbsp;".
        $unique_udp_dst_port_cnt_info[0].
        $unique_udp_dst_port_cnt_info[1]. 
        $unique_udp_port_cnt[1].
        $unique_udp_dst_port_cnt_info[2];
		if ( $compact == 0 ){
			NLIO('</li></ul>',4);
		}
		NLIO('</li></ul>');
	}else{
		if ( $show_total_events ){
			NLIO("<ul class='stats'><li>");
			print $event_cnt_info[0] . $event_cnt_info[1] . $event_cnt
				. $event_cnt_info[2];
			NLIO('</li></ul>');
		}
		NLIO("<ul class='stats'><li>");
      echo $sensor_cnt_info[1]._SCSENSORS.'</a>';
		PrintLINext();
		print $unique_alert_cnt_info[1] . _UNIALERTS
			. $unique_alert_cnt_info[2];
		PrintLINext();
		if ( $db->baseGetDBversion() >= 103 ){
			print '&nbsp;&nbsp;&nbsp;( ' . $class_cnt_info[1]
				. _SCCLASS . $class_cnt_info[2] . ' )';
		}
		PrintLINext();
		print _SCUNIADDRESS
		. $unique_src_ip_cnt_info[1] . _SCSOURCE . ' | '
		. $unique_src_ip_cnt_info[2] . $unique_dst_ip_cnt_info[1] . _SCDEST
		. $unique_dst_ip_cnt_info[2];
		PrintLINext();
		print $unique_links_info[1] . $unique_links_info[0]
		. $unique_links_info[2];
		PrintLINext();
		print $unique_src_port_cnt_info[1] . _SCSOURCE . " "
		. $unique_src_port_cnt_info[2] . _SCPORT . ": "
		. $unique_tcp_src_port_cnt_info[1]." TCP</a> | "
		. $unique_udp_src_port_cnt_info[1]." UDP</a>";
		PrintLINext();
		print $unique_dst_port_cnt_info[1] . _SCDEST . " "
		. $unique_dst_port_cnt_info[2] . _SCPORT . ": "
		. $unique_tcp_dst_port_cnt_info[1] . " TCP</a> | "
		. $unique_udp_dst_port_cnt_info[1] . " UDP</a>";
		NLIO('</li></ul>');
	}
	if ( isset($et) && is_object($et) ){
		$et->Mark("Stats: Output");
	}
}
?>
