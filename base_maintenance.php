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
//            Purpose: status and event/dns/whois cache maintenance 
//
//          Author(s): Nathan Gibbs
//                     Kevin Johnson

$sc = DIRECTORY_SEPARATOR;
require_once("includes$sc" . 'base_krnl.php');
include("$BASE_path/includes/base_include.inc.php");
include_once("$BASE_path/base_db_common.php");
include_once("$BASE_path/base_stat_common.php");
include_once("$BASE_path/setup/setup_db.inc.php");

$roleneeded = 10000;
$BUser = new BaseUser();
$AdminAuth = false; // Admin Actions Not Authorized by default.
if ($Use_Auth_System == 1){
	if ( base_array_key_exists('standalone',$_POST) ){
		$SaM = $_POST['standalone'];
	}else{
		$SaM = 'no';
	}
	if ($SaM == 'yes'){
		$usrrole = $BUser->AuthenticateNoCookie(
			filterSql($_POST['user']), filterSql($_POST['pwd'])
		);
		if ($usrrole == 'Failed'){
			HTTP_header('HTTP/1.0 401');
		}elseif ($usrrole > $roleneeded){
			HTTP_header('HTTP/1.0 403');
		}elseif ( $usrrole == 1 ){
			$AdminAuth = true;
		}
		$BCR->AddCap('UIMode', 'Con');
	}else{
		AuthorizedRole($roleneeded);
		$AdminAuth = AuthorizedRole(1);
	}
}
$cs = new CriteriaState("base_maintenance.php");
$cs->ReadState();
$db = NewBASEDBConnection($DBlib_path, $DBtype); // Connect to the Alert DB.
$db->baseDBConnect(
	$db_connect_method, $alert_dbname, $alert_host, $alert_port, $alert_user,
	$alert_password
);

// UI Presentation(s) Web & CLI.
$page_title = _MAINTTITLE;
if ($SaM == 'yes'){
	// Limit run time. PrintBASESubHeader() does this for the Web UI.
	if ( ini_get('safe_mode') != true ){
		set_time_limit($max_script_runtime);
	}
	NLIO("BASE $page_title - Logged in as: ".XSSPrintSafe($_POST['user']));
}else{
	PrintBASESubHeader(
		$page_title, $page_title, $cs->GetBackLink(), $refresh_all_pages
	);
	print '<br/>';
}

$submit = ImportHTTPVar("submit", VAR_ALPHA | VAR_SPACE);
if ( $AdminAuth ){ // Issue #146 Fix
	// Lockout non admin users from admin commands
	if ($SaM != 'yes'){
		print '<FORM METHOD="POST" ACTION="base_maintenance.php">';
	}
	if ( $debug_mode > 0 ){
		NLIO("submit = '$submit'");
	}
  $repair_output = NULL;
  if ( $submit == "Update Alert Cache" )
  {
     UpdateAlertCache($db);
  }
  else if ( $submit == "Rebuild Alert Cache" )
  {
     DropAlertCache($db);
     UpdateAlertCache($db);
  }
  else if ( $submit == "Update IP Cache" )
  {
     UpdateDNSCache($db);     
  }
  else if ( $submit == "Rebuild IP Cache" )
  {
     DropDNSCache($db);
     UpdateDNSCache($db);
  }
  else if ( $submit == "Update Whois Cache" )
  {
     UpdateWhoisCache($db);     
  }
  else if ( $submit == "Rebuild Whois Cache" )
  {
     DropWhoisCache($db);
     UpdateWhoisCache($db);
  }
  else if ( $submit == "Repair Tables")
  {
     //$repair_output = RepairDBTables($db);
     CreateBASEAG($db);
  }
  else if ( $submit == "Clear Data Tables")
  {
     ClearDataTables($db);
  }
	if ($SaM == 'yes'){
		if ($submit != ''){
			NLIO('Executed command: ' . XSSPrintSafe($submit));
		}
	}
}
NLIO();

