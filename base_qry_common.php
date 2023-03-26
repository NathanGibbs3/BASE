<?php
// Basic Analysis and Security Engine (BASE)
// Copyright (C) 2019-2023 Nathan Gibbs
// Copyright (C) 2004 BASE Project Team
// Copyright (C) 2000 Carnegie Mellon University
//
//   For license info: See the file 'base_main.php'
//
//       Project Lead: Nathan Gibbs
// Built upon work by: Kevin Johnson & the BASE Project Team
//                     Roman Danyliw <rdd@cert.org>, <roman@danyliw.com>
//
//            Purpose: support routines for processing criteria
//
//          Author(s): Nathan Gibbs
//                     Kevin Johnson

defined( '_BASE_INC' ) or die( 'Accessing this file directly is not allowed.' );
include_once("$BASE_path/includes/base_signature.inc.php");

function PrintCriteriaState(){
	GLOBAL $layer4, $new, $submit, $sort_order, $num_result_rows,
	$current_view, $caller, $action, $action_arg, $debug_mode;
	if ( $debug_mode >= 2 ){
      echo "<PRE>";
      echo "<B>"._SENSOR.":</B> ".$_SESSION['sensor']."<BR>\n".
           "<B>AG:</B> ".$_SESSION['ag']."<BR>\n".
           "<B>"._QCSIG."</B>\n";
		if ( isset($_SESSION['sig']) ){
			print_r($_SESSION['sig']);
		}
      echo "<BR><B>time struct (".$_SESSION['time_cnt']."):</B><BR>";
      print_r($_SESSION['time']);
      echo "<BR><B>"._QCIPADDR." (".$_SESSION['ip_addr_cnt']."):</B><BR>";
      print_r($_SESSION['ip_addr']);
      echo "<BR><B>"._QCIPFIELDS." (".$_SESSION['ip_field_cnt']."):</B><BR>";
      print_r($_SESSION['ip_field']);
      echo "<BR><B>"._QCTCPPORTS." (".$_SESSION['tcp_port_cnt']."):</B><BR>";
      print_r($_SESSION['tcp_port']);
      echo "<BR><B>"._QCTCPFLAGS."</B><BR>";
      print_r($_SESSION['tcp_flags']);
      echo "<BR><B>"._QCTCPFIELD." (".$_SESSION['tcp_field_cnt']."):</B><BR>";
      print_r($_SESSION['tcp_field']);
      echo "<BR><B>"._QCUDPPORTS." (".$_SESSION['udp_port_cnt']."):</B><BR>";
      print_r($_SESSION['udp_port']);
      echo "<BR><B>"._QCUDPFIELDS." (".$_SESSION['udp_field_cnt']."):</B><BR>";
      print_r($_SESSION['udp_field']);
      echo "<BR><B>"._QCICMPFIELDS." (".$_SESSION['icmp_field_cnt']."):</B><BR>";
      print_r($_SESSION['icmp_field']);
      echo "<BR><B>RawIP field (".$_SESSION['rawip_field_cnt']."):</B><BR>";
      print_r($_SESSION['rawip_field']);
      echo "<BR><B>"._QCDATA." (".$_SESSION['data_cnt']."):</B><BR>";
      print_r($_SESSION['data']);
      echo "</PRE>";
   }
	if ( $debug_mode >= 1 ){
      echo "<PRE>
            <B>new:</B> '$new'   
            <B>submit:</B> '$submit'
            <B>sort_order:</B> '$sort_order'
            <B>num_result_rows:</B> '$num_result_rows'  <B>current_view:</B> '$current_view'
            <B>layer4:</B> '$layer4'  <B>caller:</B> '$caller'
            <B>action:</B> '$action'  <B>action_arg:</B> '$action_arg'
            </PRE>";
	}
}

function FieldRows2sql($field, $cnt, &$s_sql)
{
  $tmp2 = "";

  if (!is_array($field))
	$field = array();

  for ( $i = 0; $i < $cnt; $i++ )
  {
      $tmp = "";
      if ( $field[$i][3] != "" && $field[$i][1] != " ")
      {
         $tmp = $field[$i][0]." ".$field[$i][1]." ".$field[$i][2]." '".
                $field[$i][3]."' ".$field[$i][4]." ".$field[$i][5];
      }
      else
      {
         if ( $field[$i][3] != "" && $field[$i][1] == " ")
            ErrorMessage("<B>"._QCERRCRITWARN."</B> "._QCERRVALUE." '".$field[$i][3]."' "._QCERRSPECFIELD);
         if ( ($field[$i][1] != " " && $field[$i][1] != "") && $field[$i][3] == "" )
            ErrorMessage("<B>"._QCERRCRITWARN."</B> "._QCERRFIELD." '".$field[$i][1]."' "._QCERRSPECVALUE);
      }
      $tmp2 = $tmp2.$tmp;

      if ( $i > 0 && $field[$i-1][5] == ' ' )
         ErrorMessage("<B>"._QCERRCRITWARN."</B> "._QCERRBOOLEAN);
  }

  if ( $tmp2 != "" )
  {
     $s_sql = $s_sql." AND ( ".$tmp2." )";
     return 1;
  }

  return 0;
}

// Returns a two digit string representing part of a time format.
function FormatTimeDigit( $time_digit ){
	$Ret = '00'; // Default Return, if we are passed non-numeric input.
	$tmp = trim($time_digit);
	if ( is_numeric($tmp) ){
		if ( strlen($tmp) == 1 ){
			$tmp = "0$tmp";
		}
		$Ret = $tmp;
	}
	return $Ret;
}

