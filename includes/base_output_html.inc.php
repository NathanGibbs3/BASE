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
//            Purpose: Prints or generates HTML to display
//
//          Author(s): Nathan Gibbs
//                     Kevin Johnson
// Ensure the conf file has been loaded.  Prevent direct access to this file.
defined( '_BASE_INC' ) or die( 'Accessing this file directly is not allowed.' );

include_once("$BASE_path/includes/base_state_common.inc.php");

function PageStart ( $refresh = 0, $page_title = '' ){
	GLOBAL $BASE_VERSION, $BASE_installID, $base_style, $BASE_urlpath,
	$html_no_cache, $refresh_stat_page, $stat_page_refresh_time, $UIL;
	$MHE = "<meta http-equiv='";
	$MNM = "<meta name='";
	$GT = 'BASE'; // Generator Meta Attribute.
	// Backport Shim
	$Charset = _CHARSET;
	$title = _TITLE;
	// Remove Info leaking suffix from title.
	// We can safely remove this shim once we merge the Issue11 branch.
	$title = preg_replace("/ ?\(BASE\) $BASE_installID/", '', $title);
	// End Backport Shim
	$title .= " ($GT)";
	$HT = $title; // Header Title
	if ( !AuthorizedPage('(base_denied|index)') ){
		// Additional app info allowed everywhere but landing pages.
		$GT .= " $BASE_VERSION";
		if ( isset($BASE_installID) && $BASE_installID != ''){
			$title .= " $BASE_installID";
			$HT = $title;
		}
		$title .= " $BASE_VERSION";
		if ($page_title != ''){
			$title .= ': ' . XSSPrintSafe($page_title);
		}
		if ( ChkCookie ('archive', 1) ){
			$SfxA = ' -- ARCHIVE';  // Need to add this to Translation Data.
			$title .= $SfxA;
			$HT .= $SfxA;
		}
	}
	print '<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">';
	NLIO('<!-- '. $title . ' -->');
	NLIO('<html>');
	NLIO('<head>', 1);
	NLIO($MHE."Content-Type' content='text/html; charset=$Charset'>", 2);
	if ( $html_no_cache == 1 ) {
		NLIO($MHE."pragma' content='no-cache'>", 2);
	}
	if ( $refresh == 1 && $refresh_stat_page == 1 ){
		if (isset($_SERVER["REQUEST_URI"])){
			$URI = $_SERVER["REQUEST_URI"];
		}else{
			$URI = '/';
		}
		$tmp = CleanVariable(
			$URI, VAR_FSLASH | VAR_PERIOD | VAR_DIGIT | VAR_PUNC | VAR_LETTER
		);
		$tmp = htmlspecialchars($tmp,ENT_QUOTES);
		NLIO(
			$MHE."refresh' content='$stat_page_refresh_time; URL=$tmp'>",2
		);
	}
	NLIO($MNM."Author' content='Nathan Gibbs'>",2);
	NLIO($MNM."Generator' content='$GT'>",2);
	NLIO($MNM."viewport' content='width=device-width, initial-scale=1'>",2);
	NLIO("<title>$title</title>",2);
//	NLIO('<meta name="color-scheme" content="light dark"/>',2);
	NLIO('<link rel="stylesheet" type="text/css" HREF="'. $BASE_urlpath .'/styles/base_common.css'.'">', 2);
	NLIO('<link rel="stylesheet" type="text/css" HREF="'. $BASE_urlpath .'/styles/'. $base_style .'">', 2);
	NLIO('</head>', 1);
	NLIO('<body>', 1);
	NLIO('<div class="mainheadertitle">'.$HT.'</div>',2);
}
function PageEnd (){
	NLIO('</body>',1);
	NLIO('</html>');
}
function NLI ( $Item = '', $Count = 0 ){
	if ( !is_int($Count) ){
		$Count = 0;
	}
	return "\n".str_repeat ("\t", $Count).$Item;
}
function NLIO ( $Item = '', $Count = 0 ){
	print NLI ($Item, $Count);
}
function PrintBASESubHeader(
	$page_title = '', $page_name = '', $back_link = '', $refresh = 0, $page = ''
){
	GLOBAL $debug_mode, $BASE_installID, $BASE_path, $BASE_urlpath,
	$html_no_cache, $max_script_runtime, $Use_Auth_System, $base_style, $UIL;
	if ( ini_get("safe_mode") != true ){
		set_time_limit($max_script_runtime);
	}
	PageStart($refresh, $page_title);
	PrintBASEMenu( 'Header', $back_link);
	if ( $debug_mode > 0 ){
		PrintPageHeader();
	}
}
function PrintBASESubFooter(){
	GLOBAL $BASE_VERSION, $BASE_path, $BASE_urlpath, $Use_Auth_System,
	$base_custom_footer;
	NLIO ('<!-- BASE Footer -->',2);
	PrintBASEMenu( 'Footer' );
	NLIO ("<div class='mainfootertext'>",2);
	NLIO (
		"<a class='largemenuitem' href='https://github.com/NathanGibbs3/BASE' "
		."target='_blank'>BASE</a>"
		,3
	);
	$tmp = '';
	if ( !AuthorizedPage('(base_denied|index)') ){
		$tmp = "$BASE_VERSION ";
	}
	$tmp .= _FOOTER;
	NLIO ($tmp,3);
	NLIO ('</div>',2);
	if ( AuthorizedPage('base_main') ){
		// Custom footer allowed on main page only.
		if ( strlen($base_custom_footer) != 0 ){
			NLIO ('<!-- BASE Custom Footer -->',2);
			$tmp = base_include($base_custom_footer);
			if ( $tmp == false ){
				$tmp = XSSPrintSafe ( $base_custom_footer );
				$tmp = returnErrorMessage (
					"ERROR: Include custom footer file: $tmp"
				);
				NLIO ($tmp,2);
			}
		}
	}
	PageEnd();
}
function PrintBASEMenu( $type = '', $back_link = '' ){
	GLOBAL $BASE_urlpath, $Use_Auth_System, $et;
	if ( LoadedString( $type ) == true ){
		// Common
		$type = strtolower($type);
		$ReqRE = '';
		if ( $type == 'header' ){ // Header
			$ReqRE = "(base_(denied|local_rules|main)|index)";
		}elseif ( $type == 'footer' ){ // Footer
			$ReqRE = "(base_(denied|local_rules)|index)";
		}
		// Header Menu allowed everywhere but main & landing pages.
		// Footer Menu allowed everywhere but landing pages.
		if ( $ReqRE != '' && !AuthorizedPage($ReqRE) ){
			// Html Template
			$Hrst = "<a class='menuitem' href='$BASE_urlpath/";
			// Href tag start.
			$HrstTL = $Hrst . 'base_'; // Top Level Pages.
			$Sep = ' | '; // Separator.
			NLIO ("<div class='mainheadermenu'>",2);
			NLIO ("<table border='0'>",3);
			NLIO ('<tr>',4);
			NLIO ("<td class='menuitem'>",5);
			if ( $type == 'header' ){ // Header
				NLIO ($HrstTL."main.php'>"._HOME.'</a>'.$Sep,6);
				NLIO ($HrstTL."qry_main.php?new=1'>"._SEARCH."</a>$Sep",6);
			}elseif ( $type == 'footer' ){ // Footer
				NLIO ($HrstTL."ag_main.php?ag_action=list'>". _AGMAINT."</a>$Sep",6);
				NLIO ($HrstTL."maintenance.php'>". _CACHE."</a>$Sep",6);
			}
			if ($Use_Auth_System == 1){
				NLIO ($HrstTL."user.php'>". _USERPREF ."</a>$Sep",6);
				NLIO ($HrstTL."logout.php'>". _LOGOUT .'</a>',6);
			}
			if ( $type == 'header' && $back_link != '' ){ // Header
				print $Sep;
				NLIO($back_link,6);
			}elseif ( $type == 'footer' ){ // Footer
				if ( AuthorizedRole(1) ){ // Issue #144 fix
					print $Sep;
					NLIO ($Hrst."admin/index.php'>". _ADMIN .'</a>',6);
				}
				if ( is_object($et) ){
					print $Sep;
					NLIO ('</td><td>',5);
					$et->PrintTiming();
				}
			}
			PrintFramedBoxFooter(1);
			NLIO ('</div>',2);
		}
	}
}
function PrintFramedBoxHeader(
	$title = '', $cc = 'black' , $td = 0, $tab = 3, $align = 'center',
	$wd = 100
){
	print FramedBoxHeader( $title, $cc, $td, $tab, $align, $wd);
}
function FramedBoxHeader(
	$title = '', $cc = 'black' , $td = 0, $tab = 3, $align = 'center',
	$wd = 100
){
	$Ret = '';
	// Input Validation
	$title = XSSPrintSafe($title);
	if ( HtmlColor($cc) == false ){
		$cc = 'black';
	}
	if ( !is_int($td) ){
		$td = 0;
	}
	if ( !is_int($tab) ){
		$tab = 3;
	}
	if ( !is_int($wd) ){
		$wd = 100;
	}
	$align = strtolower($align);
	$hal = array( 'left', 'center', 'right' );
	if ( !in_array($align, $hal) ){
		$align = 'center';
	}
	// Input Validation End
	$style = "'border: 2px solid $cc; border-collapse: collapse; width:$wd%;'";
	$tmp = "<table style = $style";
	if ( LoadedString($title) == true ){
		$tmp .= " summary='$title'";
	}
	$tmp .= '>';
	$Ret .= NLI($tmp, $tab) . NLI('<tr>',$tab + 1);
	if ( LoadedString($title) == true ){
		$Ret .= NLI(
			"<td class='sectiontitle' style='text-align: $align;' colspan='20'>$title</td>",
			$tab + 2
		).
		NLI('</tr><tr>',$tab + 1);
	}
	if ( $td != 0 ){
		$Ret .= NLI('<td>',$tab + 2);
	}
	return $Ret;
}
function PrintFramedBoxFooter( $td = 0, $tab = 3 ){
	print FramedBoxFooter( $td, $tab);
}
function FramedBoxFooter( $td = 0, $tab = 3 ){
	$Ret = '';
	// Input Validation
	if ( !is_int($td) ){
		$td = 0;
	}
	if ( !is_int($tab) ){
		$tab = 3;
	}
	// Input Validation End
	if ( $td != 0 ){
		$Ret .= NLI('</td>',$tab + 2);
	}
	$Ret .= NLI('</tr>',$tab + 1);
	$Ret .= NLI('</table>',$tab);
	return $Ret;
}

