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

$SW_Cli = 'unknown';
if( is_key('HTTP_USER_AGENT', $_SERVER) ){
	$SW_Cli = $_SERVER['HTTP_USER_AGENT'];
}
$SaM = 'no';
if( is_key('standalone', $_POST) ){ // Detect BASEcli
	if( preg_match('/^BASE CLI\/[0-9.]+( libwww-perl\/[0-9.]+)?$/', $SW_Cli) ){
		$SaM = $_POST['standalone'];
	}else{
		HTTP_header('', 400);
	}
}
if( $Use_Auth_System == 1 ){
	if( $SaM == 'yes' ){
		$usrrole = $BUser->AuthenticateNoCookie(
			filterSql($_POST['user']), filterSql($_POST['pwd'])
		);
		if( $usrrole == 'Failed' ){
			HTTP_header('', 401);
		}elseif( $usrrole > $roleneeded ){
			HTTP_header('', 403);
		}elseif( $usrrole == 1 ){
			$AdminAuth = true;
		}
		$BCR->AddCap('UIMode', 'Con');
	}else{
		AuthorizedRole($roleneeded);
		$AdminAuth = ARC(1);
	}
}

$db = NewBASEDBConnection($DBlib_path, $DBtype); // Connect to the Alert DB.
$db->baseDBConnect(
	$db_connect_method, $alert_dbname, $alert_host, $alert_port, $alert_user,
	$alert_password
);
$cs = new CriteriaState('base_maintenance.php');
$cs->ReadState();

// UI Presentation(s) Web & CLI.
$page_title = _MAINTTITLE;
if ($SaM == 'yes'){
	// Limit run time. PrintBASESubHeader() does this for the Web UI.
	if ( ini_get('safe_mode') != true ){
		set_time_limit($max_script_runtime);
	}
	NLIO("BASE $page_title - Logged in as: " . XSSPrintSafe($_POST['user']));
	NLIO();
}else{
	PrintBASESubHeader(
		$page_title, $page_title, $cs->GetBackLink(), $refresh_all_pages
	);
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
	if( $SaM == 'yes' ){
		if( LoadedString($submit) ){
			NLIO('Executed command: ' . XSSPrintSafe($submit));
		}
	}
}

