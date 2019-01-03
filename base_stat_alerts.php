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
** Joel Esler <joelesler@users.sourceforge.net>
********************************************************************************
*/

  include_once ("base_conf.php");
  include ("$BASE_path/includes/base_constants.inc.php");
  include ("$BASE_path/includes/base_include.inc.php");
  include_once ("$BASE_path/base_db_common.php");
  include_once ("$BASE_path/base_qry_common.php");
  include_once ("$BASE_path/base_stat_common.php");

  ($debug_time_mode >= 1) ? $et = new EventTiming($debug_time_mode) : '';
  $cs = new CriteriaState("base_stat_alerts.php");
  $submit = ImportHTTPVar("submit", VAR_ALPHA | VAR_SPACE, array(_SELECTED, _ALLONSCREEN, _ENTIREQUERY));

  $cs->ReadState();

   // Check role out and redirect if needed -- Kevin
  $roleneeded = 10000;
  $BUser = new BaseUser();
  if (($BUser->hasRole($roleneeded) == 0) && ($Use_Auth_System == 1))
    base_header("Location: ". $BASE_urlpath . "/index.php");

  $qs = new QueryState();
  $qs->AddCannedQuery("most_frequent", $freq_num_alerts, _MOSTFREQALERTS, "occur_d"); 
  $qs->AddCannedQuery("last_alerts", $last_num_ualerts, _LASTALERTS, "last_d");

  $qs->MoveView($submit);             /* increment the view if necessary */

  $page_title = _ALERTTITLE;
  if ( $qs->isCannedQuery() )
     PrintBASESubHeader($page_title.": ".$qs->GetCurrentCannedQueryDesc(),
                        $page_title.": ".$qs->GetCurrentCannedQueryDesc(), $cs->GetBackLink(), 1);
  else
     PrintBASESubHeader($page_title, $page_title, $cs->GetBackLink(), 1);
  
  /* Connect to the Alert database */
  $db = NewBASEDBConnection($DBlib_path, $DBtype);
  $db->baseDBConnect($db_connect_method,
                     $alert_dbname, $alert_host, $alert_port, $alert_user, $alert_password);

  if ( $event_cache_auto_update == 1 )  UpdateAlertCache($db);

  $criteria_clauses = ProcessCriteria();

     	/**
	* RFE by Joel. Wanted the Summary Statistics box on the base_stat_alerts page
	*/
     	echo '<TABLE WIDTH="100%">
           <TR>
             <TD WIDTH="60%" VALIGN="top">';

        PrintCriteria("");

     	echo '</TD>
           <TD WIDTH="40%" VALIGN="top">';
      
	PrintFramedBoxHeader(_QSCSUMM, "#669999", "#FFFFFF");
	PrintGeneralStats($db, 1, $show_summary_stats, 
                       "$join_sql ", "$where_sql $criteria_sql"); 
	echo('<BR><LI><A HREF="base_stat_time.php">'._QSCTIMEPROF.'</A> '._QSCOFALERTS . "</LI>");
	PrintFramedBoxFooter();

	echo ' </TD>
           </TR>
	</table>';


  $from = " FROM acid_event ".$criteria_clauses[0];
  $where = ($criteria_clauses[1] != "") ? " WHERE ".$criteria_clauses[1] : " ";

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
  ($debug_time_mode >= 1) ? $et->Mark("Initialization") : '';

  $qs->RunAction($submit, PAGE_STAT_ALERTS, $db);
  ($debug_time_mode >= 1) ? $et->Mark("Alert Action") : '';

  /* Get total number of events */
  /* mstone 20050309 this is expensive -- don't do it if we're avoiding count() */
  if ($avoid_counts != 1) {
  	$event_cnt = EventCnt($db);
  }

  /* create SQL to get Unique Alerts */
  $cnt_sql = "SELECT count(DISTINCT signature) ".$from.$where;

  /* Run the query to determine the number of rows (No LIMIT)*/
  $qs->GetNumResultRows($cnt_sql, $db);
  ($debug_time_mode >= 1) ? $et->Mark("Counting Result size") : '';

  /* Setup the Query Results Table */
  $qro = new QueryResultsOutput("base_stat_alerts.php?caller=".$caller);

  $qro->AddTitle(" ");

  $qro->AddTitle(_SIGNATURE, 
                "sig_a", " ", " ORDER BY sig_name ASC",
                "sig_d", " ", " ORDER BY sig_name DESC");

  if ( $db->baseGetDBversion() >= 103 )
    $qro->AddTitle(_CHRTCLASS,
                   "class_a", ", MIN(sig_class_id) ",
                              " ORDER BY sig_class_id ASC ",
                   "class_d", ", MIN(sig_class_id) ",
                              " ORDER BY sig_class_id DESC "); 

  $qro->AddTitle(_TOTAL."&nbsp;#", 
                "occur_a", " ",
                           " ORDER BY sig_cnt ASC",
                "occur_d", " ",
                           " ORDER BY sig_cnt DESC");
  $qro->AddTitle(_SENSOR."&nbsp;#");
  $qro->AddTitle(_NBSOURCEADDR, 
                "saddr_a", ", count(DISTINCT ip_src) AS saddr_cnt ",
                           " ORDER BY saddr_cnt ASC",
                "saddr_d", ", count(DISTINCT ip_src) AS saddr_cnt ",
                           " ORDER BY saddr_cnt DESC");
  $qro->AddTitle(_NBDESTADDR, 
                "daddr_a", ", count(DISTINCT ip_dst) AS daddr_cnt ",
                           " ORDER BY daddr_cnt ASC",
                "daddr_d", ", count(DISTINCT ip_dst) AS daddr_cnt ",
                           " ORDER BY daddr_cnt DESC");
  $qro->AddTitle(_FIRST, 
                "first_a", ", min(timestamp) AS first_timestamp ",
                           " ORDER BY first_timestamp ASC",
                "first_d", ", min(timestamp) AS first_timestamp ",
                           " ORDER BY first_timestamp DESC");

  if ( $show_previous_alert == 1 )
     $qro->AddTitle("Previous");

  $qro->AddTitle(_LAST, 
                "last_a", ", max(timestamp) AS last_timestamp ",
                           " ORDER BY last_timestamp ASC",
                "last_d", ", max(timestamp) AS last_timestamp ",
                           " ORDER BY last_timestamp DESC");

  $sort_sql = $qro->GetSortSQL($qs->GetCurrentSort(), $qs->GetCurrentCannedQuerySort());

  /* mstone 20050309 add sig_name to GROUP BY & query so it can be used in postgres ORDER BY */
  /* mstone 20050405 add sid & ip counts */
  $sql = "SELECT DISTINCT signature, count(signature) as sig_cnt, ".
         "min(timestamp), max(timestamp), sig_name, count(DISTINCT(sid)), count(DISTINCT(ip_src)), count(DISTINCT(ip_dst)), sig_class_id ".
         $sort_sql[0].$from.$where." GROUP BY signature, sig_name, sig_class_id ".$sort_sql[1];

  /* Run the Query again for the actual data (with the LIMIT) */
  $result = $qs->ExecuteOutputQuery($sql, $db);
  ($debug_time_mode >= 1) ? $et->Mark("Retrieve Query Data") : '';

  if ( $debug_mode == 1 )
  {
     $qs->PrintCannedQueryList();
     $qs->DumpState();
     echo "$sql<BR>";
  }

  /* Print the current view number and # of rows */
  $qs->PrintResultCnt();

  echo '<FORM METHOD="post" NAME="PacketForm" ACTION="base_stat_alerts.php">';
  
  $qro->PrintHeader();

  $i = 0;
  // The below is due to changes in the queries...
  // We need to verify that it works all the time -- Kevin
  $and = (strpos($where, "WHERE") != 0 ) ? " AND " : " WHERE ";
  while ( ($myrow = $result->baseFetchRow()) && ($i < $qs->GetDisplayRowCnt()) )
  {
     $sig_id = $myrow[0];

     /* get Total Occurrence */
     $total_occurances = $myrow[1];

     /* Get other data */
     $sig_name = $myrow[4];
     $num_sensors = $myrow[5];
     $num_src_ip = $myrow[6];
     $num_dst_ip = $myrow[7];

     /* First and Last timestamp of this signature */
     $start_time = $myrow[2];
     $stop_time = $myrow[3];

     /* mstone 20050406 only do this if we're going to provide links to the first/last or if we're going to show the previous event time */
     if ($show_first_last_links == 1 || $show_previous_alert == 1) {
       $temp = "SELECT timestamp, acid_event.sid, acid_event.cid ".$from.$where.$and.
               "signature='".$sig_id."'
               ORDER BY timestamp DESC";
       $result2 = $db->baseExecute($temp, 0, 2);
       $last = $result2->baseFetchRow();
       $last_num = $total_occurances - 1;

       /* Getting the previous timestamp of this signature 
        * (I.E. The occurances before Last Timestamp) 
        */
       if ( $show_previous_alert == 1 )
       {
          if ( $total_occurances == 1 )
          {
             $prev = $last;
             $prev_time = $prev[0];
             $prev_num = 0;
          }
          else
          {
                  $prev = $result2->baseFetchRow();
                  $prev_time = $prev[0];
	  	  $prev_num = $total_occurances - 2;
                  $result2->baseFreeRows();
          }
       }
     }

     if ($show_first_last_links == 1) {
       /* Doing the same as above for the first entry that we are searching for.
        * The reason for doing this is because some older DB's such as ones using ODBC
        * probably don't support the move() function. Therefore, for the older DB's 
        * to get the first entry from the $temp variable above, we would need to 
        * continue to call MoveNext() for each and every entry for that signature. For 
        * signatures with a large amount of alerts(i.e. >1000), this could cause a severe
        * performance hit for those users.
        */ 
       $temp = "SELECT timestamp, acid_event.sid, acid_event.cid ".$from.$where.$and.
               "signature='".$sig_id."'
               ORDER BY timestamp ASC";
       $result2 = $db->baseExecute($temp, 0, 1);
       $first = $result2->baseFetchRow();
       $first_num = 0;
       $result2->baseFreeRows();
     }

     /* Print out (Colored Version) -- Alejandro */ 
      qroPrintEntryHeader( (($colored_alerts == 1) ?
                GetSignaturePriority($sig_id, $db) : $i),
                $colored_alerts);


     $tmp_rowid = rawurlencode($sig_id);
     echo '  <TD>&nbsp;&nbsp;
                 <INPUT TYPE="checkbox" NAME="action_chk_lst['.$i.']" VALUE="'.$tmp_rowid.'">
                 &nbsp;&nbsp;
             </TD>';
     echo '      <INPUT TYPE="hidden" NAME="action_lst['.$i.']" VALUE="'.$tmp_rowid.'">';

     qroPrintEntry(BuildSigByID($sig_id, $db));

     if ( $db->baseGetDBversion() >= 103 )
        qroPrintEntry(GetSigClassName(GetSigClassID($sig_id, $db), $db));

     qroPrintEntry('<FONT>'.
                   '<A HREF="base_qry_main.php?new=1amp;&amp;sig%5B0%5D=%3D&amp;sig%5B1%5D='.
                   (rawurlencode($sig_id)).'&amp;sig_type=1'.
                   '&amp;submit='._QUERYDBP.'&amp;num_result_rows=-1">'.$total_occurances.'</A>'.
		   /* mstone 20050309 lose this if we're not showing stats */
                   (($avoid_counts != 1)?('('.(round($total_occurances/$event_cnt*100)).'%)'):('')).
                   '</FONT>');

     qroPrintEntry('<A HREF="base_stat_sensor.php?sig%5B0%5D=%3D&amp;sig%5B1%5D='.
                    rawurlencode($sig_id).'&amp;sig_type=1">'.$num_sensors.'</A>');

     if ( $db->baseGetDBversion() >= 100 )
        $addr_link = '&amp;sig_type=1&amp;sig%5B0%5D=%3D&amp;sig%5B1%5D='.rawurlencode($sig_id);
     else
        $addr_link = '&amp;sig%5B0%5D=%3D&amp;sig%5B1%5D='.rawurlencode($sig_id);

     qroPrintEntry('<FONT>'.BuildUniqueAddressLink(1, $addr_link).$num_src_ip.'</A></FONT>');
     qroPrintEntry('<FONT>'.BuildUniqueAddressLink(2, $addr_link).$num_dst_ip.'</A></FONT>');

     if ( $show_first_last_links == 1 ) {
       qroPrintEntry('<FONT>'.
	             '<A HREF="base_qry_alert.php?'.
                     'submit=%23'.$first_num.'-%28'.$first[1].'-'.$first[2].'%29">'.
	             $start_time.'</FONT>');

       if ( $show_previous_alert == 1 )
          qroPrintEntry('<FONT>'.
                        '<A HREF="base_qry_alert.php?'.
                        'submit=%23'.$prev_num.'-%28'.$prev[1].'-'.$prev[2].'%29">'.
		        $prev_time.'</FONT>');

       qroPrintEntry('<FONT>'.
                     '<A HREF="base_qry_alert.php?'.
                     'submit=%23'.$last_num.'-%28'.$last[1].'-'.$last[2].'%29">'.
                     $stop_time.'</FONT>');
     } else {
       qroPrintEntry('<FONT>'.$start_time.'</FONT>');
       if ( $show_previous_alert == 1 )
         qroPrintEntry('<FONT>'.$prev_time.'</FONT>');
       qroPrintEntry('<FONT>'.$stop_time.'</FONT>');
     }

     qroPrintEntryFooter();

     $i++;
     $prev_time = null;
  }

  $result->baseFreeRows();

  $qro->PrintFooter();

  $qs->PrintBrowseButtons();
  $qs->PrintAlertActionButtons();
  $qs->SaveState();
  echo "\n</FORM>\n";
  
  PrintBASESubFooter();

  if ($debug_time_mode >= 1) {
	$et->Mark("Get Query Elements");
	$et->PrintTiming();
  }

  echo "</body>\r\n</html>";
?>
