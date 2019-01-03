<?php
/*******************************************************************************
** Basic Analysis and Security Engine (BASE)
** Copyright (C) 2004 BASE Project Team
** Copyright (C) 2000 Carnegie Mellon University
**
** (see the file 'base_main.php' for license details)
**
** Project Lead: Kevin Johnson <kjohnson@secureideas.net>
**                Sean Muller <samwise_diver@users.sourceforge.net>
** Built upon work by Roman Danyliw <rdd@cert.org>, <roman@danyliw.com>
**
** Purpose: Alert action (e.g. add to AG, delete, email,
**          archive) operations   
********************************************************************************
** Authors:
********************************************************************************
** Kevin Johnson <kjohnson@secureideas.net
**
********************************************************************************
*/
/** The below check is to make sure that the conf file has been loaded before this one....
 **  This should prevent someone from accessing the page directly. -- Kevin
 **/
defined( '_BASE_INC' ) or die( 'Accessing this file directly is not allowed.' );

include_once("$BASE_path/base_ag_common.php");
include_once("$BASE_path/includes/base_constants.inc.php");
include_once("Mail.php"); // r.rioux added for PEAR::Mail
include_once("Mail/mime.php"); //r.rioux added for PEAR::Mail attachments

function IsValidAction($action, $valid_actions)
{
  return in_array($action, $valid_actions);
}

function IsValidActionOp($action_op, $valid_action_op)
{
  return in_array($action_op, $valid_action_op);
}

/*
  = action: action to perform (e.g. ag_by_id, ag_by_name, clear_alerts, delete_alerts, email_alerts)
  = valid_action: array of valid actions ($action must be in valid_action)
  = action_op: select operation to perform with $action (e.g. _SELECTED, _ALLONSCREEN, _ENTIREQUERY)
               $action_op needs to be passed by reference, because its value will need to get
               changed in order for alerts to be re-displayed after the operation.
  = valid_action_op: array of valid action operations ($action_op must be in $valid_action_op)
  = $action_arg: argument for the action
  = $context: what page is the $action being performed in? 
       - 1: from query results page
       - 2: from signature/alert page
       - 3: from sensor page
       - 4: from AG maintenance page
       - 5: base_qry_alert.php	PAGE_ALERT_DISPLAY
       - 6: base_stat_iplink.php	PAGE_STAT_IPLINK
       - 7: base_stat_class.php	PAGE_STAT_CLASS
       - 8: base_stat_uaddr.php	PAGE_STAT_UADDR
       - 9: base_stat_ports.php	PAGE_STAT_PORTS
 
  = $action_chk_lst: (used only when _SELECTED is the $action_op)
                    a sparse array where each element contains a key to alerts which should be acted 
                    on.  Some elements will be blank based on the checkbox state.
                    Depending on the setting of $context, these keys may be either
                    sid/cid pairs ($context=1), signature IDs ($context=2), or sensor IDs ($context=3)

  = $action_lst: (used only when _ALLONSCREEN is the $action_op)
                 an array denoting all elements on the screen, where each element contains a key to 
                 alerts which should be acted on. Depending on the setting of $context, these keys 
                 may be either sid/cid pairs ($context=1), signature IDs ($context=2), or sensor 
                 IDs ($context=3) 
  = $num_alert_on_screen: count of alerts on screen (used to parse through $alert_chk_lst for
                          _SELECTED and _ALLONSCREEN $action_op).
  = $num_alert_in_query: count of alerts in entire query. Passed by reference since delete operations
                         will decrement its value
  = $action_sql: (used only when _ENTIREQUERY is the $action_op)
                 SQL used to extract all the alerts to operate on
  = $page_caller: $caller variable from page
  = $db: handle to the database 
  = $action_param: extra data passed about an alert in addition to what is
                   entered by users in $action_arg
 */

function ActOnSelectedAlerts($action, $valid_action, &$action_op, $valid_action_op, $action_arg,
                             $context,
                             $action_chk_lst, $action_lst,
                             $num_alert_on_screen, &$num_alert_in_query,
                             $action_sql, $page_caller,
                             $db, $action_param = "" )
{ 
  GLOBAL $current_view, $last_num_alerts, $freq_num_alerts, $caller, 
         $ag_action, $debug_mode, $max_script_runtime;

  /* Verify that an action was actually selected */
  if ( !IsValidActionOp($action_op, $valid_action_op) )
     return;

  /* Verify that action was selected when action operation is clicked */
  if ( IsValidActionOp($action_op, $valid_action_op) &&
       $action == " " )
  {
     ErrorMessage(_NOACTION);
     return;
  }

  /* Verify that validity of action   */
  if ( !(IsValidAction($action, $valid_action) && 
         IsValidActionOp($action_op, $valid_action_op)) ) 
  {
     ErrorMessage("'".$action."'". _INVALIDACT);
     return;
  }

  /* Verify that those actions that need an argument have it
   *
   * Verify #1: Adding to an AG needs an argument 
   */
  if ( ($action_arg == "") && ( ($action == "ag_by_id") || ($action == "ag_by_name") ) )
  {
      ErrorMessage(_ERRNOAG);
      return;
  }

  /* Verify #2: Emailing alerts needs an argument */
  if ( ($action_arg == "") && ( ($action == "email_alert") || ($action == "email_alert2") ||
       ($action_arg == "csv_alert") ) )
  {
      ErrorMessage(_ERRNOEMAIL);
      return;
  }

  if ( $debug_mode > 0 )
     echo "==== "._ACTION." ======<BR>". _CONTEXT ." = $context<BR><BR>";

  if ( ini_get("safe_mode") != true )
     set_time_limit($max_script_runtime);

  if ($action_op == _SELECTED)
  {
     /* on packet lookup, only examine the first packet */
     if ( $context == PAGE_ALERT_DISPLAY ) 
     {
        $tmp = 1;
        ProcessSelectedAlerts($action, $action_op, $action_arg, $action_param,
                              $context,
                              $action_chk_lst,
                              $tmp,
                              $action_sql,
                              $db);
        $num_alert_in_query = $tmp;
     }
     else
     {
        ProcessSelectedAlerts($action, $action_op, $action_arg, $action_param,
                              $context,
                              $action_chk_lst,
                              $num_alert_in_query,
                              $action_sql,
                              $db);
     }
   }
   else if ($action_op == _ALLONSCREEN)
   {
      ProcessSelectedAlerts($action, $action_op, $action_arg, $action_param,
                            $context,
                            $action_lst,
                            $num_alert_in_query,
                            $action_sql,
                            $db);
   }
   else if ($action_op == _ENTIREQUERY)      
   {
      if ( ($context == PAGE_QRY_ALERTS) )  /* on alert listing page */
      {
         if ( $page_caller == "last_tcp"  || $page_caller == "last_udp" || 
              $page_caller == "last_icmp" || $page_caller == "last_any" )
         {
           $limit_start = 0;
           $limit_offset = $last_num_alerts; 
           $tmp_num = $last_num_alerts;
         }
         else
         {
           $tmp_num = $num_alert_in_query;
           $limit_start = $limit_offset = -1;
         }
      }
      else if ( $context == PAGE_ALERT_DISPLAY )
      {
         $tmp_num = 1;
         $limit_start = $limit_offset = -1;
      }
      else if ( $context == PAGE_STAT_ALERTS )  /* on unique alerts page */
      {
         if (  $page_caller == "most_frequent" || $page_caller == "last_alerts" )
         {
            $limit_start = 0;
            if ( $page_caller == "last_alerts" )  $limit_offset = $tmp_num = $last_num_ualerts;
            if ( $page_caller == "most_frequent" ) $limit_offset = $tmp_num = $freq_num_alerts;
         }
         else
         {
            $tmp_num = $num_alert_in_query;
            $limit_start = $limit_offset = -1;
         }
      }
      else if ( $context == PAGE_STAT_SENSOR )  /* on unique sensor page */
      {
         $tmp_num = $num_alert_in_query;
         $limit_start = $limit_offset = -1;
      }
      else if ( $context == PAGE_QRY_AG )  /* on the AG page */
      {
         $tmp_num = $num_alert_in_query;
         $limit_start = $limit_offset = -1;
      }
    
      ProcessSelectedAlerts($action, $action_op, $action_arg, $action_param,
                            $context,
                            $action_lst,
                            $tmp_num,/*&$num_alert_in_query*/
                            $action_sql,
                            $db, $limit_start, $limit_offset);
      $num_alert_in_query = $tmp_num;
   }

   /* In unique alert or unique sensor:
    * Reset the "$submit" to be a view # to mimic a browsing operation
    * so the alerts are re-displayed after the operation completes
    */
   if ( $context == PAGE_STAT_ALERTS || $context == PAGE_STAT_SENSOR )
      $action_op = $current_view;   

   /* In Query results, alert lookup, or AG maintenance:
    * Reset the "$submit" to be a view # to mimic a browsing operation
    * However if in alert lookup, set "$submit" to be the $caller (i.e. sid, cid)
    */ 
   if ( ($context == PAGE_QRY_ALERTS) || ($context == PAGE_QRY_AG) )
   {
     /* Reset $submit to a browsing view # */
     if ( (strstr($page_caller, "#") == "") && ($action_op != _QUERYDB) )
     {
        $action_op = $current_view;
     }
     /* but if in Alert Lookup, set $submit to (sid,cid) */
     
     else
     {
        $action_op = $page_caller;
     }
   }

   /* If action from AG maintenance, set operation to 'view' after
    * running the specified action;
    */
   if ( $context == PAGE_QRY_AG )
   {
     $ag_action = "view";
   } 
}

