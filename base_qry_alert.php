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
** Purpose: displays a single alert   
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

$sc = DIRECTORY_SEPARATOR;
require_once("includes$sc" . 'base_krnl.php');
include ("$BASE_path/includes/base_include.inc.php");
include_once ("$BASE_path/base_db_common.php");
include_once ("$BASE_path/base_qry_common.php");
include_once ("$BASE_path/base_stat_common.php");

AuthorizedRole(10000);
$payload = FALSE;
$offset  = 0;
if ( isset($_GET['asciiclean']) ){ // Set cookie for packet display
	1 == $_GET['asciiclean'] ? setcookie('asciiclean', 'clean') : setcookie('asciiclean', 'normal');
}
$sf_portscan_flag = 0;

function PrintCleanURL(){
	// This function creates the url to display the cleaned up payload -- Kevin
	$query = CleanVariable($_SERVER["QUERY_STRING"], VAR_PERIOD | VAR_DIGIT | VAR_PUNC | VAR_LETTER);
	$sort_order=ImportHTTPVar("sort_order", VAR_LETTER | VAR_USCORE);
	$url = '<center><a href="base_qry_alert.php?' . $query;
	$url .= '&amp;sort_order='.urlencode($sort_order).'&amp;asciiclean=';
	if ( GetAsciiClean() ){ // Create link to non-cleaned payload display.
		$url.= '0">'._QANORMALD;
	}else{ // Create link to cleaned payload display.
		$url.= '1">'._QAPLAIND;
	}
	$url.= '</a></center>';
	return $url;
}

function PrintBinDownload( $db, $cid, $sid ){
	// Offering a URL to a download possibility:
	if ( GetAsciiClean() ){
		$tmp = 1;
	} else {
		$tmp = 0;
	}
	$query = CleanVariable($_SERVER["QUERY_STRING"], VAR_PERIOD | VAR_DIGIT | VAR_PUNC | VAR_LETTER);
	$url = '<center><a href="base_payload.php?' . $query;
	$url .= '&amp;download=1';
	$url .= '&amp;cid='.urlencode($cid).'&amp;sid='.urlencode($sid);
	$url .= '&amp;asciiclean=' . $tmp;
	$url.= '">Download of Payload</a></center>';
	return $url;
}

function PrintPcapDownload( $db, $cid, $sid ){
	if (!isset($db))
	{
		error_log("ERROR: \$db is NOT set.");
		ErrorMessage(__FILE__ . ":" . __LINE__ . ": db is NOT set. Ignoring.");
    $debug_str = "<BR><PRE>\n\n" . debug_print_backtrace() . "\n\n</PRE><BR>\n";
    ErrorMessage($debug_str);
	}


	if (!isset($db->DB))
	{
		error_log("ERROR: \$db->DB is NOT set.");
		ErrorMessage(__FILE__ . ":" . __LINE__ . ": db->DB is NOT set. Ignoring.");
    $debug_str = "<BR><PRE>\n\n" . debug_print_backtrace() . "\n\n</PRE><BR>\n";
    ErrorMessage($debug_str);
	}

	if (!is_array($db->DB->MetaColumnNames('data')))
	{
		error_log("ERROR: \$db->DB->MetaColumnNames('data') is NOT an array.");
		ErrorMessage(__FILE__ . ":" . __LINE__ . ": db->DB->MetaColumnNames('data') is NOT an array. Ignoring.");
		print "<BR><PRE>\n\n";
		debug_print_backtrace();
		print "\n\n" ;
		var_dump($db->DB->MetaColumnNames('data'));
		print "</PRE><BR>\n\n" ;
	}


   if ( !in_array("pcap_header", $db->DB->MetaColumnNames('data')) ||
        !in_array("data_header", $db->DB->MetaColumnNames('data'))) {
      $type = 3;
   } else {
      $type = 2;
   }

	if ( GetAsciiClean() ){
		$tmp = 1;
	} else {
		$tmp = 0;
	}
	$query = CleanVariable($_SERVER["QUERY_STRING"], VAR_PERIOD | VAR_DIGIT | VAR_PUNC | VAR_LETTER);
	$url = '<center><a href="base_payload.php?' . $query;
	$url .= '&amp;download='.urlencode($type);
	$url .= '&amp;cid='.urlencode($cid).'&amp;sid='.urlencode($sid);
	$url .= '&amp;asciiclean=' . $tmp;
	$url .= '">Download in pcap format</a></center>';
	return $url;
}

function PrintPacketLookupBrowseButtons( $seq, $sql, $db, &$p_b, &$n_b ){
	GLOBAL $debug_mode, $UIL;
	$EMPfx = __FUNCTION__ . ': ';
	if ( class_exists('UILang') ){ // Issue 11 backport shim.
		$BtnLast = $UIL->CWA['Last'];
		$BtnFirst = $UIL->CWA['First'];
	}else{
		$BtnLast = _LAST;
		$BtnFirst = _FIRST;
	}
	if ( !is_int($seq) ){ // Input Validation
		$seq = 0;
	}
	NLIO ("<!-- Single Alert Browsing Buttons -->");
	if ( $seq > 0 ){
		$start = $seq -1;
	}else{
		$start = $seq;
	}
	if ( $debug_mode > 0 ){
		ErrorMessage (
			$EMPfx. "Execute SQL: $sql LIMIT $start, 3",'black',1
		);
	}
	$rs = $db->baseExecute($sql, $start, 3);
	if (
		$rs != false
		&& $db->baseErrorMessage() == ''
		&& $rs->baseRecordCount() > 0
	){ // Error Check
		if ( $debug_mode > 1 ){
			ErrorMessage (
				$EMPfx. "Records: ".$rs->baseRecordCount(),'black', 1
			);
		}
		if ( $seq == 0 ){
			$p_b = "[ $BtnFirst ]";
		}
		$Pfx = "<INPUT TYPE='submit' NAME='submit' VALUE='";
		for ( $i = $start; $i <= $seq + 1; $i++  ){
			$row = $rs->baseFetchRow();
			if ( $debug_mode > 1 ){
				ErrorMessage ("# $i - $seq", 'black',1);
				var_dump($row);
			}
			if ( $row == '' ){
				$n_b = "[ $BtnLast ]";
				break;
			}
			$Sfx = '-('.$row[0].'-'.$row[1].")'>";
			if ( $i == $seq - 1 ){
				$p_b = $Pfx."&lt;&lt; "._PREVIOUS." #".($seq-1).$Sfx;
			}elseif ( $i == $seq + 1 ){
				$n_b = $Pfx."&gt;&gt; "._NEXT." #".($seq+1).$Sfx;
			}
		}
		$rs->baseFreeRows();
		if ( $debug_mode > 1 ){
			ErrorMessage ( $EMPfx. "Ret-P: ".XSSPrintSafe($p_b),'black',1 );
			ErrorMessage ( $EMPfx. "Ret-N: ".XSSPrintSafe($n_b),'black',1 );
		}
	}else{
		ErrorMessage ($EMPfx. "BASE DB Error: ".$db->baseErrorMessage() == '');
	}
}

