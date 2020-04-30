<?php
// Basic Analysis and Security Engine (BASE)
// Copyright (C) 2019-2020 Nathan Gibbs
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

function PageStart ($refresh = 0, $page_title = '') {
	GLOBAL $BASE_VERSION, $BASE_installID, $base_style, $BASE_urlpath,
	$html_no_cache, $refresh_stat_page, $stat_page_refresh_time, $UIL;
	$MHE = '<meta http-equiv="';
	$Charset = _CHARSET;
	$title = _TITLE;
	// Backport Shim
	// Remove Info leaking suffix from title.
	// We can safely remove this shim once we merge the Issue11 branch.
	$title = preg_replace("/ ?\(BASE\) $BASE_installID/", '', $title);
	// End Backport Shim
	$title .= " (BASE)";
	if ( !preg_match("/(base_denied|index).php/", $_SERVER['SCRIPT_NAME']) ) {
		$title .= " $BASE_installID $BASE_VERSION";
		if ($page_title != ''){
			$title .= ' : ' . $page_title;
		}
		if ( isset($_COOKIE['archive']) && $_COOKIE['archive'] == 1 ) {
			$title .= ' -- ARCHIVE';
		}
	}
	print '<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">';
	NLIO('<!-- '. $title . ' -->');
	NLIO('<html>');
	NLIO('<head>', 1);
	NLIO($MHE.'Content-Type" content="text/html; charset='. $Charset .'">', 2);
	if ( $html_no_cache == 1 ) {
		NLIO($MHE.'pragma" content="no-cache">', 2);
	}
	if ( $refresh == 1 ) {
		if ( $refresh_stat_page ) {
			if (isset($_SERVER["REQUEST_URI"])){
				$URI = $_SERVER["REQUEST_URI"];
			}else{
				$URI = '/';
			}
			$tmp = CleanVariable(
				$URI,
				VAR_FSLASH | VAR_PERIOD | VAR_DIGIT | VAR_PUNC | VAR_LETTER
			);
			$tmp = htmlspecialchars($tmp,ENT_QUOTES);
			NLIO(
				$MHE.'refresh" content="'.$stat_page_refresh_time.'; URL='.
				$tmp.'">', 2
			);
		}
	}
	NLIO("<title>$title</title>",2);
	NLIO('<link rel="stylesheet" type="text/css" HREF="'. $BASE_urlpath .'/styles/'. $base_style .'">', 2);
	NLIO('</head>', 1);
	NLIO('<body>', 1);
}

function PageEnd () {
	NLIO('</body>',1);
	NLIO('</html>');
}

function NLI ($Item = '', $Count = 0) {
	if ( !is_int($Count) ) {
		$Count = 0;
	}
	return "\n".str_repeat ("\t", $Count).$Item;
}

function NLIO ($Item = '', $Count = 0) {
	print NLI ($Item, $Count);
}

function PrintBASESubHeader($page_title, $page_name, $back_link, $refresh = 0, $page = "") {
	GLOBAL $debug_mode, $BASE_VERSION, $BASE_path, $BASE_urlpath, $html_no_cache,
         $max_script_runtime, $Use_Auth_System, $stat_page_refresh_time, $base_style, $refresh_stat_page;
	if ( ini_get("safe_mode") != true ) {
		set_time_limit($max_script_runtime);
	}
	PageStart($refresh, $page_title);
  echo '<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<!-- '. _TITLE . $BASE_VERSION .' -->
<HTML>
  <HEAD><meta http-equiv="Content-Type" content="text/html; charset='. _CHARSET .'">';

  if ( $html_no_cache == 1 )
     echo '<META HTTP-EQUIV="pragma" CONTENT="no-cache">';

  if ( $refresh == 1 )
     PrintFreshPage($refresh_stat_page, $stat_page_refresh_time);

  if (@$_COOKIE['archive'] == 0)
    echo '<TITLE>' . _TITLE .': '.$page_title.'</TITLE>';
  else
    echo '<TITLE>' . _TITLE .': '.$page_title.' -- ARCHIVE</TITLE>';
    
  echo '<LINK rel="stylesheet" type="text/css" HREF="'. $BASE_urlpath .'/styles/'. $base_style .'">
        </HEAD>
        <BODY>';
	include("$BASE_path/base_hdr1.php");
	include("$BASE_path/base_hdr2.php");
	echo "<TABLE WIDTH=\"100%\"><TR><TD ALIGN=RIGHT>".$back_link."</TD></TR></TABLE><BR>";
	if ( $debug_mode > 0 ) {
		PrintPageHeader();
	}
}

function PrintBASESubFooter()
{
  GLOBAL $BASE_VERSION, $BASE_path, $BASE_urlpath, $Use_Auth_System;
  echo "\n\n<!-- BASE Footer -->\n".
       "<P>\n";
  include("$BASE_path/base_footer.php");
  echo "\n\n";
}

      
function PrintFramedBoxHeader($title, $fore, $back)
{
  echo '
<TABLE WIDTH="100%" CELLSPACING=0 CELLPADDING=2 BORDER=0 BGCOLOR="'.$fore.'">
<TR><TD>
  <TABLE WIDTH="100%" CELLSPACING=0 CELLPADDING=2 BORDER=0 BGCOLOR="'.$back.'">
  <TR><TD class="sectiontitle">&nbsp;'.$title.'&nbsp;</TD></TR>
    <TR><TD>';
} 

function PrintFramedBoxFooter()
{
  echo '
  </TD></TR></TABLE>
</TD></TR></TABLE>';
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

function PrintBASEAdminMenuHeader()
{
  $menu = "<table width='100%' border=0><tr><td width='15%'>";
  $menu = $menu . "<div class='mainheadermenu'>";
  $menu = $menu . "<table border='0' class='mainheadermenu'>";
  $menu = $menu . "<tr><td class='menuitem'>". _USERMAN ."<br>";
  $menu = $menu . "<hr><a href='base_useradmin.php?action=list' class='menuitem'>"._LISTU."</a><br>";
  $menu = $menu . "<a href='base_useradmin.php?action=create' class='menuitem'>"._CREATEU."</a><br>";
  $menu = $menu . "<br>". _ROLEMAN ."<br><hr>";
  $menu = $menu . "<a href='base_roleadmin.php?action=list' class='menuitem'>"._LISTR."</a><br>";
  $menu = $menu . "<a href='base_roleadmin.php?action=create' class='menuitem'>"._CREATER."</a><br>";
  $menu = $menu . "</td></tr></table></div></td><td>";
  
  echo($menu);
}
  
function PrintBASEAdminMenuFooter()
{
  $footer = "</td></tr></table>";
  
  echo($footer);
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
	$ent_pct = Percent($Value,$Count);
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
	$ent_pct = Percent($Value,$Count);
	if ( $ent_pct == 0 ) {
		$tmp = "&lt; 1";
	}else{
		$tmp = $ent_pct;
	}
	$Ret = $tmp . '%';
	return($Ret);
}

?>