$SW_Cli = 'unknown';
if ( base_array_key_exists('HTTP_USER_AGENT',$_SERVER) ){
	$SW_Cli = $_SERVER['HTTP_USER_AGENT'];
}
$title = _MNTCLIENT;
if ( $AdminAuth ){ // Issue #146 Fix
	$title = _MNTPHP;
	$SW_Svr = 'unknown';
	if ( base_array_key_exists('SERVER_SOFTWARE',$_SERVER) ){
		$SW_Svr = $_SERVER['SERVER_SOFTWARE'];
	}
}
if ($SaM == 'yes'){
	if ($submit == 'status'){
		if ($AdminAuth){
			NLIO($title);
		}
		NLIO(_MNTCLIENT.' '.XSSPrintSafe($SW_Cli));
	}
}else{
	PrintFramedBoxHeader($title, '#669999', 1,3,'left');
	NLIO('<b>'._MNTCLIENT.'</b> '.XSSPrintSafe($SW_Cli).'<br/>',4);
}
if ( $AdminAuth ){ // Issue #146 Fix
	$IER = ini_get('error_reporting');
	$ER_lst = array();
	if ( ($IER & E_ERROR) > 0 ){
		array_push($ER_lst, 'E_ERROR');
	}
	if ( ($IER & E_WARNING) > 0 ){
		array_push($ER_lst, 'E_WARNING');
	}
	if ( ($IER & E_PARSE) > 0 ){
		array_push($ER_lst, 'E_PARSE');
	}
	if ( ($IER & E_NOTICE) > 0 ){
		array_push($ER_lst, 'E_NOTICE');
	}
	if ( ($IER & E_CORE_WARNING) > 0 ){
		array_push($ER_lst, 'E_CORE_WARNING');
	}
	if ( ($IER & E_CORE_ERROR) > 0 ){
		array_push($ER_lst, 'E_CORE_ERROR');
	}
	if ( ($IER & E_COMPILE_ERROR) > 0 ){
		array_push($ER_lst, 'E_COMPILE_ERROR');
	}
	if ( ($IER & E_COMPILE_WARNING) > 0 ){
		array_push($ER_lst, 'E_COMPILE_WARNING');
	}
	$module_lst = get_loaded_extensions();
	foreach( $module_lst as $key => $val ){
		if ( preg_match("/ /", $val) ){
			$module_lst[$key] = "'$val'";
		}
	}
	$PERL = ' '. implode(', ', $ER_lst);
	$PLM = ' ' . implode(', ', $module_lst);
	if ($SaM == 'yes'){
		if ($submit == 'status'){
			NLIO(_MNTSERVER . ' ' . XSSPrintSafe($SW_Svr));
			NLIO(_MNTSERVERHW .' ' . php_uname());
			NLIO(_MNTPHPVER . ' ' . phpversion());
			NLIO('PHP API: ' . php_sapi_name());
			NLIO(
				_MNTPHPLOGLVL . ' (' . $IER . ')' . $PERL
			);
			NLIO(_MNTPHPMODS . $PLM);
		}
	}else{
print'         <B>'._MNTSERVER.'</B> '.XSSPrintSafe($SW_Svr).'<BR>
         <B>'._MNTSERVERHW.'</B> '.php_uname().'<BR>
         <B>'._MNTPHPVER.'</B> '.phpversion().'<BR>
         <B>PHP API:</B> '.php_sapi_name().'<BR>';

		NLIO(
			'<b>' ._MNTPHPLOGLVL . ': </b> (' . $IER . ')' . $PERL . '<br/>', 
			6
		);
		NLIO('<b>' ._MNTPHPMODS . ': </b>' . $PLM . '<br/>', 6);
	}
}
if ($SaM == 'yes'){
	if ($submit == 'status'){
		NLIO();
	}
}else{
	PrintFramedBoxFooter(1,3);
	NLIO ('<br/>',3);
}
if ( $AdminAuth ){ // Issue #146 Fix
	$BV = $BCR->GetCap('BASE_Ver');
	$BInID = $BCR->GetCap('BASE_InID');
	$BK = $BCR->GetCap('BASE_Kernel');
	$BR = $BCR->GetCap('BASE_RTL');
	$BDev = $BCR->GetCap('BASE_Dev');
	$BF_lst = array('Mail', 'Mime', 'Graph');
	foreach( $BF_lst as $val ){
		$BF_St[$val] = $BCR->GetCap($val);
	}
	$imgc = NLI('',6);
	$imgc .= "<img border='0' src='".$BASE_urlpath ."/images/";
	$BDevI = $imgc;
	if ( $BDev ){ // TD These.
		$BDevStatus = 'Development';
		$BDevI .= "button_exclamation.png' alt='button_exclamation";
	}else{
		$BDevStatus = 'Official';
		$BDevI .= "greencheck.gif' alt='button_greencheck";
	}
	$BDevI .= "'/>";
	$BADB = $BCR->GetCap('BASE_ADB');
	if ($SaM == 'yes'){
		if ($submit == 'status'){
			NLIO("BASE Version: $BV");
			if ( LoadedString($BInID) ){
				NLIO("Install ID: $BInID");
			}
			NLIO("Kernel: $BK Runtime: $BR");
			NLIO("Release: $BDevStatus");
			NLIO("Features:");
			foreach( $BF_St as $key => $val ){
				$tmp = "$key ";
				if ( !$val ){
					$tmp .= 'not ';
				}
				$tmp .= 'installed.';
				NLIO($tmp);
			}
			NLIO();
			NLIO(_DATABASE);
			NLIO(_MNTDBALV." $ADODB_vers");
			NLIO(_MNTDBTYPE." $DBtype");
			NLIO(_MNTDBALERTNAME." $alert_dbname");
			if ( $BADB ){
				$ADBStatus = _MNTDBARCHNAME." $archive_dbname";
			}else{
				$ADBStatus = 'Archive DB: not enabled.'; // TD This.
			}
			NLIO($ADBStatus);
			if ( $repair_output != '' ){
				NLIO($repair_output);
			}
			NLIO();
		}
	}else{
		$ADBI = $imgc;
		$ADBStatus = '<b>';
		if ( $BADB ){
			$ADBStatus .= _MNTDBARCHNAME
			. ": </b>$archive_dbname";
			$ADBI .= "greencheck.gif' alt='button_greencheck";
		}else{ // TD This.
			$ADBStatus .= 'Archive DB: </b>not enabled.';
			$ADBI .= "redcheck.gif' alt='button_redcheck";
		}
		$ADBI .= "'/>";
		PrintFramedBoxHeader('BASE Build:', '#669999', 1, 3, 'left');
		NLIO('<b>Version: </b>' . XSSPrintSafe($BV) . $BDevI . '<br/>', 6);
		if ( LoadedString($BInID) ){
			NLIO('<b>Install ID: </b>' . XSSPrintSafe($BInID) . '<br/>', 6);
		}
		NLIO(
			'<b>Kernel: </b>' . XSSPrintSafe($BK)
			. ' <b>Runtime: </b>' . XSSPrintSafe($BR) . '<br/>',
			6
		);
		NLIO(
			'<b>Release: </b>' . XSSPrintSafe($BDevStatus) . $BDevI . '<br/>',
			6
		);
		NLIO('<b>Features: </b>', 6);
		foreach( $BF_St as $key => $val ){
			$FI = "greencheck.gif' alt='button_greencheck";
			$tmp = "<b>$key: </b>";
			if ( !$val ){
				$tmp .= 'not ';
				$FI = "redcheck.gif' alt='button_redcheck";
			}
			$FI .= "'/>";
			$tmp .= 'installed.';
			NLIO("$tmp$imgc$FI",6);
		}
		PrintFramedBoxFooter(1,3);
		NLIO ('<br/>',3);
		PrintFramedBoxHeader(_DATABASE, '#669999', 1,3,'left');
		NLIO( '<b>' . _MNTDBALV . ": </b>$ADODB_vers" . '<br/>', 6);
  echo "<B>"._MNTDBTYPE."</B> $DBtype <BR>  
        <B>"._MNTDBALERTNAME."</B> $alert_dbname <BR>
";
		NLIO("$ADBStatus$ADBI" . '<br/>', 6);
		NLIO(
			"<input class='admin' type='submit' neme='submit'"
			. " value='Repair Tables'>",
			6
		);
		NLIO(
			"<input class='admin' type='submit' neme='submit'"
			. " value='Clear Data Tables'>",
			6
		);
  echo $repair_output;
		PrintFramedBoxFooter(1,3);
		NLIO ('<br/>',3);
	}
}
  $event_cnt_lst = $db->baseExecute("SELECT COUNT(*) FROM event");
  $event_cnt_row = $event_cnt_lst->baseFetchRow();
  $event_cnt = $event_cnt_row[0];
  $event_cnt_lst->baseFreeRows();

  $cache_event_cnt_lst = $db->baseExecute("SELECT COUNT(*) FROM acid_event");
  $cache_event_cnt_row = $cache_event_cnt_lst->baseFetchRow();
  $cache_event_cnt = $cache_event_cnt_row[0];
  $cache_event_cnt_lst->baseFreeRows();

  $uncached_sip_cnt = UniqueSrcIPCnt($db);
  $uncached_dip_cnt = UniqueDstIPCnt($db);
  
  $ip_result = $db->baseExecute("SELECT COUNT(DISTINCT ip_src) FROM acid_event ".
                                "INNER JOIN acid_ip_cache ON ipc_ip = ip_src ".
                                "WHERE ipc_fqdn is not NULL");
  $row = $ip_result->baseFetchRow();
  $ip_result->baseFreeRows();
  $cached_sip_cnt = $row[0];

  $ip_result = $db->baseExecute("SELECT COUNT(DISTINCT ip_dst) FROM acid_event ".
                                "INNER JOIN acid_ip_cache ON ipc_ip = ip_dst ".
                                "WHERE ipc_fqdn is not NULL");
  $row = $ip_result->baseFetchRow();
  $ip_result->baseFreeRows();
  $cached_dip_cnt = $row[0];

  $ip_result = $db->baseExecute("SELECT COUNT(DISTINCT ip_src) FROM acid_event ".
                                "INNER JOIN acid_ip_cache ON ipc_ip = ip_src ".
                                "WHERE ipc_whois is not NULL");
  $row = $ip_result->baseFetchRow();
  $ip_result->baseFreeRows();
  $cached_swhois_cnt = $row[0];

  $ip_result = $db->baseExecute("SELECT COUNT(DISTINCT ip_dst) FROM acid_event ".
                                "INNER JOIN acid_ip_cache ON ipc_ip = ip_dst ".
                                "WHERE ipc_whois is not NULL");
  $row = $ip_result->baseFetchRow();
  $ip_result->baseFreeRows();
  $cached_dwhois_cnt = $row[0];