$sort_order = ImportHTTPVar( 'sort_order', VAR_LETTER | VAR_USCORE );
// Need to import $submit and set the $QUERY_STRING early to support the back
// button. Otherwise, the value of $submit will not be passed to the history.
//
// $submit can contain values in the form of  "#xx-(xx-xx)" and
// other "submit" values.
$submit = ImportHTTPVar(
	'submit', VAR_DIGIT | VAR_PUNC | VAR_LETTER,
	array(_SELECTED, _ALLONSCREEN, _ENTIREQUERY)
);
$_SERVER["QUERY_STRING"] = "submit=".rawurlencode($submit);

  $cs = new CriteriaState("base_qry_main.php", "&amp;new=1&amp;submit="._QUERYDBP);
  $cs->ReadState();

  $qs = new QueryState();
$qs->current_sort_order = $sort_order;

  $page_title = _ALERT;
  PrintBASESubHeader($page_title, $page_title, $cs->GetBackLink(), $refresh_all_pages);
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

  PrintCriteria("");
  $criteria_clauses = ProcessCriteria();  

  $from = " FROM acid_event ".$criteria_clauses[0];
  $where = " WHERE ".$criteria_clauses[1];

  $qs->AddValidAction("ag_by_id");
  $qs->AddValidAction("ag_by_name");
  $qs->AddValidAction("add_new_ag");
  $qs->AddValidAction("del_alert");
  $qs->AddValidAction("email_alert");
  $qs->AddValidAction("email_alert2");
  $qs->AddValidAction("archive_alert");
  $qs->AddValidAction("archive_alert2");

  $qs->AddValidActionOp(_SELECTED);

  $qs->SetActionSQL($from.$where);
 
  $et->Mark("Initialization");

  $qs->RunAction($submit, PAGE_ALERT_DISPLAY, $db);
  $et->Mark("Alert Action");

	//If get a valid (sid,cid) store it in $caller. If $submit is returning
	// from an alert action get the (sid,cid) back from $caller.
	if ( $submit == _SELECTED ){
		$submit = ImportHTTPVar('caller', VAR_DIGIT | VAR_PUNC);
	}else{
		$caller = $submit;
	}
	if ( $debug_mode > 0 ){
		$TK = array ( 'caller', 'submit' );
		$DI = array();
		$DD = array();
		foreach ( $TK as $val ){
			array_push($DD, $val);
			array_push($DI, $$val);
		}
		array_push($DD, 'QS-CCQ');
		array_push($DI, $qs->GetCurrentCannedQuery());
		DDT($DI,$DD,'Caller / Submit / QS-CCQ Values','',25);
	}

  /* Setup the Query Results Table -- However, this data structure is not
   * really used for output.  Rather, it duplicates the sort SQL set in
   *  base_qry_sqlcalls.php 
   */
  $qro = new QueryResultsOutput("");
	// Common SQL Strings
	$OB = ' ORDER BY';
	$qro->AddTitle($CPSig,
		"sig_a", " ", "$OB sig_name ASC",
		"sig_d", " ", "$OB sig_name DESC"
	);
	$qro->AddTitle($CPTs,
		"time_a", " ", "$OB timestamp ASC ",
		"time_d", " ", "$OB timestamp DESC "
	);
	$qro->AddTitle($CPSA,
		"sip_a", " ", "$OB ip_src ASC",
		"sip_d", " ", "$OB ip_src DESC"
	);
	$qro->AddTitle($CPDA,
		"dip_a", " ", "$OB ip_dst ASC",
		"dip_d", " ", "$OB ip_dst DESC"
	);
	$qro->AddTitle(_NBLAYER4,
		"proto_a", " ", "$OB ip_proto ASC",
		"proto_d", " ", "$OB ip_proto DESC"
	);

// Issue #168
$save_sql = "SELECT acid_event.sid, acid_event.cid";
$sqlPFX = $from.$where;
$sort_sql = $qro->GetSortSQL($qs->GetCurrentSort(), $qs->GetCurrentCannedQuerySort());
if ( !is_null($sort_sql) ){
	$sqlPFX = $sort_sql[0].$sqlPFX.$sort_sql[1];
}
$save_sql .= $sqlPFX;
GetQueryResultID($submit, $seq, $sid, $cid);
if ( $debug_mode > 0 ){
	if ( $qs->isCannedQuery() ){
		$CCF = 'Yes';
		$qs->PrintCannedQueryList();
	}else{
		$CCF = 'No';
	}
	print "Canned Query: $CCF <br/>";
	$qs->DumpState();
	print "SQL Saved: $save_sql <br/>";
	$TK = array ( 'caller', 'submit', 'sid', 'cid', 'seq' );
	$DI = array();
	$DD = array();
	foreach ( $TK as $val ){
		array_push($DD, $val);
		array_push($DI, $$val);
	}
	DDT($DI,$DD,'Alert Lookup ','',25);
}

	// Verify (sid, cid) are extracted correctly.
	if ( is_int($sid) && is_int($cid) && !($sid > 0 && $cid > 0) ){
		// Added is_int checks as Issue #5 fix. If the above call to
		// GetQueryResultID() fails, $sid & $cid will be defined but unset,
		// which makes them of type string on PHP 5.2x & of type NULL on PHP
		// 5.3+ See: https://travis-ci.org/NathanGibbs3/BASE/jobs/546765554
		// This should only occur in the test conditions for Issue #5. This
		// fix allows $sid, & $cid of any type except int to pass through
		// without exiting the app while under test.
		// Note if this breaks something in production.
		// Comment at: https://github.com/NathanGibbs3/BASE/issues/5
		FatalError(_QAINVPAIR." (".$sid.",".$cid.")");
	}else{
		if ( getenv('TRAVIS') && version_compare(PHP_VERSION, "5.3.0", "<") ){
			// Issue #5 Test Shim
			$sid = 1;
			$cid = 1;
		}
	}
