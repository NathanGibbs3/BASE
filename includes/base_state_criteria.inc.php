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
// Ensure the conf file has been loaded. Prevent direct access to this file.
defined('_BASE_INC') or die('Accessing this file directly is not allowed.');

include_once("$BASE_path/includes/base_state_common.inc.php");
include_once("$BASE_path/includes/base_state_citems.inc.php");

class CriteriaState {
	var $clear_criteria_name;
	var $clear_criteria_element;
	var $clear_url;
	var $clear_url_params;
	var $criteria;

	function __construct( $url, $params = '' ){ // PHP 5+ constructor Shim.
		// Class/Method agnostic shim code.
		$SCname = get_class();
		if( method_exists($this, $SCname) ){
			$SCargs = func_get_args();
			call_user_func_array(array($this, $SCname), $SCargs);
		}else{
			// @codeCoverageIgnoreStart
			// Should never execute.
			trigger_error( // Will need to add this message to the TD.
				"Class: $SCname No Legacy Constructor.\n",
				E_USER_ERROR
			);
			// @codeCoverageIgnoreEnd
		}
	}
	function CriteriaState( $url, $params = '' ){ // PHP 4x constructor.
		$this->clear_url = $url;
		$this->clear_url_params = $params;
		/* XXX-SEC */
		GLOBAL $db, $debug_mode;
		$tdb =& $db;
		$obj =& $this;
		// Criteria Creation
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
		// For new criteria, add a call to the appropriate constructor here,
		// and implement the appropriate class in base_state_citems.inc.php
	}

	function InitState(){
		$valid_criteria_list = array_keys($this->criteria);
		foreach( $valid_criteria_list as $cname ){
			$this->criteria[$cname]->Init();
		}
	}

