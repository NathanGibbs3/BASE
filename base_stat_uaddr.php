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
**          destination IP addresses   
**
** Input GET/POST variables
**   - addr_type: sets the type of address on which stats will
**                be generated
**          = 1: source address
**          = 2: destination address
**   - caller: specifies the canned snapshot to run
**          = most_frequent: Most frequent IP address
**   - submit:
**   - limit_start:
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

 $addr_type = ImportHTTPVar("addr_type", VAR_DIGIT);
 $submit = ImportHTTPVar("submit", VAR_ALPHA | VAR_SPACE, array(_SELECTED, _ALLONSCREEN, _ENTIREQUERY));
 $sort_order=ImportHTTPVar("sort_order", VAR_LETTER | VAR_USCORE);
 $action = ImportHTTPVar("action", VAR_ALPHA);
 $dst_ip = NULL;

   // Check role out and redirect if needed -- Kevin
  $roleneeded = 10000;
  $BUser = new BaseUser();
  if (($BUser->hasRole($roleneeded) == 0) && ($Use_Auth_System == 1))
    base_header("Location: ". $BASE_urlpath . "/index.php");

 $et = new EventTiming($debug_time_mode);
   // The below three lines were moved from line 87 because of the odd errors some users were having
   /* Connect to the Alert database */
  $db = NewBASEDBConnection($DBlib_path, $DBtype);
  $db->baseDBConnect($db_connect_method,
                     $alert_dbname, $alert_host, $alert_port, $alert_user, $alert_password);

$cs = new CriteriaState("base_stat_uaddr.php", "&amp;addr_type=$addr_type");

 $cs->ReadState();