function addSQLItem(&$sstring, $what_to_add)
{
   $sstring = (strlen($sstring) == 0 ) ? "($what_to_add" : "$sstring AND $what_to_add";
}

// Adds valid date/time selection SQL to the 3rd param.
// Returns 1 on SQL added.
// Returns 0 on no SQL added.
function DateTimeRows2sql( $field, $cnt, &$s_sql ){
	GLOBAL $db, $debug_mode;
	// $field is an array containing 2 arrays.
	// Each has 10 elements describing time criteria.
	// The first one is sarting, the second ending criteria.
	// The is based on TimeCriteria class as defined in:
	// ./includes/base_state_citems.inc.php
	// However $field is not necessarily a TimeCriteria class.
	//	[][0]	Empty or (
	//	[][1]	Logical Operators: =, !=, <, <=, >, >=
	//			"" or " "	Empty or space on empty.
	//	2-7		""			Empty on empty.
	//	[][2]	month		[][6]	minute
	//	[][3]	day			[][7]	second
	//	[][4]	year		[][8]	Empty, (, or )
	//	[][5]	hour
	//	[][9]	AND, OR
	//			SQL Logical Operator in start array when second array is used.
	$Ret = 0; // Default Return Value.
	if ( is_array($field) && is_numeric($cnt) ){ // Input validation.
		// Setup
		$tmp2 = '';
		$allempty = false;
		$minsec = array( // Shim for ambiguous search criteria.
			'>=' => '00', '<=' => '59', '>' => '00', '<' => '00', '!=' => '00'
		);
		$EPfx = '<b>'._QCERRCRITWARN.'</b> '; // Error Message Prefix
		for ( $i = 0; $i < $cnt; $i++ ){
			$tmp = '';
			if (
				isset($field[$i]) && is_array($field[$i])
				&& count($field[$i]) == 10
			){ // Data Structure Validation.
				// Set & sanitize Index Values
				$fstart = CleanVariable($field[$i][0], VAR_OPAREN, array (''));
				$op = CleanVariable(
					$field[$i][1], '',
					array('=', '!=', '<', '<=', '>', '>=')
				);
				$month = CleanVariable($field[$i][2], VAR_DIGIT);
				$day = CleanVariable($field[$i][3], VAR_DIGIT);
				$year = CleanVariable($field[$i][4], VAR_DIGIT);
				$hour = CleanVariable($field[$i][5], VAR_DIGIT);
				$minute = CleanVariable($field[$i][6], VAR_DIGIT);
				$second = CleanVariable($field[$i][7], VAR_DIGIT);
				$fstop = CleanVariable(
					$field[$i][8], VAR_OPAREN | VAR_CPAREN, array ('')
				);
				$SQLOP = CleanVariable($field[$i][9], '', array('AND', 'OR'));
				// Catch error conditions.
				// This could be a place to stop Issue #126 input from
				// turning into invalid SQL.
//				if ( $fstart != '' || $fstop != '' )
//				if ( $fstart != '(' || ( $fstop != '(' && $fstop != ')' )
//				){ // Invalid Criteria
//					ErrorMessage($EPfx._ERRCRITELEM);
//					break;
//				}
				if (
					$cnt > 1 && $i % 2 == 0 && $SQLOP == '' && is_numeric($year)
				){ // Multi. Criteria with no SQL Op.
					ErrorMessage($EPfx._QCERRDATEBOOL);
					break;
				}
				if ( $op == '' && (
					is_numeric($month) || is_numeric($day) || is_numeric($year)
				) ){ // No logical op error.
					ErrorMessage(
						$EPfx._QCERRDATETIME." '".
						implode ('-',array($year, $month, $day)) .' '.
						implode (':',array($hour, $minute, $second))
						."' "._QCERROPERSELECT
					);
					break;
				}
				if ( $op != '' ){
					if ( !is_numeric($year) && !is_numeric($hour)
					){ // Not date or time.
						ErrorMessage(
							$EPfx._QCERROPER." '$op' "._QCERRDATEVALUE
						);
						break;
					}
					if ( !is_numeric($year) && is_numeric($hour)
					){ // Invlaid Hour
						ErrorMessage($EPfx._QCERRINVHOUR);
						break;
					}
					$t = '';
					//Build the SQL string when all ops but = are used.
					if ( $op != '=' ){
						if ( is_numeric($year) ){ // Year set.
							// Create the date string. YYYY-MM-DD
							// Catch 2 digit years, default to current century.
							if ( strlen($year) <= 2 ){
								$year = substr(date("Y"),0,2).
								FormatTimeDigit($year);
							}
							if ( is_numeric($month) ){ // Month set.
								$month = FormatTimeDigit($month);
							}else{ // Month not set, default to January.
								$month = '01';
							}
							if ( is_numeric($day) ){ // Day set.
								$day = FormatTimeDigit($day);
							}else{ // Day not set.
								if ( $i == 0 ){ // Start criteria
									$day = '01'; // Default to 1st.
								}else{ // Assume all months have 31 days.
									$day = '31';
									while (
										!checkdate( $month, $day, $year )
									){ // Bring it into reality.
										--$day;
									}
								}
							}
							$t = implode ('-',array($year, $month, $day));
						}
						// Time.
						$t .= ' ';
						if ( is_numeric($hour) ){ // Hour set.
							$hour = FormatTimeDigit($hour);
							if ( is_numeric($minute) ){ // Minute set.
								$minute = FormatTimeDigit($minute);
							}else{ // Minute not set, set defaults.
								$minute = $minsec[$op];
							}
							if ( is_numeric($second) ){ // Second set.
								$second = FormatTimeDigit($second);
							}else{ // Second not set, set defaults.
								$second = $minsec[$op];
							}
							$t .= implode (
								':',array($hour, $minute, $second)
							);
						}else{ // Hour not set, shim ambiguous search criteria.
							if(
								( $op == ">" || $op == "<=" ) &&
								is_numeric($year)
							){ // Fixup for > or <= operators, add an extra day.
								$t .= '23:59:59';
							}else{ // Default to start of day.
								$t .= '00:00:00';
							}
						}
						if( $db->DB_type == 'oci8' ){ // Oracle DB.
							// @codeCoverageIgnoreStart
							// We have no way of testing Oracle functionality.
							$tmp = " timestamp " . $op .
							"to_date( '$t', 'YYYY-MM-DD HH24MISS' )";
							// @codeCoverageIgnoreEnd
						}else{
							if ( count($field) > 1 ){
								// Better fix for bug #1199128
								$empty_count = 0; // Empty value count.
								reset($field[$i]);
								// Count empty values in array $field[$i].
								foreach ( $field[$i] as $val ){
									if ( empty($val) ){
										$empty_count += 1;
									}
								}
								// Count all values in array $field[$i].
								$array_count = count( $field[1] );
								// If count of empty valuess > (impossible) or
								// = (possible) count of all values, then all
								// are empty.
								if ( $empty_count >= $array_count ){
									$allempty = true;
								}
								if ( $allempty ){ // Empty, dont process line.
									continue;
								}else{ // Process line.
									$tmp = " timestamp " . $op . "'$t'";
								}
							}else{ // We have one criteria line, process it.
								$tmp = " timestamp " . $op . "'$t'";
							}
						}
					}else{ // Build SQL string when = operator is used.
						// NPG Performance wise, this query takes more time.
						// Consider rewriting this at some point.
						// Date.
						if ( is_numeric($year) ){ // Year set.
							addSQLItem( $tmp,
								$db->baseSQL_YEAR("timestamp", "=", $year)
							);
						}
						if ( is_numeric($month) ){ // Month set.
							addSQLItem( $tmp,
								$db->baseSQL_MONTH("timestamp", "=", $month)
							);
						}
						if ( is_numeric($day) ){ // Day set.
							addSQLItem( $tmp,
								$db->baseSQL_DAY("timestamp", "=", $day)
							);
						}
						// Time.
						if ( is_numeric($hour) ){ // Hour set.
							addSQLItem( $tmp,
								$db->baseSQL_HOUR("timestamp", "=", $hour)
							);
						}
						if ( is_numeric($minute) ){ // Minute set.
							addSQLItem( $tmp,
								$db->baseSQL_MINUTE("timestamp", "=", $minute)
							);
						}
						if ( is_numeric($second) ){ // Second set.
							addSQLItem( $tmp,
								$db->baseSQL_SECOND("timestamp", "=", $second)
							);
						}
						if ( $tmp == '' ){ // Neither date or time.
							ErrorMessage(
								$EPfx._QCERROPER." '$op' "._QCERRDATECRIT
							);
						}else{
							$tmp .= ')';
						}
					}
				}
				if ( $tmp != '' ){
					$tmp2 .= $field[$i][0] . $tmp . $field[$i][8] . ' ';
					if ( $i != $cnt -1 ){ // Catch Issue #132
						$tmp2 .= $SQLOP;
					}
				}
			}
		}
		if ( $tmp2 != '' ){
			$s_sql .= ' AND ('.$tmp2.') ';
			if ( $debug_mode > 0 ){
				var_dump($field);
				ErrorMessage( __FUNCTION__ . "() Returned SQL: $s_sql");
			}
			$Ret = 1;
		}
	}
	return $Ret;
}