	function ReadState(){
		GLOBAL $maintain_history;
		// If the BACK button was clicked, shuffle the appropriate criteria
		// variables from the $back_list (history) array into the current
		// session ($_SESSION).
		if ( $maintain_history == 1 && ImportHTTPVar("back", VAR_DIGIT) == 1 ){
			PopHistory();
		}
		// Import, update and sanitize all persistant criteria variables.
		$valid_criteria_list = array_keys($this->criteria);
		foreach ( $valid_criteria_list as $cname ){
			$this->criteria[$cname]->Import();
			$this->criteria[$cname]->Sanitize();
		}
		// Check whether criteria elements need to be cleared.
		$this->clear_criteria_name = ImportHTTPVar(
			'clear_criteria', '', array_keys($this->criteria)
		);
		$this->clear_criteria_element = ImportHTTPVar(
			'clear_criteria_element', '', array_keys($this->criteria)
		);
		if ( $this->clear_criteria_name != "" ){
			$this->ClearCriteriaStateElement(
				$this->clear_criteria_name,
				$this->clear_criteria_element
			);
		}
		// Save the current criteria into $back_list (history).
		if ( $maintain_history == 1 ){
			PushHistory();
		}
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
function PopHistory(){
	GLOBAL $maintain_history;
	if( $maintain_history == 1 && is_array($_SESSION) && session_id() != '' ){
		// We have a session, so proceed.
		if( $_SESSION['back_list_cnt'] >= 0 ){
			// Remove previous page from history.
			unset($_SESSION['back_list'][$_SESSION['back_list_cnt']]);
			// Backup history from session.
			$save_back_list = $_SESSION['back_list'];
			$save_back_list_cnt = $_SESSION['back_list_cnt'] - 1;
			session_unset(); // Destroy current session.
			kml('Hist: Pop Entry slot #' . $save_back_list_cnt, 1);
			// Restore session from history.
			session_decode($save_back_list[$save_back_list_cnt]["session"]);
			// Remove current page from history & decremement sistory stack.
			unset($save_back_list[$save_back_list_cnt]);
			--$save_back_list_cnt;
			// Restore history to session.
			$_SESSION['back_list'] = $save_back_list;
			$_SESSION['back_list_cnt'] = $save_back_list_cnt;
		}
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
function PushHistory(){
	GLOBAL $maintain_history;
	if( $maintain_history == 1 && is_array($_SESSION) && session_id() != '' ){
		// We have a session, so proceed.
		kml('Hist: Get Session slot #' . $_SESSION['back_list_cnt'], 1);
		// Save the current session without the $back_list into the history.
		// - Backup history from session. $back_list & $back_list_cnt.
		// - Remove history from session.
		// - Serialize current session is serialized without history.
		// - fix-up the QUERY_STRING.
		//   - make a new QUERY_STRING that includes QueryState variables.
		//   - remove &back=1 from QUERY_STRING
		// - Add current session into history.
		$tmp_back_list = '';
		$tmp_back_list_cnt = '';
		$query_string = '';
		if( isset($_SESSION['back_list']) ){ // Backup history from session.
			$tmp_back_list = $_SESSION['back_list'];
		}
		if( isset($_SESSION['back_list_cnt']) ){
			$tmp_back_list_cnt = $_SESSION['back_list_cnt'];
		}
		unset($_SESSION['back_list']); // Clear history from session
		unset($_SESSION['back_list_cnt']);
		$full_session = session_encode(); // Serialize session without history.
		$_SESSION['back_list'] = $tmp_back_list; // Restore history to session.
		$_SESSION['back_list_cnt'] = $tmp_back_list_cnt;
		if( isset($_SERVER['QUERY_STRING']) ){ // Query String fix ups.
			$query_string = CleanVariable(
				$_SERVER['QUERY_STRING'], VAR_ALPHA | VAR_PUNC
			);
			$QSV = array(
				'caller', 'num_result_rows', 'sort_order', 'current_view',
				'submit'
			);
			foreach( $QSV as $val ){ // Process QueryState variables.
				if( isset($_POST[$val]) ){
					$query_string .= "&amp;$val=" . $_POST[$val];
				}
			}
			$query_string = preg_replace( // remove &back=1 from QUERY_STRING.
				"/back=1&/", '', CleanVariable(
					$query_string, VAR_ALPHA | VAR_PUNC
				)
			);
		}
		$CTP = true;
		$rsn = $_SERVER['SCRIPT_NAME'];
		if( $_SESSION['back_list_cnt'] > 0 ){ // Issue #206
			$tsn = $tmp_back_list[$tmp_back_list_cnt]['SCRIPT_NAME'];
			$tqs = $tmp_back_list[$tmp_back_list_cnt]['QUERY_STRING'];
			$tsc = $tmp_back_list[$tmp_back_list_cnt]['session'];
			if(
				$tsn == $rsn && $tqs == $query_string && $tsc == $full_session
			){
				$CTP = false;
				kml(
					'Hist: Dup Entry Put Denied slot #'
					. $_SESSION['back_list_cnt'], 1
				);
			}
		}
		if( $CTP ){ // New history Entry. Add current session into history.
			++$_SESSION['back_list_cnt'];
			$_SESSION['back_list'][$_SESSION['back_list_cnt']] = array (
				'SCRIPT_NAME' => $rsn,
				'QUERY_STRING' => $query_string,
				'session' => $full_session
			);
			kml('Hist: Put Entry slot #' . $_SESSION['back_list_cnt'], 1);
		}
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
function PrintBackButton(){
	GLOBAL $maintain_history;
	$Ret = '';
	if( $maintain_history == 1 && is_array($_SESSION) && session_id() != '' ){
		// We have a session, so proceed.
		$criteria_num = $_SESSION['back_list_cnt'] - 1;
		if ( isset($_SESSION['back_list'][$criteria_num]["SCRIPT_NAME"]) ){
			$Ret = "<a class='menuitem' href=\"".
			$_SESSION['back_list'][$criteria_num]["SCRIPT_NAME"].
			"?back=1&".
			$_SESSION['back_list'][$criteria_num]["QUERY_STRING"].
			"\">"._BACK."</a>";
		}
	}
	return $Ret;
}

?>
