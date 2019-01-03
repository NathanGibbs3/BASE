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
** Purpose: routines to manipulate shared state (session information)
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

include_once("$BASE_path/includes/base_state_common.inc.php");
include_once("$BASE_path/includes/base_state_citems.inc.php");

class CriteriaState
{
  var $clear_criteria_name;
  var $clear_criteria_element;
  var $clear_url;
  var $clear_url_params;

  var $criteria;

  function CriteriaState($url, $params = "")
  {
     $this->clear_url = $url;
     $this->clear_url_params = $params;

     /* XXX-SEC */
     GLOBAL $db, $debug_mode;

     $tdb =& $db;
     $obj =& $this;
     $this->criteria['sig'] = new SignatureCriteria($tdb, $obj, "sig");
     $this->criteria['sig_class'] = new SignatureClassificationCriteria($tdb, $obj, "sig_class");
     $this->criteria['sig_priority'] = new SignaturePriorityCriteria($tdb, $obj, "sig_priority");
     $this->criteria['ag'] = new AlertGroupCriteria($tdb, $obj, "ag");
     $this->criteria['sensor'] = new SensorCriteria($tdb, $obj, "sensor");
     $this->criteria['time'] = new TimeCriteria($tdb, $obj, "time", TIME_CFCNT);
     $this->criteria['ip_addr'] = new IPAddressCriteria($tdb, $obj, "ip_addr", IPADDR_CFCNT);
     $this->criteria['layer4'] = new Layer4Criteria($tdb, $obj, "layer4");
     $this->criteria['ip_field'] = new IPFieldCriteria($tdb, $obj, "ip_field", PROTO_CFCNT);
     $this->criteria['tcp_port'] = new TCPPortCriteria($tdb, $obj, "tcp_port", PROTO_CFCNT);
     $this->criteria['tcp_flags'] = new TCPFlagsCriteria($tdb, $obj, "tcp_flags");
     $this->criteria['tcp_field'] = new TCPFieldCriteria($tdb, $obj, "tcp_field", PROTO_CFCNT);
     $this->criteria['udp_port'] = new UDPPortCriteria($tdb, $obj, "udp_port", PROTO_CFCNT);
     $this->criteria['udp_field'] = new UDPFieldCriteria($tdb, $obj, "udp_field", PROTO_CFCNT);
     $this->criteria['icmp_field'] = new ICMPFieldCriteria($tdb, $obj, "icmp_field", PROTO_CFCNT);
     $this->criteria['rawip_field'] = new TCPFieldCriteria($tdb, $obj, "rawip_field", PROTO_CFCNT);
     $this->criteria['data'] = new DataCriteria($tdb, $obj, "data", PAYLOAD_CFCNT);

     /* 
      * For new criteria, add a call to the appropriate constructor here, and implement
      * the appropriate class in base_state_citems.inc.php
      */


  }




  function InitState()
  {
     RegisterGlobalState();
  
     $valid_criteria_list = array_keys($this->criteria);

     foreach ( $valid_criteria_list as $cname )
         $this->criteria[$cname]->Init();
  }

  function ReadState()
  {
     RegisterGlobalState();

     /* 
      * If the BACK button was clicked, shuffle the appropriate
      * criteria variables from the $back_list (history) array into
      * the current session ($_SESSION)
      */
     if ( ($GLOBALS['maintain_history'] == 1) &&
          (ImportHTTPVar("back", VAR_DIGIT) == 1) )
     {
        PopHistory();
     }

     /* 
      * Import, update and sanitize all persistant criteria variables 
      */
     $valid_criteria_list = array_keys($this->criteria);
     foreach ( $valid_criteria_list as $cname )
     {
        $this->criteria[$cname]->Import();
        $this->criteria[$cname]->Sanitize();
     }

     /* 
      * Check whether criteria elements need to be cleared 
      */
     $this->clear_criteria_name = ImportHTTPVar("clear_criteria", "", 
                                                array_keys($this->criteria));
     $this->clear_criteria_element = ImportHTTPVar("clear_criteria_element", "", 
                                                   array_keys($this->criteria));

     if ( $this->clear_criteria_name != "" )
        $this->ClearCriteriaStateElement($this->clear_criteria_name,
                                         $this->clear_criteria_element);

     /*
      * Save the current criteria into $back_list (history)
      */
     if ( $GLOBALS['maintain_history'] == 1 )
        PushHistory();
  }

  function GetBackLink()
  {
    return PrintBackButton();
  }

  function GetClearCriteriaString($name, $element = "")
  {
    return '&nbsp;&nbsp;<A HREF="'.$this->clear_url.'?clear_criteria='.$name.
           '&amp;clear_criteria_element='.$element.$this->clear_url_params.'">...'._CLEAR.'...</A>';
  }

  function ClearCriteriaStateElement($name, $element)
  {
    $valid_criteria_list = array_keys($this->criteria);

    if ( in_array($name, $valid_criteria_list) )
    {
       ErrorMessage(_REMOVE." '$name' "._FROMCRIT);
  
       $this->criteria[$name]->Init();     
    }
    else
      ErrorMessage(_ERRCRITELEM);
  }
}

/* ***********************************************************************
 * Function: PopHistory()
 *
 * @doc Remove and restore the last entry of the history list (i.e., 
 *      hit the back button in the browser)
 *     
 * @see PushHistory PrintBackButton
 *
 ************************************************************************/
