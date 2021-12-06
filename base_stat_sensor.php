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
** Purpose: Sensor statistics
**
** Input GET/POST variables
**   - submit:
**   - caller:
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
include_once ("$BASE_path/base_common.php");
include_once ("$BASE_path/base_stat_common.php");
include_once ("$BASE_path/base_qry_common.php");
include_once ("$BASE_path/base_ag_common.php");

AuthorizedRole(10000);
$et = new EventTiming($debug_time_mode);
$cs = new CriteriaState("base_stat_sensor.php");
$cs->ReadState();
$qs = new QueryState();
$submit = ImportHTTPVar("submit", VAR_ALPHA | VAR_SPACE, array(_SELECTED, _ALLONSCREEN, _ENTIREQUERY));
$sort_order=ImportHTTPVar("sort_order", VAR_LETTER | VAR_USCORE);
$action = ImportHTTPVar("action", VAR_ALPHA);
$qs->MoveView($submit);             /* increment the view if necessary */
$page_title = SPSENSORLIST;
if ( $action == '' ){
	PrintBASESubHeader($page_title, $page_title, $cs->GetBackLink(), 1);
}else{
	PrintBASESubHeader($page_title, $page_title, $cs->GetBackLink(), $refresh_all_pages);
}
$db = NewBASEDBConnection($DBlib_path, $DBtype); // Connect to Alert DB.
$db->baseDBConnect(
	$db_connect_method,$alert_dbname, $alert_host, $alert_port, $alert_user,
	$alert_password
);
if ( $event_cache_auto_update == 1 ){
	UpdateAlertCache($db);
}
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

  $qs->RunAction($submit, PAGE_STAT_SENSOR, $db);
  $et->Mark("Alert Action");

  /* create SQL to get Unique Alerts */
  $cnt_sql = "SELECT count(DISTINCT acid_event.sid) ".$from.$where;

  /* Run the query to determine the number of rows (No LIMIT)*/
  $qs->GetNumResultRows($cnt_sql, $db);
  $et->Mark("Counting Result size");
// Setup the Query Results Table.
// Common SQL Strings
$OB = ' ORDER BY';
$SNID = "CONCAT(CONCAT(sensor.hostname, ':'), sensor.interface)";
$qro = new QueryResultsOutput("base_stat_sensor.php?x=x");
$qro->AddTitle('');
$qro->AddTitle(_SENSOR,
	"sid_a", " ", "$OB acid_event.sid ASC",
	"sid_d", " ", "$OB acid_event.sid DESC"
);
$qro->AddTitle( _NAME,
	"sname_a", " ", "$OB $SNID ASC ",
	"sname_d", " ", "$OB $SNID DESC ", 'left'
);
$qro->AddTitle( _SIPLTOTALEVENTS,
	"occur_a", "", "$OB event_cnt ASC",
	"occur_d", "", "$OB event_cnt DESC", 'right'
);
$qro->AddTitle( _SIPLUNIEVENTS,
	"occur_a", "", "$OB sig_cnt ASC",
	"occur_d", "", "$OB sig_cnt DESC", 'right'
);
$qro->AddTitle( _SUASRCADD,
	"saddr_a", "", "$OB saddr_cnt ASC",
	"saddr_d", "", "$OB saddr_cnt DESC", 'right'
);
$qro->AddTitle( _SUADSTADD,
	"daddr_a", "", "$OB daddr_cnt ASC",
	"daddr_d", "", "$OB daddr_cnt DESC", 'right'
);
$qro->AddTitle(_FIRST,
	"first_a", "", "$OB first_timestamp ASC",
	"first_d", "", "$OB first_timestamp DESC"
);
$qro->AddTitle(_LAST,
	"last_a", "", "$OB last_timestamp ASC",
	"last_d", "", "$OB last_timestamp DESC"
);
$sort_sql = $qro->GetSortSQL($qs->GetCurrentSort(), "");
$sql = "SELECT DISTINCT acid_event.sid, count(acid_event.cid) as event_cnt,".
	" count(distinct(acid_event.signature)) as sig_cnt, ".
	" count(distinct(acid_event.ip_src)) as saddr_cnt, ".
	" count(distinct(acid_event.ip_dst)) as daddr_cnt, ".
	"min(timestamp) as first_timestamp, max(timestamp) as last_timestamp".
	$sort_sql[0].$from." JOIN sensor using (sid) ".$where.
	" GROUP BY acid_event.sid ".$sort_sql[1];

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
  echo '<FORM METHOD="post" NAME="PacketForm" ACTION="base_stat_sensor.php">';
  $qro->PrintHeader();

  $i = 0;
  while ( ($myrow = $result->baseFetchRow()) && ($i < $qs->GetDisplayRowCnt()) )
  {
    $sensor_id = $myrow[0];
    $event_cnt = $myrow[1];
    $unique_event_cnt = $myrow[2];
    $num_src_ip = $myrow[3];
    $num_dst_ip = $myrow[4];
    $start_time = $myrow[5];
    $stop_time = $myrow[6];

    /* Print out */ 
    qroPrintEntryHeader($i);    

    $tmp_rowid = $sensor_id;
    echo '    <TD><INPUT TYPE="checkbox" NAME="action_chk_lst['.$i.']" VALUE="'.$tmp_rowid.'">';
    echo '        <INPUT TYPE="hidden" NAME="action_lst['.$i.']" VALUE="'.$tmp_rowid.'"></TD>';

    qroPrintEntry($sensor_id);
	qroPrintEntry(GetSensorName($sensor_id, $db),'left');
	qroPrintEntry(
		"<a href='base_qry_main.php?new=1&amp;sensor=$sensor_id".
		"&amp;num_result_rows=-1&amp;submit="._QUERYDBP."'>$event_cnt</a>",
		'right'
	);
	qroPrintEntry(
		BuildUniqueAlertLink("?sensor=".$sensor_id)."$unique_event_cnt</a>",
		'right'
	);
	qroPrintEntry(
		BuildUniqueAddressLink(1, "&amp;sensor=".$sensor_id)."$num_src_ip</a>",
		'right'
	);
	qroPrintEntry(
		BuildUniqueAddressLink(2, "&amp;sensor=".$sensor_id)."$num_dst_ip</a>",
		'right'
	);
     qroPrintEntry($start_time);
     qroPrintEntry($stop_time);

     qroPrintEntryFooter();

     $i++;
  }

  $result->baseFreeRows();

  $qro->PrintFooter();

  $qs->PrintBrowseButtons();
  $qs->PrintAlertActionButtons();
  $qs->SaveState();
	ExportHTTPVar("sort_order", $sort_order);
  echo "\n</FORM>\n";
$et->Mark("Get Query Elements");
PrintBASESubFooter();
?>