$title = _MNTCLIENT;
if( $AdminAuth ){ // Issue #146 Fix
	$title = _MNTPHP;
	$SW_Svr = 'unknown';
	if( is_key('SERVER_SOFTWARE', $_SERVER) ){
		$SW_Svr = XSSPrintSafe($_SERVER['SERVER_SOFTWARE']);
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
	NLIO(
		'<b>' . _MNTCLIENT . ': </b>' . Icon('client', _MNTCLIENT)
		. XSSPrintSafe($SW_Cli) . '<br/>', 4
	);
}
if( $AdminAuth ){ // Issue #146 Fix
	$PF_lst = array('Mail', 'GD', 'GMP');
	foreach( $PF_lst as $val ){
		$PF_St[$val] = $BCR->GetCap("PHP_$val");
	}
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
			NLIO(_MNTSERVERHW .' ' . php_uname());
			NLIO(_MNTSERVER . ' ' . $SW_Svr);
			NLIO(_MNTPHPVER . ' ' . phpversion());
			NLIO('PHP API: ' . php_sapi_name());
			NLIO(
				_MNTPHPLOGLVL . ' (' . $IER . ')' . $PERL
			);
			NLIO(_MNTPHPMODS . $PLM);
			NLIO('PHP Capabilities: ');
			foreach( $PF_St as $key => $val ){
				$tmp = "$key ";
				if ( !$val ){
					$tmp .= 'not ';
				}
				$tmp .= 'installed.';
				NLIO($tmp);
			}
			NLIO();
		}
	}else{
		NLIO(
			'<b>' . _MNTSERVERHW . ': </b>' . php_uname() . '<br/>',
			6
		);
		NLIO(
			'<b>' . _MNTSERVER . ": </b>$SW_Svr<br/>",
			6
		);
		NLIO(
			'<b>HTTP PHP API: </b>' . php_sapi_name() . '<br/>',
			6
		);
		NLIO(
			'<b>' . _MNTPHPVER . ': </b>' . phpversion() . '<br/>',
			6
		);
		NLIO(
			'<b>' . _MNTPHPLOGLVL . ': </b> (' . $IER . ')' . $PERL . '<br/>',
			6
		);
		NLIO('<b>' . _MNTPHPMODS . ": </b>$PLM<br/>", 6);
		NLIO('<b>PHP Capabilities: </b>', 6);
		foreach( $PF_St as $key => $val ){
			$FI = 'yes';
			$tmp = "$key: ";
			if ( !$val ){
				$tmp .= 'not ';
				$FI = 'no';
			}
			if ( LoadedString($val) ){
				$tmp .= "$val ";
			};
			$tmp .= 'installed.';
			NLIO("<b>$tmp</b>", 6);
			printIcon($FI, $tmp, 6);
		}
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

$BV = $BCR->GetCap('BASE_Ver');
$BInID = $BCR->GetCap('BASE_InID');
$BDev = $BCR->GetCap('BASE_Dev');
if( $BDev ){ // TD These.
	$BDevStatus = 'Development';
	$Icon = 'exclamation';
}else{
	$BDevStatus = 'Official';
	$Icon = 'yes';
}
if( $SaM == 'yes' ){
	if( $submit == 'status' ){
		NLIO("BASE Version: $BV");
		if( LoadedString($BInID) ){
			NLIO("Install ID: $BInID");
		}
	}
}else{
	$BDevI = Icon($Icon, "$BDevStatus Release.", 6);
	PrintFramedBoxHeader('BASE Build:', '#669999', 1, 3, 'left');
	NLIO('<b>Version: </b>' . XSSPrintSafe($BV) . $BDevI . '<br/>', 6);
	if( LoadedString($BInID) ){
		NLIO('<b>Install ID: </b>' . XSSPrintSafe($BInID) . '<br/>', 6);
	}
}

if( $AdminAuth ){ // Issue #146 Fix
	$BK = $BCR->GetCap('BASE_Kernel');
	$BR = $BCR->GetCap('BASE_RTL');
	$BF_lst = array('Mail', 'Mime', 'Graph');
	foreach( $BF_lst as $val ){
		$BF_St[$val] = $BCR->GetCap($val);
	}
	$BADB = $BCR->GetCap('BASE_ADB');
	if( $SaM == 'yes' ){
		if( $submit == 'status' ){
			NLIO("Kernel: $BK Runtime: $BR");
			NLIO("Release: $BDevStatus");
			NLIO("Features:");
			foreach( $BF_St as $key => $val ){
				$tmp = "$key ";
				if( !$val ){
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
			if( $BADB ){
				$ADBStatus = _MNTDBARCHNAME." $archive_dbname";
			}else{
				$ADBStatus = 'Archive DB: not enabled.'; // TD This.
			}
			NLIO($ADBStatus);
			if( $repair_output != '' ){
				NLIO($repair_output);
			}
			NLIO();
		}
	}else{
		$ADBStatus = '<b>';
		if( $BADB ){
			$Icon = 'archive';
			$Desc = _ENABLED;
			$ADBStatus .= _MNTDBARCHNAME
			. ": </b>$archive_dbname";
		}else{ // TD This.
			$Icon = 'no';
			$Desc = 'Not ' . _ENABLED;
			$ADBStatus .= "Archive DB: </b>$Desc.";
		}
		$ADBI = Icon($Icon, $Desc, 6);
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
			$FI = 'yes';
			$tmp = "<b>$key: </b>";
			$tmp = "$key: ";
			if( !$val ){
				$tmp .= 'not ';
				$FI = 'no';
			}
			$tmp .= 'installed.';
			NLIO("<b>$tmp</b>", 6);
			printIcon($FI, $tmp, 6);
		}
		PrintFramedBoxFooter(1, 3);
		NLIO('<br/>', 3);
		PrintFramedBoxHeader(_DATABASE, '#669999', 1, 3, 'left');
		NLIO( '<b>' . _MNTDBALV . ": </b>$ADODB_vers" . '<br/>', 6);
  echo "<B>"._MNTDBTYPE."</B> $DBtype <BR>  
        <B>"._MNTDBALERTNAME."</B> $alert_dbname <BR>
";
		NLIO("$ADBStatus$ADBI" . '<br/>', 6);
		NLIO(
			Icon('tool', 'Repair Tables', 6)
			. "<input class='admin' type='submit' name='submit'"
			. " value='Repair Tables'>",
			6
		);
		NLIO(
			Icon('delete', 'Clear Data Tables', 6)
			. "<input class='admin' type='submit' name='submit'"
			. " value='Clear Data Tables'>",
			6
		);
  echo $repair_output;
	}
}
if( $SaM != 'yes' ){
	PrintFramedBoxFooter(1, 3);
	NLIO('<br/>', 3);
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

if( $SaM == 'yes' ){
	if( $submit == 'status' ){
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
	PrintFramedBoxHeader(_MNTAIC, '#669999', 0, 3, 'left');
	NLIO("<td style='text-align: left; width: 25%;'>",5);

  echo '<B>'._MNTAICTE.'</B> '.$event_cnt.'&nbsp&nbsp
        <B>'._MNTAICCE.'</B> '.$cache_event_cnt;

	if( $AdminAuth ){ // Issue #146 Fix
		NLIO(
			"<input type='submit' name='submit' value='Update Alert Cache'>",
			6
		);
		NLIO(
			"<input class='admin' type='submit' name='submit'"
			. " value='Rebuild Alert Cache'>",
			6
		);
	}
	PrintFramedBoxFooter(1, 3);
	NLIO ('<br/>', 3);
	PrintFramedBoxHeader(_MNTIPAC, '#669999', 1, 3, 'left');

  echo '<B>'._MNTIPACUSIP.'</B> '.$uncached_sip_cnt.'&nbsp;&nbsp&nbsp;'.
       '<B>'._MNTIPACDNSC.'</B> '.$cached_sip_cnt.'&nbsp;&nbsp;&nbsp;'.
       '<B>'._MNTIPACWC.'</B> '.$cached_swhois_cnt.'<BR>'.
       '<B>'._MNTIPACUDIP.'</B> '.$uncached_dip_cnt.'&nbsp;&nbsp&nbsp;'.
       '<B>'._MNTIPACDNSC.'</B> '.$cached_dip_cnt.'&nbsp;&nbsp;&nbsp;'.
       '<B>'._MNTIPACWC.':</B> '.$cached_dwhois_cnt.'<BR>';
	if( $AdminAuth ){ // Issue #146 Fix
		NLIO(
			"<input type='submit' name='submit' value='Update IP Cache'>",
			6
		);
		NLIO(
			"<input type='submit' name='submit' value='Update Whois Cache'>",
			6
		);
		NLIO(
			"<input class='admin' type='submit' name='submit'"
			. " value='Rebuild IP Cache'>",
			6
		);
		NLIO(
			"<input class='admin' type='submit' name='submit'"
			. " value='Rebuild Whois Cache'>",
			6
		);
	}
	PrintFramedBoxFooter(1, 3);
	NLIO ('<br/>', 3);
	if ( $AdminAuth ){ // Issue #146 Fix
		NLIO('</form>', 2);
		if( $BCR->GetCap('BASE_UIDiag') > 0 ){
			$BCR->DumpCaps();
		}
	}
	PrintBASESubFooter();
}
?>