function GetActionDesc($action_name)
{
  $action_desc["ag_by_id"] = _ADDAGID;
  $action_desc["ag_by_name"] = _ADDAGNAME;
  $action_desc["add_new_ag"] = _CREATEAG;
  $action_desc["clear_alert"] = _CLEARAG;
  $action_desc["del_alert"] = _DELETEALERT;
  $action_desc["email_alert"] = _EMAILALERTSFULL;
  $action_desc["email_alert2"] = _EMAILALERTSSUMM;
  $action_desc["csv_alert"] = _EMAILALERTSCSV;
  $action_desc["archive_alert"] = _ARCHIVEALERTSCOPY;
  $action_desc["archive_alert2"] = _ARCHIVEALERTSMOVE;


  return $action_desc[$action_name];
}

function ProcessSelectedAlerts($action, &$action_op, $action_arg, $action_param,
                               $context,
                               $action_lst,
                               &$num_alert,
                               $action_sql,
                               $db, $limit_start=-1, $limit_offset=-1 )
{

  GLOBAL $debug_mode;
  $action_cnt = 0;
  $dup_cnt = 0;

  $action_desc = "";

  if ( $action == "ag_by_id" )          $action_desc = _ADDAGID;
  else if ($action == "ag_by_name")     $action_desc = _ADDAGNAME;
  else if ($action == "del_alert")      $action_desc = _DELETEALERT;
  else if ($action == "email_alert")    $action_desc = _EMAILALERTSFULL;
  else if ($action == "email_alert2")   $action_desc = _EMAILALERTSSUMM;
  else if ($action == "csv_alert")      $action_desc = _EMAILALERTSCSV;
  else if ($action == "clear_alert")    $action_desc = _CLEARAG;
  else if ($action == "archive_alert")  $action_desc = _ARCHIVEALERTSCOPY;
  else if ($action == "archive_alert2") $action_desc = _ARCHIVEALERTSMOVE;
  else if ($action == "add_new_ag")     $action_desc = _ADDAG;
  

  if ( $action == "" )  
    return;

  if ( $debug_mode > 0 )
  {
     echo "<BR>==== $action_desc Alerts ========<BR>
           num_alert = $num_alert<BR>
           action_sql = $action_sql<BR>
           action_op = $action_op<BR>
           action_arg = $action_arg<BR>
           action_param = $action_param<BR>
           context = $context<BR>
           limit_start = $limit_start<BR>
           limit_offset = $limit_offset<BR>";
  }

  /* Depending from which page/listing the action was spawned,
   * the entities selected may not necessarily be specific
   * alerts.  For example, sensors or alert names may be
   * selected.  Thus, each one of these entities referred to as 
   * alert_blobs, the specific alerts associated with them must
   * be explicitly extracted.  This blob structures SQL must be
   * used to extract the list, where the passed selected keyed
   * will be the criteria in this SQL.
   *
   * Note: When acting on any page where _ENTIREQUERY is
   * selected this is also a blob. 
   */ 

  /* if only manipulating specific alerts -- 
   * (in the Query results or AG contents list)
   */
  if ( ($context == PAGE_QRY_ALERTS) || ($context == PAGE_QRY_AG) ||
       ($context == PAGE_ALERT_DISPLAY) )
  {  
     $num_alert_blobs = 1;
     if ( $action_op == _ENTIREQUERY ) 
        $using_blobs = true;
     else
        $using_blobs = false;
  }
  /* else manipulating by alert blobs -- e.g. signature, sensor */
  else
  {
     $num_alert_blobs = $num_alert;
     $using_blobs = true;
  }

  $blob_alert_cnt = $num_alert;

  if ( $debug_mode > 0 )   echo "using_blobs = $using_blobs<BR>";

  /* ******* SOME PRE ACTION ********* */
  $function_pre = "Action_".$action."_Pre";
  $action_ctx = $function_pre($action_arg, $action_param, $db);
 
  if ( $debug_mode > 0 ) 
     echo "<BR>Gathering elements from ".sizeof($action_lst)." alert blobs<BR>";

  /* Loop through all the alert blobs */
  for ( $j = 0; $j < $num_alert_blobs; $j++ )
  {
      /* If acting on a blob construct, or on the_ENTIREQUERY 
       * of a non-blob structure (which is equivalent to 1-blob)
       * run a query to get the results.  
       *
       * For each unique blob construct two SQL statement are
       * generated: one to retrieve the alerts ($sql), and another
       * to count the number of actual alerts in this blob
       */
      if ( ($using_blobs) )
      {
         $sql = $action_sql;

         /* Unique Signature listing */
         if ( $context == PAGE_STAT_ALERTS )
         {
            if ( !isset($action_lst[$j]) ) $tmp = -1;  else $tmp = $action_lst[$j];            
            $sql = "SELECT acid_event.sid, acid_event.cid ".$action_sql." AND signature='".
                     $tmp."'"; 
            $sql2 = "SELECT count(acid_event.sid) ".$action_sql." AND signature='".
                     $tmp."'"; 
         }
         /* Unique Sensor listing */
         else if ( $context == PAGE_STAT_SENSOR )
         {
            if ( !isset($action_lst[$j]) ) $tmp = -1;  else $tmp = $action_lst[$j];
            $sql = "SELECT sid, cid FROM acid_event WHERE sid='".$tmp."'";  
            $sql2 = "SELECT count(sid) FROM acid_event WHERE sid='".$tmp."'"; 
         }
         /* Unique Classification listing */
         else if ( $context == PAGE_STAT_CLASS )
         {
            if ( !isset($action_lst[$j]) ) $tmp = -1;  else $tmp = $action_lst[$j];
            $sql = "SELECT acid_event.sid, acid_event.cid  ".$action_sql." AND sig_class_id='".
                     $tmp."'";  
            $sql2 = "SELECT count(acid_event.sid) ".$action_sql." AND sig_class_id='".
                     $tmp."'"; 
         }
         /* Unique IP links listing */
         else if ( $context == PAGE_STAT_IPLINK )
         {
            if ( !isset($action_lst[$j]) ) {
                     $tmp = -1;
            } else {
                     $tmp = $action_lst[$j];
		     $tmp_sip = strtok($tmp, "_");
		     $tmp_dip = strtok("_");
		     $tmp_proto = strtok("_");
		     $tmp = $tmp_sip . "' AND ip_dst='" . $tmp_dip . "' AND ip_proto='" . $tmp_proto;
            }
            $sql = "SELECT acid_event.sid, acid_event.cid  ".$action_sql." AND ip_src='". $tmp."'";
            $sql2 = "SELECT count(acid_event.sid) ".$action_sql." AND ip_src='". $tmp."'"; 
         }
         /* Unique IP addrs listing */
         else if ( $context == PAGE_STAT_UADDR )
         {
            if ( !isset($action_lst[$j]) ) {
                     $tmp = "ip_src='-1'";
            } else {
                     $tmp = $action_lst[$j];
                     if ($tmp[0] != "_")
                           $tmp_sip = substr($tmp, 0, strlen($tmp)-1);
                     else
                           $tmp_dip = substr($tmp, 1, strlen($tmp)-1);
		     ($tmp_sip != "") ? ($tmp = "ip_src='".$tmp_sip."'") : ($tmp = "ip_dst='".$tmp_dip."'");
            }
            $sql = "SELECT acid_event.sid, acid_event.cid  ".$action_sql." AND ". $tmp;
            $sql2 = "SELECT count(acid_event.sid) ".$action_sql." AND ". $tmp; 
         }
         /* Ports listing */
         else if ( $context == PAGE_STAT_PORTS )
         {
            if ( !isset($action_lst[$j]) ) {
                     $tmp = "ip_proto='-1'";
            } else {
                     $tmp = $action_lst[$j];
                     $tmp_proto = strtok($tmp, "_");
                     $tmp_porttype = strtok("_");
                     $tmp_ip = strtok("_");
                     if ($proto == TCP)
                           $tmp = "ip_proto='". TCP ."'";
                     else if ($proto == UDP)
                           $tmp = "ip_proto='". UDP ."'";
                     else
                           $tmp = "ip_proto IN (". TCP .", ". UDP.")";
                     ($tmp_porttype == SOURCE_PORT) ?
                           ($tmp.=" AND layer4_sport='".$tmp_ip."'") : ($tmp.=" AND layer4_dport='".$tmp_ip."'");
            }
            $sql = "SELECT acid_event.sid, acid_event.cid FROM acid_event WHERE ". $tmp;
            $sql2 = "SELECT count(acid_event.sid) FROM acid_event WHERE ". $tmp; 
         }
         /* if acting on alerts by signature or sensor, count the 
          * the number of alerts 
          */
         if ( ($context == PAGE_STAT_ALERTS) || ($context == PAGE_STAT_SENSOR)
            || ($context == PAGE_STAT_CLASS) || ($context == PAGE_STAT_IPLINK)
            || ($context == PAGE_STAT_UADDR) || ($context == PAGE_STAT_PORTS) )
         {
            $result2 = $db->baseExecute($sql2);
            $myrow2 = $result2->baseFetchRow();
            $blob_alert_cnt = $myrow2[0];
            $result2->baseFreeRows();
         }

         if ( $debug_mode > 0 )
            echo "$j = [using SQL $num_alert for blob ".
                       ( isset($action_lst[$j]) ? $action_lst[$j] : "")."]: $sql<BR>";

         /* Execute the SQL to get the alert listing */
         if ( $limit_start == -1 )
            $result = $db->baseExecute($sql, -1, -1, false);
         else
            $result = $db->baseExecute($sql, $limit_start, $limit_offset, false);

         if ( $db->baseErrorMessage() != "" )
         {
              ErrorMessage("Error retrieving alert list to $action_desc");
              if ( $debug_mode > 0 )  ErrorMessage($db->baseErrorMessage());
              return -1;
         }           
     }

     /* Limit the number of alerts acted on if in "top x alerts" */
     if ( $limit_start != -1 )    $blob_alert_cnt = $limit_offset;

     /* Loop through the specific alerts in a particular blob */
     for ( $i = 0; $i < $blob_alert_cnt; $i++ )
     {
        /* Verify that have a selected alert */
        if ( isset($action_lst[$i]) || $using_blobs )
        {
           /* If acting on a blob */
           if ( $using_blobs )
           { 
              $myrow = $result->baseFetchRow();
              $sid = $myrow[0];
              $cid = $myrow[1];  
           }
           else 
              GetQueryResultID($action_lst[$i], $seq, $sid, $cid);

           if ( $sid != "" )
           {
              if ( $debug_mode > 0 ) echo $sid.' - '.$cid.'<BR>';

              /* **** SOME ACTION on (sid, cid) ********** */
              $function_op = "Action_".$action."_op";
		$action_ctx =& $action_ctx;
              $tmp = $function_op($sid, $cid, $db, $action_arg, $action_ctx);

              if ( $tmp == 0 )
              /* xxx jl: then there was an error.  And this does not necessarily
                 refer to a duplicate */
              {
                ++$dup_cnt; 
              }
              else if ( $tmp == 1 )
              {
                ++$action_cnt; 
              }
           } 
        }
     }

     /* If acting on a blob, free the result set used to get alert list */
     if ( $using_blobs )
        $result->baseFreeRows();
  }
  /* **** SOME POST-ACTION ******* */
  $function_post = "Action_".$action."_post";
  if ($action == "del_alert")
  	$function_post($action_arg, $action_ctx, $db, $num_alert, $action_cnt, $context);
  else
  	$function_post($action_arg, $action_ctx, $db, $num_alert, $action_cnt);

  if ( $dup_cnt > 0 )
     ErrorMessage(_IGNORED .$dup_cnt._DUPALERTS);

  if ( $action_cnt > 0 )
  {
    /* 
     *  Print different message if alert action units (e.g. sensor
     *  or signature) are not individual alerts
     */ 
    if ( ($context == PAGE_STAT_ALERTS) || ($context == PAGE_STAT_SENSOR)
       || ($context == PAGE_STAT_CLASS) || ($context == PAGE_STAT_IPLINK)
       || ($context == PAGE_STAT_UADDR) || ($context == PAGE_STAT_PORTS) )
        ErrorMessage(_SUCCESS ." $action_desc - "._ON." $action_cnt "._ALERTSPARA." ("._IN." $num_alert_blobs blobs)");
     else
        ErrorMessage(_SUCCESS ." $action_desc - ".$action_cnt._ALERTSPARA);
  }
  else if ( $action_cnt == 0 )
     ErrorMessage(_NOALERTSSELECT." $action_desc "._NOTSUCCESSFUL); 

  if ( $debug_mode > 0 )
  {
     echo "-------------------------------------<BR>
          action_cnt = $action_cnt<BR>
          dup_cnt = $dup_cnt<BR>
          num_alert = $num_alert<BR> 
          ==== $action_desc Alerts END ========<BR>";
  }
}

