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
** Purpose: Displays statistics on communication links (IP addresses) 
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
include_once ("$BASE_path/includes/base_constants.inc.php");
include ("$BASE_path/includes/base_include.inc.php");
include_once ("$BASE_path/base_db_common.php");
include_once ("$BASE_path/base_qry_common.php");
include_once ("$BASE_path/base_stat_common.php");

AuthorizedRole(10000);
$et = new EventTiming($debug_time_mode);
$db = NewBASEDBConnection($DBlib_path, $DBtype); // Connect to DB.
$db->baseDBConnect(
	$db_connect_method, $alert_dbname, $alert_host, $alert_port, $alert_user,
	$alert_password
);
UpdateAlertCache($db);
$submit = ImportHTTPVar("submit", VAR_ALPHA | VAR_SPACE, array(_SELECTED, _ALLONSCREEN, _ENTIREQUERY));
$sort_order=ImportHTTPVar("sort_order", VAR_LETTER | VAR_USCORE);
$action = ImportHTTPVar("action", VAR_ALPHA);	
$cs = new CriteriaState("base_stat_iplink.php");
$cs->ReadState();
$qs = new QueryState();
$qs->AddCannedQuery("most_frequent", $freq_num_alerts, _MOSTFREQALERTS, "occur_d"); 
$qs->AddCannedQuery("last_alerts", $last_num_ualerts, _LASTALERTS, "last_d");
$qs->MoveView($submit);             /* increment the view if necessary */
$page_title = _SIPLTITLE;
if ( $qs->isCannedQuery() ){
	$page_title . ': ' . $qs->GetCurrentCannedQueryDesc();
}
$tr = 1; // Page Refresh
if ($action != '' ){
	$tr = $refresh_all_pages;
}
PrintBASESubHeader( $page_title, $page_title, $cs->GetBackLink(), $tr );

$criteria_clauses = ProcessCriteria();
PrintCriteria('');

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

  $qs->RunAction($submit, PAGE_STAT_IPLINK, $db);
  $et->Mark("Alert Action");

  /* Run the query to determine the number of rows (No LIMIT)*/
  $qs->current_view = 0;
  $qs->num_result_rows = UniqueLinkCnt($db, $criteria_clauses[0], $criteria_clauses[1]);
  $et->Mark("Counting Result size");
// Setup the Query Results Table */
$qro = new QueryResultsOutput("base_stat_iplink.php?caller=".$caller);
$qro->AddTitle('');
$qro->AddTitle(_SIPLSOURCEFGDN);
$qro->AddTitle( _PSSRCIP,
	"sip_a", "", " ORDER BY ip_src ASC",
	"sip_d", "", " ORDER BY ip_src DESC", 'right'
);
$qro->AddTitle(_SIPLDIRECTION);
$qro->AddTitle( _PSDSTIP,
	"dip_a", "", " ORDER BY ip_dst ASC",
	"dip_d", "", " ORDER BY ip_dst DESC", 'right'
);
$qro->AddTitle(_SIPLDESTFGDN);
$qro->AddTitle( _SIPLPROTO, '','','','','','','left' );
$qro->AddTitle(_SIPLUNIDSTPORTS, '','','','','','','right');
$qro->AddTitle(_SIPLUNIEVENTS, '','','','','','','right');
$qro->AddTitle(_SIPLTOTALEVENTS, '','','','','','','right');

// Issue #168
$sql = "SELECT DISTINCT acid_event.ip_src, acid_event.ip_dst, ".
	"acid_event.ip_proto ";