function FormatPayload($payload_str, $data_encode)
/* Accepts a payload string and decides whether any conversion is necessary
   to create a sql call into the DB.  Currently we only are concerned with
   hex <=> ascii. 
 */
{
  /* if the source is hex strip out any spaces and \n */
  if ( $data_encode == "hex" )
  {
     $payload_str = str_replace("\n", "", $payload_str);
     $payload_str = str_replace(" ", "", $payload_str);
  }

  /* If both the source type and conversion type are the same OR
        no conversion type is specified THEN return the plain string */
  if ( ($data_encode[0] == $data_encode[1]) ||
       $data_encode[1] == " " )
       return $payload_str;
  else
  {
     $tmp = "";

     /* hex => ascii */
     if ( $data_encode[0] == "hex" && $data_encode[1] == "ascii" )
        for ( $i = 0; $i < strlen($payload_str); $i += 2)
        {
            $t = hexdec($payload_str[$i].$payload_str[$i+1]);
                 
            if ( $t > 32 && $t < ord("z"))
                 $tmp = $tmp.chr($t);
            else
                 $tmp = $tmp.'.';
        }

     /* ascii => hex */
     else if ( $data_encode[0] == "ascii" && $data_encode[1] == "hex" )
        for ( $i = 0; $i < strlen($payload_str); $i++ )
            $tmp = $tmp.dechex(ord($payload_str[$i]));

     return strtoupper($tmp);
  }

  return "";    /* should be unreachable */
}

