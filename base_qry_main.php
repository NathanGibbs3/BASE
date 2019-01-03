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
** Purpose: Input GET/POST variables
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

  include("base_conf.php");
  include("$BASE_path/includes/base_constants.inc.php");
  include("$BASE_path/includes/base_include.inc.php");

  include_once("$BASE_path/includes/base_action.inc.php");
  include_once("$BASE_path/base_db_common.php");
  include_once("$BASE_path/base_common.php");
  include_once("$BASE_path/base_ag_common.php");
  include_once("$BASE_path/base_qry_common.php");

  $et = new EventTiming($debug_time_mode);
  $cs = new CriteriaState("base_qry_main.php", "&amp;new=1&amp;submit="._QUERYDBP);

  // Check role out and redirect if needed -- Kevin
  $roleneeded = 10000;
  $BUser = new BaseUser();
  if (($BUser->hasRole($roleneeded) == 0) && ($Use_Auth_System == 1))
    base_header("Location: ". $BASE_urlpath . "/index.php");

  $new = ImportHTTPVar("new", VAR_DIGIT);

  /* This call can include many values. */
  $submit = ImportHTTPVar("submit", VAR_DIGIT | VAR_PUNC | VAR_LETTER,
                           array(_SELECTED, _ALLONSCREEN, _ENTIREQUERY,
                                 _QUERYDB, _ADDTIME, _ADDADDRESS, _ADDIPFIELD,
                                 _ADDTCPPORT, _ADDTCPFIELD, _ADDUDPPORT,
                                 _ADDUDPFIELD, _ADDICMPFIELD));

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
			$sort_order = "time_d"; // default set to "descending order"
    }
  }

/* Code to correct 'interesting' (read: unexplained) browser behavior */

/* Something with Netscape 4.75 such that the $submit variable is no recognized
 * under certain circumstances.  This one is a result of using HTTPS and 
 * clicking on TCP traffic profile from base_main.php 
 */
  if ( $cs->criteria['layer4']->Get() != "" && $submit == "" )
    $submit = _QUERYDB;

  /* End 'interesting' browser code fixes */

  /* Totally new Search */
  if ( ($new == 1) && ($submit == "") ) {
    $cs->InitState();
  }

  /* is this a new query, invoked from the SEARCH screen ? */
  /* if the query string if very long (> 700) then this must be from the Search screen  */
  $back = ImportHTTPVar("back", VAR_DIGIT);
  if ( ( $GLOBALS['maintain_history'] == 1 ) && ( $back != 1 ) &&  ( $submit == _QUERYDB ) && ( isset($_GET['search']) && $_GET['search']  == 1  )) {
    !empty($_SESSION['back_list_cnt']) ? $_SESSION['back_list_cnt']-- : $_SESSION['back_list_cnt'] = 0;    /* save on top of initial blank query screen   */
    $submit = "";          /*  save entered search criteria as if one hit Enter */
    $_POST['submit'] = $submit;
    $cs->ReadState();      /* save the search criteria       */
    $submit = _QUERYDB;    /* restore the real submit value  */
    $_POST['submit'] = $submit;
  }

  $cs->ReadState();

  $qs = new QueryState();
  $qs->AddCannedQuery("last_tcp", $last_num_alerts, _LASTTCP, "time_d"); 
  $qs->AddCannedQuery("last_udp", $last_num_alerts, _LASTUDP, "time_d");
  $qs->AddCannedQuery("last_icmp", $last_num_alerts, _LASTICMP, "time_d");
  $qs->AddCannedQuery("last_any", $last_num_alerts, _LASTALERTS, "time_d");

  $page_title = _QUERYRESULTS;
  if ( $qs->isCannedQuery() )
     PrintBASESubHeader($page_title.": ".$qs->GetCurrentCannedQueryDesc(),
                        $page_title.": ".$qs->GetCurrentCannedQueryDesc(), $cs->GetBackLink(), $refresh_all_pages);
  else
     PrintBASESubHeader($page_title, $page_title, $cs->GetBackLink(), $refresh_all_pages);

  /* Connect to the Alert database */
  $db = NewBASEDBConnection($DBlib_path, $DBtype);
  $db->baseDBConnect($db_connect_method,
                     $alert_dbname, $alert_host, $alert_port, $alert_user, $alert_password);

  if ( $event_cache_auto_update == 1 )  UpdateAlertCache($db);

  $printing_ag = false;
?>

<FORM METHOD="GET" NAME="PacketForm" ACTION="base_qry_main.php">
<input type='hidden' name="search" value="1" />
<?php
/* Dump some debugging information on the shared state */
if ( $debug_mode > 0 )
{
   PrintCriteriaState();
}

/* a browsing button was clicked -> increment view */
if ( is_numeric($submit) )
{
    if ( $debug_mode > 0 ) ErrorMessage("Browsing Clicked ($submit)");
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
}
/* Run the SQL Query and get results */
elseif ( $submit == _QUERYDB || $submit == _QUERYDBP ||
     $submit == _SELECTED || $submit == _ALLONSCREEN || $submit == _ENTIREQUERY || 
     $qs->isCannedQuery() || 
     $qs->GetCurrentSort() != "" )
{
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

  if ( $debug_mode > 0 ) ErrorMessage("Initial/Canned Query or Sort Clicked");
  include("$BASE_path/base_qry_sqlcalls.php");
}
/* Return the input form to get more criteria from user */
else
{
   include("$BASE_path/base_qry_form.php");
}


   $qs->SaveState();

  echo "\n</FORM>\n";
  
  PrintBASESubFooter();

  $et->Mark("Get Query Elements");
  $et->PrintTiming();
  echo "</body>\r\n</html>";

?>
