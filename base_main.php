<?php
/*
** Copyright (C) 2004 Kevin Johnson
** Copyright (C) 2000 Carnegie Mellon University
**
** Author: Kevin Johnson <kjohnson@secureideas.net>
** Project Leads: Kevin Johnson <kjohnson@secureideas.net>
**                Sean Muller <samwise_diver@users.sourceforge.net>
** Built upon work by Roman Danyliw <rdd@cert.org>, <roman@danyliw.com>
**
** This program is free software; you can redistribute it and/or modify
** it under the terms of the GNU General Public License as published by
** the Free Software Foundation; either version 2 of the License, or
** (at your option) any later version.
**
** This program is distributed in the hope that it will be useful,
** but WITHOUT ANY WARRANTY; without even the implied warranty of
** MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
** GNU General Public License for more details.
**
** You should have received a copy of the GNU General Public License
** along with this program; if not, write to the Free Software
** Foundation, Inc., 59 Temple Place - Suite 330, Boston, MA 02111-1307, USA.
*/

/*  
 * Basic Analysis and Security Engine (BASE) by Kevin Johnson
 * based upon Analysis Console for Incident Databases (ACID) by Roman Danyliw
 *
 * See http://sourceforge.net/projects/secureideas for the most up to date 
 * information and documentation about this application.
 *
 * Purpose:
 *
 *   BASE is an PHP-based analysis engine to search and process 
 *   a database of security incidents generated by the NIDS Snort.
 *
 * Configuration:
 *
 *   See the 'docs/README' file, and 'base_conf.php'
 *
 */

$sc = DIRECTORY_SEPARATOR;
require_once("includes$sc" . 'base_krnl.php');
include_once("$BASE_path$sc" . "includes$sc" . 'base_include.inc.php');
include_once("$BASE_path/base_db_common.php");

AuthorizedRole(10000);
// Initialize the history
$_SESSION = NULL;
InitArray($_SESSION['back_list'], 1, 3, '');
$_SESSION['back_list_cnt'] = 0;
PushHistory();
if ( isset($_GET['archive']) ){ // Set cookie to use the correct db.
	"no" == $_GET['archive'] ? $value = 0 : $value = 1;
	BCS('archive', $value);
	HTTP_header("Location: $BASE_urlpath/base_main.php");
}

function DBLink(){ // Generate link to select other DB.
	GLOBAL $archive_exists;
	if ( ChkArchive() ){
		NLIO('<a href="base_main.php?archive=no">'
			. Icon('alert', _USEALERTDB, 6) . _USEALERTDB . '</a>', 5
		);
	}elseif( $archive_exists != 0 ){
		NLIO(
			'<a href="base_main.php?archive=1">'
			. Icon('archive', _USEARCHIDB, 6) . _USEARCHIDB . '</a>', 5
		);
	}
}

PrintBASESubHeader('', '', '', 1);
$CTR = verify_php_build($DBtype); // Check that PHP was built correctly.
// @codeCoverageIgnoreStart
if ( LoadedString($CTR) ){
	BuildError($CTR, $CTR);
}
// @codeCoverageIgnoreEnd
$db = NewBASEDBConnection($DBlib_path, $DBtype); // Connect to DB.
$db->baseDBConnect(
	$db_connect_method, $alert_dbname, $alert_host, $alert_port, $alert_user,
	$alert_password
);
// Check that DB schema is recent.
$CTR = verify_db($db, $alert_dbname, $alert_host);
// @codeCoverageIgnoreStart
if ( LoadedString($CTR) ){
	BuildError($CTR, $CTR);
}
// @codeCoverageIgnoreEnd

NLIO("<table width='100%' style='border:0;padding:0'>",2);
NLIO('<tr>',2);
NLIO("<td align='left' rowspan='2'>",3);

// Various things for the snapshot functiuonality on the first page.... Kevin
$tmp_month = date("m");
$tmp_day = date("d");
$tmp_year = date("Y");
$tmp_DSO = '&amp;sort_order=occur_d'; // Default Sort Order.
$today = '&amp;time%5B0%5D%5B0%5D=+&amp;time%5B0%5D%5B1%5D=%3E%3D'.
    '&amp;time%5B0%5D%5B2%5D='.$tmp_month.
    '&amp;time%5B0%5D%5B3%5D='.$tmp_day.
    '&amp;time%5B0%5D%5B4%5D='.$tmp_year.
    '&amp;time%5B0%5D%5B5%5D=&amp;time%5B0%5D%5B6%5D=&amp;time%5B0%5D%5B7%5D='.
    '&amp;time%5B0%5D%5B8%5D=+&amp;time%5B0%5D%5B9%5D=+';