function DataRows2sql($field, $cnt, $data_encode, &$s_sql)
{
  $tmp2 = "";
  for ( $i = 0; $i < $cnt; $i++ )
  {
      $tmp = "";
      if ( $field[$i][2] != "" && $field[$i][1] != " ")
      {
         $tmp = $field[$i][0]." data_payload ".$field[$i][1]." '%".FormatPayload($field[$i][2], $data_encode).
                "%' ".$field[$i][3]."".$field[$i][4]." ".$field[$i][5];
      }
      else
      {
         if ( $field[$i][2] != "" && $field[$i][1] == " ")
            ErrorMessage("<B>"._QCERRCRITWARN."</B> "._QCERRPAYLOAD." '".$field[$i][2]."' "._QCERRPAYCRITOPER);
         if ( ($field[$i][1] != " " && $field[$i][1] != "") && $field[$i][2] == "" )
            ErrorMessage("<B>"._QCERRCRITWARN."</B> "._QCERROPER." '".$field[$i][1]."' "._QCERRPAYCRITVALUE);
      }
      $tmp2 = $tmp2.$tmp;

      if ( $i > 0 && $field[$i-1][4] == ' ' )
         ErrorMessage("<B>"._QCERRCRITWARN."</B> "._QCERRPAYBOOL);
  }

  if ( $tmp2 != "" )
  {
     $s_sql = $s_sql." AND ( ".$tmp2." )";
     return 1;
  }

  return 0;
}
function PrintCriteria( $caller ){
	GLOBAL $db, $cs, $last_num_alerts, $save_criteria, $debug_mode, $UIL;
	if ( !is_object($cs) ){ // Issue #5
		ErrorMessage('Invalid CriteriaState Object.', 0,1);
	}else{
		if ( $debug_mode > 0 ){
			ErrorMessage(__FUNCTION__." CALLER: ($caller)", 'black', 1);
		}
		if ( class_exists('UILang') ){ // Issue 11 backport shim.
			$CPLast = $UIL->CWA['Last'];
			$CPAlert = $UIL->CWA['Alert'];
		}else{
			$CPLast = _LAST;
			$CPAlert = _ALERT;
		}
		// Generate the Criteria entered into a human readable form.
		// Search criteria Display
		// Table Title needs to be translated.
		$CS = 'width: 35%;'; // Common Style Hack
		$save_criteria =
		FramedBoxHeader('Search Criteria','black',0,2,'',30).
		NLI("<td class='metatitle' style='$CS'>"._QCMETACRIT.'</td>',4).
		NLI('<td>',4);
		// If printing any of the LAST-X stats then ignore all other criteria.
		if (
			$caller == 'last_tcp' || $caller == 'last_udp'
			|| $caller == 'last_icmp' || $caller == 'last_any'
		){
			$save_criteria .= "&nbsp;&nbsp;$CPLast $last_num_alerts ";
			if ( $caller == 'last_tcp' ){
				$save_criteria .= 'TCP ';
			}elseif ( $caller == 'last_udp' ){
				$save_criteria .= 'UDP ';
			}elseif ( $caller == 'last_icmp' ){
				$save_criteria .= 'ICMP ';
			}
			$save_criteria .= $CPAlert.'&nbsp;&nbsp;'.
			FramedBoxFooter(1,2);
			print $save_criteria;
			return;
		}
		// Meta Criteria
		$tmp_len = strlen($save_criteria);
		$save_criteria .= $cs->criteria['sensor']->Description('');
		$save_criteria .= $cs->criteria['sig']->Description('');
		$save_criteria .= $cs->criteria['sig_class']->Description('');
		$save_criteria .= $cs->criteria['sig_priority']->Description('');
		$save_criteria .= $cs->criteria['ag']->Description('');
		$save_criteria .= $cs->criteria['time']->Description('');
		// Common Text
		$APH = '<i>&nbsp;&nbsp;'._ANY.'&nbsp;&nbsp;</i>';
		$NTR = '</td>'.NLI('</tr><tr>',3); // New Table Row.
		if ( $tmp_len == strlen($save_criteria) ){
			$save_criteria .= $APH;
		}
		$save_criteria .= $NTR;
		// IP Criteria
		$save_criteria .= NLI(
			"<td class='iptitle' style='$CS'>"._QCIPCRIT.'</td>', 4
		).NLI('<td>',3);
		if (
			!$cs->criteria['ip_addr']->isEmpty() ||
			!$cs->criteria['ip_field']->isEmpty()
		){
			$save_criteria .= $cs->criteria['ip_addr']->Description('');
			$save_criteria .= $cs->criteria['ip_field']->Description('');
		}else{
			$save_criteria .= $APH;
		}
		$save_criteria .= $NTR;
		// Layer 4 Criteria
		$save_criteria .= NLI("<td class='layer4title' style='$CS'>",4);
		$save_criteria .= $cs->criteria['layer4']->Description('');
		$save_criteria .= '</td>'.
		NLI('<td>',4);
		if ( $cs->criteria['layer4']->Get() == 'TCP' ){
			if (
				!$cs->criteria['tcp_port']->isEmpty()
				|| !$cs->criteria['tcp_flags']->isEmpty()
				|| !$cs->criteria['tcp_field']->isEmpty()
			){
				$save_criteria .= $cs->criteria['tcp_port']->Description('');
				$save_criteria .= $cs->criteria['tcp_flags']->Description('');
				$save_criteria .= $cs->criteria['tcp_field']->Description('');
			}else{
				$save_criteria .= $APH;
			}
		}elseif ( $cs->criteria['layer4']->Get() == 'UDP' ){
			if (
				!$cs->criteria['udp_port']->isEmpty()
				|| !$cs->criteria['udp_field']->isEmpty()
			){
				$save_criteria .= $cs->criteria['udp_port']->Description('');
				$save_criteria .= $cs->criteria['udp_field']->Description('');
			}else{
				$save_criteria .= $APH;
			}
		}elseif ( $cs->criteria['layer4']->Get() == 'ICMP' ){
			if ( !$cs->criteria['icmp_field']->isEmpty() ) {
				$save_criteria .= $cs->criteria['icmp_field']->Description('');
			}else{
				$save_criteria .= $APH;
			}
		}elseif ( $cs->criteria['layer4']->Get() == 'RawIP' ){
			if ( !$cs->criteria['rawip_field']->isEmpty() ) {
				$save_criteria .= $cs->criteria['rawip_field']->Description('');
			}else{
				$save_criteria .= $APH;
			}
		}else{
			$save_criteria .= '<i>&nbsp;&nbsp;'._NONE.'&nbsp;&nbsp;</i>';
		}
		$save_criteria .= $NTR;
		// Payload Criteria
		$save_criteria .= NLI(
			"<td class='payloadtitle' style='$CS'>"._QCPAYCRIT.'</td>', 4
		).NLI('<td>',4);
		if ( !$cs->criteria['data']->isEmpty() ){
			$save_criteria .= $cs->criteria['data']->Description('');
		}else{
			$save_criteria .= $APH;
		}
		$save_criteria .= FramedBoxFooter(1,2);
		if ( class_exists('UILang') ){ // Issue 11 backport shim.
			$UIL->SetUILocale();
		}else{
			if ( !setlocale (LC_TIME, _LOCALESTR1) ){
				if ( !setlocale (LC_TIME, _LOCALESTR2) ){
					setlocale (LC_TIME, _LOCALESTR3);
				}
			}
		}
		$save_criteria = NLIO(
			'<b>'._QUERIED.'</b>: '.strftime(_STRFTIMEFORMAT),2
			).
		$save_criteria;
		print $save_criteria;
	}
}

