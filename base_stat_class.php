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
** Purpose: Displays statistics on the detected alerts   
**
** Input GET/POST variables
**   - caller
**   - submit:
********************************************************************************
** Authors:
********************************************************************************
** Kevin Johnson <kjohnson@secureideas.net
**
********************************************************************************
*/

  include ("base_conf.php");
  include ("$BASE_path/includes/base_constants.inc.php");
  include ("$BASE_path/includes/base_include.inc.php");
  include_once ("$BASE_path/base_db_common.php");
  include_once ("$BASE_path/base_qry_common.php");
  include_once ("$BASE_path/base_stat_common.php");

  $et = new EventTiming($debug_time_mode);
  $cs = new CriteriaState("base_stat_class.php");
  $cs->ReadState();

  $qs = new QueryState();

   // Check role out and redirect if needed -- Kevin
  $roleneeded = 10000;
  $BUser = new BaseUser();
  if (($BUser->hasRole($roleneeded) == 0) && ($Use_Auth_System == 1))
    base_header("Location: ". $BASE_urlpath . "/index.php");

  $submit = ImportHTTPVar("submit", VAR_ALPHA | VAR_SPACE, array(_SELECTED, _ALLONSCREEN, _ENTIREQUERY));
  $sort_order=ImportHTTPVar("sort_order", VAR_LETTER | VAR_USCORE);
  $action = ImportHTTPVar("action", VAR_ALPHA); 
  $qs->MoveView($submit);             /* increment the view if necessary */

  $page_title = _CHRTCLASS;
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
  
  /* Connect to the Alert database */
  $db = NewBASEDBConnection($DBlib_path, $DBtype);
  $db->baseDBConnect($db_connect_method,
                     $alert_dbname, $alert_host, $alert_port, $alert_user, $alert_password);

  if ( $event_cache_auto_update == 1 )  UpdateAlertCache($db);

  $criteria_clauses = ProcessCriteria();  
  PrintCriteria("");

  $from = " FROM acid_event ".$criteria_clauses[0];
  $where = " WHERE ".$criteria_clauses[1];

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

  $qs->SetActionSQL($from.$where);
  $et->Mark("Initialization");

  $qs->RunAction($submit, PAGE_STAT_CLASS, $db);
  $et->Mark("Alert Action");

  /* Get total number of events */
  $event_cnt = EventCnt($db);

  /* create SQL to get Unique Alerts */
  $cnt_sql = "SELECT count(DISTINCT sig_class_id) ".$from.$where;

  /* Run the query to determine the number of rows (No LIMIT)*/
  $qs->GetNumResultRows($cnt_sql, $db);
  $et->Mark("Counting Result size");

  /* Setup the Query Results Table */
  $qro = new QueryResultsOutput("base_stat_class.php?caller=".$caller);

  $qro->AddTitle(" ");
  $qro->AddTitle(_CHRTCLASS, 
                "class_a", " ",
                         " ORDER BY sig_class_id ASC",
                "class_d", " ",
                         " ORDER BY sig_class_id DESC");

  $qro->AddTitle(_TOTAL."&nbsp;#", 
                "occur_a", " ",
                           " ORDER BY num_events ASC",
                "occur_d", " ",
                           " ORDER BY num_events DESC");
  $qro->AddTitle(_SENSOR."&nbsp;#",
                 "sensor_a", " ",
                             " ORDER BY num_sensors ASC",
                 "sensor_d", " ",
                             " ORDER BY num_sensors DESC");
  $qro->AddTitle(_SIGNATURE,
                 "sig_a", " ",
                          " ORDER BY num_sig ASC",
                 "sig_d", " ",
                          " ORDER BY num_sig DESC");
  $qro->AddTitle(_NBSOURCEADDR, 
                "saddr_a", ", count(ip_src) AS saddr_cnt ",
                           " ORDER BY saddr_cnt ASC",
                "saddr_d", ", count(ip_src) AS saddr_cnt ",
                           " ORDER BY saddr_cnt DESC");
  $qro->AddTitle(_NBDESTADDR, 
                "daddr_a", ", count(ip_dst) AS daddr_cnt ",
                           " ORDER BY daddr_cnt ASC",
                "daddr_d", ", count(ip_dst) AS daddr_cnt ",
                           " ORDER BY daddr_cnt DESC");
  $qro->AddTitle(_FIRST, 
                "first_a", ", min(timestamp) AS first_timestamp ",
                           " ORDER BY first_timestamp ASC",
                "first_d", ", min(timestamp) AS first_timestamp ",
                           " ORDER BY first_timestamp DESC");

  $qro->AddTitle(_LAST, 
                "last_a", ", max(timestamp) AS last_timestamp ",
                           " ORDER BY last_timestamp ASC",
                "last_d", ", max(timestamp) AS last_timestamp ",
                           " ORDER BY last_timestamp DESC");

  $sort_sql = $qro->GetSortSQL($qs->GetCurrentSort(), $qs->GetCurrentCannedQuerySort());

  $sql = "SELECT DISTINCT sig_class_id, ".
         " COUNT(acid_event.cid) as num_events,".
         " COUNT( DISTINCT acid_event.sid) as num_sensors, ".
         " COUNT( DISTINCT signature ) as num_sig, ".
         " COUNT( DISTINCT ip_src ) as num_sip, ".
         " COUNT( DISTINCT ip_dst ) as num_dip, ".
         " min(timestamp) as first_timestamp, ".
         " max(timestamp) as last_timestamp ".
         $sort_sql[0].$from.$where." GROUP BY sig_class_id ".$sort_sql[1];

  /* Run the Query again for the actual data (with the LIMIT) */
  $result = $qs->ExecuteOutputQuery($sql, $db);
  $et->Mark("Retrieve Query Data");

  if ( $debug_mode == 1 )
  {
     $qs->PrintCannedQueryList();
     $qs->DumpState();
     echo "$sql<BR>";
  }

  /* Print the current view number and # of rows */
  $qs->PrintResultCnt();

  echo '<FORM METHOD="post" NAME="PacketForm" ACTION="base_stat_class.php">';
  
  $qro->PrintHeader();

  $i = 0;
  while ( ($myrow = $result->baseFetchRow()) && ($i < $qs->GetDisplayRowCnt()) )
  {
     $class_id = $myrow[0];
     if ( $class_id == "" )
        $class_id = 0;
     $total_occurances = $myrow[1];
     $sensor_num = $myrow[2];
     $sig_num = $myrow[3];
     $sip_num = $myrow[4];
     $dip_num = $myrow[5];
     $min_time = $myrow[6];
     $max_time = $myrow[7];

     /* Print out */ 
     qroPrintEntryHeader($i);

     $tmp_rowid = rawurlencode($class_id);
     echo '  <TD>&nbsp;&nbsp;
                 <INPUT TYPE="checkbox" NAME="action_chk_lst['.$i.']" VALUE="'.$tmp_rowid.'">
                 &nbsp;&nbsp;
             </TD>';
     echo '      <INPUT TYPE="hidden" NAME="action_lst['.$i.']" VALUE="'.$tmp_rowid.'">';

     qroPrintEntry(GetSigClassName($class_id, $db));

     qroPrintEntry('<FONT>'.
                   '<A HREF="base_qry_main.php?new=1&amp;sig_class='.$class_id.
                   '&amp;submit='._QUERYDBP.'&amp;num_result_rows=-1">'.$total_occurances.'</A> 
                   ('.(round($total_occurances/$event_cnt*100)).'%)'.
                   '</FONT>');
     qroPrintEntry('<FONT><A HREF="base_stat_sensor.php?sig_class='.$class_id.'">'.
                    $sensor_num.'</A>');
     qroPrintEntry('<FONT><A HREF="base_stat_alerts.php?sig_class='.$class_id.'">'.
                    $sig_num.'</FONT>');

     qroPrintEntry('<FONT>'.BuildUniqueAddressLink(1, '&amp;sig_class='.$class_id).$sip_num.'</A></FONT>');
     qroPrintEntry('<FONT>'.BuildUniqueAddressLink(2, '&amp;sig_class='.$class_id).$dip_num.'</A></FONT>');

     qroPrintEntry('<FONT>'.$min_time.'</FONT>');
     qroPrintEntry('<FONT>'.$max_time.'</FONT>');

     qroPrintEntryFooter();

     $i++;
     $prev_time = null;
  }

  $result->baseFreeRows();

  $qro->PrintFooter();

  $qs->PrintBrowseButtons();
  $qs->PrintAlertActionButtons();
  $qs->SaveState();
	ExportHTTPVar("sort_order", $sort_order);
  echo "\n</FORM>\n";
  
  PrintBASESubFooter();

  $et->Mark("Get Query Elements");
  $et->PrintTiming();
  echo "</body>\r\n</html>";
?>