$yesterday_year = date("Y", time() - 86400);
$yesterday_month = date("m", time() - 86400);
$yesterday_day = date ("d", time() - 86400);
$yesterday_hour = date ("H", time() - 86400);
$yesterday =  '&amp;time%5B0%5D%5B0%5D=+&amp;time%5B0%5D%5B1%5D=%3E%3D'.
    '&amp;time%5B0%5D%5B2%5D='.$yesterday_month.
    '&amp;time%5B0%5D%5B3%5D='.$yesterday_day.
    '&amp;time%5B0%5D%5B4%5D='.$yesterday_year.
    '&amp;time%5B0%5D%5B5%5D='.$yesterday_hour.
    '&amp;time%5B0%5D%5B6%5D=&amp;time%5B0%5D%5B7%5D='.
    '&amp;time%5B0%5D%5B8%5D=+&amp;time%5B0%5D%5B9%5D=+';
$last72_year  = date("Y", time()-86400 * 3);
$last72_month = date("m", time()-86400 * 3);
$last72_day   = date ("d", time()-86400 * 3);
$last72_hour  = date ("H", time()-86400 * 3);
$last72 = '&amp;time%5B0%5D%5B0%5D=+&amp;time%5B0%5D%5B1%5D=%3E%3D'.
    '&amp;time%5B0%5D%5B2%5D='.$last72_month.
    '&amp;time%5B0%5D%5B3%5D='.$last72_day.
    '&amp;time%5B0%5D%5B4%5D='.$last72_year.
    '&amp;time%5B0%5D%5B5%5D='.$last72_hour.
    '&amp;time%5B0%5D%5B6%5D=&amp;time%5B0%5D%5B7%5D='.
    '&amp;time%5B0%5D%5B8%5D=+&amp;time%5B0%5D%5B9%5D=+';
$tmp_24hour        = 'base_qry_main.php?new=1'.$yesterday.'&amp;submit='._QUERYDBP.'&amp;num_result_rows=-1&amp;time_cnt=1';
$tmp_24hour_unique = 'base_stat_alerts.php?time_cnt=1'.$yesterday;
$tmp_24hour_sip    = 'base_stat_uaddr.php?addr_type=1' . $tmp_DSO . '&amp;time_cnt=1'.$yesterday;
$tmp_24hour_dip    = 'base_stat_uaddr.php?addr_type=2' . $tmp_DSO . '&amp;time_cnt=1'.$yesterday;
$tmp_72hour        = 'base_qry_main.php?new=1'.$last72.'&amp;submit='._QUERYDBP.'&amp;num_result_rows=-1&amp;time_cnt=1';
$tmp_72hour_unique = 'base_stat_alerts.php?time_cnt=1'.$last72;
$tmp_72hour_sip    = 'base_stat_uaddr.php?addr_type=1' . $tmp_DSO . '&amp;time_cnt=1'.$last72;
$tmp_72hour_dip    = 'base_stat_uaddr.php?addr_type=2' . $tmp_DSO . '&amp;time_cnt=1'.$last72;
$tmp_today         = 'base_qry_main.php?new=1'.$today.'&amp;submit='._QUERYDBP.'&amp;num_result_rows=-1&amp;time_cnt=1';
$tmp_today_unique  = 'base_stat_alerts.php?time_cnt=1'.$today;
$tmp_sip           = 'base_stat_uaddr.php?addr_type=1' . $tmp_DSO . '&amp;time_cnt=1'.$today;
$tmp_dip           = 'base_stat_uaddr.php?addr_type=2' . $tmp_DSO . '&amp;time_cnt=1'.$today;

$tmp_Source = _SOURCE;
$tmp_Dest = _DEST;