/********************************************************************************************/
function ProcessCriteria(){
	GLOBAL $db, $join_sql, $where_sql, $criteria_sql, $sql, $debug_mode,
	$caller, $DBtype;
  /* the JOIN criteria */
  $ip_join_sql  = " LEFT JOIN iphdr ON acid_event.sid=iphdr.sid AND acid_event.cid=iphdr.cid ";
  $tcp_join_sql = " LEFT JOIN tcphdr ON acid_event.sid=tcphdr.sid AND acid_event.cid=tcphdr.cid ";
  $udp_join_sql = " LEFT JOIN udphdr ON acid_event.sid=udphdr.sid AND acid_event.cid=udphdr.cid ";
  $icmp_join_sql= " LEFT JOIN icmphdr ON acid_event.sid=icmphdr.sid AND acid_event.cid=icmphdr.cid ";
  $rawip_join_sql= " LEFT JOIN iphdr ON acid_event.sid=iphdr.sid AND acid_event.cid=iphdr.cid ";
  $data_join_sql= " LEFT JOIN data ON acid_event.sid=data.sid AND acid_event.cid=data.cid ";
  $ag_join_sql  = " LEFT JOIN acid_ag_alert ON acid_event.sid=acid_ag_alert.ag_sid AND acid_event.cid=acid_ag_alert.ag_cid "; 

  $sig_join_sql = "";

  $sql = "SELECT acid_event.sid, acid_event.cid, signature, timestamp, ".
         "acid_event.ip_src, acid_event.ip_dst, acid_event.ip_proto FROM acid_event";
 
  // This needs to be examined!!! -- Kevin
  $where_sql = " WHERE ";
  //$where_sql = "";
  // $criteria_sql = " acid_event.sid > 0";
  $criteria_sql = " 1 = 1 ";
  $join_sql = "";

  /* ********************** Meta Criteria ******************************************** */

  /* XXX-SEC */
  GLOBAL $cs;

	if (is_object($cs)){ // Issue #5
  $sig = $cs->criteria['sig']->criteria;
  $sig_type = $cs->criteria['sig']->sig_type;
  $sig_class = $cs->criteria['sig_class']->criteria;
  $sig_priority = $cs->criteria['sig_priority']->criteria;
  $ag = $cs->criteria['ag']->criteria;
  $sensor = $cs->criteria['sensor']->criteria;
  $time = $cs->criteria['time']->criteria;
  $time_cnt = $cs->criteria['time']->GetFormItemCnt();
  $ip_addr = $cs->criteria['ip_addr']->criteria;
  $ip_addr_cnt = $cs->criteria['ip_addr']->GetFormItemCnt();
  $layer4 = $cs->criteria['layer4']->criteria;
  $ip_field = $cs->criteria['ip_field']->criteria;
  $ip_field_cnt = $cs->criteria['ip_field']->GetFormItemCnt();
  $tcp_port = $cs->criteria['tcp_port']->criteria;
  $tcp_port_cnt = $cs->criteria['tcp_port']->GetFormItemCnt();
  $tcp_flags = $cs->criteria['tcp_flags']->criteria;
  $tcp_field = $cs->criteria['tcp_field']->criteria;
  $tcp_field_cnt = $cs->criteria['tcp_field']->GetFormItemCnt();
  $udp_port = $cs->criteria['udp_port']->criteria;
  $udp_port_cnt = $cs->criteria['udp_port']->GetFormItemCnt();
  $udp_field = $cs->criteria['udp_field']->criteria;
  $udp_field_cnt = $cs->criteria['udp_field']->GetFormItemCnt();
  $icmp_field = $cs->criteria['icmp_field']->criteria;
  $icmp_field_cnt= $cs->criteria['icmp_field']->GetFormItemCnt();
  $rawip_field = $cs->criteria['rawip_field']->criteria;
  $rawip_field_cnt= $cs->criteria['rawip_field']->GetFormItemCnt();
  $data = $cs->criteria['data']->criteria;
  $data_cnt = $cs->criteria['data']->GetFormItemCnt();
  $data_encode = $cs->criteria['data']->data_encode;
	}
	$tmp_meta = "";
	// Sensor
	if ( isset($sensor) && $sensor != "" && $sensor != " " ){
     $tmp_meta = $tmp_meta." AND acid_event.sid='".$sensor."'";
	}else{
		if (is_object($cs)){ // Issue #5
     $cs->criteria['sensor']->Set("");
		}
	}
	// Alert Group
	if ( isset($ag) && $ag != "" && $ag != " " ){
     $tmp_meta = $tmp_meta." AND ag_id =".$ag;
     $join_sql = $join_sql.$ag_join_sql;
	}else{
		if (is_object($cs)){ // Issue #5
     $cs->criteria['ag']->Set("");
		}
	}
	// Signature
	// xxx jl
	if ($debug_mode > 0 ){
		if ( isset($_SESSION['sig']) ){
			print "<BR>\n\$_SESSION['sig'] = <PRE>\n";
			print_r($_SESSION['sig']);
			print "</PRE>\n";
		}
    print "\$sig[0] = \"" . $sig[0] . "\"<BR>\n";
    print "\$sig[1] = \"" . $sig[1] . "\"<BR>\n";
    print "\$sig[2] = \"" . $sig[2] . "\"<BR>\n";
    print "\$sig[3] = \"" . $sig[3] . "\"<BR>\n";
    print "<BR>\n";
	}
	if (
       (isset($sig[0]) && $sig[0] != " " && $sig[0] != "") && 
       (
         (isset($sig[1]) && $sig[1] != "" && $sig[1] != NULL) ||
         (isset($sig[3]) && $sig[3] != "" && $sig[3] != NULL)
       )
     )
  {
     $sig_neg = "";
     if ( $sig[2] == "!=" )
        $sig_neg = " NOT ";

     if ( $db->baseGetDBversion() >= 100 )
     {
        /* If given a sig ID instead of a name */
        if ( ($sig_type == 1) && ($sig[0] == "=") )
        {
           $tmp_meta = $tmp_meta." AND (signature='".$sig[1]."') ";
        }
        else
        {
           if (isset($sig[3]) && !empty($sig[3]) && $sig[3] != "" &&  $sig[3] != " " && $sig[3] != "NULL" && $sig[3] != "null" && $sig[3] != NULL)
           {
             $sig_name = $sig[3];
           }
           else
           {
             $sig_name = $sig[1];
           }

           // xxx jl
           if ($debug_mode > 0)
           {
             print "\$sig[1]   = \"$sig[1]\"<BR>\n";
             print "\$sig[3]   = \"$sig[3]\"<BR>\n";
             print "\$sig_name = \"$sig_name\"<BR><BR>\n\n";
           }

           if ( $sig[0] == "=" )
           {
              if ($db->DB_type != "mssql")
              {
                $tmp_meta = $tmp_meta." AND ".$sig_neg." (sig_name='". $sig_name . "') ";
              }
              else 
              {
                $tmp_meta = $tmp_meta." AND ".$sig_neg." (sig_name LIKE '".MssqlKludgeValue($sig_name) . "') ";
              }
           }
           else if ($sig[0] == "LIKE" )
           {
              $tmp_meta = $tmp_meta." AND ".$sig_neg." (sig_name LIKE '%" . $sig_name . "%') ";
           }
        }
     }
     else
     {
       if ( $sig[0] == "=" )
         $tmp_meta = $tmp_meta." AND ".$sig_neg." (signature='" . $sig_name . "') ";
       else if ($sig[0] == "LIKE" )
         $tmp_meta = $tmp_meta." AND ".$sig_neg." (signature LIKE '%" . $sig_name . "%') ";
     }
	}else{
		if (is_object($cs)){ // Issue #5
     $cs->criteria['sig']->Set("");
		}
	}
	// Signature Classification
	if (isset($sig_class)){
		if ( $sig_class != " " && $sig_class != "" && $sig_class != "0" ){
     $tmp_meta = $tmp_meta." AND sig_class_id = '".$sig_class."'";
		}else if ($sig_class == "0"){
     $tmp_meta = $tmp_meta." AND (sig_class_id is null OR sig_class_id = '0')";
		}else{
			if (is_object($cs)){ // Issue #5
     $cs->criteria['sig_class']->Set("");
			}
		}
	}
	// Signature Priority
	if (
		isset($sig_priority[1]) && $sig_priority[1] != " "
		&& $sig_priority[1] != "" && $sig_priority[1] != "0"
	){
     if ($sig_priority[0] != "" && $sig_priority[0] != " ")
     {
       $tmp_meta = $tmp_meta." AND sig_priority ".$sig_priority[0]." '".$sig_priority[1]."'";
     }
     else
     {
       $tmp_meta = $tmp_meta." AND sig_priority = '".$sig_priority[1]."'";
     }
	}else if (isset($sig_priority[1]) && $sig_priority[1] == "0"){
     $tmp_meta = $tmp_meta." AND (sig_priority is null OR sig_priority = '0')";
	}else{
		if (is_object($cs)){ // Issue #5
     $cs->criteria['sig_priority']->Set("");
		}
	}
	// Date/Time
	if ( isset($time) && isset($time_cnt) ){
		if ( DateTimeRows2sql($time, $time_cnt, $tmp_meta) == 0 ){
			if (is_object($cs)){ // Issue #5
     $cs->criteria['time']->SetFormItemCnt(0);
			}
		}
	}
  $criteria_sql = $criteria_sql.$tmp_meta;
	// IP Criteria
		// IP Addresses
  $tmp2 = "";
	if (isset($ip_addr_cnt)){
  for ( $i = 0; $i < $ip_addr_cnt; $i++ )
  {
     $tmp = "";
     if ( isset($ip_addr[$i][3]) && $ip_addr[$i][1] != " ")
     {
        if ( ($ip_addr[$i][3] != "") && ($ip_addr[$i][4] != "") &&
             ($ip_addr[$i][5] != "") && ($ip_addr[$i][6] != "" ) )
        {
           /* if use illegal 256.256.256.256 address then
            *  this is the special case where need to search for portscans
            */
           if ( ($ip_addr[$i][3] == "256") && ($ip_addr[$i][4] == "256") &&
                ($ip_addr[$i][5] == "256") && ($ip_addr[$i][6] == "256" ) )
           {
             $tmp = $tmp." acid_event.".$ip_addr[$i][1]." IS NULL"." ";
           }
           else
           {
             if ( $ip_addr[$i][10] == "" )
             {
             $tmp = $tmp." acid_event.".$ip_addr[$i][1].$ip_addr[$i][2]."'".
                    baseIP2long($ip_addr[$i][3].".". 
                                $ip_addr[$i][4].".".
                                $ip_addr[$i][5].".".
                                $ip_addr[$i][6])."' ";
             }
             else
             {
                $mask = getIPMask($ip_addr[$i][3].".". 
                                  $ip_addr[$i][4].".".
                                  $ip_addr[$i][5].".".
                                  $ip_addr[$i][6], $ip_addr[$i][10]);
                if ( $ip_addr[$i][2] == "!=" )
                   $tmp_op = " NOT ";
                else 
                   $tmp_op = "";

                $tmp = $tmp.$tmp_op." (acid_event.".$ip_addr[$i][1].">= '".
                               baseIP2long($mask[0])."' AND ".
                               "acid_event.".$ip_addr[$i][1]."<= '".
                               baseIP2long($mask[1])."')"; 
             }       
           }
        }
        /* if have chosen the address type to be both source and destination */
		if ( preg_match("/ip_both/", $tmp) )
		{
			$tmp_src = preg_replace("/ip_both/","ip_src",$tmp);
			$tmp_dst = preg_replace("/ip_both/","ip_dst",$tmp);
           
           if ( $ip_addr[$i][2] == '=' )
             $tmp = "(".$tmp_src.') OR ('.$tmp_dst.')';
           else
             $tmp = "(".$tmp_src.') AND ('.$tmp_dst.')';
        }
    
        if ( $tmp != "" )       
           $tmp = $ip_addr[$i][0]."(".$tmp.")".$ip_addr[$i][8].$ip_addr[$i][9];
     }
     else if ( (isset($ip_addr[$i][3]) && $ip_addr[$i][3] != "" ) || $ip_addr[$i][1] != " " )
     {
        /* IP_addr_type, but MALFORMED IP address */
        if ( $ip_addr[$i][1] != " " && $ip_addr[$i][3] == "" && 
             ($ip_addr[$i][4] != "" || $ip_addr[$i][5] != "" || $ip_addr[$i][6] != "" ) )
            ErrorMessage("<B>"._QCERRCRITWARN."</B> "._QCERRINVIPCRIT." ' *.".
                         $ip_addr[$i][4].".".$ip_addr[$i][5].".".$ip_addr[$i][6]." '");

        /* ADDRESS, but NO IP_addr_type was given */
        if ( isset($ip_addr[$i][3]) && $ip_addr[$i][1] == " ")
           ErrorMessage("<B>"._QCERRCRITWARN."</B> "._QCERRIP." '".
                        $ip_addr[$i][3].".".$ip_addr[$i][4].".".
                        $ip_addr[$i][5].".".$ip_addr[$i][6]."' "._QCERRCRITADDRESSTYPE);

        /* IP_addr_type IS FILLED, but no ADDRESS */
        if ( ($ip_addr[$i][1] != " " && $ip_addr[$i][1] != "") && $ip_addr[$i][3] == "" )
           ErrorMessage("<B>"._QCERRCRITWARN."</B> "._QCERRIPTYPE." '".
                        $ip_addr[$i][1]."' "._QCERRCRITIPADDRESSNONE1.$i.") "._QCERRCRITIPADDRESSNONE);
     }
     $tmp2 = $tmp2.$tmp;

     if ( ($i > 0 && $ip_addr[$i-1][9] == ' ' && $ip_addr[$i-1][3] != "") )
        ErrorMessage("<B>"._QCERRCRITWARN."</B> "._QCERRCRITIPIPBOOL." #$i and #".($i+1).".");
  }
	}
	if ( $tmp2 != "" ){
     $criteria_sql = $criteria_sql." AND ( ".$tmp2." )";  
	}else{
		if (is_object($cs)){ // Issue #5
     $cs->criteria['ip_addr']->SetFormItemCnt(0);
		}
	}
		// IP Fields
	if (isset($ip_field) && isset($ip_field_cnt)){
		if ( FieldRows2sql($ip_field, $ip_field_cnt, $criteria_sql) == 0 ){
			if (is_object($cs)){ // Issue #5
     $cs->criteria['ip_field']->SetFormItemCnt(0);
			}
		}
	}
	// Layer-4 encapsulation
	if (isset($layer4)){
  if ( $layer4 == "TCP" )
     $criteria_sql = $criteria_sql." AND acid_event.ip_proto= '6'";  
  else if ( $layer4 == "UDP" )
     $criteria_sql = $criteria_sql." AND acid_event.ip_proto= '17'";
  else if ( $layer4 == "ICMP" )
     $criteria_sql = $criteria_sql." AND acid_event.ip_proto= '1'";
  else if ( $layer4 == "RawIP" )
     $criteria_sql = $criteria_sql." AND acid_event.ip_proto= '255'";
  else{
			if (is_object($cs)){ // Issue #5
     $cs->criteria['layer4']->Set("");
			}
		}
	// Join the iphdr table if necessary
		if (is_object($cs)){ // Issue #5
  if ( !$cs->criteria['ip_field']->isEmpty() )
     $join_sql = $ip_join_sql.$join_sql;
		}
	// TCP Criteria
if ( $layer4 == "TCP" ){
  $proto_tmp = "";
		// TCP Ports
		if ( FieldRows2sql($tcp_port, $tcp_port_cnt, $proto_tmp) == 0 ){
			if (is_object($cs)){ // Issue #5
     $cs->criteria['tcp_port']->SetFormItemCnt(0);
			}
		}
  $criteria_sql = $criteria_sql.$proto_tmp;
  
  $proto_tmp = "";
  /* TCP Flags */
  if ( isset($tcp_flags) && sizeof($tcp_flags) == 8)
  {
    if ( $tcp_flags[0] == "contains" || $tcp_flags[0] == "is" )
    {
      $flag_tmp = $tcp_flags[1]+$tcp_flags[2]+$tcp_flags[3]+$tcp_flags[4]+
                  $tcp_flags[5]+$tcp_flags[6]+$tcp_flags[7]+$tcp_flags[8];
      if ( $tcp_flags[0] == "is" )
        $proto_tmp = $proto_tmp.' AND tcp_flags='.$flag_tmp;
      else if ( $tcp_flags[0] == "contains" )
        $proto_tmp = $proto_tmp.' AND (tcp_flags & '.$flag_tmp.' = '.$flag_tmp." )";
      else 
        $proto_tmp = "";
    }
  }
		// TCP Fields
		if (is_object($cs)){ // Issue #5
			if ( FieldRows2sql($tcp_field, $tcp_field_cnt, $proto_tmp) == 0 ){
     $cs->criteria['tcp_field']->SetFormItemCnt(0);
			}
		// TCP Options - not implemented
  if ( !$cs->criteria['tcp_port']->isEmpty() || !$cs->criteria['tcp_flags']->isEmpty() || !$cs->criteria['tcp_field']->isEmpty() )
  {
     $criteria_sql = $criteria_sql.$proto_tmp;
     if ( !$cs->criteria['tcp_flags']->isEmpty() || !$cs->criteria['tcp_field']->isEmpty() )
        $join_sql = $tcp_join_sql.$join_sql;
  }
		}
}
	// UDP Criteria
if ( $layer4 == "UDP" ){
  $proto_tmp = "";
		// UDP Ports
		if ( FieldRows2sql($udp_port, $udp_port_cnt, $proto_tmp) == 0 ){
			if (is_object($cs)){ // Issue #5
     $cs->criteria['udp_port']->SetFormItemCnt(0);
			}
		}
  $criteria_sql = $criteria_sql.$proto_tmp;
  $proto_tmp = "";
		// UDP Fields
		if (is_object($cs)){ // Issue #5
			if ( FieldRows2sql($udp_field, $udp_field_cnt, $proto_tmp) == 0 ){
     $cs->criteria['udp_field']->SetFormItemCnt(0);
			}
  if ( !$cs->criteria['udp_port']->isEmpty() || !$cs->criteria['udp_field']->isEmpty() )
  {
     $criteria_sql = $criteria_sql.$proto_tmp;
     if ( !$cs->criteria['udp_field']->isEmpty() )
        $join_sql = $udp_join_sql.$join_sql;
  }
		}
}
	// ICMP Criteria
if ( $layer4 == "ICMP" ){
  $proto_tmp = "";
		// ICMP Fields
		if (is_object($cs)){ // Issue #5
			if ( FieldRows2sql($icmp_field, $icmp_field_cnt, $proto_tmp) == 0 ){
     $cs->criteria['icmp_field']->SetFormItemCnt(0);
			}
  if ( !$cs->criteria['icmp_field']->isEmpty() )
  {
     $criteria_sql = $criteria_sql.$proto_tmp;
     $join_sql = $icmp_join_sql.$join_sql;
  }
		}
}
	// Packet Scan Criteria
if ( $layer4 == "RawIP" ){
  $proto_tmp = "";
		// RawIP Fields
		if (is_object($cs)){ // Issue #5
  if ( FieldRows2sql($rawip_field, $rawip_field_cnt, $proto_tmp) == 0 )
     $cs->criteria['rawip_field']->SetFormItemCnt(0);

  if ( !$cs->criteria['rawip_field']->isEmpty() )
  {
     $criteria_sql = $criteria_sql.$proto_tmp;
     $join_sql = $rawip_join_sql.$join_sql;
  }
		}
	}
}
	// Payload Criteria
  $tmp_payload = "";
	if (is_object($cs)){ // Issue #5
  if ( DataRows2sql($data, $data_cnt, $data_encode, $tmp_payload) == 0 )
     $cs->criteria['data']->SetFormItemCnt(0);

  if ( !$cs->criteria['data']->isEmpty() )
  {
     $criteria_sql = $criteria_sql.$tmp_payload;
     $join_sql = $data_join_sql.$join_sql;
  }
	}
  $csql[0] = $join_sql;
  $csql[1] = $criteria_sql;
	return $csql;
}
?>
