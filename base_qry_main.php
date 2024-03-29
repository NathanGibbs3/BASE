<?php
/*******************************************************************************
** Basic Analysis and Security Engine (BASE)
** Copyright (C) 2004 BASE Project Team
** Copyright (C) 2000 Carnegie Mellon University
**
** (see the file 'base_main.php' for license details)
**
** Project Leads: Kevin Johnson <kjohnson@secureideas.net>
** Built upon work by Roman Danyliw <rdd@cert.org>, <roman@danyliw.com>
**
** Purpose: Query Form & Results Dsiplay System.
// Input GET/POST variables
**   - caller: specifies the canned snapshot to run
**   - submit:
********************************************************************************
** Authors:
********************************************************************************
** Kevin Johnson <kjohnson@secureideas.net
** Chris Shepherd <chsh>
**
********************************************************************************
*/

/*
 * $caller: an auxiliary variable used to determine the how the search parameters were entered (i.e.
 *          whether through a form or through another mechanism
 *  - "stat_alerts" : display results based on the the Alert Listings
 *  - "top_tcp" :
 *  - "top_udp" :
 *  - "top_icmp" :
 *  - "last_tcp" :
 *  - "last_udp" :
 *  - "last_icmp" :
 *
 * $submit: used to determine the next action which should be taken when the form is submitted.
 *  - _QUERYDB         : triggers a query into the database
 *  - _ADDTIME         : adds another date/time row 
 *  - _ADDADDR         : adds another IP address row
 *  - _ADDIPFIELD      : adds another IP field row
 *  - _ADDTCPPORT      : adds another TCP port row
 *  - _ADDTCPFIELD     : adds another TCP field row
 *  - _ADDUDPPORT      : adds another UDP port row
 *  - _ADDUDPFIELD     : adds another UDP field row
 *  - _ADDICMPFIELD    : adds another ICMP field row
 *  - "#X-(X-X)"       : sid-cid keys for a packet lookup
 *  - _SELECTED
 *  - _ALLONSCREEN
 *  - _ENTIREQUERY
 *
 * $layer4: stores the layer 4 protocol used in query
 *
 * $save_sql: the current sql string generating the query
 *
 * $save_criteria: HTML-human readable criteria of the $save_sql string
 *
 * $num_result_rows: rows in the entire record set retried under the current 
 *                   query
 *
 * $current_view: current view of the result set
 *
 * $sort_order: how to sort the output
 *
 * ----- Search Result Variables ----
 * $action_chk_lst[]: array of check boxes to determine if an alert
 *                    was selected for action
 * $action_lst[]: array of (sid,cid) of all alerts on screen
 */

$sc = DIRECTORY_SEPARATOR;
require_once("includes$sc" . 'base_krnl.php');
include_once(BASE_IPath . 'base_include.inc.php');
include_once(BASE_IPath . 'base_action.inc.php');
include_once("$BASE_path/base_db_common.php");
include_once("$BASE_path/base_ag_common.php");
include_once("$BASE_path/base_qry_common.php");

AuthorizedRole(10000);
$db = NewBASEDBConnection($DBlib_path, $DBtype); // Connect to Alert DB.
$db->baseDBConnect(
	$db_connect_method,$alert_dbname, $alert_host, $alert_port, $alert_user,
	$alert_password
);
UpdateAlertCache($db);
if ( class_exists('UILang') ){ // Issue 11 backport shim.
	$CPSig = $UIL->CWA['Sig'];
	$CPSA = $UIL->CPA['SrcAddr'];
	$CPDA = $UIL->CPA['DstAddr'];
	$CPTs = $UIL->CWA['Ts'];
}else{
	$CPSig = _SIGNATURE;
	$CPSA = _NBSOURCEADDR;
	$CPDA = _NBDESTADDR;
	$CPTs = _TIMESTAMP;
}
if ( getenv('TRAVIS') && version_compare(PHP_VERSION, "5.3.0", "<") ){
	// Issue #5 Test Shim
	$new = 1;
	$submit = '';
}else{
	$new = ImportHTTPVar("new", VAR_DIGIT);
	// This call can include many values.
	$submit = ImportHTTPVar(
		'submit', VAR_ALPHA | VAR_PUNC,
		array(
			_SELECTED, _ALLONSCREEN, _ENTIREQUERY, _QUERYDB, _ADDTIME,
			_ADDADDRESS, _ADDIPFIELD, _ADDTCPPORT, _ADDTCPFIELD, _ADDUDPPORT,
			_ADDUDPFIELD, _ADDICMPFIELD
		)
	);
}

  // Set the sort order to the new sort order if one has been selected
  $sort_order = ImportHTTPVar("sort_order", VAR_LETTER | VAR_USCORE);
  if ($sort_order == "" || !isset($sort_order)) 
  {
    // If one wasn't picked, try the prev_sort_order
    $sort_order = ImportHTTPVar("prev_sort_order", VAR_LETTER | VAR_USCORE);
    // If there was no previous sort order, default it to none.
    if ($sort_order == "" || !isset($sort_order))
    {
      // $sort_order = "none"; //default to none.
			$sort_order = $CPTs .'_time_d'; // default set to "descending order"
    }
  }