echo '
          <div class="stats">
            <table width="100%" class="systemstats">
              <tr class="main_quick_surf">
	            <td style="text-align:left;">- '. _TALERTS .'</td>
	            <td><a href="'.$tmp_today_unique.'">'. _UNI .'</a></td>
	            <td><a href="'.$tmp_today.'">'. _LISTING .'</a></td>
	            <td><a href="'.$tmp_sip.'">'._SOURCEIP.'</a></td>
	            <td><a href="'.$tmp_dip.'">'._DESTIP.'</a></td>
	          </tr>

              <tr class="main_quick_surf">
	            <td style="text-align:left;">- '. _L24ALERTS .'</td>
	            <td><A href="'.$tmp_24hour_unique.'">'. _UNI .'</a></td>
	            <td><A href="'.$tmp_24hour.'">'. _LISTING .'</a></td>
	            <td><A href="'.$tmp_24hour_sip.'">'._SOURCEIP.'</a></td>
	            <td><A href="'.$tmp_24hour_dip.'">'._DESTIP.'</a></td>
	          </tr>

              <tr class="main_quick_surf">
	            <td style="text-align:left;">- '. _L72ALERTS .'</td>
	            <td><a href="'.$tmp_72hour_unique.'">'._UNI.'</a></td>
	            <td><a href="'.$tmp_72hour.'">'. _LISTING .'</a></td>
	            <td><a href="'.$tmp_72hour_sip.'">'._SOURCEIP.'</a></td>
	            <td><a href="'.$tmp_72hour_dip.'">'._DESTIP.'</a></td>
	          </tr>

	          <tr class="main_quick_surf">
	            <td style="text-align:left;">- ' . _MOSTRECENT . $last_num_alerts . _ALERTS .'</td>
	            <td><a href="base_qry_main.php?new=1&amp;caller=last_any&amp;num_result_rows=-1&amp;submit=Last%20Any">' . _ANYPROTO . '</a></td>
	            <td><a href="base_qry_main.php?new=1&amp;layer4=TCP&amp;caller=last_tcp&amp;num_result_rows=-1&amp;submit=Last%20TCP">TCP</a></td>
	            <td><a href="base_qry_main.php?new=1&amp;layer4=UDP&amp;caller=last_udp&amp;num_result_rows=-1&amp;submit=Last%20UDP">UDP</a></td>
	            <td><a href="base_qry_main.php?new=1&amp;layer4=ICMP&amp;caller=last_icmp&amp;num_result_rows=-1&amp;submit=Last%20ICMP">ICMP</a></td>
	          </tr>

              <tr class="main_quick_surf">
	            <td style="text-align:left;">- '._LSOURCEPORTS.'</td>
	            <td><a href="base_stat_ports.php?caller=last_ports&amp;port_type=1&amp;proto=-1&amp;sort_order=last_d">'._ANYPROTO.'</a></td>
                <td><a href="base_stat_ports.php?caller=last_ports&amp;port_type=1&amp;proto=6&amp;sort_order=last_d">TCP</a></td>
                <td><a href="base_stat_ports.php?caller=last_ports&amp;port_type=1&amp;proto=17&amp;sort_order=last_d">UDP</a></td>
	          </tr>
      
              <tr class="main_quick_surf">
	            <td style="text-align:left;">- '._LDESTPORTS.'
                <td><a href="base_stat_ports.php?caller=last_ports&amp;port_type=2&amp;proto=-1&amp;sort_order=last_d">'._ANYPROTO.'</a></td>
                <td><a href="base_stat_ports.php?caller=last_ports&amp;port_type=2&amp;proto=6&amp;sort_order=last_d">TCP</a></td>
                <td><a href="base_stat_ports.php?caller=last_ports&amp;port_type=2&amp;proto=17&amp;sort_order=last_d">UDP</a></td>
              </tr>

              <tr class="main_quick_surf">
	            <td style="text-align:left;">- '._FREGSOURCEP.'</td>
	            <td><a href="base_stat_ports.php?caller=most_frequent&amp;port_type=1&amp;proto=-1' . $tmp_DSO . '">'._ANYPROTO.'</a></td>
	            <td><a href="base_stat_ports.php?caller=most_frequent&amp;port_type=1&amp;proto=6' . $tmp_DSO . '">TCP</a></td>
	            <td><a href="base_stat_ports.php?caller=most_frequent&amp;port_type=1&amp;proto=17' . $tmp_DSO . '">UDP</a></td>
	          </tr>
      
              <tr class="main_quick_surf">
	            <td style="text-align:left;">- '._FREGDESTP.'</td>
	            <td><a href="base_stat_ports.php?caller=most_frequent&amp;port_type=2&amp;proto=-1' . $tmp_DSO . '">'._ANYPROTO.'</a></td>
	            <td><a href="base_stat_ports.php?caller=most_frequent&amp;port_type=2&amp;proto=6' . $tmp_DSO . '">TCP</a></td>
	            <td><a href="base_stat_ports.php?caller=most_frequent&amp;port_type=2&amp;proto=17' . $tmp_DSO . '">UDP</a></td>
	          </tr>

              <tr class="main_quick_surf">
	            <td style="text-align:left;">- '._MOSTFREQUENT . $freq_num_uaddr . " " ._ADDRESSES.":".'</td>';
