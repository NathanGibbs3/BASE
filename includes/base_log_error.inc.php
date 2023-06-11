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
** Purpose: debugging and logging routines   
********************************************************************************
** Authors:
********************************************************************************
** Kevin Johnson <kjohnson@secureideas.net
**
********************************************************************************
*/
// Ensure the conf file has been loaded. Prevent direct access to this file.
defined('_BASE_INC') or die('Accessing this file directly is not allowed.');

function ErrorMessage( $message, $color = '#ff0000', $br = 0 ){
	GLOBAL $BCR, $debug_mode, $BASE_VERSION, $BASE_installID;
	if(
		!getenv('TRAVIS')
		&& !(
			$BASE_VERSION == '0.0.0 (Joette)'
			&& $BASE_installID == 'Test Runner'
		)
	){
		$UIM = 'Knl'; // Default UI Mode Under Boot.
	}else{
		$UIM = 'Web'; // Default UI Mode Under Test.
	}
	if( isset($BCR) && is_object($BCR) ){
		$UIM = $BCR->GetCap('UIMode'); // Running System Sets UI Mode.
	}
	switch( $UIM ){
		case 'Gfx';
		case 'Knl';
			KML($message, $debug_mode);
			break;
		case 'Con';
			NLI($message);
			break;
		case 'Web';
		default;
			print returnErrorMessage($message, $color, $br);
	}
}

function returnErrorMessage( $message, $color = "#ff0000", $br = 0 ){
	if( HtmlColor($color) == false ){
		// Default to Red if we are passed something odd.
		$color = "#ff0000";
	}
	$error = "<font color='$color'>$message</font>";
	if( is_int($br) && $br == 1 ){ // Issue #160
		$error .= '<br/>';
	}
	return $error;
}

function returnSMFN( $msg = '' ){
	// Standardiazed PHP Safe Mode File Owner Notice.
	$PHPVer = GetPHPSV();
	$Ret = '';
	if(
		($PHPVer[0] < 5 || ($PHPVer[0] == 5 && $PHPVer[1] < 4))
		&& ini_get('safe_mode') == true && LoadedString($msg)
	){
		$EMsg = 'In PHP safe_mode ' . XSSPrintSafe($msg) . ' must be owned by '
		. 'the user under which the web server is running.';
		$Ret = returnErrorMessage($EMsg, 'black', 1);
	}
	return $Ret;
}

function returnBuildError( $Desc = '', $Opt = ''){
	// Standardiazed PHP build error.
	if( LoadedString($Desc) && LoadedString($Opt) ){
		$Desc = XSSPrintSafe($Desc);
		$Opt = XSSPrintSafe($Opt);
		$Ret = returnErrorMessage(_ERRPHPERROR . ':', 0, 1);
		// TD this.
		$Ret .=
		NLI("<b>PHP build incomplete</b>: $Desc support required.<br/>")
		. NLI("Recompile PHP with $Desc support (<code>$Opt</code>) .<br/>");
		return $Ret;
	}
}

function BuildError( $message = '', $fmessage = '' ){
	if( LoadedString($message) ){
		ErrorMessage(_ERRPHPERROR.':',0,1);
		ErrorMessage($message, 'black', 1);
		print '<br/>';
	}
	// @codeCoverageIgnoreStart
	if( LoadedString($fmessage) ){
		FatalError($fmessage);
	}
	// @codeCoverageIgnoreEnd
}

function LibIncError(
		$Desc, $Loc, $Lib, $message = '', $LibName = '', $URL = '', $Fatal = 0,
		$Pear = 0
	){
	// Translation data this msg when we get to _ERRSQLDBALLOAD1 on Issue#11
	$msg = "<b>Error loading the $Desc library:</b> ".
	XSSPrintSafe('from "' . $Loc . '".');
	if( LoadedString($LibName) ){
		$msg .= '<br/>';
		// Translation data this msg when we get to _ERRSQLDBALLOAD2 on Issue#11
		$msg .= "The underlying $Desc library currently used is $LibName";
		if( LoadedString($URL) ){
			$URL = XSSPrintSafe($URL);
			$msg .= ', that can be downloaded at ';
			$msg .= "<a href='$URL'>$URL</a>";
		}
		$msg .= '.';
	}
	ErrorMessage($msg, 'black', 1);
	if( LoadedString($message) ){
		ErrorMessage($message, 'black', 1);
	}
	$FLib = $Lib;
	if( $Pear == 1 ){
		$EMsg = "Check your Pear::$LibName installation!<br/>";
		$EMsg .= 'Make sure PEAR libraries can be found by PHP.';
		$EMsg .= '<pre>';
		$EMsg .= XSSPrintSafe('pear config-show | grep "PEAR directory"'."\n");
		$EMsg .= XSSPrintSafe('PEAR directory      php_dir     /usr/share/pear');
		$EMsg .= '</pre>';
		$EMsg .= 'This path must be part of the include path of php (cf. /etc/php.ini).';
		$EMsg .= '<pre>';
		$EMsg .= XSSPrintSafe('php -i | grep "include_path"');
		$EMsg .= XSSPrintSafe(
			'include_path => .:/usr/share/pear:/usr/share/php => .:/usr/share/pear:/usr/share/php'
		);
		$EMsg .= '</pre>';
		if( ini_get('safe_mode') ){
			$EMsg .= XSSPrintSafe(
				'In "safe_mode" it must also be part of safe_mode_include_dir in /etc/php.ini'
			);
		}
		ErrorMessage($EMsg,'black',1);
		$FLib = $LibName;
	}
	$tmp = "PHP setup incomplete: $FLib required.";
	if( $Fatal == 0 ){
		ErrorMessage($tmp, 0, 1);
	}else{
		// @codeCoverageIgnoreStart
		FatalError($tmp);
		// @codeCoverageIgnoreEnd
	}
}