PrintPacketLookupBrowseButtons($seq, $save_sql, $db, $previous, $next);
  echo "<FORM METHOD=\"GET\" ACTION=\"base_qry_alert.php\">\n"; 
  echo "<CENTER>\n<B>"._ALERT." #".($seq)."</B><BR>\n$previous &nbsp&nbsp&nbsp\n$next\n</CENTER>\n";
  echo "<HR>\n";

  /* Make Selected */
  echo "\n<INPUT TYPE=\"hidden\" NAME=\"action_chk_lst[0]\" VALUE=\"$submit\">\n";

  /* Event */
  $sql2 = "SELECT signature, timestamp FROM acid_event WHERE sid='".filterSql($sid)."' AND cid='".filterSql($cid)."'";
	if ( $debug_mode > 0 ){
		print "<BR><BR>\n\n" . __FILE__ . ":" . __LINE__ . ": DEBUG: \$sql2 = \"$sql2\"<BR><BR>\n\n";
	}
  $result2 = $db->baseExecute($sql2);
  $myrow2 = $result2->baseFetchRow();
	if ( is_array($myrow2) ){
		if ( $myrow2[0] == "" ){
			print '<center><b>'.returnErrorMessage(_QAALERTDELET).'</center></b>';
		}
		$Alert_Time = $myrow2[1];
		$Alert_Sig = $myrow2[0];
	}else{
		$Alert_Time = 'Testing';
		$Alert_Sig = 'Testing';
	}
  /* Get sensor parameters: */
  $sql4 = "SELECT hostname, interface, filter, encoding, detail FROM sensor  WHERE sid='".filterSql($sid)."'";
  $result4 = $db->baseExecute($sql4);
  $myrow4 = $result4->baseFetchRow();
  $result4->baseFreeRows();
	if ( is_array($myrow4) ){
		$Sensor_Name = $myrow4[0];
		if ( $myrow4[1] == "" ){
			$Sensor_Int = "&nbsp;<I>"._NONE."</I>&nbsp;";
		}else{
			$Sensor_Int = $myrow4[1];
		}
		if ( $myrow4[2] == "" ){
			$Sensor_Filt = "&nbsp;<I>"._NONE."</I>&nbsp;";
		}else{
			$Sensor_Filt = $myrow4[2];
		}
		$encoding = $myrow4[3];
		$detail = $myrow4[4];
	}else{
		$Sensor_Name = _NONE;
		$Sensor_Int = _NONE;
		$Sensor_Filt = _NONE;
		$encoding = 2;
		$detail = 1;
	}
  echo '
       <BLOCKQUOTE>
       <TABLE BORDER=1 width="90%">
          <TR><TD CLASS="metatitle" WIDTH=50 ALIGN=CENTER ROWSPAN=4>Meta
              <TD>
                  <TABLE BORDER=1 CELLPADDING=4>
                    <TR><TD CLASS="plfieldhdr" >'._ID.' #</TD>
                        <TD CLASS="plfieldhdr">'._CHRTTIME.'</TD>
                        <TD CLASS="plfieldhdr">'._QATRIGGERSIG.'</TD></TR>
                    <TR><TD CLASS="plfield">'.($sid." - ".$cid).'</TD>
                    <TD CLASS="plfield">'.htmlspecialchars($Alert_Time).'</TD>
                        <TD CLASS="plfield">'.(GetTagTriger(BuildSigByID($Alert_Sig, $db), $db, $sid, $cid)).'</TD></TR>
                  </TABLE>
              </TD>
           </TR>';

  echo '  <TR>
             <TD>
                <TABLE BORDER=1 CELLPADDING=4>
                  <TR><TD CLASS="metatitle" ALIGN=CENTER ROWSPAN=2>'._SENSOR.'</TD>
                       <TD class="plfieldhdr">',_SENSOR.' '._ADDRESS,'</TD>
                       <TD class="plfieldhdr">'._INTERFACE.'</TD>
                       <TD class="plfieldhdr">'._FILTER.'</TD>
                  </TR>
                  <TR><TD class="plfield">'.htmlspecialchars($Sensor_Name).'</TD>
                      <TD class="plfield">'.$Sensor_Int.'</TD>
                      <TD class="plfield">'.$Sensor_Filt.'</TD>
                  </TR>
                 </TABLE>     
          </TR>';

	if ( isset($resolve_IP) && $resolve_IP == 1 ){
     echo '  <TR>
              <TD>
                <TABLE BORDER=1 CELLPADDING=4>
                  <TR><TD CLASS="iptitle" ALIGN=CENTER ROWSPAN=2>FQDN</TD>
                       <TD class="plfieldhdr">'._SENSOR.' '._NAME.'</TD>
                  </TR>
                  <TR><TD class="plfield">';
     # Is this a dotted IPv4 address?
     $pattern = '/(\d{1,3}\.){3}\d{1,3}/';
     if (preg_match($pattern, $myrow4[0]))
     {
       echo baseGetHostByAddr($myrow4[0], $db, $dns_cache_lifetime);
     }
     else
     {
       echo $myrow4[0];
     }
     echo '           </TD>
                  </TR>
                 </TABLE>     
            </TR>';
	}
	$result4->baseFreeRows();
  $sql4 = "SELECT acid_ag_alert.ag_id, ag_name, ag_desc ".
          "FROM acid_ag_alert LEFT JOIN acid_ag ON acid_ag_alert.ag_id = acid_ag.ag_id ".
          "WHERE ag_sid='".$sid."' AND ag_cid='".$cid."'";
  $result4 = $db->baseExecute($sql4);
  $num = $result4->baseRecordCount();

  echo ' <TR>
           <TD>
             <TABLE BORDER=1 CELLPADDING=4>
               <TR><TD CLASS="metatitle" ALIGN=CENTER ROWSPAN='.($num+1).'>'._ALERTGROUP.'</TD>';
  
  if ( $num > 0 )
     echo '        <TD class="plfieldhdr">'._ID.'</TD>
                   <TD class="plfieldhdr">'._NAME.'</TD>
                   <TD class="plfieldhdr">'._DESC.'</TD></TR>';  
  else
     echo '        <TD>&nbsp;&nbsp;<I>'._NONE.'</I>&nbsp;</TD></TR>';

  for ($i = 0; $i < $num; $i++)
  {
     $myrow4 = $result4->baseFetchRow();

     echo '    <TR><TD class="plfield">'.htmlspecialchars($myrow4[0]).'</TD>
                   <TD class="plfield">'.htmlspecialchars($myrow4[1]).'</TD>
                   <TD class="plfield">'.htmlspecialchars($myrow4[2]).'</TD>
               </TR>';
  }
  echo '      </TABLE>';
  $result4->baseFreeRows();

  echo '   </TR>
        </TABLE>';
  $result2->baseFreeRows();

  /* IP */
  $sql2 = "SELECT ip_src, ip_dst, ".
          "ip_ver, ip_hlen, ip_tos, ip_len, ip_id, ip_flags, ip_off, ip_ttl, ip_csum, ip_proto". 
          " FROM iphdr  WHERE sid='".$sid."' AND cid='".$cid."'";

  $result2 = $db->baseExecute($sql2);
  $myrow2 = $result2->baseFetchRow();

  $layer4_proto = -1;
	if ( is_array($myrow2) ){
		$IP_Src = $myrow2[0];
	}else{
		$IP_Src = '';
	}
	if ( $IP_Src != '' ){
  $sql3 = "SELECT * FROM opt  WHERE sid='".$sid."' AND cid='".$cid."' AND opt_proto='0'";
  $result3 = $db->baseExecute($sql3);
  $num_opt = $result3->baseRecordCount();

  echo '
       <TABLE BORDER=1 WIDTH="90%">
          <TR><TD CLASS="iptitle" WIDTH=50 ROWSPAN=3 ALIGN=CENTER>IP';
  echo '      <TD>';
  echo '         <TABLE BORDER=1 CELLPADDING=2>';
  echo '            <TR><TD class="plfieldhdr">'._NBSOURCEADDR.'</TD>
                        <TD class="plfieldhdr">&nbsp;'._NBDESTADDR.'&nbsp</TD>
                        <TD class="plfieldhdr">Ver</TD>
                        <TD class="plfieldhdr">Hdr Len</TD>
                        <TD class="plfieldhdr">TOS</TD>
                        <TD class="plfieldhdr">'._LENGTH.'</TD>
                        <TD class="plfieldhdr">'._ID.'</TD>
                        <TD class="plfieldhdr">fragment</TD>
                        <TD class="plfieldhdr">offset</TD>
                        <TD class="plfieldhdr">TTL</TD>
                        <TD class="plfieldhdr">chksum</TD></TR>';
  echo '             <TR><TD class="plfield">
                       <A HREF="base_stat_ipaddr.php?ip='.baseLong2IP($myrow2[0]).'&amp;netmask=32">'.
                            baseLong2IP($myrow2[0]).'</A></TD>';
  echo '                 <TD class="plfield">
                         <A HREF="base_stat_ipaddr.php?ip='.baseLong2IP($myrow2[1]).'&amp;netmask=32">'.
                            baseLong2IP($myrow2[1]).'</A></TD>';
  echo '                 <TD class="plfield">'.htmlspecialchars($myrow2[2]).'</TD>';
  echo '                 <TD class="plfield">'.($myrow2[3] << 2).'</TD>';    /* ihl is in 32 bit words, must be multiplied by 4 to show in bytes */
  echo '                 <TD class="plfield">'.htmlspecialchars($myrow2[4]).'</TD>';
  echo '                 <TD class="plfield">'.htmlspecialchars($myrow2[5]).'</TD>';
  echo '                 <TD class="plfield">'.htmlspecialchars($myrow2[6]).'</TD>';
  echo '                 <TD class="plfield">';
  if ($myrow2[7] == 1)
	echo 'yes';
  else
	echo 'no';
  echo 							  '</TD>';
  list( , $my_offset, ) = unpack("n", pack("S", $myrow2[8]));
  echo '                 <TD class="plfield">'. ($my_offset * 8) .'</TD>';
  echo '                 <TD class="plfield">'.htmlspecialchars($myrow2[9]).'</TD>';
  echo '                 <TD class="plfield">'.htmlspecialchars($myrow2[10]).'<BR>= 0x'.dechex($myrow2[10]).'</TD></TR>';
  echo '         </TABLE>';

	if ( isset($resolve_IP) && $resolve_IP == 1 ){
     echo '  <TR>
              <TD>
                <TABLE BORDER=1 CELLPADDING=4>
                  <TR><TD CLASS="iptitle" ALIGN=CENTER ROWSPAN=2>FQDN</TD>
                       <TD class="plfieldhdr">'._SOURCENAME.'</TD>
                       <TD class="plfieldhdr">'._DESTNAME.'</TD>
                  </TR>
                  <TR><TD class="plfield">'.
                      (baseGetHostByAddr(baseLong2IP($myrow2[0]),
                                        $db, $dns_cache_lifetime)).'</TD>
                      <TD class="plfield">'.
                      (baseGetHostByAddr(baseLong2IP($myrow2[1]),
                                         $db, $dns_cache_lifetime)).'</TD>
                  </TR>
                 </TABLE>     
            </TR>';
	}
  echo '  <TR>';
  echo '      <TD>';
  echo '         <TABLE BORDER=1 CELLPADDING=4>';
  echo '           <TR><TD CLASS="iptitle" ALIGN=CENTER ROWSPAN='.(($num_opt != 0) ? ($num_opt+1) : 1).'>'._OPTIONS.'</TD>';

  $layer4_proto = $myrow2[11];

  if ( $num_opt > 0 )
  {
     echo '            <TD></TD>
                       <TD class="plfieldhdr">'._CODE.'</TD>
                       <TD class="plfieldhdr">'._LENGTH.'</TD>
                       <TD class="plfieldhdr" ALIGN=CENTER>'._DATA.'</TD>';

     for ( $i = 0; $i < $num_opt; $i++)
     {
         $myrow3 = $result3->baseFetchRow();
         echo '    <TR><TD>#'.($i+1).'</TD>';
         echo '        <TD class="plfield">'.IPOption2str($myrow3[4]).'</TD>';
         echo '        <TD class="plfield">'.htmlspecialchars($myrow3[5]).'</TD>';
         echo '        <TD class="plfield">';
         if ($myrow3[6] != "" )
           echo $myrow3[6];
         else
           echo '&nbsp;';
         echo '</TD></TR>';
     }
   }
   else
   {
     echo '             <TD> &nbsp&nbsp&nbsp <I>'._NONE.' </I></TD></TR>';
   }
      echo '         </TABLE></TD></TR>';  
                       

  echo '</TABLE>';
  $result3->baseFreeRows();
  }
  $result2->baseFreeRows();


  /* If we have FLoP's (Fast Logging Project for Snort) extended 
   * database schema then we can show mac addresses from `data_header`
   * field from `data` table
   */
	if (!isset($db))
	{
		error_log("ERROR: \$db is NOT set.");
		ErrorMessage(__FILE__ . ":" . __LINE__ . ": db is NOT set. Ignoring.");
    $debug_str = "<BR><PRE>\n\n" . debug_print_backtrace() . "\n\n</PRE><BR>\n";
    ErrorMessage($debug_str);
	}


	if (!isset($db->DB))
	{
		error_log("ERROR: \$db->DB is NOT set.");
		ErrorMessage(__FILE__ . ":" . __LINE__ . ": db->DB is NOT set. Ignoring.");
    $debug_str = "<BR><PRE>\n\n" . debug_print_backtrace() . "\n\n</PRE><BR>\n";
    ErrorMessage($debug_str);
	}

	if (!is_array($db->DB->MetaColumnNames('data')))
	{
		error_log("ERROR: \$db->DB->MetaColumnNames('data') is NOT an array.");
		ErrorMessage(__FILE__ . ":" . __LINE__ . ": db->DB->MetaColumnNames('data') is NOT an array. Ignoring.");
    print "<BR><PRE>\n\n";
		debug_print_backtrace();
		print "\n\n" ;
		var_dump($db->DB->MetaColumnNames('data'));
		print "</PRE><BR>\n\n" ;
	}


  if (in_array("data_header", $db->DB->MetaColumnNames('data'))) {

     $sql5 = "SELECT data_header FROM data WHERE sid='$sid' AND cid='$cid'";
     $result5 = $db->baseExecute($sql5);
     $myrow5 = $result5->baseFetchRow();
     $result5->baseFreeRows();

    if (is_array($myrow5))
    {
      if ( $debug_mode > 0 ) {
        echo "&lt;debug&gt;<BR>";
        echo "Encoding: $encoding<BR>";
        echo "Data header: &lt;$myrow5[0]&gt;<BR>";
        echo "strlen: " . strlen($myrow5[0]);
        echo "<br>Base64 decoded: &lt;" . base64_decode($myrow5[0]) . "&gt;<BR>";
        echo "strlen: " . strlen(base64_decode($myrow5[0]));
        echo "<br>bin2hex: &lt;" . bin2hex(base64_decode($myrow5[0])) . "&gt;<BR>";
        echo "strlen: " . strlen(bin2hex(base64_decode($myrow5[0])));
        echo "<BR>&lt;/debug&gt;<BR>";
      }

      /* 0 == hex, 1 == base64, 2 == ascii; cf. snort-2.4.4/src/plugbase.h */
      if ($encoding == 0) {
       	$t = $myrow5[0];
      } elseif ($encoding == 1) {
       	$t = bin2hex(base64_decode($myrow5[0]));
      } else {
        echo "<BR><BR>This type of encoding is not supported. Please use either hex oder ";
        echo "base64 encoding. Do not use ascii, because ascii encoding loses data.<BR><BR>";
      }

      /* from here on $t is in hex format, even if original encoding was base64 */

      /* "MACDAD" (ascii code in hex: 4d 41 43 44 41 44) is a key word used by
       * sfPortscan, rather than a real MAC address; cf. 
       * snort-2.6.0/doc/README.sfportscan
       * snort-2.6.0/src/preprocessors/spp_sfportscan.c
       * snort-2.6.0/src/preprocessors/flow/portscan/flowps_snort.c */
      if ( strlen($t) >= 24 && strncmp($t, '4d41434441444d4143444144', 24) != 0) 
      {
        $dst_mac = $t[0].$t[1].':'.$t[2].$t[3].':'.$t[4].$t[5].':'.$t[6].$t[7].':'.$t[8].$t[9].':'.$t[10].$t[11];
        $src_mac = $t[12].$t[13].':'.$t[14].$t[15].':'.$t[16].$t[17].':'.$t[18].$t[19].':'.$t[20].$t[21].':'.$t[22].$t[23];

        echo '
             <TABLE BORDER=1 WIDTH="90%">
                <TR><TD CLASS="iptitle" WIDTH=50 ROWSPAN=3 ALIGN=CENTER>MAC';
        echo '      <TD>';
        echo '         <TABLE BORDER=1 CELLPADDING=2>';
        echo '            <TR><TD class="plfieldhdr">'._NBSOURCEADDR.'</TD>
                              <TD class="plfieldhdr">&nbsp;'._NBDESTADDR.'&nbsp</TD></TR>
                          <TR><TD>'. $src_mac .'</TD>
                              <TD>'. $dst_mac .'</TD></TR>
                          <TR><TD>'. GetVendor($src_mac) .'</TD>
                              <TD>'. GetVendor($dst_mac) .'</TD></TR>';
        echo '         </TABLE>';
        echo '</TABLE></TD></TR>';
      }
      else
      {
        /* "MACDAD" indicates that this is an sfportscan packet.  This means
           the database does NOT contain a real packet.  Therefore 
           building a pcap file won't be possible. */
        $sf_portscan_flag = 1;
      }
    }
  }


  /* TCP */
  if ( $layer4_proto == "6" )  
  {
     $sql2 = "SELECT tcp_sport, tcp_dport, tcp_seq, tcp_ack, tcp_off, tcp_res, tcp_flags, tcp_win, ".
             "       tcp_csum, tcp_urp FROM tcphdr  WHERE sid='".$sid."' AND cid='".$cid."'";
     $result2 = $db->baseExecute($sql2);
     $myrow2 = $result2->baseFetchRow();

     $sql3 = "SELECT * FROM opt  WHERE sid='".$sid."' AND cid='".$cid."' AND opt_proto='6'";
     $result3 = $db->baseExecute($sql3);
     $num_opt = $result3->baseRecordCount();

      echo '
           <TABLE BORDER=1 WIDTH="90%">
              <TR><TD CLASS="layer4title" WIDTH=50 ROWSPAN=2 ALIGN=CENTER>TCP';
      echo '      <TD>';
      echo '         <TABLE BORDER=1 CELLPADDING=2>';
      echo '            <TR><TD class="plfieldhdr">'._SHORTSOURCE.'<BR> '._PORT.'</TD>
                            <TD class="plfieldhdr"> '._SHORTDEST.'<BR> &nbsp '._PORT.' &nbsp</TD>
                            <TD class="plfieldhdr">R<BR>1</TD>
                            <TD class="plfieldhdr">R<BR>0</TD>
                            <TD class="plfieldhdr">U<BR>R<BR>G</TD>
                            <TD class="plfieldhdr">A<BR>C<BR>K</TD>
                            <TD class="plfieldhdr">P<BR>S<BR>H</TD>
                            <TD class="plfieldhdr">R<BR>S<BR>T</TD>
                            <TD class="plfieldhdr">S<BR>Y<BR>N</TD>
                            <TD class="plfieldhdr">F<BR>I<BR>N</TD>
                            <TD class="plfieldhdr">seq #</TD>
                            <TD class="plfieldhdr">ack</TD>
                            <TD class="plfieldhdr">offset</TD>
                            <TD class="plfieldhdr">res</TD>
                            <TD class="plfieldhdr">window</TD>
                            <TD class="plfieldhdr">urp</TD>
                            <TD class="plfieldhdr">chksum</TD></TR>';
      $src_port = $myrow2[0].'<BR>';
      foreach ($external_port_link as $name => $baseurl) {
           $src_port = $src_port.'[<A HREF="'.$baseurl.$myrow2[0].'" TARGET="_ACID_PORT_">'.$name.'</A>] ';
      }
      $dst_port = $myrow2[1].'<BR>';
      foreach ($external_port_link as $name => $baseurl) {
           $dst_port = $dst_port.'[<A HREF="'.$baseurl.$myrow2[1].'" TARGET="_ACID_PORT_">'.$name.'</A>] ';
      } 
      echo '            <TR><TD class="plfield">'.$src_port.'</TD>';
      echo '                <TD class="plfield">'.$dst_port.'</TD>';
      echo '                <TD class="plfield">';
      if ( ($myrow2[6] & 128) != 0 )
           echo 'X';
      else
           echo '&nbsp;';
      echo '                    </TD><TD class="plfield">';
      if ( ($myrow2[6] & 64 ) != 0 )
           echo 'X';
      else
           echo '&nbsp;';
      echo '                    </TD><TD class="plfield">';
      if ( ($myrow2[6] & 32) != 0 )
           echo 'X';
      else
           echo '&nbsp;';
      echo '                    </TD><TD class="plfield">';
      if ( ($myrow2[6] & 16 ) != 0 )
           echo 'X';
      else
           echo '&nbsp;';
      echo '                    </TD><TD class="plfield">';
      if ( ($myrow2[6] & 8) != 0 )
           echo 'X';
      else
           echo '&nbsp;';
      echo '                    </TD><TD class="plfield">';
      if ( ($myrow2[6] & 4 ) != 0 )
           echo 'X';
      else
           echo '&nbsp;';
      echo '                    </TD><TD class="plfield">';
      if ( ($myrow2[6] & 2 ) != 0 )
           echo 'X';
      else
           echo '&nbsp;';
      echo '                    </TD><TD class="plfield">';
      if ( ($myrow2[6] & 1 ) != 0 )
           echo 'X';
      else
           echo '&nbsp;';
      echo '                    </TD>';
     
      echo '                <TD class="plfield">'.$myrow2[2].'</TD>';    
      echo '                <TD class="plfield">'.$myrow2[3].'</TD>';

	/* data offset is in 32 bit words, cf. RFC 793, 3.1 (= p. 16), 
	 * PrintTCPHeader() in snort-2.6.0/src/log.c
	 * DecodeTCP() in snort-2.6.0/src/decode.c
	 * #define TCP_OFFSET(tcph) in snort-2.6.0/src/decode.h
	 * Database() in snort-2.6.0/src/output-plugins/spo_database.c */
      echo '                <TD class="plfield">'. ($myrow2[4] << 2) .'</TD>';
      echo '                <TD class="plfield">'.$myrow2[5].'</TD>';
      echo '                <TD class="plfield">'.$myrow2[7].'</TD>';
      echo '                <TD class="plfield">'.$myrow2[9].'</TD>';
      echo '                <TD class="plfield">'.$myrow2[8].'<BR>=<BR>0x'.dechex($myrow2[8]).'</TD></TR>';
      echo '         </TABLE></TR>';
      echo '  <TR>';
      echo '      <TD>';
      echo '         <TABLE BORDER=1 CELLPADDING=4>';
      echo '           <TR><TD CLASS="layer4title" ALIGN=CENTER ROWSPAN='.(($num_opt != 0) ? ($num_opt+1) : 1).'>'._OPTIONS.'</TD>';

      if ( $num_opt != 0)
      {
         echo '            <TD></TD>
                           <TD class="plfieldhdr">'._CODE.'</TD>
                           <TD class="plfieldhdr">'._LENGTH.'</TD>
                           <TD class="plfieldhdr">'._DATA.'</TD>';

	 /* Check which kind of encoding is used: */
	 $sql4 = 'SELECT encoding FROM sensor WHERE sid='.$sid;
	 $result4 = $db->baseExecute($sql4);
	 $myrow4 = $result4->baseFetchRow();
	 $result4->baseFreeRows();

         for ( $i = 0; $i < $num_opt; $i++)
         {
             $myrow3 = $result3->baseFetchRow();
             echo '    <TR><TD class="plfield">#'.($i+1).'</TD>';
             echo '        <TD class="plfield">'.TCPOption2str($myrow3[4]).'</TD>';
             echo '        <TD class="plfield">'.$myrow3[5].'</TD>';
	     echo '        <TD class="plfield">';

	     if ($myrow4[0] == 1) 
             /* base64 encoding */
	     {
	       if ($myrow3[5] > 0)
	       {
		 $mystr = bin2hex(base64_decode($myrow3[6]));
		 for ($j = 0; $j < $myrow3[5] * 2; $j = $j + 2)
		 {
	           echo $mystr[$j];
		   echo $mystr[$j + 1];
		   echo '&nbsp;';
		 }
		 echo '<BR>';
		 if (TCPOption2str($myrow3[4]) == "(8) TS")
		 /* timestamp: cf. RFC 1323, 3.2 */
		 {
			 /* TSval */
			 $tmpstr = "";
			 for ($j = 0; $j < 8; $j++)
			 {
				 $tmpstr = $tmpstr . $mystr[$j];
			 }
			 $TSval = hexdec($tmpstr);
			 echo '        TSval: ' . $TSval . '<BR>';

			 /* TSecr */
			 $tmpstr = "";
			 for ($j = 8; $j < 16; $j++)
			 {
		           $tmpstr = $tmpstr . $mystr[$j];
			 }
			 $TSecr = hexdec($tmpstr);
			 echo '        TSecr: ' . $TSecr . '<BR>';
		 }
		 
		 echo '        </TD></TR>';
	       }
	       else
	       {
	         echo '{No data}</TD></TR>';
	       }
	     }
	     else
	     {
	       /* hexadecimal encoding (and ASCII) */
               if ($myrow3[6] != "" )
                 echo $myrow3[6];
               else
                 echo '&nbsp;';
	       echo '</TD></TR>';
	     }
         }
      }
      else
      {
         echo '             <TD class="plfield"> &nbsp;&nbsp;&nbsp; <I>'._NONE.' </I></TD></TR>';
      }
      echo '         </TABLE></TD></TR>';                       

      echo '</TABLE>';

      $result2->baseFreeRows();
      $result3->baseFreeRows();
  }

  /* UDP */
  if ( $layer4_proto == "17" )
  {

     $sql2 = "SELECT * FROM udphdr  WHERE sid='".$sid."' AND cid='".$cid."'";
     $result2 = $db->baseExecute($sql2);
     $myrow2 = $result2->baseFetchRow();

     echo '
           <TABLE BORDER=1 WIDTH="90%">
              <TR><TD CLASS="layer4title" WIDTH=50 ROWSPAN=2 ALIGN=CENTER>UDP</TD>';
     echo '      <TD>';
     echo '         <TABLE BORDER=1 CELLPADDING=2>';
     echo '            <TR><TD class="plfieldhdr">'._SOURCEPORT.'</TD>
                            <TD class="plfieldhdr">'._DESTPORT.'</TD>
                            <TD class="plfieldhdr">'._LENGTH.'</TD></TR>';

     $src_port = $myrow2[2].'<BR>';
     foreach ($external_port_link as $name => $baseurl) {
        $src_port = $src_port.'[<A HREF="'.$baseurl.$myrow2[2].'" TARGET="_ACID_PORT_">'.$name.'</A>] ';
     }
    
     $dst_port = $myrow2[3].'<BR>';
     foreach ($external_port_link as $name => $baseurl) {
        $dst_port = $dst_port.'[<A HREF="'.$baseurl.$myrow2[3].'" TARGET="_ACID_PORT_">'.$name.'</A>] ';
     } 

     echo '            <TR><TD class="plfield">'.$src_port.'</TD>';
     echo '                <TD class="plfield">'.$dst_port.'</TD>';
     echo '                <TD class="plfield">'.$myrow2[4].'</TD></TR>';
     echo '         </TABLE></TD></TR>';
     echo '</TABLE>';

     $result2->baseFreeRows();
  }

  /* ICMP */
  if ( $layer4_proto == "1" )
  {
     $sql2 = "SELECT icmp_type, icmp_code, icmp_csum, icmp_id, icmp_seq FROM icmphdr ".
             "WHERE sid='".$sid."' AND cid='".$cid."'";
     $result2 = $db->baseExecute($sql2);
     $myrow2 = $result2->baseFetchRow();
     $ICMPitype = $myrow2[0];
     $ICMPicode = $myrow2[1];


     echo '
           <TABLE BORDER=1 WIDTH="90%">
              <TR><TD class="layer4title" WIDTH=50 ROWSPAN=2 ALIGN=CENTER>ICMP';
     echo '      <TD>';
     echo '         <TABLE BORDER=1 CELLPADDING=2>';
     echo '            <TR><TD class="plfieldhdr">'._TYPE.'</TD>
                           <TD class="plfieldhdr">'._CODE.'</TD>
                           <TD class="plfieldhdr">checksum</TD>';
		if ($ICMPitype == "5") {
		 echo '                <TD class="plfieldhdr">gateway address</TD>';
     echo '                <TD class="plfieldhdr">gateway hostname</TD>';
		} else {
     echo '                <TD class="plfieldhdr">'._ID.'</TD>
                           <TD class="plfieldhdr">seq #</TD>';
		}

		 echo '            </TR>';
     echo '            <TR><TD class="plfield">('.$myrow2[0].') '.ICMPType2str($myrow2[0]).'</TD>';
     echo '                <TD class="plfield">('.$myrow2[1].') '.ICMPCode2str($myrow2[0],$myrow2[1]).'</TD>';
     echo '                <TD class="plfield">'.$myrow2[2].'<BR>=<BR>0x'. dechex($myrow2[2])  .'</TD>';

		if ($ICMPitype == "5") {
		 $gateway_numeric_ip = (integer)($myrow2[3] / 256) . "." . ($myrow2[3] % 256) . ".". (integer)($myrow2[4] / 256) . "." . ($myrow2[4] % 256);
		 $gateway_hostname = basegetHostByAddr($gateway_numeric_ip, $db, $dns_cache_lifetime); 

     echo '                <TD class="plfield"><A HREF="base_stat_ipaddr.php?ip=' . $gateway_numeric_ip . '&amp;netmask=32" TARGET="_PL_SIP">' . $gateway_numeric_ip . '</A></TD>';
		 echo '                <TD class="plfield">' . $gateway_hostname   . '</TD>';
		} else {
     echo '                <TD class="plfield">'.$myrow2[3].'</TD>';
     echo '                <TD class="plfield">'.$myrow2[4].'</TD>';
		}

     echo '            </TR>';
     echo '         </TABLE>';
     echo '</TABLE>';

     

     $result2->baseFreeRows();
  }

  /* Print the Payload */
  $sql2 = "SELECT data_payload FROM data WHERE sid='".$sid."' AND cid='".$cid."'";
  $result2 = $db->baseExecute($sql2);
  $myrow2 = $result2->baseFetchRow();
  $result2->baseFreeRows();
  !empty($myrow2) ? $payload = $myrow2[0] : '';

  echo '
        <TABLE BORDER=1 WIDTH="90%">
           <TR><TD class="payloadtitle" WIDTH=50 ROWSPAN=2 ALIGN=CENTER>Payload';
           echo("<br><br>".PrintCleanURL());
           echo("<br>".PrintBinDownload($db, $cid, $sid));
           if ($sf_portscan_flag != 1)
           {
             echo("<br>".PrintPcapDownload($db, $cid, $sid));
           }
           else
           {
             echo "<br>(Download in pcap format is NOT possible with portscan data)";
           }
  echo '       <TD>';

  if ( $payload )
  {
     /* print the packet based on encoding type */
     PrintPacketPayload($payload, $encoding, 1);

     if ( $layer4_proto == "1" )
     {
          if ( /* IF ICMP source quench */ 
               ($ICMPitype == "4" && $ICMPicode == "0") ||
               /* IF ICMP redirect */
               ($ICMPitype == "5") ||
               /* IF ICMP parameter problem */
               ($ICMPitype == "12" && $ICMPicode == "0") ||
               /* IF ( network, host, port unreachable OR 
               frag needed OR network admin prohibited OR filtered) */
               ($ICMPitype == "3" || $ICMPitype == "11" ) &&
               $ICMPicode == "0" || $ICMPicode == "1" ||
               $ICMPicode == "3" || $ICMPicode == "4" || 
               $ICMPicode == "9" || $ICMPicode == "13" )
          {
              /* 0 == hex, 1 == base64, 2 == ascii; cf. snort-2.4.4/src/plugbase.h */
              if ($encoding == 1) {
                 /* encoding is base64 */
                 $work = bin2hex(base64_decode(str_replace("\n","",$payload)));
              } else {
                 /* assuming that encoding is hex */
                 $work = str_replace("\n","",$payload);
              }






              /* 
               *  - depending on how the packet logged, 32-bits of NULL padding after
               *    the checksum may still be present.
               */
              if ( substr($work, 0, 8) == "00000000" ) {
                 $offset = 8;
							}
              /* for dest. unreachable, frag needed and DF bit set indent the padding
               * of MTU of next hop
               */
              else if ( ($ICMPitype == "3") && ($ICMPicode == "4") ) {
                 $offset += 8;
              }
            

              $icmp_proto = hexdec($work[18+$offset].$work[19+$offset]);

              $payload_ip_checksum = "0x" . 
                          ($work[20 + $offset] . $work[21 + $offset]) .
                          ($work[22 + $offset] . $work[23 + $offset]); 

              $icmp_src = hexdec($work[24+$offset].$work[25+$offset]).".".
                          hexdec($work[26+$offset].$work[27+$offset]).".".
                          hexdec($work[28+$offset].$work[29+$offset]).".".
                          hexdec($work[30+$offset].$work[31+$offset]);
              $icmp_dst = hexdec($work[32+$offset].$work[33+$offset]).".".
                          hexdec($work[34+$offset].$work[35+$offset]).".".
                          hexdec($work[36+$offset].$work[37+$offset]).".".
                          hexdec($work[38+$offset].$work[39+$offset]);
              
              



              $hdr_offset = ($work[$offset+1]) * 8 + $offset;
              $icmp_src_port = hexdec($work[$hdr_offset].$work[$hdr_offset+1].$work[$hdr_offset+2].$work[$hdr_offset+3]);
              $icmp_dst_port = hexdec($work[$hdr_offset+4].$work[$hdr_offset+5].$work[$hdr_offset+6].$work[$hdr_offset+7]);


              if ($ICMPitype == "5") {
                 $seq_no_hex = ($work[ 8 + $hdr_offset]) . ($work[ 9 + $hdr_offset]) .
                               ($work[10 + $hdr_offset]) . ($work[11 + $hdr_offset]) .
                               ($work[12 + $hdr_offset]) . ($work[13 + $hdr_offset]) .
                               ($work[14 + $hdr_offset]) . ($work[15 + $hdr_offset]);
                 $seq_no = hexdec($seq_no_hex);
              }


 
              echo '<TABLE BORDER=1>';
              echo '<TR>';
              
              echo '<TD class="plfieldhdr">Protocol</TD>';
              echo '<TD class="plfieldhdr">Org.Source<BR>IP</TD>';
              echo '<TD class="plfieldhdr">Org.Source<BR>Name</TD>';

              if ( $icmp_proto == "6" || $icmp_proto == "17" ) {
                 echo '<TD class="plfieldhdr">Org.Source<BR>Port</TD>';
              }

              echo '<TD class="plfieldhdr">Org.Destination<BR>IP</TD>';
              echo '<TD class="plfieldhdr">Org.Destination<BR>Name</TD>';

              if ( $icmp_proto == "6" || $icmp_proto == "17" ) {
                 echo '<TD class="plfieldhdr">Org.Destination<BR>Port</TD>';
              }

              if ( $ICMPitype == "5" ) {
                 echo '<TD class="plfieldhdr">IP Hdr Checksum</TD>';
                 echo '<TD class="plfieldhdr">Sequence Number</TD>';
              }

              echo '</TR>';
              echo '<TR>';

              
              
              echo '<TD class="plfield">'.IPProto2Str($icmp_proto).'</TD>';
              echo '<TD class="plfield">';
              echo '<A HREF="base_stat_ipaddr.php?ip='.$icmp_src.'&amp;netmask=32" TARGET="_PL_SIP">'.$icmp_src.'</A></TD>';
              echo '<TD class="plfield">'.baseGetHostByAddr($icmp_src, $db, $dns_cache_lifetime).'</TD>';

              if ( $icmp_proto == "6" || $icmp_proto == "17" ) {
                 echo '<TD class="plfield">'.$icmp_src_port.'</TD>';
              }

              echo '<TD class="plfield">';
              echo '<A HREF="base_stat_ipaddr.php?ip='.$icmp_dst.'&amp;netmask=32" TARGET="_PL_DIP">'.$icmp_dst.'</A></TD>';
              echo '<TD class="plfield">'.baseGetHostByAddr($icmp_dst, $db, $dns_cache_lifetime).'</TD>';

              if ( $icmp_proto == "6" || $icmp_proto == "17" ) {
                 echo '<TD class="plfield">'.$icmp_dst_port.'</TD>';
              }

              if ($ICMPitype == "5") {
                echo '<TD class="plfield">' . $payload_ip_checksum . '</TD>';
                echo '<TD class="plfield">' . $seq_no . '</TD>';
              }

              echo '</TR>';
              echo '</TABLE>';
         }
     }
  }
  else
  {
     /* Don't have payload so lets print out why by checking the detail level */

     /* if have fast detail level */
     if ( $detail == "0" )
        echo '<BR> &nbsp <I>'._QANOPAYLOAD.'</I><BR>';
     else
        echo '<BR> &nbsp <I>'._NONE.' </I><BR>';
  }

  echo '</TABLE></BLOCKQUOTE><P>';

  echo "<CENTER>$previous &nbsp&nbsp&nbsp $next</CENTER>";

  $qs->PrintAlertActionButtons();
  $qs->SaveState();
ExportHTTPVar("caller", $caller); // QueryState Onject property Override.
ExportHTTPVar("sort_order", $sort_order);
NLIO('</form>',2);
$et->Mark("Get Query Elements");
PrintBASESubFooter();
?>