$cs = new CriteriaState("base_qry_main.php", "&amp;new=1&amp;submit="._QUERYDBP);

/* Code to correct 'interesting' (read: unexplained) browser behavior */

/* Something with Netscape 4.75 such that the $submit variable is no recognized
 * under certain circumstances.  This one is a result of using HTTPS and 
 * clicking on TCP traffic profile from base_main.php 
 */
  if ( $cs->criteria['layer4']->Get() != "" && $submit == "" )
    $submit = _QUERYDB;

  /* End 'interesting' browser code fixes */
	if( $new == 1 && $submit == '' ){ // New Search
		$cs->InitState();
	}
	// Is this a new query, invoked from the SEARCH screen ?
	// If the query string if very long (> 700) then this must be from the
	// Search screen.
if ( isset($maintain_history) && $maintain_history == 1 ){
	$back = ImportHTTPVar("back", VAR_DIGIT);
	if ( $back != 1 && $submit == _QUERYDB && ChkGet ('search', 1) ){
		// Rewrite previous history stack item to reflect entries submitted
		// on the search form.
		if( !empty($_SESSION['back_list_cnt']) ){
			$_SESSION['back_list_cnt']--;
		}else{
			$_SESSION['back_list_cnt'] = 0;
		}
		// Save on top of history stack item for initial blank search form.
		$submit = ''; // Fake manual search form submit.
		$_POST['submit'] = $submit;
		$cs->ReadState(); // Save the search criteria.
		$submit = _QUERYDB; // Restore the real submit value.
		$_POST['submit'] = $submit;
	}
}
$cs->ReadState();
$qs = new QueryState();
$qs->current_sort_order = $sort_order; // Issue #133 fix
$tmp = $CPTs . '_time_d';
$qs->AddCannedQuery("last_tcp", $last_num_alerts, _LASTTCP, $tmp);
$qs->AddCannedQuery("last_udp", $last_num_alerts, _LASTUDP, $tmp);
$qs->AddCannedQuery("last_icmp", $last_num_alerts, _LASTICMP, $tmp);
$qs->AddCannedQuery("last_any", $last_num_alerts, _LASTALERTS, $tmp);
$tmp = '';

$page_title = _QUERYRESULTS;
if ( $qs->isCannedQuery() ){
	$page_title . ': ' . $qs->GetCurrentCannedQueryDesc();
}
PrintBASESubHeader(
	$page_title, $page_title, $cs->GetBackLink(), $refresh_all_pages
);
if ( $debug_mode > 0 ){ // Dump debugging info on the shared state.
	PrintCriteriaState();
}
$printing_ag = false;
if ( is_numeric($submit) ){ // A browsing button was clicked.
	if ( $debug_mode > 0 ){
		ErrorMessage("Browsing Clicked ($submit)");
	}
	$qs->MoveView($submit);
	$submit = _QUERYDB;
}

/* Return the input form to get more criteria from user */
if (
     ($submit == _ADDTIME) ||
     ($submit == _ADDADDRESS) ||
     ($submit == _ADDIPFIELD) ||
     ($submit == _ADDTCPPORT) ||
     ($submit == _ADDUDPPORT) ||
     ($submit == _ADDICMPFIELD) ||
     ($submit == _ADDPAYLOAD) ||
     ($submit == "TCP") ||
     ($submit == "UDP") ||
     ($submit == "ICMP") ||
     ($submit == _NOLAYER4) 
   )
{
  include("$BASE_path/base_qry_form.php");
}elseif(
	$qs->isCannedQuery() || $new != 1 ||
	$submit == _QUERYDB || $submit == _QUERYDBP || $submit == _SELECTED ||
	$submit == _ALLONSCREEN || $submit == _ENTIREQUERY
){ // Run the SQL Query and get results.
  /* Init and run the action */
  $criteria_clauses = ProcessCriteria();  

  $from = "FROM acid_event ".$criteria_clauses[0];
  $where = "";
  if( $criteria_clauses[1] != "" )
    $where = "WHERE ".$criteria_clauses[1];

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
  $qs->AddValidActionOp(_ENTIREQUERY);

  $qs->SetActionSQL("SELECT acid_event.sid, acid_event.cid $from $where");
  $et->Mark("Initialization");

  $qs->RunAction($submit, PAGE_QRY_ALERTS, $db);
  $et->Mark("Alert Action");

	if ( $debug_mode > 0 ){
		ErrorMessage("Initial/Canned Query or Sort Clicked");
	}
	include("$BASE_path/base_qry_sqlcalls.php");
}else{ // Return the input form to get more criteria from user.
	include("$BASE_path/base_qry_form.php");
}
$qs->SaveState();
NLIO('</form>',2);
$et->Mark("Get Query Elements");
PrintBASESubFooter();
?>
