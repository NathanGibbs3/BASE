<?php
//
//base-rss (config for rss-core) - Queries the snort database and
//returns an alerts RSS feed with links to BASE.
//

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
// Copy this file to another file and change the values to make different RSS
// feeds for different source subnets or for different alerts in the feed.

// Title of the RSS feed
$channel_title="Snort IDS - Alerts";

// How many hours of past alerts to report on.
// (Your RSS reader should refresh at least this often.)
$time_window="2";

// Will ET TROJAN alerts will be in the RSS feed?
// (Only applies if using the "Emerging Threats" ruleset.)
// "true" will show only the ET TROJAN alerts. 
// "false" will show any alerts except the ET TROJAN alerts.
//
//  No entry will show ALL alerts within the specified time window.
$trojan_alerts="";

// Only report on source IPs matching a regular expression.
// ex. /^202\.12\.((19[2-9])|((20[0-9])|(210)))\./
$src_ip_regex="/.*/";

include("./rss-core.php");
?>
