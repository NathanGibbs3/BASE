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

$sc = DIRECTORY_SEPARATOR;
require_once("includes$sc" . 'base_krnl.php');
include_once("$BASE_path/includes/base_include.inc.php");
include_once("$BASE_path/base_db_common.php");
include_once("$BASE_path/base_qry_common.php");

AuthorizedRole(10000);
$db = NewBASEDBConnection($DBlib_path, $DBtype); // Connect to Alert DB.
$db->baseDBConnect(
	$db_connect_method, $alert_dbname, $alert_host, $alert_port, $alert_user,
	$alert_password
);
UpdateAlertCache($db);
if ( class_exists('UILang') ){ // Issue 11 backport shim.
	$CPSensor = $UIL->CWA['Sensor'];
	$CPTotal = $UIL->CWA['Total'];
}else{
	$CPSensor = _SENSOR;
	$CPTotal = _TOTAL;
}
$addr_type = ImportHTTPVar('addr_type', VAR_DIGIT);
$submit = ImportHTTPVar('submit', VAR_ALPHA | VAR_SPACE, array(_SELECTED, _ALLONSCREEN, _ENTIREQUERY));
$sort_order = ImportHTTPVar('sort_order', VAR_LETTER | VAR_USCORE);
$caller = ImportHTTPVar('caller', VAR_LETTER | VAR_USCORE);
$action = ImportHTTPVar('action', VAR_ALPHA);
$cs = new CriteriaState("base_stat_uaddr.php", "&amp;addr_type=$addr_type");
$cs->ReadState();
if ( $debug_mode > 0 ){ // Dump debugging info on the shared state.
	PrintCriteriaState();
}
if ( $addr_type == SOURCE_IP ){
	$page_title = _UNISADD;
	$results_title = _SUASRCIP;
	$addr_type_name = "ip_src";
}else{ // Default to Dst.
	if ( $addr_type != DEST_IP ){
		ErrorMessage(_SUAERRCRITADDUNK);
	}
	$page_title = _UNIDADD;
	$results_title = _SUADSTIP;
	$addr_type_name = "ip_dst";
}
if ( $caller == 'most_frequent' && $sort_order = 'occur_d' ){
	// Issue(s) #123 Fix
	$sort_order = $CPTotal.'_occur_d';
}
$qs = new QueryState();
if ( $caller == 'most_frequent' ){ // Issue #123 Fix
	$qs->current_sort_order = $sort_order;
}
$qs->AddCannedQuery(
	'most_frequent', $freq_num_uaddr, _MOSTFREQADDRS, $CPTotal.'_occur_d'
);
$qs->MoveView($submit); // Increment the view if necessary.
$tr = 1; // Page Refresh
if ( $qs->isCannedQuery() ){
	$page_title.': '.$qs->GetCurrentCannedQueryDesc();
}else{
	if ($action != '' ){
		$tr = $refresh_all_pages;
	}
}
PrintBASESubHeader( $page_title, $page_title, $cs->GetBackLink(), $tr );
$criteria_clauses = ProcessCriteria();
PrintCriteria('');
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

// Setup the Query Results Table.
// Common SQL Strings
$OB = ' ORDER BY';
$qro = new QueryResultsOutput("base_stat_uaddr.php?caller=".$caller."&amp;addr_type=".$addr_type);
$qro->AddTitle('');
$qro->AddTitle( $results_title,
	"addr_a", " ", "$OB $addr_type_name ASC",
	"addr_d", " ", "$OB $addr_type_name DESC", 'right'
);
if ( $resolve_IP == 1 ){
	$qro->AddTitle('FQDN');
}
$qro->AddTitle( $CPSensor);
$qro->AddTitle( $CPTotal,
	"occur_a", " ", "$OB num_events ASC",
	"occur_d", " ", "$OB num_events DESC", 'right'
);
$qro->AddTitle( _SUAUNIALERTS,
	"sig_a", " ", "$OB num_sig ASC",
	"sig_d", " ", "$OB num_sig DESC", 'right'
);
if ( $addr_type == DEST_IP ){
	$qro->AddTitle( _SUASRCADD,
		"saddr_a", " ", "$OB num_sip ASC",
		"saddr_d", " ", "$OB num_sip DESC"
	);
}else{
	$qro->AddTitle( _SUADSTADD,
		"daddr_a", " ", "$OB num_dip ASC",
		"daddr_d", " ", "$OB num_dip DESC"
	);
}