NLIO('<td>',4);
NLIO(
	"<a href='base_stat_uaddr.php?caller=most_frequent&amp;addr_type=1"
	. $tmp_DSO . "'>" . $tmp_Source . '</a>', 5
);
NLIO('</td><td>',4);
NLIO(
	"<a href='base_stat_uaddr.php?caller=most_frequent&amp;addr_type=2"
	. $tmp_DSO . "'>" . $tmp_Dest . '</a>', 5
);
NLIO('<td>',4);
NLIO("</tr><tr class='main_quick_surf_2'>",3);
echo'              <td colspan=2>- <a href="base_stat_alerts.php?caller=last_alerts&amp;sort_order=last_d">'._MOSTRECENT.$last_num_ualerts._UNIALERTS.'</a></td>
	          </tr>

	          <tr class="main_quick_surf_2">
	            <td colspan=2>- <a href="base_stat_alerts.php?caller=most_frequent' . $tmp_DSO . '">'._MOSTFREQUENT . $freq_num_alerts . " " ._UNIALERTS.'</a>';
PrintFramedBoxFooter(1,2);
print '          </div>
    </td>
    <td align="right" valign="top">
      <div class="systemstats">';
UpdateAlertCache($db);
NLIO('<b>' . _QUERIED . ':</b> ' . date('D F d, Y H:i:s') . '<br/>');
$DSN = $db->DB_host; // Pull this info from the DB object.
$tdp = $db->DB_port;
if ( LoadedString($tdp) ){
	$DSN = "$DSN:$tdp";
}
$tmp = $db->DB_name . '@' . $DSN;
printf(
	"<b>" . _DATABASE . "</b> %s (<b>" . _SCHEMAV . "</b> %d)<br/>",
	$tmp, $db->baseGetDBversion()
);
StartStopTime($start_time, $end_time, $db);
$tmp = '<b>' . _TIMEWIN . '</b> ';
if ( LoadedString($start_time) ){
	$tmp .= '[' . $start_time . '] - [' . $end_time . ']';
}else{
	$tmp .= '<em>' . _NOALERTSDETECT . '</em>';
}
NLIO($tmp,4);
NLIO('</div>',3);
PrintTblNewRow(0);
?>
    <td align="center" valign="top">
<?php
NLIO(
	"<a href='base_qry_main.php?new=1'>" .  Icon('search', _SEARCH, 6)
	. _SEARCH . '</a>', 5
);
NLIO('<br/>', 5);
if ( PearInc('Graphing', 'Image', 'Graph') ){
	NLIO(
		"<a href='base_graph_main.php?new=1'>" . Icon('graph', _GALERTD, 6)
		. _GALERTD . '</a>', 5
	);
	NLIO('<br/>', 5);
}
NLIO(
	"<a href='base_stat_time.php'>" . Icon('graph', _GALERTDT, 6) . _GALERTDT
	. '</a>', 5
);
NLIO('<br/>', 5);
DBLink();
PrintFramedBoxFooter(1,2);
NLIO('<hr/>',2);
?>
<table style='border:0' width='100%'>
  <tr>
    <td width='30%' valign='top'>
<?php
/* mstone 20050309 avoid count(*) if requested */
PrintGeneralStats($db, 0, $main_page_detail, "", "", $avoid_counts != 1);

/* mstone 20050309 make show_stats even leaner! */
if ( $main_page_detail == 1 ){
    echo '
    </td>
    <td width="70%" valign="top">
    <strong>'._TRAFFICPROBPRO.'</strong>';
    PrintProtocolProfileGraphs($db);
}
PrintFramedBoxFooter(1,2);
NLIO('<hr/>',2);
PrintBASESubFooter();
?>