/* Dump some debugging information on the shared state */
if ( $debug_mode > 0 )
{
   PrintCriteriaState();
}

 $qs = new QueryState();
 $qs->AddCannedQuery("most_frequent", $freq_num_uaddr, _MOSTFREQADDRS, "occur_d"); 

 $qs->MoveView($submit);             /* increment the view if necessary */

  if ( $addr_type == SOURCE_IP ) 
  {
    $page_title = _UNISADD;
    $results_title = _SUASRCIP;
    $addr_type_name = "ip_src";
  }
  else
  {
    if ( $addr_type != DEST_IP )
      ErrorMessage(_SUAERRCRITADDUNK);
    $page_title = _UNIDADD;
    $results_title = _SUADSTIP;
    $addr_type_name = "ip_dst";
  }

  if ( $qs->isCannedQuery() )
	{
     PrintBASESubHeader($page_title.": ".$qs->GetCurrentCannedQueryDesc(),
                        $page_title.": ".$qs->GetCurrentCannedQueryDesc(), 
                        $cs->GetBackLink(), 1);
	}
  else
	{
		if ($action != "")
		{
			PrintBASESubHeader($page_title, $page_title, $cs->GetBackLink(), $refresh_all_pages);
		}
		else
		{
    	PrintBASESubHeader($page_title, $page_title, $cs->GetBackLink(), 1);
		}
	}
  
  if ( $event_cache_auto_update == 1 )  UpdateAlertCache($db);

  $criteria_clauses = ProcessCriteria();
  PrintCriteria("");

  $criteria = $criteria_clauses[0]." ".$criteria_clauses[1];
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
  
  $qs->RunAction($submit, PAGE_STAT_UADDR, $db);
  $et->Mark("Alert Action");

  /* Run the query to determine the number of rows (No LIMIT)*/
  $cnt_sql = "SELECT count(DISTINCT $addr_type_name) ". $from. $where;
  $qs->GetNumResultRows($cnt_sql, $db);
  $et->Mark("Counting Result size");

  /* Setup the Query Results Table */
  $qro = new QueryResultsOutput("base_stat_uaddr.php?caller=".$caller."&amp;addr_type=".$addr_type);

  $qro->AddTitle(" "); 
  $qro->AddTitle($results_title, 
                "addr_a", " ",
                         " ORDER BY $addr_type_name ASC",
                "addr_d", " ",
                         " ORDER BY $addr_type_name DESC");

  if ( $resolve_IP == 1 )
    $qro->AddTitle("FQDN"); 

  $qro->AddTitle(_SENSOR."&nbsp;#");
  $qro->AddTitle(_TOTAL."&nbsp;#", 
                "occur_a", " ",
                           " ORDER BY num_events ASC",
                "occur_d", " ",
                           " ORDER BY num_events DESC");

  $qro->AddTitle(_SUAUNIALERTS, 
                "sig_a", " ",
                           " ORDER BY num_sig ASC",
                "sig_d", " ",
                           " ORDER BY num_sig DESC");

  if ( $addr_type == DEST_IP )
  {
    $qro->AddTitle(_SUASRCADD, 
                   "saddr_a", " ",
                           " ORDER BY num_sip ASC",
                   "saddr_d", " ",
                           " ORDER BY num_sip DESC");
  }
  else
  {
    $qro->AddTitle(_SUADSTADD, 
                  "daddr_a", "  ",
                           " ORDER BY num_dip ASC",
                  "daddr_d", " ",
                           " ORDER BY num_dip DESC");
  }

  $sort_sql = $qro->GetSortSQL($qs->GetCurrentSort(), $qs->GetCurrentCannedQuerySort());

  $sql = "SELECT DISTINCT $addr_type_name, ".
         " COUNT(acid_event.cid) as num_events,".
         " COUNT( DISTINCT acid_event.sid) as num_sensors, ".
         " COUNT( DISTINCT signature ) as num_sig, ";

  if ( $addr_type == DEST_IP )
     $sql = $sql." COUNT( DISTINCT ip_src ) as num_sip ";
  else
     $sql = $sql." COUNT( DISTINCT ip_dst ) as num_dip ";

  $sql = $sql. $sort_sql[0]. $from. $where.
         " GROUP BY $addr_type_name ".$sort_sql[1];

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

  echo '<FORM METHOD="post" NAME="PacketForm" ACTION="base_stat_uaddr.php">';
  
  $qro->PrintHeader();
  
   $i = 0;
   while ( ($myrow = $result->baseFetchRow()) && ($i < $qs->GetDisplayRowCnt()) )
   {
      $currentIP = baseLong2IP($myrow[0]);
      $num_events = $myrow[1];
      $num_sensors = $myrow[2];
      $num_sig = $myrow[3];
      $num_ip = $myrow[4];

      if ( $myrow[0] == NULL ) $no_ip = true; else $no_ip = false;

     qroPrintEntryHeader($i);

     /* Generating checkbox value -- nikns */
     ($addr_type == SOURCE_IP) ? ($src_ip = $myrow[0]) : ($dst_ip = $myrow[0]);
     $tmp_rowid = $src_ip . "_" . $dst_ip;

     echo '    <TD><INPUT TYPE="checkbox" NAME="action_chk_lst['.$i.']" VALUE="'.$tmp_rowid.'">';
     echo '    <INPUT TYPE="hidden" NAME="action_lst['.$i.']" VALUE="'.$tmp_rowid.'"></TD>';

     /* Check for a NULL IP which indicates an event (e.g. portscan)
      * which has no IP
      */
     if ( $no_ip )
        qroPrintEntry('<A HREF="'.$BASE_urlpath.'/help/base_app_faq.php#1">'._UNKNOWN.'</A>');
     else
        qroPrintEntry('&nbsp;&nbsp;'.
                      BuildAddressLink($currentIP, 32).$currentIP.'</A>&nbsp;&nbsp');
    
      if ( $resolve_IP == 1 )
         qroPrintEntry('&nbsp;&nbsp;'.
                        baseGetHostByAddr($currentIP, $db, $dns_cache_lifetime).
                       '&nbsp;&nbsp;');

      /* Print # of Occurances */
      $tmp_iplookup = 'base_qry_main.php?new=1'.
                      '&amp;num_result_rows=-1'.
                      '&amp;sort_order='.$sort_order.
                      '&amp;submit='._QUERYDBP.'&amp;current_view=-1&amp;ip_addr_cnt=1';

      $tmp_iplookup2 = 'base_stat_alerts.php?new=1'.   
                       '&amp;num_result_rows=-1'.
                       '&amp;sort_order='.$sort_order.
                       '&amp;submit='._QUERYDBP.'&amp;current_view=-1&amp;ip_addr_cnt=1';

      if ( $addr_type == 1 )
      {
         if ( $no_ip )
            $url_criteria = BuildSrcIPFormVars(NULL_IP);
         else
            $url_criteria = BuildSrcIPFormVars($currentIP);
      }
      else if ( $addr_type == 2 )
      {
         if ( $no_ip )
           $url_criteria = BuildDstIpFormVars(NULL_IP);
         else 
           $url_criteria = BuildDstIPFormVars($currentIP);
      }

      qroPrintEntry($num_sensors);
      qroPrintEntry('<A HREF="'.$tmp_iplookup.$url_criteria.'">'.
                                $num_events.'</A>');
      qroPrintEntry('<A HREF="'.$tmp_iplookup2.$url_criteria.'">'.
                                $num_sig.'</A>');

      qroPrintEntry($num_ip);

      qroPrintEntryFooter();
      ++$i;
   }

  $result->baseFreeRows();     

  $qro->PrintFooter();

  $qs->PrintBrowseButtons();
  $qs->PrintAlertActionButtons();
  $qs->SaveState();
  ExportHTTPVar("addr_type", $addr_type);
	ExportHTTPVar("sort_order", $sort_order);

  echo "\n</FORM>\n";
  $et->Mark("Get Query Elements");
  $et->PrintTiming();
  
  PrintBASESubFooter();
  echo "</body>\r\n</html>";
?>