// Issue #168
$sql = "SELECT DISTINCT $addr_type_name, ".
		" COUNT(acid_event.cid) as num_events,".
		" COUNT( DISTINCT acid_event.sid) as num_sensors, ".
		" COUNT( DISTINCT signature ) as num_sig, ";
if ( $addr_type == DEST_IP ){
	$tmp = 'src';
	$tmp2 = 's';
}else{
	$tmp = 'dst';
	$tmp2 = 'd';
}
$tmp = "ip_$tmp";
$tmp2 = "num_$tmp2".'ip ';
$sql .= " COUNT( DISTINCT $tmp ) as $tmp2 ";
$sqlPFX = $from.$where." GROUP BY $addr_type_name ";
$sort_sql = $qro->GetSortSQL($qs->GetCurrentSort(), $qs->GetCurrentCannedQuerySort());
if ( !is_null($sort_sql) ){
	$sqlPFX = $sort_sql[0].$sqlPFX.$sort_sql[1];
}
$sql .= $sqlPFX;
// Run the Query again for the actual data (with the LIMIT), if any.
$result = $qs->ExecuteOutputQuery($sql, $db);
$et->Mark("Retrieve Query Data");
if ( $debug_mode > 0 ){
	if ( $qs->isCannedQuery() ){
		$CCF = 'Yes';
		$qs->PrintCannedQueryList();
	}else{
		$CCF = 'No';
	}
	print "Canned Query: $CCF <br/>";
	$qs->DumpState();
	print "SQL Executed: $sql <br/>";
}
$qs->PrintResultCnt(); // Print current view number and # of rows.

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
	// Generating checkbox value. -- nikns
	// Fix for Issue #69 https://github.com/NathanGibbs3/BASE/issues/69
	if ( $addr_type == SOURCE_IP ){
		$src_ip = $myrow[0];
		$dst_ip = '';
	}else{
		$src_ip = '';
		$dst_ip = $myrow[0];
	}
	$tmp_rowid = $src_ip.'_'.$dst_ip;
     echo '    <TD><INPUT TYPE="checkbox" NAME="action_chk_lst['.$i.']" VALUE="'.$tmp_rowid.'">';
     echo '    <INPUT TYPE="hidden" NAME="action_lst['.$i.']" VALUE="'.$tmp_rowid.'"></TD>';
	// Check for a NULL IP indicating an event (e.g. portscan) which has no IP.
	if ( $no_ip ){
		$tmp = '<A HREF="'.$BASE_urlpath.'/help/base_app_faq.php#1">'._UNKNOWN;
	}else{
		$tmp = BuildAddressLink($currentIP, 32).$currentIP;
	}
	$tmp .= '</a>';
	qroPrintEntry($tmp,'right');
	if ( $resolve_IP == 1 ){
		qroPrintEntry(
			baseGetHostByAddr($currentIP, $db, $dns_cache_lifetime), 'right'
		);
	}
      /* Print # of Occurances */
      $tmp_iplookup = 'base_qry_main.php?new=1'.
                      '&amp;num_result_rows=-1'.
                      '&amp;sort_order='.$sort_order.
                      '&amp;submit='._QUERYDBP.'&amp;current_view=-1&amp;ip_addr_cnt=1';
      $tmp_iplookup2 = 'base_stat_alerts.php?new=1'.   
                       '&amp;num_result_rows=-1'.
                       '&amp;sort_order='.$sort_order.
                       '&amp;submit='._QUERYDBP.'&amp;current_view=-1&amp;ip_addr_cnt=1';
	if ( $addr_type == 1 ){
         if ( $no_ip )
            $url_criteria = BuildSrcIPFormVars(NULL_IP);
         else
            $url_criteria = BuildSrcIPFormVars($currentIP);
	}elseif ( $addr_type == 2 ){
         if ( $no_ip )
           $url_criteria = BuildDstIpFormVars(NULL_IP);
         else 
           $url_criteria = BuildDstIPFormVars($currentIP);
	}
	qroPrintEntry($num_sensors);
	qroPrintEntry(
		'<A HREF="'.$tmp_iplookup.$url_criteria.'">'.$num_events.'</A>','right'
	);
	qroPrintEntry(
		'<A HREF="'.$tmp_iplookup2.$url_criteria.'">'.$num_sig.'</A>','right'
	);
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
NLIO('</form>',2);
$et->Mark("Get Query Elements");
PrintBASESubFooter();
?>