/* 
 *
 *  function Action_*_Pre($action, $action_arg)
 *
 *  RETURNS: action context
 */

/*
 *  function Action_*_Op($sid, $cid, &$db, $action_arg, &$action_ctx)
 *
 *  RETURNS: 1: successful act on an alert
 *           0: ignored (duplicate) or error
 */

/*
 * function Action_post($action_arg, &$action_ctx, $db, &$num_alert, $action_cnt)
 *
 */

/* ADD to AG (by ID) ****************************************/
function Action_ag_by_id_Pre($action_arg, $action_param, $db)
/*
 * $action_arg: a AG ID
 */
{
  if ( VerifyAGID($action_arg, $db ) == 0 )
     ErrorMessage(_ERRUNKAGID);

  return null;
}

function Action_ag_by_id_Op($sid, $cid, $db, $action_arg, &$ctx)
{
   $sql2 = "INSERT INTO acid_ag_alert (ag_id, ag_sid, ag_cid) ".
           "VALUES ('".$action_arg."','".$sid."','".$cid."');";
   $db->baseExecute($sql2, -1, -1, false);

   if ( $db->baseErrorMessage() != "" )
      return 0; 
   else
      return 1;
}

function Action_ag_by_id_Post($action_arg, &$action_ctx, $db, &$num_alert, $action_cnt)
{
  /* none */
}

/* ADD to AG (by Name ) *************************************/
function Action_ag_by_name_Pre($action_arg, $action_param, $db)
/*
 * $action_arg: a AG name
 */
{
  return GetAGIDbyName($action_arg, $db);
}

function Action_ag_by_name_Op($sid, $cid, $db, $action_arg, &$ctx)
{
   $sql2 = "INSERT INTO acid_ag_alert (ag_id, ag_sid, ag_cid) ".
           "VALUES ('".$ctx."','".$sid."','".$cid."');";
   $db->baseExecute($sql2, -1, -1, false);

   if ( $db->baseErrorMessage() != "" )
      return 0; 
   else
      return 1;
}

function Action_ag_by_name_Post($action_arg, &$action_ctx, $db, &$num_alert, $action_cnt)
{
 /* none */
}

/* ADD NEW AG (by Name) *************************************/
function Action_add_new_ag_pre($action_arg, $action_param, $db)
/*
 *  $action_arg:  New AG name
 */
{
   if($action_arg == "") 
     $ag_name = "AG_".date("Y-m-d_H:i:s", time());
   else
     $ag_name = $action_arg;

   $ag_id = CreateAG($db, $ag_name, "");

   return $ag_id;
}