// Debug Data Table
function DDT(
	$Items, $Desc = array(), $title = NULL, $tab = 3, $wd = 75, $vf = 0,
	$XSS = 1
){
	if( is_array($Items) ){ // Input Validation
		if( !is_array($Desc) ){
			$Desc = array($Desc);
		}
		if( !is_int($tab) ){
			$tab = 3;
		}
		if( !is_int($wd) ){
			$wd = 75;
		}
		if( !is_int($vf) ){
			$vf = 0;
		}
		if( !is_int($XSS) ){
			$XSS = 1;
		}
		if( !LoadedString($title) ){
			$title = 'Debug Data Table';
		}
		$title = XSSPrintSafe($title);
		$Desc = XSSPrintSafe($Desc);
		if( $XSS > 0 ){ // Anti XSS Output Data
			$Items = XSSPrintSafe($Items);
		}
		PrintFramedBoxHeader($title, 'red', 0, $tab, '', $wd);
		$icnt = count($Items);
		$DF = false;
		if( $icnt <= count($Desc) ){ // Do we have Descriptions?
			$DF = true;
		}
		if( $icnt > 0 ){
			$style = '';
			if ( $vf == 1 && $DF ){ // Vertical Dsiplay
				$style = " class='sectiontitle' style='text-align: right;".
				" padding-right: 10px; width: 10%'";
			}
			NLIO("<td$style>", $tab + 2);
			if( $vf == 0 ){
				if( $DF ){
					for( $i = 0; $i < $icnt; $i++){
						NLIO($Desc[$i], $tab + 3);
						if( $i != $icnt - 1 ){
							NLIO('</td><td>', $tab + 2);
						}
					}
					PrintTblNewRow(1, '', $tab + 2 );
				}
			}
			for( $i = 0; $i < $icnt; $i++){
				if( $vf == 0 ){
					NLIO($Items[$i], $tab + 3);
					if( $i != $icnt - 1 ){
						NLIO('</td><td>', $tab + 2);
					}
				}else{
					if( $DF ){
						NLIO($Desc[$i].': ', $tab + 3);
						NLIO("</td><td style='padding-left:10px;'>", $tab + 2);
					}
					NLIO($Items[$i], $tab + 3);
					if( $i != $icnt -1 ){
						PrintTblNewRow(0, '', $tab + 2 );
						NLIO("<td$style>", $tab + 2);
					}
				}
			}
			NLIO('</td>', $tab + 2);
		}
		PrintFramedBoxFooter(0, $tab);
	}
}

// @codeCoverageIgnoreStart
function FatalError( $message ){
	print returnErrorMessage('<b>'._ERRBASEFATAL.'</b>',0,1)."\n".$message;
	$message = preg_replace("/\//", '', $message);
	$message = preg_replace("/<br>/i", ' ', $message);
	$message = strip_tags($message)."\n";
	error_log($message);
	trigger_error($message, E_USER_ERROR);
}
// @codeCoverageIgnoreEnd

function PrintServerInformation()
{
   echo '';
}