if ($SaM == 'yes'){
	if ($submit == 'status'){
		NLIO(_MNTAIC);
		NLIO(_MNTAICTE.' '.$event_cnt);
		NLIO(_MNTAICCE.' '.$cache_event_cnt);
		NLIO();
		NLIO(_MNTIPAC.':');
		NLIO(
			_MNTIPACUSIP.' '.$uncached_sip_cnt.' '.
			_MNTIPACDNSC.' '.$cached_sip_cnt.' '.
			_MNTIPACWC.' '.$cached_swhois_cnt
		);
		NLIO(
			_MNTIPACUDIP.' '.$uncached_dip_cnt.' '.
			_MNTIPACDNSC.' '.$cached_dip_cnt.' '.
			_MNTIPACWC.' '.$cached_dwhois_cnt);
		NLIO();
	}
}else{
	PrintFramedBoxHeader(_MNTAIC, '#669999', 0,3,'left');
	NLIO("<td style='text-align: left; width: 25%;'>",5);

  echo '<B>'._MNTAICTE.'</B> '.$event_cnt.'&nbsp&nbsp
        <B>'._MNTAICCE.'</B> '.$cache_event_cnt;

	if ( $AdminAuth ){ // Issue #146 Fix
		NLIO(
			"<input type='submit' neme='submit' value='Update Alert Cache'>",
			6
		);
		NLIO(
			"<input class='admin' type='submit' neme='submit'"
			. " value='Rebuild Alert Cache'>",
			6
		);
	}
	PrintFramedBoxFooter(1,3);
	NLIO ('<br/>',3);
	PrintFramedBoxHeader(_MNTIPAC, '#669999', 1,3,'left');

  echo '<B>'._MNTIPACUSIP.'</B> '.$uncached_sip_cnt.'&nbsp;&nbsp&nbsp;'.
       '<B>'._MNTIPACDNSC.'</B> '.$cached_sip_cnt.'&nbsp;&nbsp;&nbsp;'.
       '<B>'._MNTIPACWC.'</B> '.$cached_swhois_cnt.'<BR>'.
       '<B>'._MNTIPACUDIP.'</B> '.$uncached_dip_cnt.'&nbsp;&nbsp&nbsp;'.
       '<B>'._MNTIPACDNSC.'</B> '.$cached_dip_cnt.'&nbsp;&nbsp;&nbsp;'.
       '<B>'._MNTIPACWC.':</B> '.$cached_dwhois_cnt.'<BR>';
	if ( $AdminAuth ){ // Issue #146 Fix
		NLIO(
			"<input type='submit' neme='submit' value='Update IP Cache'>",
			6
		);
		NLIO(
			"<input type='submit' neme='submit' value='Update Whois Cache'>",
			6
		);
		NLIO(
			"<input class='admin' type='submit' neme='submit'"
			. " value='Rebuild IP Cache'>",
			6
		);
		NLIO(
			"<input class='admin' type='submit' neme='submit'"
			. " value='Rebuild Whois Cache'>",
			6
		);
	}
	PrintFramedBoxFooter(1,3);
	NLIO ('<br/>',3);
	if ( $AdminAuth ){ // Issue #146 Fix
		NLIO('</form>',2);
	}
	PrintBASESubFooter();
}
?>