function Action_add_new_ag_Op($sid, $cid, $db, $action_arg, &$ctx)
{
   /* Add alerts to new AG */
   $ag_id = $ctx;
   $retval = Action_ag_by_id_Op($sid, $cid, $db, $ag_id, $ctx);

   /* Check the return code, if an error occurs we need to remove
    * the AG created in the Pre-action section.  Rollback would be
    * a better option, but for now we'll just delete.
    */ 
   if($retval == 0)
   {
      $sql = "DELETE FROM acid_ag WHERE ag_id='".$ag_id."'";
      $db->baseExecute($sql, -1, -1, false);

      if($db->baseErrorMessage() != "")
         ErrorMessage("Failed to remove new AG");
   }

   return $retval;
}

function Action_add_new_ag_Post($action_arg, &$action_ctx, $db, &$num_alert, $action_cnt)
{
   $sql = "SELECT COUNT(ag_id) FROM acid_ag_alert WHERE ag_id='".$action_ctx."'";
  
   $result = $db->baseExecute($sql, -1, -1, false);

   if($db->baseErrorMessage() != "")
   {
     ErrorMessage("Could not stat AG".$action_ctx);
     return 0;
   }

   $cnt = $result->baseRecordCount();
   $result->baseFreeRows();

   /* If no alerts were inserted, remove the new AG */
   if($cnt <= 0) {
     $sql = "DELETE FROM acid_ag WHERE ag_id='".$action_ctx."'";
     $db->baseExecute($sql, -1, -1, false);

     if($db->baseErrorMessage() != "")
       ErrorMessage(_ERRREMOVEFAIL);
   }
   else
   {
     /* Add was successful, so redirect user to AG edit page */
     echo '<script type=text/javascript>
            var _page = "base_ag_main.php?ag_action=edit&amp;ag_id='.$action_ctx.'&amp;submit=x";
            window.location=_page;
          </script>';
   }
}

/* DELETE **************************************************/
function Action_del_alert_pre($action_arg, $action_param, $db)
{
  GLOBAL $num_alert_blobs;

  return $num_alert_blobs;
}

function Action_del_alert_op($sid, $cid, $db, $action_arg, &$ctx)
{
  return PurgeAlert($sid, $cid, $db);
}

function Action_del_alert_post($action_arg, &$action_ctx, $db, &$num_alert, $action_cnt, $context)
{
  $sel_cnt = 0;
  $action_lst_cnt = count(ImportHTTPVar("action_lst"));
  $action_chk_lst = ImportHTTPVar("action_chk_lst");

  /* count the number of check boxes selected  */
  for ( $i = 0; $i < $action_lst_cnt ; $i++)  
  { 
     if (isset($action_chk_lst[$i]))
       $sel_cnt++;
  }
  if ($sel_cnt > 0)       /* 1 or more check boxes selected ? */
      $num_alert -= $sel_cnt;
 /* No, must have been a Delete ALL on Screen or Delete Entire Query  */
  elseif  ($context == 1)     /* detail alert list ? */
      $num_alert -= $action_cnt; 
  else  
      $num_alert -= count(ImportHTTPVar("action_chk_lst"));  

}

/* Email ***************************************************/
function Action_email_alert_pre($action_arg, $action_param, $db)
{
  return "";
}

function Action_email_alert_op($sid, $cid, $db, $action_arg, &$ctx)
{
  $tmp = ExportPacket($sid, $cid, $db);
  $ctx = $ctx.$tmp;

  if ( $tmp == "" )
     return 0;
  else
     return 1;
}

function Action_email_alert_post($action_arg, &$action_ctx, $db, &$num_alert, &$action_cnt)
{
  GLOBAL $BASE_VERSION, 
         $action_email_from, $action_email_mode, $action_email_subject, $action_email_msg,
         $action_email_smtp_host, $action_email_smtp_auth, $action_email_smtp_user, $action_email_smtp_pw,
         $action_email_smtp_localhost;

  /* Return if there is no alerts */
  if ( $action_ctx == "" )
        return;
        
  $smtp_host = $action_email_smtp_host;
  $smtp_auth = $action_email_smtp_auth;
  $smtp_user = $action_email_smtp_user;
  $smtp_pw = $action_email_smtp_pw;
  $smtp_localhost = $action_email_smtp_localhost;
  $mail_recip = $action_arg;

  $hdrs = array('From'    => $action_email_from,
                'To'      => $mail_recip,
                'Subject' => $action_email_subject
               );
  $mail_content = $action_email_msg . _GENBASE . " v$BASE_VERSION on ".date("r",time())."\n";
  

  /* alerts inline */
  if ( $action_email_mode == 0 )
  {
    $body = $mail_content."\n".$action_ctx . "\n"; 
  }
  /* alerts as attachment */
  else
  {
  	
    $boundary = strtoupper(md5(uniqid(time())));  
    $file_name = "base_report_".date("Ymd",time()).".log";
	$crlf = "\n";
    
    $mime = new Mail_Mime($crlf);
    
    $mime->addAttachment($action_ctx, 'text/csv', $file_name, 0, 'quoted-printable');
    
    $body = $mime->get();
    $hdrs = $mime->headers($hdrs);
  }

  if ( !send_email($smtp_host, $smtp_auth, $smtp_user, $smtp_pw, $mail_recip, $hdrs, $body, $smtp_localhost) )
  {
    ErrorMessage(_ERRNOEMAILEXP." '".$mail_recip."'.  "._ERRNOEMAILPHP); 
    $action_cnt = 0;
    return 0;
  }
  else
  {
    $action_cnt = 1;
    return 1;
  }
}

/* Email ***************************************************/
function Action_email_alert2_pre($action_arg, $action_param, $db)
{
  return "";
}

function Action_email_alert2_op($sid, $cid, $db, $action_arg, &$ctx)
{
  $tmp = ExportPacket_summary($sid, $cid, $db);
  $ctx = $ctx.$tmp;

  if ( $tmp == "" )
     return 0;
  else
     return 1;
}

function Action_email_alert2_post($action_arg, &$action_ctx, $db, &$num_alert, &$action_cnt)
{
	$action_ctx =& $action_ctx;
	$num_alert =& $num_alert;
	return Action_email_alert_post($action_arg, $action_ctx, $db, $num_alert, $action_cnt);
}

/* CSV    ***************************************************/
function Action_csv_alert_pre($action_arg, $action_param, $db)
{
  return "";
}

function Action_csv_alert_op($sid, $cid, $db, $action_arg, &$ctx)
{
  $tmp = ExportPacket_summary($sid, $cid, $db, 1);
  $ctx = $ctx.$tmp;

  if ( $tmp == "" )
     return 0;
  else
     return 1;
}

function Action_csv_alert_post($action_arg, &$action_ctx, $db, &$num_alert, &$action_cnt)
{
	$action_ctx =& $action_ctx;
	$num_alert =& $num_alert;
	return Action_email_alert_post($action_arg, $action_ctx, $db, $num_alert, $action_cnt);
}

/* Clear ***************************************************/
function Action_clear_alert_pre($action_arg, $action_param, $db)
{
  return $action_param;
}

function Action_clear_alert_op($sid, $cid, $db, $action_arg, &$ctx)
{
  $cnt = 0;
  $clear_table_list[0] = "acid_ag_alert";

  for ( $j = 0; $j < count($clear_table_list); $j++ )
  {
     $sql2 = "DELETE FROM ".$clear_table_list[$j].
             " WHERE ag_sid='".$sid."' AND ag_cid='".$cid."' AND ag_id='".$action_arg."'";//$ctx;
     $db->baseExecute($sql2);

     if ( $db->baseErrorMessage() != "" )
        ErrorMessage(_ERRDELALERT." ".$del_table_list[$j]);
     else 
        ++$cnt; 
  }

  return $cnt; 
}

function Action_clear_alert_post($action_arg, &$action_ctx, $db, &$num_alert, $action_cnt)
{
  $num_alert -= $action_cnt;
}

