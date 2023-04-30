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
	$CPLast = $UIL->CWA['Last'];
	$CPFirst = $UIL->CWA['First'];
}else{
	$CPSensor = _SENSOR;
	$CPLast = _LAST;
	$CPFirst = _FIRST;
}
$port_proto = 'TCP';
$submit = ImportHTTPVar("submit", VAR_ALPHA | VAR_SPACE, array(_SELECTED, _ALLONSCREEN, _ENTIREQUERY));
$port_type = ImportHTTPVar("port_type", VAR_DIGIT);
$proto = ImportHTTPVar("proto", VAR_DIGIT);
$sort_order=ImportHTTPVar("sort_order", VAR_LETTER | VAR_USCORE);
$caller = ImportHTTPVar('caller', VAR_LETTER | VAR_USCORE);
$action = ImportHTTPVar("action", VAR_ALPHA);
$cs = new CriteriaState("base_stat_ports.php");
$cs->ReadState();
if ( $debug_mode > 0 ){ // Dump debugging info on the shared state.
	PrintCriteriaState();
}
if ( $caller == 'most_frequent' && $sort_order = 'occur_d' ){
	// Interim Issue #124 Fix
	$sort_order = _OCCURRENCES.'_occur_d';
}
if ( $caller == 'last_ports' && $sort_order = 'last_d' ){
	$sort_order = $CPLast.'_last_d';
}
$qs = new QueryState();
if ( $caller == 'most_frequent' || $caller == 'last_ports' ){
	// Issue #124 Fix
	$qs->current_sort_order = $sort_order;
}
$qs->AddCannedQuery(
	'most_frequent', $freq_num_uports, _MOSTFREQPORTS, _OCCURRENCES."_occur_d"
);
$qs->AddCannedQuery(
	'last_ports', $last_num_uports, _LASTPORTS, $CPLast.'_last_d'
);
$qs->MoveView($submit); // Increment the view if necessary.
$page_title = _UNIQ.' ';
switch ( $proto ){
	case TCP:
		$page_title .= "TCP ";
		break;
	case UDP:
		$page_title .= "UDP ";
		break;
}
switch ( $port_type ){
	case SOURCE_PORT:
		$page_title .= _SRCPS;
		break;
	case DEST_PORT:
		$page_title .= _DSTPS;
		break;
}
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

// Setup the Query Results Table.
// Common SQL Strings
$OB = ' ORDER BY';
$qro = new QueryResultsOutput(
	"base_stat_ports.php?caller=$caller".
	"&amp;sort_order=".$sort_order.
	"&amp;port_type=$port_type&amp;proto=$proto"
);
$qro->AddTitle('');
$qro->AddTitle( _PORT,
	"port_a", " ", "$OB $port_type_sql ASC",
	"port_d", " ", "$OB $port_type_sql DESC", 'right'
);
$qro->AddTitle( $CPSensor,
	"sensor_a", " ", "$OB num_sensors ASC",
	"sensor_d", " ", "$OB num_sensors DESC"
);
$qro->AddTitle( _OCCURRENCES,
	"occur_a", " ", "$OB num_events ASC",
	"occur_d", " ", "$OB num_events DESC", 'right'
);
$qro->AddTitle( _UNIALERTS,
	"alerts_a", " ", "$OB num_sig ASC",
	"alerts_d", " ", "$OB num_sig DESC", 'right'
);
$qro->AddTitle( _SUASRCADD,
	"sip_a", " ", "$OB num_sip ASC",
	"sip_d", " ", "$OB num_sip DESC", 'right'
);
$qro->AddTitle( _SUADSTADD,
	"dip_a", " ", "$OB num_dip ASC",
	"dip_d", " ", "$OB num_dip DESC", 'right'
);
$qro->AddTitle( $CPFirst,
	"first_a", " ", "$OB first_timestamp ASC",
	"first_d", " ", "$OB first_timestamp DESC"
);
$qro->AddTitle( $CPLast,
	"last_a", " ", "$OB last_timestamp ASC",
	"last_d", " ", "$OB last_timestamp DESC"
);

// Issue #168
$sql = "SELECT DISTINCT $port_type_sql, MIN(ip_proto), ".
		" COUNT(acid_event.cid) as num_events,".
		" COUNT( DISTINCT acid_event.sid) as num_sensors, ".
		" COUNT( DISTINCT signature ) as num_sig, ".
		" COUNT( DISTINCT ip_src ) as num_sip, ".
		" COUNT( DISTINCT ip_dst ) as num_dip, ".
		" MIN(timestamp) as first_timestamp, ".
		" MAX(timestamp) as last_timestamp ";
$sqlPFX = " FROM acid_event ".$criteria_clauses[0].
	" WHERE ".$proto_sql.$criteria_clauses[1]." GROUP BY ".$port_type_sql." ";
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
	$TK = array ( 'port_type', 'proto' );
	$DI = array();
	$DD = array();
	foreach ( $TK as $val ){
		array_push($DD, $val);
		array_push($DI, $$val);
	}
	NLIO('<hr/>');
	DDT($DI,$DD,'Port / Protocol Constraints', '', 12);
}
$qs->PrintResultCnt(); // Print current view number and # of rows.

  echo '<FORM METHOD="post" NAME="PacketForm" ACTION="base_stat_ports.php">'."\n";

  $qro->PrintHeader();

  echo "<input type='hidden' name='port_type' value='$port_type'>\n"; 

   $i = 0;
while ( ($myrow = $result->baseFetchRow()) && ($i < $qs->GetDisplayRowCnt()) ){
	if ( is_null($myrow[0]) ){
		continue; // Issue #112 Interim fix.
	}
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

	qroPrintEntry($currentPort,'right');
      qroPrintEntry('<A HREF="base_stat_sensor.php?'.$url_param.'">'.$num_sensors.'</A>');
	qroPrintEntry('<A HREF="base_qry_main.php?'.$url_param.'&amp;new=1&amp;submit='._QUERYDBP.
                    '&amp;sort_order=sig_a">'.$num_events.'</A>','right');
	qroPrintEntry('<A HREF="base_stat_alerts.php?'.$url_param.'&amp;sort_order=sig_a">'.
                    $num_sig.'</A>','right');
	qroPrintEntry('<A HREF="base_stat_uaddr.php?'.$url_param.'&amp;addr_type=1'.
                    '&amp;sort_order=addr_a">'.$num_sip,'right');
	qroPrintEntry('<A HREF="base_stat_uaddr.php?'.$url_param.'&amp;addr_type=2'.
                    '&amp;sort_order=addr_a">'.$num_dip,'right');
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
NLIO('</form>',2);
$et->Mark("Get Query Elements");
PrintBASESubFooter();
?>