function PopHistory()
{
   if ( $_SESSION['back_list_cnt'] >= 0 )
   {
      /* Remove the state of the page from which the back button was
       * just hit
       */
      unset($_SESSION['back_list'][$_SESSION['back_list_cnt']]);

      /* 
       * save a copy of the $back_list because session_destroy()/session_decode() will 
       * overwrite it. 
       */
      $save_back_list = $_SESSION['back_list'];
      $save_back_list_cnt = $_SESSION['back_list_cnt']-1;

      /* Restore the session 
       *   - destroy all variables in the current session
       *   - restore proper back_list history entry into the current variables (session)
       *       - but, first delete the currently restored entry and 
       *              decremement the history stack
       *   - push saved back_list back into session
       */
      session_unset();

      if ( $GLOBALS['debug_mode'] > 2 )
         ErrorMessage("Popping a History Entry from #".$save_back_list_cnt);

      session_decode($save_back_list[$save_back_list_cnt]["session"]);
      unset($save_back_list[$save_back_list_cnt]);
      --$save_back_list_cnt;

      $_SESSION['back_list'] = $save_back_list;
      $_SESSION['back_list_cnt'] = $save_back_list_cnt;
   }
}

/* ***********************************************************************
 * Function: PushHistory()
 *
 * @doc Save the current criteria into the history list ($back_list, 
 *      $back_list_cnt) in order to support the BASE back button.
 *     
 * @see PopHistory PrintBackButton
 *
 ************************************************************************/
function PushHistory()
{
   if ( $GLOBALS['debug_mode'] > 1 )
   {
      ErrorMessage("Saving state (into ".$_SESSION['back_list_cnt'].")");
   }

   /* save the current session without the $back_list into the history 
    *   - make a temporary copy of the $back_list
    *   - NULL-out the $back_list in $_SESSION (so that 
    *       the current session is serialized without these variables)
    *   - serialize the current session
    *   - fix-up the QUERY_STRING
    *       - make a new QUERY_STRING that includes the temporary QueryState variables
    *       - remove &back=1 from any QUERY_STRING
    *   - add the current session into the $back_list (history)
    */
   if (isset($_SESSION['back_list'])) {
       $tmp_back_list = $_SESSION['back_list'];
   } else {
       $tmp_back_list = '';
   }
   
   if (isset($_SESSION['back_list_cnt'])) {
       $tmp_back_list_cnt = $_SESSION['back_list_cnt'];
   } else {
       $tmp_back_list_cnt = '';
   }

   $_SESSION['back_list'] = NULL;
   $_SESSION['back_list_cnt'] = -1;

   $full_session = session_encode();
   $_SESSION['back_list'] = $tmp_back_list;
   $_SESSION['back_list_cnt'] = $tmp_back_list_cnt;

   $query_string = CleanVariable($_SERVER["QUERY_STRING"], VAR_PERIOD | VAR_DIGIT | VAR_PUNC | VAR_LETTER);
   if ( isset($_POST['caller']) ) $query_string .= "&amp;caller=".$_POST['caller'];
   if ( isset($_POST['num_result_rows']) ) $query_string .= "&amp;num_result_rows=".$_POST['num_result_rows'];
   if ( isset($_POST['sort_order']) ) $query_string .= "&amp;sort_order=".$_POST['sort_order'];
   if ( isset($_POST['current_view']) ) $query_string .= "&amp;current_view=".$_POST['current_view'];
   if ( isset($_POST['submit']) ) $query_string .= "&amp;submit=".$_POST['submit'];

   $query_string = ereg_replace("back=1&", "", CleanVariable($query_string, VAR_PERIOD | VAR_DIGIT | VAR_PUNC | VAR_LETTER));

   ++$_SESSION['back_list_cnt'];
   $_SESSION['back_list'][$_SESSION['back_list_cnt']] =  
          array ("SCRIPT_NAME"     => $_SERVER["SCRIPT_NAME"],
                 "QUERY_STRING" => $query_string, 
                 "session"      => $full_session );

  if ( $GLOBALS['debug_mode'] > 1 )
  {
      ErrorMessage("Insert session into slot #".$_SESSION['back_list_cnt']);

      echo "Back List (Cnt = ".$_SESSION['back_list_cnt'].") <PRE>";
      print_r($_SESSION['back_list']);
      echo "</PRE>";
  }
}

/* ***********************************************************************
 * Function: PrintBackButton()
 *
 * @doc Returns a string with the URL of the previously viewed
 *      page.  Clicking this link is equivalent to using the browser
 *      back-button, but all the associated BASE meta-information 
 *      propogates correctly.
 *     
 * @see PushHistory PopHistory
 *
 ************************************************************************/
function PrintBackButton()
{
   if ( $GLOBALS['maintain_history'] == 0 )
      return "&nbsp;";

   $criteria_num = $_SESSION['back_list_cnt'] - 1;

   if ( isset($_SESSION['back_list'][$criteria_num]["SCRIPT_NAME"]) )
     return "[&nbsp;<FONT><A HREF=\"".$_SESSION['back_list'][$criteria_num]["SCRIPT_NAME"].
            "?back=1&".
            $_SESSION['back_list'][$criteria_num]["QUERY_STRING"]."\">"._BACK."</A></FONT>&nbsp;]";
   else
     return "&nbsp;";
}
?>
