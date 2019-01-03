<?
//
//rss-core - Queries the snort database and returns an alerts RSS feed with links to BASE.
//Copyright (C) 2010 Daniel Michitsch
//
//This program is free software; you can redistribute it and/or
//modify it under the terms of the GNU General Public License
//as published by the Free Software Foundation; either version 2
//of the License, or (at your option) any later version.
//
//This program is distributed in the hope that it will be useful,
//but WITHOUT ANY WARRANTY; without even the implied warranty of
//MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
//GNU General Public License for more details.
//
//You should have received a copy of the GNU General Public License
//along with this program; if not, write to the Free Software
//Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
//
//
$timeoffset=(idate('Z')/3600);
if($timeoffset < 0) {
$timeoffset=$timeoffset+$time_window;
} else {
$timeoffset=$timeoffset-$time_window;
}
date_default_timezone_set('GMT');

include("../base_conf.php");

function dec2IP ($dec) {
        $hex = dec2hex ($dec);
        if (strlen($hex) == 7) $hex = "0".$hex;
        $one = hexdec(substr($hex,0,2));
        $two = hexdec(substr($hex,2,2));
        $three = hexdec(substr($hex,4,2));
        $four = hexdec(substr($hex,6,2));
        $ip = $one.".".$two.".".$three.".".$four;
        return ($ip);
}
function dec2hex($dec) {
        if($dec > 2147483648) {
                $result = dechex($dec - 2147483648);
                $prefix = dechex($dec / 268435456);
                $suffix = substr($result,-7);
                $hex = $prefix.str_pad($suffix, 7, "0000000", STR_PAD_LEFT);
        }
        else {
                $hex = dechex($dec);
        }
        $hex = strtoupper ($hex);
        return($hex);
}

function RFC2822($date, $time = '00:00') {
    list($y, $m, $d) = explode('-', $date);
    list($h, $i, $s) = explode(':', $time);

    return date('r', mktime($h,$i,$s,$m,$d,$y));
}

function hex2asc($myin) {
for ($i=0; $i<strlen($myin)/2; $i++) {
$myout.=chr(base_convert(substr($myin,$i*2,2),16,10));
}
return $myout;
}

mysql_connect($alert_host,$alert_user,$alert_password);
@mysql_select_db($alert_dbname) or die( "Unable to select database");
if($trojan_alerts=='true') {
$query="SELECT acid_event.*, data.data_payload FROM acid_event INNER JOIN data ON acid_event.cid = data.cid WHERE sig_name like 'ET TROJAN%' AND DATE_SUB(NOW(), INTERVAL $timeoffset HOUR) < timestamp ORDER BY timestamp DESC";
} elseif($trojan_alerts=='false') {
$query="SELECT acid_event.*, data.data_payload FROM acid_event INNER JOIN data ON acid_event.cid = data.cid WHERE sig_name not like 'ET TROJAN%' AND DATE_SUB(NOW(), INTERVAL $timeoffset HOUR) < timestamp ORDER BY timestamp DESC";
} else {
$query="SELECT acid_event.*, data.data_payload FROM acid_event INNER JOIN data ON acid_event.cid = data.cid WHERE DATE_SUB(NOW(), INTERVAL $timeoffset HOUR) < timestamp ORDER BY timestamp DESC";
}
$result=mysql_query($query);

$num=mysql_numrows($result);

mysql_close();

header("Content-Type: text/xml");
header("Pragma: no-cache");
header("Cache-Control: no-store, no-cache, must-revalidate, post-check=0, pre-check=0");
echo "<?xml version=\"1.0\" encoding=\"ISO-8859-1\"?>\n";
echo "<rss version=\"2.0\">\n";
echo "  <channel>\n";
echo "    <title>$channel_title</title>\n";
echo "    <link>http://".$_SERVER['SERVER_NAME'].$BASE_urlpath."</link>\n";
echo "    <description>Snort IDS with Emerging Threats signatures.</description>\n";

$i=0;
while ($i < $num) {

$ip_src=dec2IP(mysql_result($result,$i,"ip_src"));

if(preg_match($src_ip_regex, $ip_src)) {
$ip_dst=dec2IP(mysql_result($result,$i,"ip_dst"));
$sid=mysql_result($result,$i,"sid");
$cid=mysql_result($result,$i,"cid");
$sig_name=mysql_result($result,$i,"sig_name");
$sig_name=str_replace("<", "&lt;", $sig_name);
$sig_name=str_replace(">", "&gt;", $sig_name);
$sig_name=str_replace("&", "&amp;", $sig_name);
$sig_name=str_replace("%", "&#37;", $sig_name);
$timestamp=mysql_result($result,$i,"timestamp");
$data_payload=mysql_result($result,$i,"data_payload");
$hostbegin = "486F73743A20";
if(strpos($data_payload, $hostbegin) === false) {
$host_dst = $ip_dst;
} else {
$beginpos = strpos($data_payload, $hostbegin)+12;
$endpos = strpos($data_payload, '0D', $beginpos);
$host_dst = hex2asc(substr($data_payload, $beginpos, $endpos - $beginpos));
}
$layer4_sport=mysql_result($result,$i,"layer4_sport");
$layer4_dport=mysql_result($result,$i,"layer4_dport");
$timestamp=mysql_result($result,$i,"timestamp");
$guid=md5($sig_name).$ip_src;
list($date , $time ) = explode(' ', $timestamp);
$timestamp=RFC2822($date, $time);
$timechars=array("-"," ",":");
if (!empty($layer4_sport)) $layer4_sport=":".$layer4_sport;
if (!empty($layer4_dport)) $layer4_dport=":".$layer4_dport;
echo "    <item>\n";
echo "      <title>$sig_name</title>\n";
echo "      <link>http://".$_SERVER['SERVER_NAME'].$BASE_urlpath."/base_qry_alert.php?clear_criteria=ip_addr&amp;clear_criteria_element=&amp;new=1&amp;submit=%23$i-%28$sid-$cid%29&amp;sort_order=time_d</link>\n";
echo "      <description>source IP address &lt;a href=\"http://".$_SERVER['SERVER_NAME'].$BASE_urlpath."/base_stat_ipaddr.php?ip=$ip_src&amp;netmask=32\"&gt;$ip_src&lt;/a&gt; to destination &lt;a href=\"http://www.mywot.com/en/scorecard/$host_dst\"&gt;$host_dst&lt;/a&gt;</description>\n";
echo "      <author>$ip_src</author>\n";
echo "      <pubDate>$timestamp</pubDate>\n";
echo "      <guid>$guid</guid>\n";
echo "    </item>\n";
}

$i++;
}

echo "  </channel>\n";
echo "</rss>\n";
?>
