<?php
/*******************************************************************************
** Basic Analysis and Security Engine (BASE)
** Copyright (C) 2004 BASE Project Team
** Copyright (C) 2000 Carnegie Mellon University
**
** (see the file 'base_main.php' for license details)
**
** Project Leads: Kevin Johnson <kjohnson@secureideas.net>
**                Sean Muller <samwise_diver@users.sourceforge.net>
** Built upon work by Roman Danyliw <rdd@cert.org>, <roman@danyliw.com>
**
** Purpose: FAQ page
********************************************************************************
** Authors:
********************************************************************************
** Kevin Johnson <kjohnson@secureideas.net
**
********************************************************************************
*/
if (!isset($BASE_path)){ // Issue #5
	$BASE_path = dirname(__FILE__);
	$BASE_path = preg_replace("/\/help.*/", "", $BASE_path);
}
include("$BASE_path/base_conf.php");
include_once("$BASE_path/includes/base_auth.inc.php");
include_once("$BASE_path/includes/base_db.inc.php");
include_once("$BASE_path/includes/base_output_html.inc.php");
include_once("$BASE_path/includes/base_constants.inc.php");
include_once("$BASE_path/base_common.php");

// Check role out and redirect if needed -- Kevin
$roleneeded = 10000;
$BUser = new BaseUser();
if ( $Use_Auth_System == 1 && $BUser->hasRole($roleneeded) == 0 ){
	base_header("Location: $BASE_urlpath/index.php");
}

PrintBASESubHeader('Local FAQ');
NLIO("<h1>For the latest FAQ: <a href='https://github.com/NathanGibbs3/BASE'>"
	."Check the Github Repo</a>.</h1>",2);
NLIO('<hr/>',2);

NLIO("<h2>Why do certain alerts seem to have 'unknown' IPs?</h2>",2);
NLIO('<p>'."The Snort database plug-in only logs packet information into "
."the database when an alert is triggered by a rule (signature).  Therefore, "
."since alerts generated by pre-preprocessors such as portscan and "
."mini-fragment have no corresponding rules, no packet information is logged "
."beyond an entry indicating their occurrence.  As a consequence, BASE "
."cannot display any packet-level (e.g. IP address) information for these "
."alerts.</p>",2);
NLIO('<p>'."For these particular alerts, certain statistics may show zero "
."unique IP addresses, list the IP address as 'unknown', and will not list "
."any packet information when decoding the alert.</p>",2);

NLIO("<h2>BASE appears to be broken in Lynx</h2>",2);
NLIO('<p>'."This is a known issue.  Lynx mangles some of the form arguments "
."appended to the URL.  It's resolution is being investigated, but use "
."Firefox, Netscape, Opera, or IE in the mean time.</p>",2);

NLIO("<h2>Can priorities be assigned to Alerts?</h2>",2);
NLIO('<p>'."The quick answer to this question is no.  BASE is at the mercy of "
."the underlying database, since Snort doesn't assign priorities, BASE does "
."not have priorities.  Nevertheless, there are several work-arounds:</p>",2);
NLIO('<ul>',2);
NLIO('<li>'."It is possible to enforce priorities of sort at the database "
."level by writing alerts of different severity to separate databases.  For "
."example, critical alerts such as buffer overflows can be written to one "
."database, while scan alerts can be written to another.  Then load two "
."different versions of BASE, each pointing to a different instance of the "
."database.</li>",3);
NLIO('<li>'."With manual intervention Alert Groups (AG) can be used to assign "
."priority.  Essentially, this strategy entails creating an AG for each "
."severity level and manually moving the alerts as they arrive into the "
."appropriate group.</li>",3);
NLIO('</ul>',2);

include("$BASE_path/base_footer.php");
PageEnd();
?>