function PrintPageHeader(){
	GLOBAL $DBtype, $ADODB_vers, $Use_Auth_System, $BCR;
	if( !AuthorizedPage('(base_denied|index)') ){
		// Additional app info allowed everywhere but landing pages.
		if( ARC(10000) ){ // Auth check
			$BV = $BCR->GetCap('BASE_Ver');
			$AdminAuth = ARC(1); // Admin Flag
			$UserAuth = ARC(50); // User Flag
			if( $AdminAuth ){ // Issue #146 Fix
				$SW_Svr = 'unknown';
				if( is_key('SERVER_SOFTWARE', $_SERVER) ){
					$SW_Svr = XSSPrintSafe($_SERVER['SERVER_SOFTWARE']);
				}
			}
			if( $UserAuth ){
				$http_referer = '';
				if( is_key('HTTP_REFERER', $_SERVER) ){
					$http_referer = XSSPrintSafe($_SERVER['HTTP_REFERER']);
				}
				$tmp = session_encode();
			}
			$SW_Cli = 'unknown';
			if( is_key('HTTP_USER_AGENT', $_SERVER) ){
				$SW_Cli = XSSPrintSafe($_SERVER['HTTP_USER_AGENT']);
			}
			$request_uri = XSSPrintSafe($_SERVER['REQUEST_URI']);
			$query_string = XSSPrintSafe($_SERVER['QUERY_STRING']);
			// TD these labels from Issue #11 at some point.
			$DD = array(_MNTCLIENT, 'URL', 'Request Parameters',);
			$DI = array(
				Icon('client', _MNTCLIENT) . $SW_Cli,
				$request_uri, $query_string,
			);
			if( $Use_Auth_System == 1 && $UserAuth ){
				array_push($DD, 'Referer', 'SESSION ID', 'SESSION Contents');
				array_push($DI, $http_referer);
				array_push(
					$DI, session_id() . ' ( ' . strlen($tmp) . '  bytes )'
				);
				array_push(
					$DI,
					"<div style='width:auto'>"
					. 'Back List Count: ' . $_SESSION['back_list_cnt']
					. ' Back List Type: ' . gettype($_SESSION['back_list'])
					. '</div>' . printHistory()
				);
			}
			DDT($DI, $DD, 'Request Information', '', '', 1, 0);
			$DD = array('BASE VERSION');
			$DI = array($BV);
			if( $Use_Auth_System == 1 && $AdminAuth ){
				array_push($DD, 'OS', 'HTTP SW', 'HTTP PHP API', _MNTPHPVER);
				array_push($DD, _MNTDBALV, _MNTDBTYPE, 'Executed Script');
				array_push($DI, php_uname(), $SW_Svr, php_sapi_name());
				array_push($DI, phpversion(), $ADODB_vers, $DBtype);
				array_push($DI, XSSPrintSafe($_SERVER['SCRIPT_NAME']));
			}
			DDT($DI, $DD, 'Server Information', '', '', 1, 0);
		}
	}
}

function PrintHistory(){
	GLOBAL $maintain_history;
	$Ret = '';
	if( $maintain_history == 1 && is_array($_SESSION) && session_id() != '' ){
		// We have a session, so proceed.
		if( isset($_SESSION['back_list']) ){
			$SHA = $_SESSION['back_list'];
			if(is_array($_SESSION['back_list']) ){
				$Ret = "<pre class='session'>";
				foreach( $SHA as $key => $val ){
					$Ret .= "$key: ";
					if( $key == 0 ){
						$Ret .= "History Start";
						foreach( $val as $HEval ){
							if( LoadedString($HEval) ){
								$Ret .= " corrupted";
								break;
							}
						}
						$Ret .= ".\n";
					}else{
						if(
							isset($val['SCRIPT_NAME'])
							&& isset($val['QUERY_STRING'])
							&& isset($val['session'])
						){
							$Ret .= "\tURL: " . $val['SCRIPT_NAME'];
							if( LoadedString($val['QUERY_STRING']) ){
								$Ret .= "?" . $val['QUERY_STRING'];
							}
							$Ret .= "\n";
							$Ret .= "\tSession: " . $val['session'] . "\n\n";
						}else{
							$Ret .= "History Entry corrupted.\n";
						}
					}
				}
				$Ret .= '</pre>';
			}else{
				$Ret = returnErrorMessage('History corrupted!', '', 1);
				$Ret .= returnErrorMessage(
					XSSPrintSafe(print_r($_SESSION['back_list'], true)), '', 1
				);
			}
		}
	}
	return $Ret;
}

function PrintHTTPPost()
{
   echo "<BR><B>HTTP POST Variables</B><PRE>";
   XSSPrintSafe($_POST);
   echo "</PRE>";
}

function SQLTraceLog($message)
{
  GLOBAL $sql_trace_mode, $sql_trace_file;


  if ($sql_trace_mode < 1)
  // then fallback to http server's error log:
  {
    error_log($message);
  }
  else
  // preferred
  {
    if (($sql_trace_file != "") && file_exists($sql_trace_file))
    {
      $fd = fopen($sql_trace_file, "a");
      if ($fd)
      {
        fputs($fd, $message);
        fputs($fd, "\n");
        fflush($fd);
        fclose($fd);
      }
      else
      {
        ErrorMessage("ERROR: Could not open " . $sql_trace_file);
      }
		}else{
			error_log($message);
		}
	}
}
?>