/* Archive ***************************************************/
function Action_archive_alert_pre($action_arg, $action_param, $db)
{
  GLOBAL $DBlib_path, $DBtype,
         $archive_dbname, $archive_host, $archive_port, 
         $archive_user, $archive_password;

  $db2 = NewBASEDBConnection($DBlib_path, $DBtype);
  $db2->baseConnect($archive_dbname, $archive_host, $archive_port, 
                    $archive_user, $archive_password);

  return $db2;
}



function Action_archive_alert_op($sid, $cid, &$db, $action_arg, &$ctx)
{
  GLOBAL $DBlib_path, $DBtype, $db_connect_method,
         $alert_dbname, $alert_host, $alert_port, 
         $alert_user, $alert_password,
         $archive_dbname, $archive_host, $archive_port, 
         $archive_user, $archive_password,
         $debug_mode;

   $db2 = &$ctx;

   $insert_sql = array();
   $sql_cnt = 0;
   $archive_cnt = 0;
	 $pcap_header = "";

   $sql = "SELECT hostname, interface, filter, detail, encoding FROM sensor ".
          "WHERE sid=$sid";
   $tmp_result = $db->baseExecute($sql);
   $tmp_row = $tmp_result->baseFetchRow();
   $tmp_result->baseFreeRows();

   /* Run the same query on archive db, to check if sensor data already in */
   $tmp_result_db2 = $db2->baseExecute($sql);
   $tmp_row_db2 = $tmp_result_db2->baseFetchRow();
   $tmp_result_db2->baseFreeRows();

   /* Insert sensor data only if we got it from alerts db and it's not already in archive db */
   if (isset($tmp_row) && !empty($tmp_row))
   {
     if ( $tmp_row && !$tmp_row_db2 )
     {
       $sql = "INSERT INTO sensor (sid,hostname,interface,filter,detail,encoding,last_cid) ".
              "VALUES ($sid,'".$tmp_row[0]."','".$tmp_row[1]."','".$tmp_row[2]."','".
              $tmp_row[3]."','".$tmp_row[4]."','0')";

       if ($db->DB_type == "mssql")
       {
         $insert_sql[$sql_cnt++] = "SET IDENTITY_INSERT sensor ON";
       }
       $insert_sql[$sql_cnt++] = $sql;
       if ($db->DB_type == "mssql")
       {
          $insert_sql[$sql_cnt++] = "SET IDENTITY_INSERT sensor OFF";
       }
     }
   }


   /* If we have FLoP's event `reference` column - archive it too. */
   if ( in_array("reference", $db->DB->MetaColumnNames('event')) ) {
      $sql = "SELECT signature, timestamp, reference FROM event WHERE sid=$sid AND cid=$cid";
   } else {
      $sql = "SELECT signature, timestamp FROM event WHERE sid=$sid AND cid=$cid";
   }

   $tmp_result = $db->baseExecute($sql);   
   $tmp_row = $tmp_result->baseFetchRow();
   
   /* baseFetchRow() may return an empty string rather than as array */
   if (isset($tmp_row) && !empty($tmp_row) && $tmp_row != NULL)
   {
     $sig = $tmp_row[0];
     $timestamp = $tmp_row[1];

     /* Not everybody uses FLoP: */
     if (array_key_exists(2, $tmp_row))
     {
       $reference = $tmp_row[2];	/* FLoP's event reference */
     }
     else
     {
       $reference = "";
     }
   }
   else
   {
     $reference = "";
     $sig = "";
     $timestamp = "";
   }
  
   $tmp_result->baseFreeRows();
  

   /* Run the same query on archive db, to check if event data already in */
   $tmp_result_db2 = $db2->baseExecute($sql);
   $tmp_row_event_db2 = $tmp_result_db2->baseFetchRow();
   $tmp_result_db2->baseFreeRows();

   $sig_name = "";

   /* Insert event data only if we got it from alerts db and it's not already in archive db */

   /* xxx jl: */ 
   if ( $db->baseGetDBversion() < 100 && !$tmp_row_event_db2)
   {
      /* If we have FLoP's event `reference` column - archive it too. */
      if ($reference != "") {
         $sql = "INSERT INTO event (sid,cid,signature,timestamp,reference) VALUES ";
         $sql.= "($sid, $cid, '".$sig."', '".$timestamp."', '".$reference."')"; 
      } else {
         $sql = "INSERT INTO event (sid,cid,signature,timestamp) VALUES ";
         $sql.= "($sid, $cid, '".$sig."', '".$timestamp."')"; 
      }

      $insert_sql[$sql_cnt++] = $sql;
   }
   /* Catch alerts with a null signature (e.g. with use of tag rule option) */
   else if ( $sig != "" )
   {
      $sig_name = GetSignatureName($sig, $db);

      if ( $db->baseGetDBversion() >= 103 )
      {  
         if ( $db->baseGetDBversion() >= 107 )
            $sql = "SELECT sig_class_id, sig_priority, sig_rev, sig_sid, sig_gid ";
         else
            $sql = "SELECT sig_class_id, sig_priority, sig_rev, sig_sid ";

         $sql .= "FROM signature WHERE sig_id = '".$sig."'";
         $result = $db->baseExecute($sql);
         $row = $result->baseFetchRow();

         if (isset($row) && !empty($row)) 
         {
           $sig_class_id = $row[0];
           $sig_class_name = GetSigClassName($sig_class_id, $db);
           $sig_priority = $row[1];
           $sig_rev = $row[2];
           $sig_sid = $row[3];
           if ( $db->baseGetDBversion() >= 107 ) {
              $sig_gid = $row[4];
           }
         }
      }

      $MAX_REF_CNT = 6;
      $sig_reference = array($MAX_REF_CNT);
      $sig_reference_cnt = 0;
      $sql = "SELECT ref_id FROM sig_reference WHERE sig_id='".$sig."'";
      $tmp_result = $db->baseExecute($sql);   
 
      while ( (($tmp_row = $tmp_result->baseFetchRow()) != "") &&
              ($sig_reference_cnt < $MAX_REF_CNT) )
      {
         if (isset($tmp_row) && !empty($tmp_row))
         {
           $ref_id = $tmp_row[0];

           $sql = "SELECT ref_system_id, ref_tag FROM reference ".
                  "WHERE ref_id='".$ref_id."'";
           $tmp_result2 = $db->baseExecute($sql);
           $tmp_row2 = $tmp_result2->baseFetchRow();   
           $tmp_result2->baseFreeRows();

           $sig_reference[$sig_reference_cnt++] = array ($tmp_row2[0],
                                                         $tmp_row2[1],
                                                         GetRefSystemName($tmp_row2[0], $db));
         }
      }

      $tmp_result->baseFreeRows();

      if ( $debug_mode > 1 )
      {
        echo "<PRE>";
        print_r($sig_reference);
        echo "</PRE>";
      }
   }




  /********************* xxx jl: <event> ********************/

  $archive_cnt = 0;

  /* If signatures are normalized (schema v100+), then it is 
   * impossible to merely copy the event table completely.  Rather
   * the signatures must be written to the archive DB, and their
   * new ID must be written into the archived event table
   */
  if ( $db->baseGetDBversion() >= 100 )
  {
     /* Check whether this signature already exists in
      * the archive DB.  If so, get the ID, otherwise first
      * write the signature into the archive DB, and then
      * get the newly inserted ID
      */
     $sig_id = GetSignatureID($sig_name, $db2);
     if ( $sig_id == "" && $sig_name != "" )
     {
        if ( $db->baseGetDBversion() >= 103 )
        {
           if ( $sig_class_id == "" )  
           {
              $sig_class_id = 'NULL';
           }
           else
           {
              /********** xxx jl: <sig_class> *************/
              /* get the ID of the classification */
              $tmp_sql = "SELECT sig_class_id FROM sig_class WHERE ".
                         "sig_class_name = '".$sig_class_name."'";
              $tmp_result = $db2->baseExecute($tmp_sql);
              $tmp_row = $tmp_result->baseFetchRow();
              $tmp_result->baseFreeRows();

              if ( $tmp_row == "" )
              {
                  $sql = "INSERT INTO sig_class (sig_class_name) ".
                         " VALUES ('".$sig_class_name."')";
                  $db2->baseExecute($sql);
                  $sig_class_id = $db2->baseInsertID();

		              /* Kludge query. Getting insert ID fails on postgres. */
                  if ($db->DB_type == "postgres")
                  {
                    $sql = "SELECT last_value FROM sig_class_sig_class_id_seq";
                    $tmp_result = $db2->baseExecute($sql);
                    $tmp_row = $tmp_result->baseFetchRow();
                    $tmp_result->baseFreeRows();
                    if (isset($tmp_row) && !empty($tmp_row)) 
                    {
                      $sig_class_id = $tmp_row[0];
                    } 
                    else 
                    {
                      $sig_class_id = -1; // There's "NOT NULL" in the mysql schema
                    }
                  }                
              }
              else
              {
                  $sig_class_id = $tmp_row[0];
              }

              if (!isset($sig_class_id))
                $sig_class_id = -1; // There's "NOT NULL" in the mysql schema.


              /********** xxx jl: </sig_class> *************/
           }

           

           /*************** xxx jl: <signature> ************/
           if ( !isset($sig_priority) || $sig_priority == "" )  
             $sig_priority = 'NULL';

           if ( !isset($sig_rev) || $sig_rev == "" )  
             $sig_rev = 'NULL';

           if ( !isset($sig_gid) || $sig_gid == "" )  
             $sig_gid = 'NULL';
           
           if ( $db->baseGetDBversion() >= 107 ) {
                $sql = "INSERT INTO signature (sig_name, sig_class_id, sig_priority, sig_rev, sig_sid, sig_gid) ".
                       "VALUES ('" . addslashes($sig_name) . "',".$sig_class_id.", ".$sig_priority.", ".$sig_rev.", ".$sig_sid.", ".$sig_gid.")";
           } else {
                $sql = "INSERT INTO signature (sig_name, sig_class_id, sig_priority, sig_rev, sig_sid) ".
                       "VALUES ('" . addslashes($sig_name) . "',".$sig_class_id.", ".$sig_priority.", ".$sig_rev.", ".$sig_sid.")";
           }

        }
        else 
        {
           $sql = "INSERT INTO signature (sig_name) VALUES ('".$sig_name."')";
        }




        $db2->baseExecute($sql);
        $sig_id = $db2->baseInsertID();     
        
        /* Kludge query. Getting insert ID fails on postgres. */
        if ($db->DB_type == "postgres")
        {
          $sql = "SELECT last_value FROM signature_sig_id_seq";     
          $tmp_result = $db2->baseExecute($sql);
          $tmp_row = $tmp_result->baseFetchRow();
          $tmp_result->baseFreeRows();
          $sig_id = $tmp_row[0];
        }

        /*********** xxx jl: </signature> *************/
     }




     /************* xxx jl: <reference> **************/
     /* add reference information */
     if (!isset($sig_reference_cnt) || empty($sig_reference_cnt)) 
     {
       $sig_reference_cnt = 0;
     }

     for ( $j = 0; $j < $sig_reference_cnt; $j++ )
     {
        /* get the ID of the reference system */
        $tmp_sql = "SELECT ref_system_id FROM reference_system WHERE ".
                   "ref_system_name = '".$sig_reference[$j][2]."'";
        $tmp_result = $db2->baseExecute($tmp_sql);
        $tmp_row = $tmp_result->baseFetchRow();
        $tmp_result->baseFreeRows();

        if ( !isset($tmp_row) || empty($tmp_row) || $tmp_row == "" )
        {
           $sql = "INSERT INTO reference_system (ref_system_name) ".
                  " VALUES ('".$sig_reference[$j][2]."')";
           $db2->baseExecute($sql);
           $ref_system_id = $db2->baseInsertID();          

           /* Kludge query. Getting insert ID fails on postgres. */
           if ($db->DB_type == "postgres")
           {
             $sql = "SELECT last_value FROM reference_system_ref_system_id_seq";
             $tmp_result = $db2->baseExecute($sql);
             $tmp_row = $tmp_result->baseFetchRow();
             $tmp_result->baseFreeRows();
             $ref_system_id = $tmp_row[0];
           }          
        }
        else
        {
           $ref_system_id = -1;
        }

        $sql = "SELECT ref_id FROM reference WHERE ".
               "ref_system_id='".$ref_system_id."' AND ".
               "ref_tag='".$sig_reference[$j][1]."'";

        if ($db->DB_type == "mssql")
        {
           /* MSSQL doesn't allow "=" with TEXT data types, but it does
            * allow LIKE. By escaping all the characters in the search
            * string, we make LIKE work like =.
            */
           $mssql_kludge_sig_tag = MssqlKludgeValue($sig_reference[$j][1]);
           $sql = "SELECT ref_id FROM reference WHERE ".
                  "ref_system_id='".$ref_system_id."' AND ".
                  "ref_tag LIKE '".$mssql_kludge_sig_tag."'";
        }

        $tmp_result = $db2->baseExecute($sql);
        $tmp_row = $tmp_result->baseFetchRow();

        if (isset($tmp_row) && !empty($tmp_row) && $tmp_row != "")
        {
           $ref_id = $tmp_row[0];
           $tmp_result->baseFreeRows();
        }
        else
        {
           $sql = "INSERT INTO reference (ref_system_id, ref_tag) ".
                  " VALUES (".$sig_reference[$j][0].",'".
                              $sig_reference[$j][1]."')";
           $db2->baseExecute($sql);
           $ref_id = $db2->baseInsertID(); 

           /* Kludge query. Getting insert ID fails on postgres. */
           if ($db->DB_type == "postgres")
           {
             $sql = "SELECT last_value FROM reference_ref_id_seq";
             $tmp_result = $db2->baseExecute($sql);
             $tmp_row = $tmp_result->baseFetchRow();
             $tmp_result->baseFreeRows();
             $ref_id = $tmp_row[0];
           } 
        }        

        if (!isset($ref_id))
        {
          $ref_id = "";
        }

        if ( ($ref_id != "") && ($ref_id > 0) )
        {
           $sql = "INSERT INTO sig_reference (sig_id, ref_seq, ref_id) ".
                  "VALUES (".$sig_id.",".($j+1).",".$ref_id.")";
           $select_sql = "SELECT sig_id FROM sig_reference WHERE sig_id=".$sig_id.
                         " AND ref_seq=".($j+1);

           /* Run the select query on archive db, to check if data already in */
           $tmp_result_db2 = $db2->baseExecute($select_sql);
           $tmp_row_db2 = $tmp_result_db2->baseFetchRow();
           $tmp_result_db2->baseFreeRows(); 

           /* Insert data only if it's not already in archive db */
           if (!$tmp_row_db2 )
              $insert_sql[$sql_cnt++] = $sql;
        }
     } // for ( $j = 0; $j < $sig_reference_cnt; $j++ )
     /************* xxx jl: <reference> **************/




     /* xxx jl */
     /* If almost everything in 
        /var/www/html/base-php4/sql/create_base_tbls_pgsql_extra.sql
        references event (sid, cid), then the following should come
        BEFORE anything else
     */

     /* Insert event data only if it's not already in archive db */
     if (!$tmp_row_event_db2) {

        /* If we have FLoP's event `reference` column - archive it too. */
        if ($reference != "") {
           $sql = "INSERT INTO event (sid,cid,signature,timestamp,reference) VALUES ";
           $sql.= "($sid, $cid, '".$sig_id."', '".$timestamp."', '".$reference."')";
        } else {
           $sql = "INSERT INTO event (sid,cid,signature,timestamp) VALUES ";
           $sql.= "($sid, $cid, '".$sig_id."', '".$timestamp."')";
        }

        $insert_sql[$sql_cnt++] = $sql;
     }
  } // if ( $db->baseGetDBversion() >= 100 )

  /********************** xxx jl: </event> **************************/








   /*************** xxx jl: <iphdr> ******************/
   $sql = "SELECT ip_src,
                  ip_dst,
                  ip_ver, ip_hlen, ip_tos, ip_len, ip_id, ip_flags,
                  ip_off, ip_ttl, ip_proto, ip_csum ".
          "FROM iphdr WHERE sid='$sid' AND cid='$cid'";
   $tmp_result = $db->baseExecute($sql);   
   $tmp_row = $tmp_result->baseFetchRow();
   $tmp_result->baseFreeRows();

   /* Run the same query on archive db, to check if iphdr data already in */
   $tmp_result_db2 = $db2->baseExecute($sql);
   $tmp_row_db2 = $tmp_result_db2->baseFetchRow();
   $tmp_result_db2->baseFreeRows();

   /* Insert iphdr data only if we got it from alerts db */
   if (isset($tmp_row) && !empty($tmp_row))
   {
      $ip_proto = $tmp_row[10];

      /* Insert iphdr data only if it's not already in archive db */

      /* xxx jl: 
         /usr/local/src/snort-2.8.1_unpatched/schemas/create_postgresql
         /var/www/html/base-php4/sql/create_base_tbls_pgsql.sql
         /var/www/html/base-php4/sql/create_base_tbls_pgsql_extra.sql
      */

      if (!$tmp_row_db2) {
         $sql = "INSERT INTO iphdr (sid,cid, 
                                 ip_src,
                                 ip_dst,
                                 ip_ver,ip_hlen,ip_tos,ip_len,ip_id,ip_flags,
                                 ip_off,ip_ttl,ip_proto,ip_csum) VALUES ".
             "($sid, $cid, '".$tmp_row[0]."', '".$tmp_row[1]."',".
             "'".$tmp_row[2]."','".$tmp_row[3]."','".$tmp_row[4]."','".$tmp_row[5]."',".
             "'".$tmp_row[6]."','".$tmp_row[7]."','".$tmp_row[8]."','".$tmp_row[9]."',".
             "'".$tmp_row[10]."','".$tmp_row[11]."')";
         $insert_sql[$sql_cnt++] = $sql;
      }
   }
   else
   {
      $ip_proto = -1;
   }
   /*************** xxx jl: </iphdr> ******************/



   /*************** xxx jl: <tcphdr, udphdr, icmphdr> ******************/
   if ( $ip_proto == 6 )
   {
      $sql = "SELECT tcp_sport, tcp_dport, tcp_seq, tcp_ack, tcp_off,
                  tcp_res, tcp_flags, tcp_win, tcp_csum, tcp_urp ".
             "FROM tcphdr WHERE sid='$sid' AND cid='$cid'";
      $tmp_result = $db->baseExecute($sql);   
      $tmp_row = $tmp_result->baseFetchRow();
      $tmp_result->baseFreeRows();

      /* Run the same query on archive db, to check if tcphdr data already in */
      $tmp_result_db2 = $db2->baseExecute($sql);
      $tmp_row_db2 = $tmp_result_db2->baseFetchRow();
      $tmp_result_db2->baseFreeRows();
       
      /* Insert tcphdr data only if we got it from alerts db and it's not already in archive db */
      if (isset($tmp_row) && !empty($tmp_row) && !$tmp_row_db2 ) {
          $sql = "INSERT INTO tcphdr (sid,cid,
                               tcp_sport, tcp_dport, tcp_seq,
                               tcp_ack, tcp_off, tcp_res, tcp_flags,
                               tcp_win, tcp_csum, tcp_urp) VALUES ".
             "($sid, $cid, '".$tmp_row[0]."', '".$tmp_row[1]."',".
             "'".$tmp_row[2]."','".$tmp_row[3]."','".$tmp_row[4]."','".$tmp_row[5]."',".
             "'".$tmp_row[6]."','".$tmp_row[7]."','".$tmp_row[8]."','".$tmp_row[9]."')";
          $insert_sql[$sql_cnt++] = $sql;
      }
   }
   else if ( $ip_proto == 17 )
   {
      $sql = "SELECT udp_sport, udp_dport, udp_len, udp_csum ".
             "FROM udphdr WHERE sid='$sid' AND cid='$cid'";
      $tmp_result = $db->baseExecute($sql);   
      $tmp_row = $tmp_result->baseFetchRow();
      $tmp_result->baseFreeRows();

      /* Run the same query on archive db, to check if udphdr data already in */
      $tmp_result_db2 = $db2->baseExecute($sql);
      $tmp_row_db2 = $tmp_result_db2->baseFetchRow();
      $tmp_result_db2->baseFreeRows();
          
      /* Insert udphdr data only if we got it from alerts db and it's not already in archive db */
      if (isset($tmp_row) && !empty($tmp_row) && !$tmp_row_db2 ) {
         $sql = "INSERT INTO udphdr (sid,cid, udp_sport, udp_dport, ".
                "udp_len, udp_csum) VALUES ".
                "($sid, $cid, '".$tmp_row[0]."', '".$tmp_row[1]."',".
                "'".$tmp_row[2]."','".$tmp_row[3]."')";
         $insert_sql[$sql_cnt++] = $sql;
      }
   }
   else if ( $ip_proto == 1 )
   {
      $sql = "SELECT icmp_type, icmp_code, icmp_csum, icmp_id, icmp_seq ".
             "FROM icmphdr WHERE sid='$sid' AND cid='$cid'";
      $tmp_result = $db->baseExecute($sql);   
      $tmp_row = $tmp_result->baseFetchRow();
      $tmp_result->baseFreeRows();

      /* Run the same query on archive db, to check if icmphdr data already in */
      $tmp_result_db2 = $db2->baseExecute($sql);
      $tmp_row_db2 = $tmp_result_db2->baseFetchRow();
      $tmp_result_db2->baseFreeRows();
      
      /* Insert icmphdr data only if we got it from alerts db and it's not already in archive db */
      if (isset($tmp_row) && !empty($tmp_row) && !$tmp_row_db2 ) {
         $sql = "INSERT INTO icmphdr (sid,cid,icmp_type,icmp_code,".
                "icmp_csum,icmp_id,icmp_seq) VALUES ".
                "($sid, $cid, '".$tmp_row[0]."', '".$tmp_row[1]."',".
                "'".$tmp_row[2]."','".$tmp_row[3]."','".$tmp_row[4]."')";
         $insert_sql[$sql_cnt++] = $sql;
      }
   }
   /*************** xxx jl: </tcphdr, udphdr, icmphdr> ******************/



   /************** xxx jl: <flop specific tables> ********************/
   /* If we have FLoP extended db, archive `pcap_header` and `data_header` too. */
   if ( in_array("pcap_header", $db->DB->MetaColumnNames('data')) &&
        in_array("data_header", $db->DB->MetaColumnNames('data'))) {
      $sql = "SELECT data_payload, pcap_header, data_header FROM data WHERE sid='$sid' AND cid='$cid'";
      $tmp_result = $db->baseExecute($sql);   
      $tmp_row = $tmp_result->baseFetchRow();
      $tmp_result->baseFreeRows();
      if (isset($tmp_row) && !empty($tmp_row)) 
      {
        $pcap_header = $tmp_row[1];
        $data_header = $tmp_row[2];
      }
      else
      {
        $pcap_header = "";
        $data_header = "";
      }
   } else { 
      $sql = "SELECT data_payload FROM data WHERE sid='$sid' AND cid='$cid'";
      $tmp_result = $db->baseExecute($sql);   
      $tmp_row = $tmp_result->baseFetchRow();
      $tmp_result->baseFreeRows();
   }


   /* Run the same query on archive db, to check if data already in */
   $tmp_result_db2 = $db2->baseExecute($sql);
   $tmp_row_db2 = $tmp_result_db2->baseFetchRow();
   $tmp_result_db2->baseFreeRows(); 

   /* Insert data only if we got it from alerts db and it's not already in archive db */
   if (isset($tmp_row) && !empty($tmp_row) && !$tmp_row_db2 )
   {

      /* If we have FLoP extended db `pcap_header` or `data_header` then archive it too. */
      if ( ($pcap_header!="") || ($data_header!="") ) {
         $sql = "INSERT INTO data (sid,cid, data_payload, pcap_header, data_header) VALUES ";
         $sql.= "($sid, $cid, '".$tmp_row[0]."', '".$pcap_header."', '".$data_header."')";
      } else {
         $sql = "INSERT INTO data (sid,cid, data_payload) VALUES ";
         $sql.= "($sid, $cid, '".$tmp_row[0]."')";
      }

      $insert_sql[$sql_cnt++] = $sql;
   }
   /************** xxx jl: </flop specific tables> ********************/





   /*************** xxx jl: <opt> ********************/
   $sql = "SELECT optid, opt_proto, opt_code, opt_len, opt_data ".
          "FROM opt WHERE sid='$sid' AND cid='$cid'";
   $tmp_result = $db->baseExecute($sql);
   $tmp_num_opt = $tmp_result->baseRecordCount();
   
   if (!isset($tmp_num_opt))
   {
     $tmp_num_opt = 0;
   }

   //while ( ($tmp_row = $tmp_result->baseFetchRow()) != "" )
   for ($i = 0; $i < $tmp_num_opt; $i++)
   {
			$tmp_row = $tmp_result->baseFetchRow();

      if (!isset($tmp_row) || empty($tmp_row) || $tmp_row == "") {
        {
          echo __FILE__ . ":" . __LINE__ . ": WARNING: \$tmp_row = \"\" with \$i = $i out of $tmp_num_opt IP options. Continueing.<BR>\n";
          var_dump($tmp_row);
          echo "<BR>\n";
        }
        continue;
      }

      $sql = "INSERT INTO opt (sid,cid,optid,opt_proto,".
             "opt_code,opt_len,opt_data) VALUES ".
             "($sid, $cid, '".$tmp_row[0]."', '".$tmp_row[1]."',".
             "'".$tmp_row[2]."','".$tmp_row[3]."','".$tmp_row[4]."')";

      if (isset($tmp_row[0])) {
        $select_sql = "SELECT optid, opt_proto, opt_code, opt_len, opt_data ".
                      "FROM opt WHERE sid='$sid' AND cid='$cid' AND optid='$tmp_row[0]'";
      } else {
        $select_sql = "SELECT optid, opt_proto, opt_code, opt_len, opt_data ".
                      "FROM opt WHERE sid='$sid' AND cid='$cid' AND opt_len='$tmp_row[3]' AND opt_data='$tmp_row[4]'";
      }



      /* Run the select query on archive db, to check if data already in */
      $tmp_result_db2 = $db2->baseExecute($select_sql);
      $tmp_row_db2 = $tmp_result_db2->baseFetchRow();
      $tmp_result_db2->baseFreeRows(); 

      /* Insert data only if it's not already in archive db */
      if (!$tmp_row_db2 )
         $insert_sql[$sql_cnt++] = $sql;
   }

   $tmp_result->baseFreeRows();
   /************** xxx jl: </opt> ********************/




  /***** Preparing is over, now actually execute all those commands *******/
  if ( $debug_mode > 0 )
  {
    echo "<PRE>";
    print_r($insert_sql);
    echo "</PRE>";
  }
 
  /* Write Alerts into archive database */
  for ( $j = 0; $j < count($insert_sql); $j++)
  {
     $db2->baseExecute($insert_sql[$j], -1, -1, false);
     if ( $db2->baseErrorMessage() == "" )
     {
       ++$archive_cnt;
     }
     else
     {
        if ($db2->DB_type == "mssql")
        {
          // MSSQL must be reset in this case, or else the same error message
          //  will be returned for all subsequent INSERTS, even though they
          //  succeed.
          $db2->baseConnect($archive_dbname, $archive_host, $archive_port,
                         $archive_user, $archive_password);
        }

        /* When we get such an error, assume that this is ok */
        if ( strstr($insert_sql[$j], "SET IDENTITY_INSERT") )
        {
           ++$archive_cnt;
        }
        else
        { 
          ErrorMessage(_ERRARCHIVE.$db2->baseErrorMessage()."<BR>".
                         $insert_sql[$j]);

          /* When detect a duplicate then stop */
          /* xxx jl: Error may be anything, not just a duplicate error! */
          break;
        }
     }
  } 

  /* Check if all or any data was written to archive database,
   * before purging the alert from the current database
   */
  if (!isset($archive_cnt))
  {
    $archive_cnt = 0;
  }

  if (!isset($sql_cnt))
  {
    $sql_cnt = 0;
  }

  if ($debug_mode > 1)
  {
    print "xxx jl: archive_cnt = " . $archive_cnt . "<BR>\n";
    print "        sql_cnt     = " . $sql_cnt . "<BR>\n";
  }


  if ($archive_cnt == $sql_cnt) {
     $archive_cnt = 1;


     if ($sql_cnt > 0) {
       /*
        * Update alert cache for archived alert right after we copy it to archive db. 
        * This fixes issue when alert in archive db not cached if archived alert cid 
        * is lesser than other alerts cid already cached in archive db.
        */
       CacheAlert($sid,$cid,$db2);
     }


  }
  else
  {
     ErrorMessage(__FILE__ . ":" . __LINE__ . ": " . _ERRARCHIVE . ": Not everything seems to have been written to the archive db: <BR>\$sql_cnt = $sql_cnt <BR>\$archive_cnt = $archive_cnt<BR><BR>");
     $archive_cnt = 0;
  }

  return $archive_cnt;  
}


