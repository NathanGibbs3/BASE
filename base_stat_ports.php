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
** Purpose: Displays statistics on the detected source and
**          destination TCP/UDP ports   
**
** Input GET/POST variables
**   - port_type: sets the type of address on which stats will
**                be generated
**          = 1: source port
**          = 2: destination port
**   - proto: sets the protocol 
**          = 6: TCP
**          = 17: UDP
**          = -1: either
**   - caller: specifies the canned snapshot to run
**          = most_frequent: Most frequent Port
**   - submit:
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
  include_once("$BASE_path/base_qry_common.php");

  $et = new EventTiming($debug_time_mode);

  /* Connect to the Alert database */
  $db = NewBASEDBConnection($DBlib_path, $DBtype);
  $db->baseDBConnect($db_connect_method,
                     $alert_dbname, $alert_host, $alert_port, $alert_user, $alert_password);

  $cs = new CriteriaState("base_stat_ports.php");
  $cs->ReadState();

   // Check role out and redirect if needed -- Kevin
  $roleneeded = 10000;
  $port_proto = "TCP";
  $BUser = new BaseUser();
  if (($BUser->hasRole($roleneeded) == 0) && ($Use_Auth_System == 1))
    base_header("Location: ". $BASE_urlpath . "/index.php");

  $qs = new QueryState();
  $qs->AddCannedQuery("most_frequent", $freq_num_uports, _MOSTFREQPORTS, "occur_d");
  $qs->AddCannedQuery("last_ports", $last_num_uports, _LASTPORTS, "last_d");

  $submit = ImportHTTPVar("submit", VAR_ALPHA | VAR_SPACE, array(_SELECTED, _ALLONSCREEN, _ENTIREQUERY));
  $port_type = ImportHTTPVar("port_type", VAR_DIGIT);
  $proto = ImportHTTPVar("proto", VAR_DIGIT);
	$sort_order=ImportHTTPVar("sort_order", VAR_LETTER | VAR_USCORE);
  $action = ImportHTTPVar("action", VAR_ALPHA);


  $qs->MoveView($submit);             /* increment the view if necessary */

  $page_title = "";
  switch ($proto)
  {
    case TCP:
       $page_title = _UNIQ." TCP ";
       break;
    case UDP:
       $page_title = _UNIQ." UDP ";
       break;
    case -1:
       $page_title = _UNIQ." ";
       break;
  }

  switch ($port_type)
  {
    case SOURCE_PORT:
       $page_title = $page_title._SRCPS;
       break;
    case DEST_PORT:
       $page_title = $page_title._DSTPS;
       break;
  }

  if ( $qs->isCannedQuery() )
	{
		if ($action == "")
		{
     	PrintBASESubHeader($page_title.": ".$qs->GetCurrentCannedQueryDesc(),
                         $page_title.": ".$qs->GetCurrentCannedQueryDesc(), 
                         $cs->GetBackLink(), 1);
		}
		else
		{
			PrintBASESubHeader($page_title.": ".$qs->GetCurrentCannedQueryDesc(),
                         $page_title.": ".$qs->GetCurrentCannedQueryDesc(), 
                         $cs->GetBackLink(), $refresh_all_pages);
		}
	}
  else
	{
		if ($action == "")
		{
     	PrintBASESubHeader($page_title, $page_title, $cs->GetBackLink(), 1);
		}
		else
		{
			PrintBASESubHeader($page_title, $page_title, $cs->GetBackLink(), $refresh_all_pages);
		}
	}

  if ( $event_cache_auto_update == 1 )  UpdateAlertCache($db);

  $criteria_clauses = ProcessCriteria();
  PrintCriteria("");

  $criteria = $criteria_clauses[0]." ".$criteria_clauses[1];

  $qs->AddValidAction("ag_by_id");
  $qs->AddValidAction("ag_by_name");
  $qs->AddValidAction("add_new_ag");
  $qs->AddValidAction("del_alert");
  $qs->AddValidAction("email_alert");
  $qs->AddValidAction("email_alert2");
  $qs->AddValidAction("csv_alert");
  $qs->AddValidAction("archive_alert");
  $qs->AddValidAction("archive_alert2");
 
  $qs->AddValidActionOp(_SELECTED);
  $qs->AddValidActionOp(_ALLONSCREEN);
 
  $et->Mark("Initialization");
  
  $qs->RunAction($submit, PAGE_STAT_PORTS, $db);
  $et->Mark("Alert Action");

  switch($proto)
  {
     case TCP:
       $proto_sql = " ip_proto = ".TCP;
       break;
     case UDP:
       $proto_sql = " ip_proto = ".UDP; 
       break;
     default:
       $proto_sql = " ip_proto IN (".TCP.", ".UDP.")";
       break;
  }

  if ( $criteria_clauses[1] != "" )
     $proto_sql = $proto_sql." AND ";
  
  switch($port_type)
  {
     case SOURCE_PORT:
       $port_type_sql = "layer4_sport";
       break;
     case DEST_PORT:
     default:
       $port_type_sql = "layer4_dport";
       break;
  }

  /* create SQL to get Unique Alerts */
  $cnt_sql = "SELECT count(DISTINCT $port_type_sql) ".
             " FROM acid_event ".$criteria_clauses[0].
             " WHERE $proto_sql".$criteria_clauses[1];

  /* Run the query to determine the number of rows (No LIMIT)*/
  $qs->GetNumResultRows($cnt_sql, $db);
  $et->Mark("Counting Result size");

  /* Setup the Query Results Table */
  $qro = new QueryResultsOutput("base_stat_ports.php?caller=$caller".
                                "&amp;sort_order=".$sort_order.
                                "&amp;port_type=$port_type&amp;proto=$proto");

  $qro->AddTitle(" ");
  $qro->AddTitle(_PORT, 
                "port_a", " ", " ORDER BY $port_type_sql ASC",
                "port_d", " ", " ORDER BY $port_type_sql DESC");
  $qro->AddTitle(_SENSOR, 
                "sensor_a", " ", " ORDER BY num_sensors ASC",
                "sensor_d", " ", " ORDER BY num_sensors DESC");
  $qro->AddTitle(_OCCURRENCES, 
                "occur_a", " ", " ORDER BY num_events ASC",
                "occur_d", " ", " ORDER BY num_events DESC");
  $qro->AddTitle(_UNIALERTS, 
                "alerts_a", " ", " ORDER BY num_sig ASC",
                "alerts_d", " ", " ORDER BY num_sig DESC");
  $qro->AddTitle(_SUASRCADD, 
                "sip_a", " ", " ORDER BY num_sip ASC",
                "sip_d", " ", " ORDER BY num_sip DESC");
  $qro->AddTitle(_SUADSTADD, 
                "dip_a", " ", " ORDER BY num_dip ASC",
                "dip_d", " ", " ORDER BY num_dip DESC");
  $qro->AddTitle(_FIRST, 
                "first_a", " ", " ORDER BY first_timestamp ASC",
                "first_d", " ", " ORDER BY first_timestamp DESC");
  $qro->AddTitle(_LAST, 
                "last_a", " ", " ORDER BY last_timestamp ASC",
                "last_d", " ", " ORDER BY last_timestamp DESC");


  $sort_sql = $qro->GetSortSQL($qs->GetCurrentSort(), $qs->GetCurrentCannedQuerySort());

  $sql = "SELECT DISTINCT $port_type_sql, MIN(ip_proto), ".
         " COUNT(acid_event.cid) as num_events,".
         " COUNT( DISTINCT acid_event.sid) as num_sensors, ".
         " COUNT( DISTINCT signature ) as num_sig, ".
         " COUNT( DISTINCT ip_src ) as num_sip, ".
         " COUNT( DISTINCT ip_dst ) as num_dip, ".
         " MIN(timestamp) as first_timestamp, ".
         " MAX(timestamp) as last_timestamp ".
         $sort_sql[0].
         " FROM acid_event ".$criteria_clauses[0]." WHERE ".$proto_sql.$criteria_clauses[1].
         " GROUP BY ".$port_type_sql." ".$sort_sql[1];

  /* Run the Query again for the actual data (with the LIMIT) */
  $result = $qs->ExecuteOutputQuery($sql, $db);
  $et->Mark("Retrieve Query Data");

  if ( $debug_mode == 1 )
  {
     $qs->PrintCannedQueryList();
     $qs->DumpState();
     echo "$sql<BR>";
     echo '<HR><TABLE BORDER=1>
             <TR><TD>port_type</TD>
                 <TD>proto</TD></TR>
             <TR><TD>'.$port_type.'</TD>
                 <TD>'.$proto.'</TD></TR>
           </TABLE>';
  }

  /* Print the current view number and # of rows */
  $qs->PrintResultCnt();

  echo '<FORM METHOD="post" NAME="PacketForm" ACTION="base_stat_ports.php">'."\n";

  $qro->PrintHeader();

  echo "<input type='hidden' name='port_type' value='$port_type'>\n"; 

   $i = 0;
   while ( ($myrow = $result->baseFetchRow()) && ($i < $qs->GetDisplayRowCnt()) )
   {
      $currentPort = $url_port = $myrow[0].' ';
      if ( $port_proto == TCP )
      {
        $currentPort = $currentPort.'/ tcp ';
      }
      if ( $port_proto == UDP )
      {
        $currentPort = $currentPort.'/ udp ';
      }
      // Go here to change the format of the Port lookup stuff! -- Kevin Johnson
      foreach ($external_port_link as $name => $baseurl) {
            $currentPort = $currentPort.'[<A HREF="'.$baseurl.$myrow[0].'" TARGET="_ACID_PORT_">'.$name.'</A>] ';
      }
      $port_proto = $myrow[1];
      $num_events = $myrow[2];
      $num_sensors = $myrow[3];
      $num_sig = $myrow[4];
      $num_sip = $myrow[5];
      $num_dip = $myrow[6];
      $first_time = $myrow[7];
      $last_time = $myrow[8];

      if ( $port_proto == TCP )
      { 
        $url_port_type = "tcp";
        $url_layer4 = "TCP";
      }
      if ( $port_proto == UDP )
      { 
        $url_port_type = "udp";
        $url_layer4 = "UDP";
      }

      $url_param = $url_port_type."_port%5B0%5D%5B0%5D=+".
                   "&amp;".$url_port_type."_port%5B0%5D%5B1%5D=".$port_type_sql.
                   "&amp;".$url_port_type."_port%5B0%5D%5B2%5D=%3D".
                   "&amp;".$url_port_type."_port%5B0%5D%5B3%5D=".$url_port.
                   "&amp;".$url_port_type."_port%5B0%5D%5B4%5D=+".
                   "&amp;".$url_port_type."_port%5B0%5D%5B5%5D=+".
                   "&amp;".$url_port_type."_port_cnt=1".
                   "&amp;layer4=".$url_layer4.
                   "&amp;sort_order=".$sort_order.
                   "&amp;num_result_rows=-1&amp;current_view=-1";

      qroPrintEntryHeader($i);

      /* Generating checkbox value -- nikns */
      if ($proto == TCP)
            $tmp_rowid = TCP ."_";
      else if ($proto == UDP)
            $tmp_rowid = UDP ."_";
      else
            $tmp_rowid = -1 ."_";
      
      ($port_type == SOURCE_PORT) ? ($tmp_rowid .= SOURCE_PORT) : ($tmp_rowid .= DEST_PORT);
      $tmp_rowid .= "_" . $myrow[0];

      echo '    <TD><INPUT TYPE="checkbox" NAME="action_chk_lst['.$i.']" VALUE="'.$tmp_rowid.'"></TD>';
      echo '        <INPUT TYPE="hidden" NAME="action_lst['.$i.']" VALUE="'.$tmp_rowid.'">';

      qroPrintEntry($currentPort);
      qroPrintEntry('<A HREF="base_stat_sensor.php?'.$url_param.'">'.$num_sensors.'</A>');
      qroPrintEntry('<A HREF="base_qry_main.php?'.$url_param.'&amp;new=1&amp;submit='._QUERYDBP.
                    '&amp;sort_order=sig_a">'.$num_events.'</A>');
      qroPrintEntry('<A HREF="base_stat_alerts.php?'.$url_param.'&amp;sort_order=sig_a">'.
                    $num_sig.'</A>');
      qroPrintEntry('<A HREF="base_stat_uaddr.php?'.$url_param.'&amp;addr_type=1'.
                    '&amp;sort_order=addr_a">'.$num_sip);
      qroPrintEntry('<A HREF="base_stat_uaddr.php?'.$url_param.'&amp;addr_type=2'.
                    '&amp;sort_order=addr_a">'.$num_dip);
      qroPrintEntry($first_time);
      qroPrintEntry($last_time);

      qroPrintEntryFooter();
      ++$i;
   }

  $result->baseFreeRows();     

  $qro->PrintFooter();

  $qs->PrintBrowseButtons();
  $qs->PrintAlertActionButtons();
  $qs->SaveState();
  ExportHTTPVar("port_type", $port_type);
  ExportHTTPVar("proto", $proto);
	ExportHTTPVar("sort_order", $sort_order);	

  echo "\n</FORM>\n";
  
  PrintBASESubFooter();

  $et->Mark("Get Query Elements");
  $et->PrintTiming();
  echo "</body>\r\n</html>";
?>