$sqlPFX = $from.$where;
$sort_sql = $qro->GetSortSQL($qs->GetCurrentSort(), $qs->GetCurrentCannedQuerySort());
if ( !is_null($sort_sql) ){
	$sqlPFX = $sort_sql[0].$sqlPFX.$sort_sql[1];
}
$sql .= $sqlPFX;
if ( is_numeric($submit) ){
	$qs->current_view = $submit;
}
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

  echo '<FORM METHOD="post" NAME="PacketForm" ACTION="base_stat_iplink.php">';
  
  $qro->PrintHeader();

  $i = 0;
  while ( ($myrow = $result->baseFetchRow()) && ($i < $qs->GetDisplayRowCnt()) )
  {
     $sip = $myrow[0];
     $dip = $myrow[1];
     $proto = $myrow[2];  
	if ( $resolve_IP == 1 ){
		$sip_fqdn = baseGetHostByAddr(baseLong2IP($sip), $db, $dns_cache_lifetime);
		$dip_fqdn = baseGetHostByAddr(baseLong2IP($dip), $db, $dns_cache_lifetime);
	}else{
		$sip_fqdn =_PSNODNS;
		$sip_fqdn =_PSNODNS;
	}
	if ( $sip && $dip ){ // Get stats on the link.
        $temp = "SELECT COUNT(DISTINCT layer4_dport), ".
                 "COUNT(acid_event.cid), COUNT(DISTINCT acid_event.signature)  ".
                 $from.$where." AND acid_event.ip_src='".$sip."' AND acid_event.ip_dst='".$dip."' AND acid_event.ip_proto='".$proto."'";

        $result2 = $db->baseExecute($temp);
        $row = $result2->baseFetchRow();
        $num_occurances = $row[1];
        $num_unique_dport = $row[0];
        $num_unique = $row[2];
        $result2->baseFreeRows(); 

        /* Print out */ 
        qroPrintEntryHeader($i);

        $tmp_ip_criteria = 
          '&amp;ip_addr%5B0%5D%5B0%5D=+&amp;ip_addr%5B0%5D%5B1%5D=ip_src&amp;ip_addr%5B0%5D%5B2%5D=%3D'.
          '&amp;ip_addr%5B0%5D%5B3%5D='.baseLong2IP($sip).
          '&amp;ip_addr%5B0%5D%5B8%5D=+&amp;ip_addr%5B0%5D%5B9%5D=AND'.
          '&amp;ip_addr%5B1%5D%5B0%5D=+&amp;ip_addr%5B1%5D%5B1%5D=ip_dst&amp;ip_addr%5B1%5D%5B2%5D=%3D'.
          '&amp;ip_addr%5B1%5D%5B3%5D='.baseLong2IP($dip).
          '&amp;ip_addr%5B1%5D%5B8%5D=+&amp;ip_addr%5B1%5D%5B9%5D=+'.
          '&amp;ip_addr_cnt=2&amp;layer4='.IPProto2str($proto).
          '&amp;sort_order='.$sort_order;

        $tmp_rowid = $sip . "_" . $dip . "_" . $proto;
        echo '    <TD><INPUT TYPE="checkbox" NAME="action_chk_lst['.$i.']" VALUE="'.$tmp_rowid.'"></TD>';
        echo '        <INPUT TYPE="hidden" NAME="action_lst['.$i.']" VALUE="'.$tmp_rowid.'">';
		qroPrintEntry($sip_fqdn, 'right');
		qroPrintEntry(
			BuildAddressLink(baseLong2IP($sip), 32).baseLong2IP($sip).'</a>',
			'right'
		);
		qroPrintEntry('-->');
		qroPrintEntry(
			BuildAddressLink(baseLong2IP($dip), 32).baseLong2IP($dip).'</a>',
			'right'
		);
		qroPrintEntry($dip_fqdn,'right');
		qroPrintEntry(IPProto2str($proto),'left');
        $tmp = '<A HREF="base_stat_ports.php?port_type=2&amp;proto='.$proto.$tmp_ip_criteria.'">';
		qroPrintEntry($tmp.$num_unique_dport.'</a>','right');
        $tmp = '<A HREF="base_stat_alerts.php?foo=1'.$tmp_ip_criteria.'">';
		qroPrintEntry($tmp.$num_unique.'</a>','right');
        $tmp = '<A HREF="base_qry_main.php?new=1'.
                      '&amp;num_result_rows=-1'.
                      '&amp;submit='._QUERYDBP.'&amp;current_view=-1'.$tmp_ip_criteria.'">'; 
		qroPrintEntry($tmp.$num_occurances.'</a>','right');
		qroPrintEntryFooter();
	}
	$i++;
}
$result->baseFreeRows();

$qro->PrintFooter();
$qs->PrintBrowseButtons();
$qs->PrintAlertActionButtons();
$qs->SaveState();
ExportHTTPVar("sort_order", $sort_order);
NLIO('</form>',2);
$et->Mark("Get Query Elements");
PrintBASESubFooter();
?>