function Action_archive_alert_post($action_arg, &$action_ctx, $db, &$num_alert, $action_cnt)
{
   /* BEGIN LOCAL FIX */
   /* Call UpdateAlertCache to properly set cid values and make sure caches are current */ 
   $archive_db=&$action_ctx;
   UpdateAlertCache($archive_db);
   UpdateAlertCache($db);
   /* END LOCAL FIX */
}

function Action_archive_alert2_pre($action_arg, $action_param, $db)
{
  return Action_archive_alert_pre($action_arg, $action_param, $db);
}


function Action_archive_alert2_op($sid, $cid, &$db, $action_arg, &$ctx)
{
  $cnt = $cnt2 = 0;

  $cnt = Action_archive_alert_op($sid, $cid, $db, $action_arg, $ctx);
  if ( $cnt == 1 )
     $cnt2 = PurgeAlert($sid, $cid, $db);

  /* Note: the inconsistent state possible if alerts are copied to
   * the archive DB, but not deleted 
   */

  if ( ($cnt == 1) && ($cnt2 == 1) )
    return 1;
  else
    return 0;
}

function Action_archive_alert2_post($action_arg, &$action_ctx, $db, &$num_alert, $action_cnt)
{
  /* BEGIN LOCAL FIX */ 
  /* Call UpdateAlertCache to properly set cid values and make sure caches are current */
  $archive_db=&$action_ctx;
  UpdateAlertCache($archive_db); 
  UpdateAlertCache($db);
  /* END LOCAL FIX */
  /* Reset the alert count that the query is re-executed to reflect the deletion */
  $num_alert -= $action_cnt;
}