function chk_select($stored_value, $current_value){
	$msg = ' ';
	if ( strnatcmp($stored_value,$current_value) == 0 ){
		$msg .= 'selected';
	}
	return $msg;
}

function chk_check($stored_value, $current_value){
	$msg = ' ';
	if ( $stored_value == $current_value ){
		$msg .= 'checked';
	}
	return $msg;
}

function dispYearOptions($stored_value)
{
  // Creates the years for drop down boxes
  $thisyear = date("Y");
  $options = "";
  $options = "<OPTION VALUE=' ' ".chk_select($stored_value, " ").">"._DISPYEAR."\n";
  for($i=1999; $i<=$thisyear; $i++) {
    $options = $options ."<OPTION VALUE='".$i."' ".chk_select($stored_value, $i).">".$i."\n";
  }
  $options = $options. "</SELECT>";
  
  return($options);
}

function PrintBASEAdminMenuHeader(){
	GLOBAL $Use_Auth_System;
	$menu = NLI("<div>",2);
	$menu .= NLI(
		"<div class='mainheadermenu' style='float: left; width: 15%;'>",3
	);
	$menu .= NLI(_USERMAN ."<hr/>",4);
	// Html Templates
	$Umca = "base_useradmin.php?action="; // User Managemnt Common Action.
	$Hrst = "<a href='$Umca"; // Href tag start.
	$Hrsp = " class='menuitem'>"; // Href tag end.
	if ( $Use_Auth_System == 1 ){ // Issue #144 Fix
		$menu .= NLI($Hrst . "list'" . $Hrsp . _LISTU . "</a><br>",4);
	}
	$menu .= NLI($Hrst . "create'" . $Hrsp . _CREATEU."</a><br>",4);
	$Umca = "base_roleadmin.php?action="; // Role Managemnt Common Action.
	$Hrst = "<a href='$Umca"; // Href tag start.
	if ( $Use_Auth_System == 1 ){ // Issue #144 Fix
		$menu .= NLI("<br>". _ROLEMAN ."<hr>",4);
		$menu .= NLI($Hrst . "list'" . $Hrsp . _LISTR."</a><br>",4);
		$menu .= NLI($Hrst . "create'" . $Hrsp ._CREATER."</a><br>",4);
	}
	$menu .= NLI("</div>",3);
	$menu .= NLI(
		"<div style='padding-left: 10px; width: auto;'>",3
	);
	print $menu;
}
function PrintBASEAdminMenuFooter(){
	NLIO("</div>",3);
	NLIO("</div>",2);
}
function PrintBASEHelpLink($target)
{
  /*
    This function will accept a target variable which will point to
    an anchor in the base_help.php file.  It will output a help icon
    that will link to that target in a new window.
  */
}

// Generate Horizontal Bar Graph <td> tag set.
function HBarGraph (
	$Value = 1, $Count = 1, $color = "ff0000", $bgcolor = "ffffff"
){
	$pfx = '<td bgcolor="#';
	// Input Validation.
	if ( HtmlColor($color) == false ){
		$color = 'ff0000';
	}
	if ( HtmlColor($bgcolor) == false ){
		$bgcolor = 'ffffff';
	}
	// Input End.
	$ent_pct = Percent( $Value, $Count );
	if ( $ent_pct > 0 ){
		$ent_clr = $color;
	}else{
		$ent_pct = 100;
		$ent_clr = $bgcolor;
	}
	$Ret = $pfx . $ent_clr . '" width="' . $ent_pct. '%">&nbsp;</td>';
	if ( $ent_pct > 0 && $ent_pct < 100 ){
		$Ret .= $pfx . $bgcolor.'"></td>';
	}
	return($Ret);
}
function HtmlPercent ( $Value = 1, $Count = 1 ){
	$ent_pct = Percent( $Value, $Count );
	if ( $ent_pct == 0 ){
		$tmp = "&lt; 1";
	}else{
		$tmp = $ent_pct;
	}
	$Ret = $tmp . '%';
	return($Ret);
}
?>