/* This function accepts a (sid,cid) and purges it
 * from the database 
 *
 * - (sid,cid) : sensor, event id pair to delete
 * - db        : database handle
 *
 * RETURNS: 0 or 1 depending on whether the alert was deleted
 */
function PurgeAlert($sid, $cid, $db)
{
  $del_table_list = array("event",
                          "iphdr",
                          "tcphdr",
                          "udphdr",
                          "icmphdr",
                          "opt",
                          "data",
                          "acid_ag_alert",
                          "acid_event");

  $del_cnt = 0;

  if ( ($GLOBALS['use_referential_integrity'] == 1) &&
       ($GLOBALS['DBtype'] != "mysql") )
     $del_table_list = array ("event");

  for ( $k = 0; $k < count($del_table_list); $k++ )
  {
     /* If trying to add to an BASE table append ag_ to the fields */
     if ( strstr($del_table_list[$k], "acid_ag") == "" )
        $sql2 = "DELETE FROM ".$del_table_list[$k]." WHERE sid='".$sid."' AND cid='".$cid."'";
     else
        $sql2 = "DELETE FROM ".$del_table_list[$k]." WHERE ag_sid='".$sid."' AND ag_cid='".$cid."'";
     
     $db->baseExecute($sql2);

     if ( $db->baseErrorMessage() != "" )
        ErrorMessage(_ERRDELALERT." ".$del_table_list[$k]);
     else if ( $k == 0 ) 
        $del_cnt = 1; 
  }

  return $del_cnt;  
}

/* RETURNS: boolean on success of sending message 
 *
 */

function send_email($smtp_host, $smtp_auth, $smtp_user, $smtp_pw, $to, $hdrs, $body, $smtp_localhost='localhost')
{
  if ($to != "")
  {
	$smtp =& Mail::factory('smtp', 
				array ('host'     => $smtp_host, 
				       'auth'     => $smtp_auth, 
				       'username' => $smtp_user, 
				       'password' => $smtp_pw,
                                       'localhost' => $smtp_localhost
				)
			      );
	$rv = $smtp->send($to, $hdrs, $body);
        if (is_bool($rv) && $rv)
        {
          return true;
        }
        else
        {
          ErrorMessage($rv);
          return false;
        }
  }
  else
  {
     ErrorMessage(_ERRMAILNORECP);
     return false;
  }
} 

?>
