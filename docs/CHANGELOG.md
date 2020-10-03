# Changelog

All notable changes to this project will be documented in this file using the
[Keep a CHANGELOG](http://keepachangelog.com) principles.

## [Unreleased]
### Added
 - PHP 7x Support.
 - MariaDB Support.
 - PHPUnit Tests.
 - Whois support for LACNIC & Afrinic.
 - Support for Travis-CI build checks on commit.
 - Support for Coveralls.io & Codecov.io code coverage reports on commit.

Contributor(s): [Nathan Gibbs]
### Changed
- Update snort.org sid lookup URL.  
Now matches new functionality in the snort.org web site.  
Such a lookup needs a URL, such as https://snort.org/rule_docs/1-2003 .
- Update isc.sans.org port lookup URL.

Contributor(s): [Nathan Gibbs]
### Deprecated
### Removed
- ports.tantalo.net port lookup URL.
Contributor(s): [Nathan Gibbs]
### Fixed
- [#2 Return value of function `GetVendor()`. ](
https://github.com/NathanGibbs3/BASE/issues/2)
- [#6 PHP Strict standards/Warning: Declaration Messages on Travis-CI & Local.](
https://github.com/NathanGibbs3/BASE/issues/6)
- [#7 PHP Deprecated: Messages on Travis-CI & Local.](
https://github.com/NathanGibbs3/BASE/issues/7)
- [#28 Change ADODB download URL in error message.](
https://github.com/NathanGibbs3/BASE/issues/28)
- [#34 `VerifyDBAbstractionLib()` Not operative under safe mode.](
https://github.com/NathanGibbs3/BASE/issues/34)
- [#38 BASE does not check for unset variables in admin pages before access.](
https://github.com/NathanGibbs3/BASE/issues/38)
- [#39 Function `base_header()` does not check if headers where sent before
calling `header()` and exiting.](
https://github.com/NathanGibbs3/BASE/issues/39)
- [#40 `PrintFreshPage()` does not check for unset Superglobal variable
`$_SERVER['REQUEST_URI']` before access.](
https://github.com/NathanGibbs3/BASE/issues/40)
- [#31 BASE does not check for unset conf variables external_*_link before
access.](
https://github.com/NathanGibbs3/BASE/issues/31)
- [#44 `SignatureCriteria::PrintForm()` does not check for unset conf
variable `$GLOBALS['use_sig_list']` before access.](
https://github.com/NathanGibbs3/BASE/issues/44)
- [#45 BASE does not check for unset conf variable `$show_expanded_query`
before access.](
https://github.com/NathanGibbs3/BASE/issues/45)
- [#27 `BuildSigLookup()` does not check for unset conf variable
`external_sig_link` before access.](
https://github.com/NathanGibbs3/BASE/issues/27)
- [#46 BASE does not check for unset conf variable `$show_summary_stats`
before access.](
https://github.com/NathanGibbs3/BASE/issues/46)
- [#47 BASE does not check for unset conf variable `$show_previous_alert`
before access.](
https://github.com/NathanGibbs3/BASE/issues/47)
- [#48 `QueryState::ExecuteOutputQuery()` does not check for unset conf
variable `$show_rows` before access.](
https://github.com/NathanGibbs3/BASE/issues/48)
- [#49 BASE does not check for unset conf variable `$show_first_last_links`
before access.](
https://github.com/NathanGibbs3/BASE/issues/49)
- [#50 BASE does not check for unset conf variable `$colored_alerts`
before access.](
https://github.com/NathanGibbs3/BASE/issues/50)
- [#51 `returnUser()` does not check for set cookie before access.](
https://github.com/NathanGibbs3/BASE/issues/51)
- [#52 `Init()` does not check for unset conf variable `$MAX_ROWS`
before access.](
https://github.com/NathanGibbs3/BASE/issues/52)
- [#53 'IPAddressCriteria::PrintForm()` does not check for unset conf
variable `$ip_address_input` before access.](
https://github.com/NathanGibbs3/BASE/issues/53)
- [#59 BASE does not check for unset conf variable `$priority_colors`
before access.](
https://github.com/NathanGibbs3/BASE/issues/59)
- [#60 BASE color alert severity information is downgraded by one.](
https://github.com/NathanGibbs3/BASE/issues/60)
- [#58 FQDN DB errors.](
https://github.com/NathanGibbs3/BASE/issues/58)
- [#67 `baseExecute()` returns invalid data structure.](
https://github.com/NathanGibbs3/BASE/issues/67)
- [#69 BASE does not check for unset variable `$src_ip` before access.](
https://github.com/NathanGibbs3/BASE/issues/69)
- [#70 Standardize HTML page closures.](
https://github.com/NathanGibbs3/BASE/issues/70)
- [#72 Incorrect Display of Admin Users.](
https://github.com/NathanGibbs3/BASE/issues/72)

Contributor(s): [Nathan Gibbs]
- [#57 `tr bgcolor="#"` if `$prio` not in `$priority_colors`
(unknown event class)](
https://github.com/NathanGibbs3/BASE/issues/57)
Contributor(s):
  - [FalcoGer]
  - [Nathan Gibbs]
### Security
- [#32 Add Anti XSS code to CGI Action "display" in base_user.php.](
https://github.com/NathanGibbs3/BASE/issues/32)
- [#13 Add Anti XSS code to `returnRoleNamesDropDown()`.](
https://github.com/NathanGibbs3/BASE/issues/13)

Contributor(s): [Nathan Gibbs]

---
## [1.4.5 (lilias)] - 2009-11-03
### Added
- Default Sort Order.  
Now "time_d" (= descending order) instead on "none".  
Bug [reported](https://sourceforge.net/p/secureideas/bugs/234/)
on sourceforge.net by Brian. Original ID: 2899177  
- Added some debug code to take on bug #2880551  
Bug [reported](https://sourceforge.net/p/secureideas/bugs/228/)
on sourceforge.net. Original ID: 2880551

Contributor(s): Juergen Leising
### Changed
- Snort,org SID lookup.  
Now matches new functionality in the snort.org web site.
Such a lookup needs a URL, such as http://www.snort.org/search/sid/1-2003 .  
Contributor(s):
  - Shawn Jefferson from the "Snort-users" mailing list on Feb, 23rd 2010.
  - Juergen Leising
  - [Kevin Johnson]
- Contrib code base-rss.  
Contributor(s): Dan Michitsch
### Fixed
- Display of archived Alerts.  
While the alerts have been archived correctly, any lookups of them in the 
archive DB have failed, because connecting to the archive DB was 
performed using the `baseConnect()` method only, and several sql queries in 
`base_qry_alert()` are simply not compatible with `baseConnect()`. Using 
`basePConnect()` fixes this. **Actually, more a workaround than a real fix.**  
Bug [reported](https://sourceforge.net/forum/message.php?msg_id=7609952)
on sourceforge.net.   
- Changing packet display type shows error message Temporary fix.  
Bug [reported](https://sourceforge.net/p/secureideas/bugs/226/)
on sourceforge.net. Original ID: 2874199
- Fixed pcap download for FLoP-extended DB's on 64-bit platforms.  
- Fixed Fatal error: Call to undefined method.  
"Call to undefined method `ProtocolFieldCriteria::ProtocolFieldCriteria()` in
includes/base_state_citems.inc.php"  
Bug [reported](https://sourceforge.net/p/secureideas/bugs/235/)
on sourceforge.net. Original ID: 2919599
- Insufficient memory for the world map functions in base_graph_main.php.  
Increased it to 256MB.
- Labelling of the x-axis with the signature classifications chart.
- The font system in the PEAR::Image::Graph and Canvas library is broken on
fedora 12 with php-5.3.  
This can not be fixed from inside BASE, any more.
However, a fix is possible, if the IMAGE_CANVAS_SYSTEM_FONT_PATH
in /usr/share/pear/Image/Canvas.php from the PEAR::Image::Canvas library gets
defined properly. The fontmap.txt file does NOT matter, any more.  
See docs/README.graph_alert_data for details.

Contributor(s): Juergen Leising
- IP address encoding problem in base_payload.php:  
This refers to non-flop DB's only.  
Bug [reported](https://sourceforge.net/p/secureideas/bugs/233/)
on sourceforge.net. Original ID: 2889623.  
Contributor(s): Brian Brzezicki & Brad Benson Discovery & patch.
### Security
- XSS flaws in base_local_rules.php:  
Contributor(s):
  - Dave Hull -- XSS Scan
  - [Kevin Johnson]

---
## [1.4.4 (dawn)] - 2009-07-24
### Changed
- Local signature documentation link generation.  
Rewrote code that produces a link to a local directory with documentation for
alerts in front of the signature names.  
Bug [reported](https://sourceforge.net/p/secureideas/bugs/216/)
on sourceforge.net. Original ID: 2808527  
Contributor(s):
  - Akos Daniel - Report
  - Juergen Leising
### Fixed
- Warning display issue.  
Warning displayed: "The following query key has not been implemented, yet."
Debug code responsible for this has been removed.   
Bug [reported](https://sourceforge.net/p/secureideas/bugs/218/)
on sourceforge.net. Original ID: 2813925  
Bug [reported](https://sourceforge.net/p/secureideas/bugs/215/)
on sourceforge.net. Original ID: 2808291  
Bug [reported](https://sourceforge.net/p/secureideas/bugs/211/)
on sourceforge.net. Original ID: 2798983  
Bug [reported](https://sourceforge.net/p/secureideas/bugs/221/)
on sourceforge.net. Original ID: 2828709
- Signature display error.  
Signature names were displayed as raw html rather than as real names with
clickable URLs.  
Bug [reported](https://sourceforge.net/p/secureideas/bugs/213/)
on sourceforge.net. Original ID: 2803137
- PHP Warning Issues.  
  - `array_count-values()` in base_qry_common.php.  
  - Undefined variable: sf_portscan_flag
- "local whois" link issues.  
Bug [reported](http://sourceforge.net/forum/forum.php?thread_id=3331857&forum_id=404428)
on sourceforge.net.
  - IP addresses updated and moved to the configuration file.
  - No storage to cache in case of negative whois responses.
  - New search strings to determine if the query is forwarded to another whois server.

Contributor(s): Juergen Leising
- Email alert action issue.  
Bug [reported](https://sourceforge.net/p/secureideas/bugs/220/)
on sourceforge.net. Original ID: 2825018  
Contributor(s):
  - Patch submitted anonymously.
  - Juergen Leising
### Security
- SQLI flaw.  
Contributor(s):
  - Peter Österberg - Report
  - [Kevin Johnson]
- XSS and LFI in base_local_rules.php  
Contributor(s):
  - Clint Ruoho - Report
  - [Kevin Johnson]

---
## [1.4.3.1 (zig)] - 2009-05-30
### Security
- Multiple XSS flaws in User and Role management.  
Contributor(s): [Kevin Johnson]

---
## [1.4.3 (gabi)] - 2009-05-14
### Security
- XSS Flaws in alert groups.
- Potential SQLI flaw in AG.
- XSS Flaws in base_qry files.
- Multiple XSS flaws in citems.

Contributor(s): [Kevin Johnson]

---
## [1.4.2 (chandy)] - 2009-04-03
### Added
- A new reference "[rule]" now points to base_local_rules.php, which displays
a particular rule for a given rules id (sid).  
The variable "local_rules_dir" in base_conf.php must point to an existing
and readable/searchable directory which contain the snort rules.  
NOTE: A web server is usually **NOT** allowed to access any files outside of
its document root.  
Bug [reported](https://sourceforge.net/forum/message.php?msg_id=5310420)
on sourceforge.net.  
Bug [reported](https://sourceforge.net/forum/message.php?msg_id=5311517)
on sourceforge.net.  
Contributor(s):
  - Chris Ryan - Feature Request
  - Juergen Leising
### Changed
- EmThreats_link now opens in separate browser window/tab.  
Bug [reported](https://sourceforge.net/p/secureideas/bugs/199/)
on sourceforge.net.  
Contributor(s):
  - Micah Gersten - Report & patch.
  - Juergen Leising.
- Update of base.spec; works with fedora 10.  
Contributor(s): Juergen Leising
### Fixed
- Oracle 10 DB Issues.  
Bug [reported](https://sourceforge.net/forum/message.php?msg_id=5795641)
on sourceforge.net.  
Bug [reported](https://sourceforge.net/forum/message.php?msg_id=5796556)
on sourceforge.net.  
Contributor(s):
  - asavenkov - Report & patches.
  - Juergen Leising.
- Duplicate variable defines in base_conf.php.  
The "email-the-alerts"-variables were defined twice.
- Archiving IP options Issue.

Contributor(s): Juergen Leising
- Email action empty recipient field.  
Emails from BASE containing one or more alerts now include a "To:"-header, as
well.  
Bug [reported](https://sourceforge.net/p/secureideas/bugs/207/)
on sourceforge.net. Original ID: 2234733
- Loss of Sort Order when action applied to alerts.  
$sort_order, once it has been chosen, now survives an "action".  
Bug [reported](https://sourceforge.net/p/secureideas/bugs/208/)
on sourceforge.net. Original ID: 2234745  
Affected files:  
  - base_stat_uaddr.php
  - base_stat_ports.php
  - base_stat_iplink.php
  - base_stat_class.php
  - base_stat_sensor.php

Contributor(s):  
    - Reported anonymously.  
    - Juergen Leising

- Page refresh issue.  
All pages would refresh after an "action" has been taken. Even pages that
didn't need to.  
Bug [reported](https://sourceforge.net/p/secureideas/bugs/171/)
on sourceforge.net. Original ID: 1681012  
Affected files:  
  - base_stat_uaddr.php
  - base_stat_ports.php
  - base_stat_iplink.php
  - base_stat_class.php
  - base_stat_sensor.php

Contributor(s):  
    - Jordan Wiens - Report.  
    - Juergen Leising.

- Display of ICMP redirect messages.  
Contributor(s):
  - Bruno G. San Alejo
  - Juergen Leising
- Preprocessor alert handling issue.  
Several preprocessor events that did not get stored in the acid_event table,
so far, are now processed and displayed by BASE. This affects all those
preprocessors which have sig names that do NOT start with an "spp_" prefix.  
Bug [reported](https://sourceforge.net/p/secureideas/discussion/404428/thread/a1df8608/)
on sourceforge.net.  
Contributor(s):
  - Trey Beck - Report.
  - Juergen Leising.

---
## [1.4.1 (lara)] - 2008-06-29
### Added
- Support for new chart type "unique alerts vs. number of alerts".  
Bug [reported](https://sourceforge.net/p/secureideas/feature-requests/61/)
on sourceforge.net. Original ID: 1659968
- Information to the docs about how to fix a pear::image::graph library bug
preventing legends from being displayed with pie charts.
- Information to the docs about how to fix a missing fonts problem.
- A README about the usage of Jason Brvenik's SnortUnified plugin.
- New coordinates file: world_map6.txt.

Contributor(s): Juergen Leising
### Changed
- Translations:
  - German  -- Chris Ryan & Juergen Leising.
- Update emergingthreats.net SID lookup URL.
- Update of base.spec.  
Complete rewrite for building rpm packages of BASE. The base-contrib rpm
should now be installable under fedora 9.
- uf_csv.pl now generates IP addresses in human readable form.
- Relative paths of the adodb library are converted to an absolute path.
- Updates to Jason Brvenik's SnortUnified plugin.  
Fixed some smaller issues.  
Adjusted to work with perl-5.10 and Net::Packet-3.25, as shipped with fedora 9.

Contributor(s): Juergen Leising
### Fixed
- Moving alerts with empty sig_priority.  
Contributor(s):
  - Michel Lundell.
  - [Kevin Johnson].
- Further Issues in the setup procedure.  
- Download of pcap for sfportscan alerts.  
Now disabled as these are just pseudo packets rather than real packets from
the network.  
Bug [reported](https://sourceforge.net/p/secureideas/bugs/189/)
on sourceforge.net. Original ID: 188567
- Workaround for issues related to
[1699443](https://sourceforge.net/p/secureideas/bugs/174/)  
Bug [Reported](
https://sourceforge.net/p/secureideas/discussion/404428/thread/e928db2a/)
on sourceforge.net.
- Workaround for bug #1762491.  
Suspect it is related to [1699443](https://sourceforge.net/p/secureideas/bugs/174/)  
Bug [reported](https://sourceforge.net/p/secureideas/bugs/181/)
on sourceforge.net. Original ID: 1762491
- Bug #1974990.  
Bug [reported](https://sourceforge.net/p/secureideas/bugs/194/)
on sourceforge.net. Original ID: 1974990
- Issues in `PrintPortscanEvents()`.  
Bug [reported](https://sourceforge.net/p/secureideas/discussion/404428/thread/0783409a/)
on sourceforge.net.  
- Workaround for a potential bug in `preg_replace()`.  
Bug [reported](https://sourceforge.net/p/secureideas/discussion/404428/thread/de160eca/)
on sourceforge.net.  
- Missing signature classification names issue in base_graph_common.php.  
In this case different bars should be displayed, not just one "unclassified"
one.
- Misc HTML bugs in the query form.
- Syntax error in setup1.php in case of a wrong adodb path.
- Sensor display issue in search form.
Same problem as in `UpdateAlertCache()` in base-php4/includes/base_cache.inc.php.
- SQL error with sig_priority when queried from the query form.
- "Select Signature from List" issue in the query form.
- Bug #2001211.  
Bug [reported](https://sourceforge.net/p/secureideas/bugs/197/)
on sourceforge.net. Original ID: 2001211

Contributor(s): Juergen Leising
- Insufficient memory for the world map functions in base_graph_common.php.  
Increased it from 50MB to 128MB.  
Bug [reported](https://sourceforge.net/p/secureideas/discussion/404428/thread/330426b5/)
on sourceforge.net.  
Contributor(s):
  - Chris Ryan - Testing
  - Juergen Leising
- Workarounds and fixes for:  
1 "Fatal error: Call to a member function `MoveNext()` on a non-object in
  /var/www/base-127/includes/base_db.inc.php on line 463". Workaround.  
2 Improved error handling in base_action.inc.php: Errors are now shown in
  regular mode rather than with $debug_mode > 1.  Not every kind of error is a
  duplicate error.  
3 Fixed `baseGetHostByAddr()` in base_net.inc.php in cases, where it does not
  get provided with a numerical ("dotted") IP address. The "ERROR: invalid
  input syntax for integer is a rather late state", though.  
4 Fixed a violation of referential integrity, that does not get accepted by
  PostgreSQL, at least not by those ones of version 8.x.  
NOTE: base_action.inc.php, where I have essentially moved the actions around
  the table "event" upwards, so that these lines get performed prior to lines
  that change/update any other tables.  
Bug [reported](https://sourceforge.net/p/secureideas/bugs/174/)
on sourceforge.net. Original ID: 1699443  
Contributor(s):
  - Jeff Balderson - Report & Testing.
  - Chris Ryan - Testing.
  - Juergen Leising.

---
## [1.4.0 (katherine)] - 2008-04-25
### Added
- Country support for source and destination ip in "graph alert data".  
RFE [submitted](https://sourceforge.net/p/secureideas/feature-requests/52/)
on sourceforge.net. Original ID: 1399907  
Contributor(s):
  - Axton Grams - Submission
  - Juergen Leising
- Worldmap feature.
- BASE64 support for TCP options.
- Vendor MAC Map.
- Debug function calls.

Contributor(s): Juergen Leising
- Patch for Barnyard.  
Contributor(s):
  - University of Florida - Patch Submission
  - [Kevin Johnson]
### Changed
- Translations:
  - Norwegian SEARCH tag. -- Ronny Hansen & [Kevin Johnson]
  - Chinese. -- Johnson Chiang
- Updated INSTALL documents.  
Contributor(s): Sean Muller
### Fixed
- index.php redirect issues.  
Bug [reported](https://sourceforge.net/p/secureideas/bugs/185/)
on sourceforge.net. Original ID: UNKNOWN  
Contributor(s):  
  - Terry Burton - Report.  
  - [Kevin Johnson]
- base_qry_alert.php Showing corrupt HTML.  
Bug [reported](https://sourceforge.net/p/secureideas/bugs/183/)
on sourceforge.net. Original ID: 1836274
- Line Duplication.  
Bug [reported](https://sourceforge.net/p/secureideas/bugs/190/)
on sourceforge.net. Original ID: 1886513

Contributor(s):  
    - Reported anonymously.  
    - [Kevin Johnson]

- Error message "You have an error in your SQL syntax".  
Bug [reported](https://sourceforge.net/p/secureideas/bugs/187/)
on sourceforge.net. Original ID: 1859481  
- Multiple graphing issues.
- Query bug regarding time.
- Setup issues.

Contributor(s): Juergen Leising
- Paging does not work in base_stat_sensor.php.  
Bug [reported](https://sourceforge.net/p/secureideas/bugs/184/)
on sourceforge.net. Original ID: 1846317  
Contributor(s): Kevin Marion

---
## [1.3.9 (anne)] - 2007-11-20
### Added
- Sean Muller as the BASE Project Manager. Welcome. :-)
Approvals & Sign off(s): [Kevin Johnson]
- INSTALL and INSTALL.rtf files to documentation.
Contributor(s): Sean Muller
### Changed
- Translations:
  - Spanish. -- David Gil
  - Chinese. -- Randy
### Fixed
- Issue `base_header()` is undefined.  
Bug [reported](https://sourceforge.net/p/secureideas/bugs/177/)
on sourceforge.net. Original ID: 1750697
Contributor(s):  
  - Juergen Leising - Report.
  - [Kevin Johnson]
- Sans lookup fails.  
Patch [submitted](https://sourceforge.net/p/secureideas/bugs/169/)
on sourceforge.net. Original ID: 1680965  
Contributor(s): Jordan Wiens
- Contrib code base-rss.  
Contributor(s): Dan Michitsch
- Sort order ignored in initial search request.  
Bug [reported](https://sourceforge.net/p/secureideas/bugs/179/)
on sourceforge.net. Original ID: 1760615
Contributor(s):  
  - Jordan Wiens - Report.
  - Sean Muller
  - [Kevin Johnson]
### Security
- XSS bug in BASE fixed.  
Bug [reported](https://sourceforge.net/p/secureideas/bugs/182/)
on sourceforge.net. Original ID: 1801192  
Contributor(s):  
  - Daniel Medianero - Discovery & Report.
  - Sean Muller
  - [Kevin Johnson]

---
## [1.3.8 (jodie)] - 2007-07-08
### Added
- base_header wrapper.  
Please use it instead of header if you're not sure.  
Contributor(s): GaRaGeD
- Spanish install guide.  
Contributor(s): Daniel Medianero
### Removed
- PDF generation support.  
Provided by fpdf library.  
This will save space as we are not using it.  
Contributor(s): [Kevin Johnson]
### Fixed
- "colored_alerts" missing from base_conf.php  
Bug [reported](https://sourceforge.net/p/secureideas/patches/26/)
on sourceforge.net. Original ID: UNKNOWN
- HTML fixes in base_main.php  
Remove an extra table and repair two column display.  
Bug [reported](https://sourceforge.net/p/secureideas/patches/25/)
on sourceforge.net. Original ID: UNKNOWN

Contributor(s): Jonathan W Miner
- Top Right, DB and User not shown.  
Bug [reported](https://sourceforge.net/p/secureideas/bugs/175/)
on sourceforge.net. Original ID: 1723928
- Snort signature information links broken.  
This fix is really a hack, as it works around bugs in snort & barnyard.
Bug [reported](https://sourceforge.net/p/secureideas/bugs/167/)
on sourceforge.net. Original ID: 1675094
- Maybe need count(DISTINCT ip_src) to sort by IP correctly.  
Bug [reported](https://sourceforge.net/p/secureideas/bugs/172/)
on sourceforge.net. Original ID: 1689885
- Use of archive DB seems broken in "karen" release -- [Kevin Johnson]
Bug [reported](https://sourceforge.net/p/secureideas/bugs/161/)
on sourceforge.net. Original ID: 1649659

Contributor(s): [Kevin Johnson]
- Cleaned a warning.  
Contributor(s): Marek Cruz
### Security
- Call to `exit()` added to the redirect to fix security hole.  
Contributor(s): Jon Hart

---
## [1.3.7] - Missing from Legacy Changelog

---
## [1.3.6 (louise)] - 2007-05-13
### Removed
- Portsdb.org as it is no longer valid.  
Contributor(s): Marek Cruz	
### Fixed
- Issues with graphing.  
Bug [reported](https://sourceforge.net/p/secureideas/bugs/162/)
on sourceforge.net. Original ID: 166596
- MySQL Syntax error with base_net.inc.php.  
Bug [reported](https://sourceforge.net/p/secureideas/bugs/163/)
on sourceforge.net. Original ID: 1666340

Contributor(s): [Kevin Johnson]
- Patch to fix display of index.php -- Jonathon W Miner
Patch [submitted](https://sourceforge.net/p/secureideas/patches/24/)
on sourceforge.net. Original ID: UNKNOWN  
Contributor(s): Jonathan W Minor
- Memory Leak in base_cache.inc.php.  
Patch [submitted](https://sourceforge.net/p/secureideas/bugs/164/)
on sourceforge.net. Original ID: 1669265  
Contributor(s): Stephen Sadowski
- BACK text not commented out when history disabled.  
Bug [reported](https://sourceforge.net/p/secureideas/bugs/166/)
on sourceforge.net. Original ID: 1675086
- SANS IP Lookup fails.  
Bug [reported](https://sourceforge.net/p/secureideas/bugs/169/)
on sourceforge.net. Original ID: 1680965

Contributor(s):  
    - Jordan Wiens - Report.  
    - [Kevin Johnson]

- Multiple errors related to undefined vars.
- HTML code for W3C.org compliance.
- Misspelled constants.
- `CreateBASEAG()` function.
- Check for Image_Graph.

Contributor(s): Marek Cruz

---
## [1.3.5 (marie)] - 2007-02-19
### Added
- Pcap download functionality for individual alerts.
- Logout functionality when internal authentication is used.

Contributor(s): Jon Hart
### Changed
- Updated README.
- Updated Ports list.

Contributor(s): [Kevin Johnson]
### Fixed
- [IP Fragments].  
Patch [submitted](https://sourceforge.net/p/secureideas/bugs/156/)
on sourceforge.net. Original ID: 1602391  
Contributor(s): Juergen Leising
- Typo in setup4.php.  
Patch [submitted](https://sourceforge.net/p/secureideas/patches/20/)
on sourceforge.net. Original ID: UNKNOWN  
- Extra \<table\> in base_main.php.  
Patch [submitted](https://sourceforge.net/p/secureideas/patches/23/)
on sourceforge.net. Original ID: UNKNOWN  

Contributor(s): Jonathan W Miner
- Site location in footer.  
Contributor(s): [Kevin Johnson]
- Correction to `IPFieldCriteria::Description()` on base_state_citems.inc.php.
- Check for `ProtocolFieldCriteria::Description()` array correctness.

Contributor(s): GaRaGeD - Patch
- Payload queries fail due to inconsistent case.  
Patch [submitted](https://sourceforge.net/p/secureideas/bugs/149/)
on sourceforge.net. Original ID: 1550613  
Contributor(s):
  - Patch submitted anonymously.
  - [Kevin Johnson]

---
## [1.2.8 - 1.3.4] - Missing from Legacy Changelog

---
## [1.2.7 (karen)] - 2006-11-17
### Added
- Contrib code SnortUnified, a perl replacement for Barnyard.  
Contributor(s): Jason Brvenik
### Fixed
- HTML \<table\> output in "base_qry_alert.php".  
Patch [submitted](https://sourceforge.net/p/secureideas/patches/18/)
on sourceforge.net. Original ID: UNKNOWN
- Remove message when 0 alerts.  
Patch [submitted](https://sourceforge.net/p/secureideas/patches/19/)
on sourceforge.net. Original ID: UNKNOWN

Contributor(s): Jonathan W Miner
- PrintBase64PacketPayload fix for payload length modulo = 0.  
Patch [submitted](https://sourceforge.net/p/secureideas/bugs/143/)
on sourceforge.net. Original ID: UNKNOWN  
Contributor(s): Juergen Leising
- ProtocolFieldCriteria object constructors broken on PHP 5.1+.  
Added empty PHP 5.1+ class constructor shim to ProtocolFieldCriteria.  
Bug [reported](https://sourceforge.net/p/secureideas/bugs/141/)
on sourceforge.net. Original ID: UNKNOWN  
Contributor(s): [Kevin Johnson]
- Issue if sig_gid was empty.  
Contributor(s): Valter Santos
- Contrib code base-rss.  
Contributor(s): Dan Michitsch

---
## [1.2.6 (christine)] - 2006-07-23
### Added
- Check for base_users and base_roles tables in base_main.php.
- A . to VAR_PUNC to fix query issue.

Contributor(s): [Kevin Johnson]
- FQDN to display.  
Patch [submitted](https://sourceforge.net/p/secureideas/patches/15/)
on sourceforge.net. Original ID: UNKNOWN  
Contributor(s): Jonathan W Miner
- Settings for automatic expansion of the IP and Payload Criteria on Search screen - Bruce Briggs
RFE [submitted](https://sourceforge.net/p/secureideas/feature-requests/58/)
on sourceforge.net. Original ID: UNKNOWN
- Save the fields entered on the Search screen for Back button proper refilling - Bruce Briggs
RFE [submitted](https://sourceforge.net/p/secureideas/feature-requests/58/)
on sourceforge.net. Original ID: UNKNOWN

Contributor(s): Bruce Briggs
- Support for managing last_cid.  
RFE [submitted](https://sourceforge.net/p/secureideas/feature-requests/54/)
on sourceforge.net. Original ID: 1520185  
Contributor(s): Eric Jacobsen
- Contrib code base-rss.  
Contributor(s): Dan Michitsch
### Changed
- Changed show_rows to 49 in base_conf.php.dist to fix IE 6/7 bug - Bruce Briggs
RFE [submitted](https://sourceforge.net/p/secureideas/feature-requests/58/)
on sourceforge.net. Original ID: UNKNOWN  
Contributor(s): Bruce Briggs
- Changed base_stat_time.php to use GET instead of POST to avoid the
'resend data' warning on refresh.  
Contributor(s): Max Valdez (garaged)
### Fixed
- Issue with base_users table being required.  
Contributor(s): [Kevin Johnson]
- Search punctuation fix.  
Bug [reported](https://sourceforge.net/p/secureideas/bugs/134/)
on sourceforge.net. Original ID: UNKNOWN  
- `PrintForm()` Issues.

Contributor(s): Bruce Briggs
- Link to FAQ.  
Patch [submitted](https://sourceforge.net/p/secureideas/patches/16/)
on sourceforge.net. Original ID: UNKNOWN  
Contributor(s): Juergen Leising
- VAR_BOOLEAN error.
- Typos in the footer.

Contributor(s): Eric Jacobsen

---
## [1.2.5 (sarah)] - 2006-06-04
### Added
- Base64 support for MAC address display for FLoP extended DB.  
Patch [submitted](https://sourceforge.net/p/secureideas/patches/11/)
on sourceforge.net. Original ID: UNKNOWN
- Base64 support for rebuild of packet in pcap format for FLoP extended DB.  
Patch [submitted](https://sourceforge.net/p/secureideas/patches/13/)
on sourceforge.net. Original ID: UNKNOWN
- Base64 support for ICMP payload additional table in base_qry_alert.php.  
Patch [submitted](https://sourceforge.net/p/secureideas/patches/12/)
on sourceforge.net. Original ID: UNKNOWN

Contributor(s): Juergen Leising
- Check for PHP Logging Level against E_NOTICES in setup/index.php.  
- Function `CreateBASEAG()` in setup/setup_db.inc.php to resolve redundancy in
setup and base_db_setup.php.
- "Clear Data Tables" option in base_maintenance.php.
- "Repair Tables" option to execute `CreateBASEAG()`.

Contributor(s): Nikns
- Missing ICMP and TCP type and codes.  
Contributor(s):  
  - Jon Hart - Patch
  - Max Valdez (garaged)
- Support for ICMP redirect decoding.
- Decoding support for ICMP source quench and ICMP parameter problem.

Contributor(s): Jon Hart
### Changed
- Translations:
  - Chinese -- Johnson Chiang
- Use FLoP's event reference.  
Signature name of alert which triggered "Tagged Packet" alert is shown too.  
Contributor(s): Nikns
- Nessus.org URL Updated.  
Contributor(s): Jonathan W Miner
- split up "flags" into DF and MF, much like tcp flags are currently handled.  
Contributor(s): Jon Hart
### Removed
- Unnecessary or broken search index stuff from `CreateBASEAG()`.
Since schemas are already included.  
Contributor(s): Nikns
### Fixed
- Issue with Oracle and schema version in base_db.inc.php.
- Failure of alerts with sig references to archive causing duplicates error.
- Certain preprocessor alerts would not be cached (for example arpspoof).

Contributor(s): Nikns
- Time error in searches.  
Bug [reported](https://sourceforge.net/p/secureideas/bugs/134/)
on sourceforge.net. Original ID: UNKNOWN  
Contributor(s): Jeff Kell
- Refresh issue with ~ directories.  
Contributor(s): [Kevin Johnson]
- Display after deleting alerts.  
Contributor(s): Bruce Briggs
- Back button doesn't work after refresh.  
Bug [reported](https://sourceforge.net/p/secureideas/bugs/135/)
on sourceforge.net. Original ID: 1466392
Contributor(s): Juergen Leising
### Security
- Function `XSSPrintSafe()` ( array safe `htmlspecilchars()`).
- Made `filterSql()` use ADOdb `qmagic()`.
- Changed input type of the password field setup3.php.
- Filtered all $_POST and $_GET variables using `filterSql()`.  
Mainly auth system stuff.
- Sanitized all $_SERVER variables to be protected against XSS attacks.

Contributor(s): Nikns
- Session forgery issue with cookie data and authentication scheme.  
Contributor(s):  
  - Nikns - Report
  - Max Valdez (garaged)

---
## [1.2.4 (melissa)] - 2006-03-19
### Added
- Checkboxes in base_stat_iplink.php.
- Support for actions in base_stat_uaddr.php.  
RFE [submitted](https://sourceforge.net/p/secureideas/feature-requests/19/)
on sourceforge.net. Original ID: 1123382
- Archive DB support for schema 107.
- Sig_gid (signature generator id) to snort signature reference URL for
schema 107.
- Archive DB support for FLoP extended DB schema.
- Rebuild of packet in pcap format for FLoP extended DB.
- Display of MAC addresses in base_query_alert.php for FLoP extended DB.
- HTTP response codes in base_maintenance.php for standalone mode.  
Indicating authentication failure.

Contributor(s): Nikns
### Changed
- MSSQL schema for table acid_event.  
Sig_name now has type VARCHAR instead of TEXT.  
- Detection of attempts to archive duplicate alerts.  
Now done via select queries instead of catching db conflict errors.

Contributor(s): Nikns
- Moved `session_start()` to base_conf.php.  
Reasoning for this was avoiding repetition, easier to work with debug output.  
Issue [opened](https://github.com/NathanGibbs3/BASE/issues/66)
on GitHub.com to facilitate continued tracking of this.
- Force conf variable $debug_mode to off on login in index.php.

Contributor(s): Max Valdez (garaged)
### Fixed
- Issue with PostgreSQL and schema in base_db.inc.php.  
Bug [reported](https://sourceforge.net/p/secureideas/bugs/124/)
on sourceforge.net. Original ID: UNKNOWN  
- Error in SQL with PostgreSQL.  
Bug [reported](https://sourceforge.net/p/secureideas/bugs/107/)
on sourceforge.net. Original ID: 1284695  

Contributor(s):  
    - Nikns  
    - [Kevin Johnson]
- Issues displaying PortScans.  
- Archive move and Email summary issues.  
Bug [reported](https://sourceforge.net/p/secureideas/bugs/123/)
on sourceforge.net. Original ID: 1408387
- Archive DB wasn't used after setup.
- PostgreSQL archive DB support.  
Bug [reported](https://sourceforge.net/p/secureideas/bugs/105/)
on sourceforge.net. Original ID: UNKNOWN
- Unable to use actions in base_stat_sensor.php.  
Bug [reported](https://sourceforge.net/p/secureideas/bugs/113/)
on sourceforge.net. Original ID: 1313261
- First of month timestamp issue.  
Bug [reported](https://sourceforge.net/p/secureideas/bugs/118/)
on sourceforge.net. Original ID: 1371532
- Lost alert order when switching between payload display.  
Bug [reported](https://sourceforge.net/p/secureideas/bugs/121/)
on sourceforge.net. Original ID: 1406945
- Search by signature name.  
Bug [reported](https://sourceforge.net/p/secureideas/bugs/127/)
on sourceforge.net. Original ID: 1418660
- Incorrect line termination in sql/create_base_tbls_mssql_extra.sql.  
Converted to CRLF line terminators.
- Broken auth system for MSSQL.
- Broken base_stat_alerts.php with MSSQL.  
Bug [reported](https://sourceforge.net/p/secureideas/bugs/112/)
on sourceforge.net. Original ID: 1307250
- Force to use alert DB for auth system stuff.  
Bug [reported](https://sourceforge.net/p/secureideas/bugs/125/)
on sourceforge.net. Original ID: 1413594
- Case of name 'Archive' in base_main.php (in sync with base_hdr1.php).
- Support for actions in base_stat_class.php.  
- Broken search by IP criteria.  
Bug [reported](https://sourceforge.net/p/secureideas/bugs/127/)
on sourceforge.net. Original ID: 1418660
- Support for actions in base_stat_iplink.php.
- Support for actions in base_stat_ports.php.
- Empty email sent even if action unsuccessful.  
Bug [reported](https://sourceforge.net/p/secureideas/bugs/128/)
on sourceforge.net. Original ID: 1422575
- Unable to Graph Alert Detection Time.  
Bug [reported](https://sourceforge.net/p/secureideas/bugs/129/)
on sourceforge.net. Original ID: 1424033
- Score removed from email address.  
Bug [reported](https://sourceforge.net/p/secureideas/bugs/130/)
on sourceforge.net. Original ID: 1426089
- Packet display mode issues.  
Bug [reported](https://sourceforge.net/p/secureideas/bugs/82/)
on sourceforge.net. Original ID: 1210542  
Bug [reported](https://sourceforge.net/p/secureideas/bugs/109/)
on sourceforge.net. Original ID: 1288402  
- Update alert cache for archived alert right after it is copied to archive db.  
Bug [reported](https://sourceforge.net/p/secureideas/bugs/132/)
on sourceforge.net. Original ID: 1430686
- Unable to download binary payload in Internet Explorer when using SSL.  
Bug [reported](https://sourceforge.net/p/secureideas/bugs/102/)
on sourceforge.net. Original ID: 1275536
- Sequential non-ASCII payload character display issue in plain display mode.  
Several sequential non-ASCII payload characters join together displaying their
count.

Contributor(s): Nikns
- Issues with filtering by sig_class and sig_priority.  
Bug [reported](https://sourceforge.net/p/secureideas/bugs/122/)
on sourceforge.net. Original ID: 1407325  
Contributor(s):
  - Nikns  
  - Max Valdez (garaged)
- Issues with file paths under MS Windows in base_conf.php.  
Bug [reported](https://sourceforge.net/p/secureideas/bugs/126/)
on sourceforge.net. Original ID: 1413712  
- Issue when errors are encountered during setup.  
Form values are remembered, except language which defaults back to English.

Contributor(s): Max Valdez (garaged)
- Show IP header length in bytes, not words.  
Bug [reported](https://sourceforge.net/p/secureideas/bugs/133/)
on sourceforge.net. Original ID: 1341286
Contributor(s): Juergen Leising

### Security
- BASE authentication bypass in standalone mode for base_maintenance.php.  
Contributor(s): Nikns
- Changed input type of the password field in admin/useradmin.php.  
Contributor(s): [Kevin Johnson]

---
## [1.2.3] - Missing from Legacy Changelog

---
## [1.2.2 (cindy)] - 2006-01-12
### Added
- Translations:
  - Turkish -- Umut Nacak
- Portscan Information.  
Contributor(s):  
  - Nikns
  - [Kevin Johnson]
### Changed
- Login button to actually say login.  
Patch [submitted](https://sourceforge.net/p/secureideas/patches/9/)
on sourceforge.net. Original ID: UNKNOWN  
Contributor(s): Jonathan W Minor
- Updated project lead comments.  
Contributor(s): [Kevin Johnson]
### Fixed
- Issue with signature names and MySQL 5.0.  
Contributor(s): Kade P. Cole
- Auto-refresh ignored for stat pages.  
Bug [reported](https://sourceforge.net/p/secureideas/bugs/116/)
on sourceforge.net. Original ID: 1347623  
Contributor(s): Shane Castle
- Fixed Sort order issues.   
Bug [reported](https://sourceforge.net/p/secureideas/bugs/119/)
on sourceforge.net. Original ID: UNKNOWN  
Contributor(s): Timothy Doty
### Security
- SQL Injection vulnerability.  
Contributor(s):  
  - Debian maintainer - Patch
  - [Kevin Johnson]

---
## [1.2.1 (kris)] - 2005-10-30
### Added
- Black theme.  
Contributor(s): Nick Whitehill
### Fixed
- URL for sstats and display for the external port links.  
Patch [submitted](https://sourceforge.net/p/secureideas/patches/8/)
on sourceforge.net. Original ID: 1333209  
Contributor(s): Jonathan W Miner
### Security
- SQL Injection vulnerability.  
Bug [reported](https://sourceforge.net/p/secureideas/bugs/115/)
on sourceforge.net. Original ID: 1338142  
Contributor(s): [Kevin Johnson]
- Better error reporting to catch some forms of SQL injection.
Contributor(s): Doug Mackie

---
## [1.2.0 (betty)] - 2005-10-09
### Added
- Binary download capability.  
Patch [submitted](https://sourceforge.net/p/secureideas/patches/7/)
on sourceforge.net. Original ID: UNKNOWN  
Contributor(s): Juergen Leising
- ISC Source/Subnet report.
- Other sources of port information.
- TrustedSource IP lookup.

Contributor(s): Alex Butcher
- Local reference to read sigs from a directory in the BASE install.
Contributor(s):  
  - Juergen Leising
  - [Kevin Johnson]
### Fixed
- Error when performing search.  
Bug [reported](https://sourceforge.net/p/secureideas/bugs/98/)
on sourceforge.net. Original ID: 1256237
- Rawip criteria in base_qry_common.php.  
Bug [reported](https://sourceforge.net/p/secureideas/bugs/97/)
on sourceforge.net. Original ID: 1254318

Contributor(s): Tim Rupp
- ASCII representation with base64 encoding.  
Bug [reported](https://sourceforge.net/p/secureideas/bugs/94/)
on sourceforge.net. Original ID: 1249911  
Contributor(s):  
  - Juergen Leising - Report & Patch
  - [Kevin Johnson]
- Quote issue in base_stat_iplink.php.  
Contributor(s): Michael Stone
- Sort of alerts by address.  
Contributor(s): Alex Butcher
- Fixed Portscan issue with emails.  
Bug [reported](https://sourceforge.net/p/secureideas/bugs/104/)
on sourceforge.net. Original ID: UNKNOWN  
Contributor(s): Nikns Siankin

---
## [1.1.4 (cheryl)] - 2005-08-08
### Added
- Translations:
  - Polish. -- Michal Fraszek
### Changed
- Documentation.  
Contributor(s): [Kevin Johnson]
- Translations:
  - Misc changes across entire TD set. -- João
  - Misc issue across entire TD set. -- [Kevin Johnson]
  - Polish. -- Michal Fraszek
  - English. -- Christian Svensson
  - Swedish most of the //NEW variables. -- Christian Svensson
### Fixed
- Typo in create_base_tbls_mysql.sql.  
This typo was in all of the sql files.  
Bug [reported](https://sourceforge.net/p/secureideas/bugs/89/)
on sourceforge.net. Original ID: 1220169
- URL reference with BleedingSnort rules.  
Bug [reported](https://sourceforge.net/p/secureideas/bugs/96/)
on sourceforge.net. Original ID: 1253176

Contributor(s): [Kevin Johnson]

---
## [1.1.3 (lynn)] - 2005-06-13
### Added
- Options to base_conf.php to increase performance.
Contributor(s): Michael Stone
- Translations:
  - Czech. -- Michal Mertl
  - Simplified Chinese. -- Hai Xie (xxxss@msn.com)
- Custom footer option.   
Patch [submitted](https://sourceforge.net/p/secureideas/patches/5/)
on sourceforge.net. Original ID: UNKNOWN  
Contributor(s): Jonathan W Miner
- Oracle support.  
Contributor(s): J Vladimir Anufriev
- Red UI theme.  
Contributor(s): [Kevin Johnson]
### Changed
- Default value of $avoid_counts conf var in base_conf.php.  
Contributor(s): [Kevin Johnson]
- Whois entry for ARIN.  
Contributor(s): Jonathan W Miner
- Translations:
  - French. -- Dominik Gehl
### Removed
- jpGraph leftovers.  
Contributor(s): [Kevin Johnson]
### Fixed
- PostgreSQL performance.  
- Performance Issues with today queries.  
Bug [reported](https://sourceforge.net/p/secureideas/bugs/65/)
on sourceforge.net. Original ID: UNKNOWN

Contributor(s): Michael Stone
- Issue with show_stats.  
Need to change this based on Alejandro's change to the front page.
- Issues with README.

Contributor(s): [Kevin Johnson]
- Oracle DB Setup script.  
Contributor(s):  
  - John Evans
  - [Kevin Johnson]
- Off by one bug.
- HTML code for W3C.org compliance.  
Patch [submitted](https://sourceforge.net/p/secureideas/patches/6/)
on sourceforge.net. Original ID: UNKNOWN

Contributor(s): Jonathan W Miner
- Form Issues.  
Contributor(s):  
  - Bruce Briggs
  - [Kevin Johnson]
- Table width issues.  
Contributor(s):  
  - Thinh Pham
  - [Kevin Johnson]
- FAQ Update.
Contributor(s): Joel Esler
- Search with multiple Time criteria is broken.  
Bug [reported](https://sourceforge.net/p/secureideas/bugs/78/)
on sourceforge.net. Original ID: 1199128  
- Incorrect count/list of Unique alerts after deleting some.  
Bug [reported](https://sourceforge.net/p/secureideas/bugs/67/)
on sourceforge.net. Original ID: 1183916
- Auto Refresh.  
Bug [reported](https://sourceforge.net/p/secureideas/bugs/80/)
on sourceforge.net. Original ID: 1208972

Contributor(s): Tim Rupp
- icat.nist.gov link error.  
Bug [reported](https://sourceforge.net/p/secureideas/bugs/83/)
on sourceforge.net. Original ID: 1214307  
Contributor(s): Thinh Pham

---
## [1.1.2 (zora)] - 2005-04-08
### Added
- Option to set the "archive_exists" conf var to the setup system.  
Contributor(s): [Kevin Johnson]
### Fixed
- Sort order bug.  
Bug [reported](https://sourceforge.net/p/secureideas/bugs/61/)
on sourceforge.net. Original ID: UNKNOWN  
Contributor(s): Jonathan W Miner
- Issue with TimeStamps.  
Bug [reported](https://sourceforge.net/p/secureideas/bugs/57/)
on sourceforge.net. Original ID: UNKNOWN  
- Fatal error in some installs.

Contributor(s): Michael Stone
- Unable to check alerts.  
Bug [reported](https://sourceforge.net/p/secureideas/bugs/58/)
on sourceforge.net. Original ID: UNKNOWN  
Contributor(s): [Kevin Johnson]

---
## [1.1.1] - Missing from Legacy Changelog

---
## [1.1.0 (elizabeth)] - 2005-04-03
### Added
- Packet display.  
RFE [submitted](https://sourceforge.net/p/secureideas/feature-requests/2/)
on sourceforge.net. Original ID: 1048868  
Contributor(s):  
  - Patrick Harper - RFE
  - [Kevin Johnson]
- Unique install ID variable to base_conf.php.  
Contributor(s):  
  - Al Reust - RFE
  - [Kevin Johnson]
- Access to the archive DB from one BASE install.  
RFE [submitted](https://sourceforge.net/p/secureideas/feature-requests/18/)
on sourceforge.net. Original ID: 1122502  
Contributor(s):  
  - RFE submitted anonymously.
  - [Kevin Johnson]
- FAQ to documentation.  
Contributor(s): Joel Esler
- Themes (Needs much more work).  
RFE [submitted](https://sourceforge.net/p/secureideas/feature-requests/22/)
on sourceforge.net. Original ID: 1150696
- Add Archive to the page header & title.  
RFE [submitted](https://sourceforge.net/p/secureideas/feature-requests/23/)
on sourceforge.net. Original ID: 1150789
- Oracle DB support.  
RFE [submitted](https://sourceforge.net/p/secureideas/feature-requests/11/)
on sourceforge.net. Original ID: 1114443  
RFE [submitted](https://sourceforge.net/p/secureideas/feature-requests/29/)
on sourceforge.net. Original ID: Unknown
- Cookie to make payload display persistent.
- Stand alone maintenance program. base_maintenance.pl.
- Authentication support to base_maintenance.pl.
- Check to verify the existence of the archive DB before allowing it's use.

Contributor(s): [Kevin Johnson]
### Changed
- Portuguese Translation.  
Contributor(s): Thiago Martins
- German Translation.  
Contributor(s): Heinrich Lieker
- Spanish Translation.  
Contributor(s): Ricardo Montañana Gómez
- Payload Display link moved to the Payload section of the display.
- Snort,org SID lookup.  
Now matches new functionality in the snort.org web site.
- Default value of variable "$resolve_IP" in base_conf.php.  
New default is 0.
- Moved FORM tag before Timing box.
- Docs to reflect removal of $ChartLib_path.

Contributor(s): [Kevin Johnson]
- Search form simplified.
- Graph form simplified.  
RFE [submitted](https://sourceforge.net/p/secureideas/feature-requests/21/)
on sourceforge.net. Original ID: 1144818

Contributor(s): Thom Dosedel

- Replaced jpgraph with PEAR::Image_Graph.
Contributor(s): Alejandro Flores

### Removed
- $ChartLib_path.  
Contributor(s): [Kevin Johnson]

### Fixed
- PostgreSQL setup.  
Contributor(s): C. Bensend
- Clarified comments to BASE_urlpath in base_conf.php.  
Bug [reported](https://sourceforge.net/p/secureideas/bugs/27/)
on sourceforge.net. Original ID: Unknown  
Contributor(s):
  - Jeff Kell - Report
  - [Kevin Johnson]
- Call to undefined function in Whois Cache.  
Bug [reported](https://sourceforge.net/p/secureideas/bugs/28/)
on sourceforge.net. Original ID: 1123243
- Mix up in the title of the Source and Destination Ports pages.  
- Lack of distinct results after clicking Src IP.  
Bug [reported](https://sourceforge.net/p/secureideas/bugs/31/)
on sourceforge.net. Original ID: 1143574

Contributor(s): Tim Rupp
- Listing users with no users error.  
Bug [reported](https://sourceforge.net/p/secureideas/bugs/29/)
on sourceforge.net. Original ID: 1123247
- Archive DB switching.  
Bug [reported](https://sourceforge.net/p/secureideas/bugs/33/)
on sourceforge.net. Original ID: 1145254
- Summary display for the archive DB.  
Bug [reported](https://sourceforge.net/p/secureideas/bugs/36/)
on sourceforge.net. Original ID: 1150681
- Incorrect display of Archive Actions.  
Bug [reported](https://sourceforge.net/p/secureideas/bugs/37/)
on sourceforge.net. Original ID: 1150688
- IP Links sorting issue.
Bug [reported](https://sourceforge.net/p/secureideas/bugs/44/)
on sourceforge.net. Original ID: 1164491  
- Search Criteria lost.  
Bug [reported](https://sourceforge.net/p/secureideas/bugs/46/)
on sourceforge.net. Original ID: 1166158  
Bug introduced in BASE [1.0.2 (racquel)]

Contributor(s): [Kevin Johnson]
- Garbled Web UI messages due to hard coded charset.  
Bug [reported](https://sourceforge.net/p/secureideas/bugs/30/)
on sourceforge.net. Original ID: 1123815  
Contributor(s):
  - Patch submitted anonymously.
  - Tim Rupp
- Previous / Next Bug.  
Bug [reported](https://sourceforge.net/p/secureideas/bugs/22/)
on sourceforge.net. Original ID: 1116034  
Contributor(s): [Kevin Johnson] for Alejandro Flores
- Pie charts not working.  
Bug [reported](https://sourceforge.net/p/secureideas/bugs/32/)
on sourceforge.net. Original ID: 1144817  
Contributor(s): 
  - Alejandro Flores
  - Tim Rupp
- Fixed bug in new base_maintenance.pl.  
Contributor(s): Doug Mackie
- Error when emailing alerts from the AG page.  
Bug [reported](https://sourceforge.net/p/secureideas/bugs/48/)
on sourceforge.net. Original ID: UNKNOWN  
Contributor(s):
  - Ethan Chai - Report
  - [Kevin Johnson]
- Search sort issues, another fix.  
Bug [reported](https://sourceforge.net/p/secureideas/bugs/3/)
on sourceforge.net. Original ID: 1051872  
Contributor(s):
  - Harry Bloomberg - Report
  - Chris Shepherd
  - Thom Dosedel
  - Bruce Briggs
- PHP safe mode breaks BASE.  
Bug [reported](https://sourceforge.net/p/secureideas/bugs/47/)
on sourceforge.net. Original ID: 1168587  
Contributor(s):
  - Michael Scheidell - Report
  - [Kevin Johnson]
- Setup would die silently.
Contributor(s): Jeff Nathan
- Misc actions errors.  
Contributor(s):
  - Alejandro Flores
  -  Tim Rupp
- Error "cannot use string as array offset" in search page again.  
Contributor(s): Tim Rupp
- Unexpected Result Inconsistency.  
Bug [reported](https://sourceforge.net/p/secureideas/bugs/51/)
on sourceforge.net. Original ID: UNKNOWN  
Contributor(s):
  - Bruce Briggs - Report
  - Joel Esler

---
## [1.0.2 (racquel)] - 2005-02-13
### Added
- DShield.org IP Info page.
- isc.sans.org port DB for more information.  
Contributor(s): [Kevin Johnson]
- Summary Statistics box to base_stat_alerts page.  
RFE [submitted](https://sourceforge.net/p/secureideas/feature-requests/9/)
on sourceforge.net. Original ID: Unknown  
Contributor(s):
  - Joel Esler - RFE
  - Tim Rupp
### Changed
- Packaging directory structure.  
Contributor(s): Kevin Hoffman
- External IP links now open in a new window/tab.  
Contributor(s): [Kevin Johnson]
- All HTTP_*_VARS have been changed to newer superglobals.  
Contributor(s): Tim Rupp
- Portuguese Translation.  
Contributor(s): Thiago Martins
- Setup only checks for indexes on MySQL.
- Cleaned up conf files.  
Contributor(s): [Kevin Johnson]
- All Language files now have proper spacing between items and links to
Members list.  
Contributor(s): Joel Esler
### Fixed
- Host cache expiry issue.  
Contributor(s): Michael Stone - Report
- Search issue with PHP 5.  
Contributor(s): Tim Rupp
- Performance on time based queries for MySQL.  
Contributor(s): Michael Stone
- Potential navigation fatal error seen with some PHP5 installations.
- Duplicate defines in language files.  
Bug [reported](https://sourceforge.net/p/secureideas/bugs/17/)
on sourceforge.net. Original ID: 1114778  
Contributor(s): Tim Rupp
- Errors creating PostgreSQL tables.  
Bug [reported](https://sourceforge.net/p/secureideas/bugs/23/)
on sourceforge.net. Original ID: 1118792  
Contributor(s): [Kevin Johnson]
- Bug #1111841.  
Bug [reported](https://sourceforge.net/p/secureideas/bugs/11/)
on sourceforge.net. Original ID: 1111841  
Contributor(s): Alejandro Flores
- Out of memory error in Graph Alert Data on vanilla installs of PHP5.  
Contributor(s): Tim Rupp
- Footer link plus look and feel.  
Bug [reported](https://sourceforge.net/p/secureideas/bugs/19/)
on sourceforge.net. Original ID: Unknown  
Contributor(s):
  - [Kevin Johnson]
  - Joel Esler

---
## [1.0.1 (michelle)] - 2005-01-17
### Added
- Translations:
  - Finnish -- Elmo Mäntynen
  - Chinese -- Johnson Chiang
  - Indonesian -- Rachim Tamsjadi
- Better display of error messages.  
Contributor(s): Jason Dixon
### Changed
- Code Cleanup.  
  - Include code that limits number of includes per file.  
Issue [opened](https://github.com/NathanGibbs3/BASE/issues/63)
on GitHub.com to facilitate continued tracking of this.  
  - Removed regex from base_db.inc.php and related WHERE clauses.  
  - Contributor(s): [Kevin Johnson]
### Fixed
- Alert Group Issues.  
Contributor(s): Tim Rupp
- PostgreSQL sql files.  
Bug [reported](https://sourceforge.net/p/secureideas/bugs/9/)
on sourceforge.net. Original ID: Unknown  
Contributor(s):
  - Jeff Balderson - Report & patch.
  - [Kevin Johnson]
- PostgreSQL issues.
- User add issue.  
Adding a user after deleting one that was not the last user added caused this.  
- Graphing issues.  

Contributor(s): [Kevin Johnson]
- Setup bugs.  
Contributor(s): Michiel Brendel
- "Duplicate Entry" errors.  
Contributor(s): pr00f at users.sourceforge.net    
- MSSQL setup scripts did not correctly insert roles.  
Contributor(s): Alan Vallance
- PostgreSQL setup issues.  
Contributor(s): Jason Dixon

---
## [1.0.0 (denise)] - 2004-11-21
### Added
- Patch from nobody.
- Functions in base_stat_common.php now return sensors with no events. New
function `SensorCnt()` created to handle this.
- images/ directory.
- languages/ directory.
- Start of the language files.
- setup/ directory with  program.
- help/ directory.
- Start of help icon system used in the setup system.
- Checks on each page to determine if you are logged in.
- BASE tables to the PostgreSQL and the MSSQL files.
- Added Upgrade script for 0.9.x -> 1.0 for MySQL.  

Contributor(s): [Kevin Johnson]
- Added Refresh to multiple pages.  
Bug [reported](https://sourceforge.net/p/secureideas/bugs/2/)
on sourceforge.net. Original ID: 1048873  
Contributor(s): Arkadiusz Majer, [Kevin Johnson]
- Translations:
  - English -- [Kevin Johnson]
  - Japanese -- Kenji
  - French -- Maurice Lanselle and Sebastien Desse
  - Norwegian -- Ole-Martin Bækkeli
  - Swedish -- Jimmy Hansson
  - German -- Menrath Johann
  - Portuguese -- Pedro Walter
  - Russian -- Dmitry Purgin
  - Spanish -- Jason Santos
  - Italian -- DJ Echelon
  - Danish -- Kim Christensen
- Nessus ref to base_conf.dist.  
Contributor(s): Christian Svensson
- New Flow-Portscan functionality.  
Contributor(s): Scott Elgram & Jean-Marc
### Changed
- Finished the User stuff! This framework will continue to be built upon but
as of right now, I would consider it stable!
- Naming of admin pages to match the rest of the project.
- Role management system.
- moved app_faq to help/.  

Contributor(s): [Kevin Johnson]
- Minor UI changes.  
Contributor(s): Joel Esler, [Kevin Johnson]
- Formatting for/in base_signature.inc.php  
Contributor(s): Randy McEoin
### Fixed
- Issue where people couldn't add a user if UseAuth was off.  
Bug [reported](https://sourceforge.net/p/secureideas/bugs/6/)
on sourceforge.net.
- Error where menu appeared on the login page.
- Error with menus in the footer on any page but base_main.php.
- Fixed password size in the DB files.
- Fixed issue with MSSQL tables creation commands.  

Contributor(s): [Kevin Johnson]

---
## [0.9.9 (brenna)] - 2004-10-28
### Added
- Start of PDF work.
- GPL file.
- User Preferences page.
- Added listing of users to admin pages.  

Contributor(s): [Kevin Johnson]
- URL signature reference.  
Contributor(s): Joel Esler
### Changed
- All acid functions to base functions.
  - base_db.inc.php
  - base_net.inc.php
- File base_conf.php.dist
- External links.
Bug [reported](https://sourceforge.net/p/secureideas/bugs/4/)
on sourceforge.net. Original ID: 1051873
- Authentication functions are finished.
- Completed all of the $BASE_path/ for includes.  

Contributor(s): [Kevin Johnson]
### Removed
- External links to SamSpade.  
Contributor(s): [Kevin Johnson]
### Fixed
- Sort bug.  
Bug [reported](https://sourceforge.net/p/secureideas/bugs/3/)
on sourceforge.net. Original ID: 1051872  
Contributor(s): Chris Shepherd
- Error in include on base_stat_common.php,  
Contributor(s): Justin Best

---
## [0.9.8 (dhara)] - 2004-10-14
### Added
- PHP 5 support
- Start of the capabilities class.  

Contributor(s): Chris Shepherd
- Header to prevent direct access to certain pages.
- user class.
- Start of the authentication system.
- File acid2base_tbls_mysql.sql.
- admin/ and various files within it.
- Added variables to base_conf.php.dist.  

Contributor(s): [Kevin Johnson]
### Changed
- Comment banner
- Title bar
- Function names in HTML output file.
- Display of the summary graphs.
- Rename html file to php in prep for the template system.  

Contributor(s): [Kevin Johnson]
- Moved around the front page.  
Bug [reported](https://sourceforge.net/p/secureideas/discussion/404428/thread/43cd4a58/?limit=25#3364)
on sourceforge.net.  
Contributor(s):
  - Patchset submitted anonymously.
  - [Kevin Johnson]
- Look to use new CSS and various tweaks.  
Contributor(s): Walter B, [Kevin Johnson]
### Deprecated
- ADODB Support. Future transition to PEAR:DB.  
Contributor(s): [Kevin Johnson]
### Fixed
- Datetime to timestamp error in create_base_tbls_pgsql.sql   
Bug [reported](https://sourceforge.net/p/secureideas/bugs/1/)
on sourceforge.net. Original ID: 1025011
- Various typos.....
- Year dropdowns via `dispYearOptions()`.

Contributor(s): [Kevin Johnson]

---
## [0.9.7.1 (Francis)] - 2004-09-04
### Changed
- Credits
- TODO
- CSS file for different look (kinda) :)
- Whois entry for ARIN.
### Removed
- Commented code that was floating around.
### Fixed
- ADODB information in the README... missed it there!<grin>
- Various typos and display changes.

---
## [0.9.7 (Initial Release)] - 2004-09-01
### Added
- First Release.
- Packaged application as BASE.
- Code forked from [ACID project](http://www.andrew.cmu.edu/user/rdanyliw/snort/snortacid.html).
### Changed
- Split individual files into a more manageable directory structure (more work
necessary there).
### Fixed
- ADODB link and information.

[unreleased]: https://github.com/NathanGibbs3/BASE/compare/v1.4.5...devel
[1.4.5 (lilias)]: https://github.com/NathanGibbs3/BASE/releases/tag/v1.4.5
[1.4.4 (dawn)]: https://sourceforge.net/projects/secureideas/files/BASE/base-1.4.4/
[1.4.3.1 (zig)]: https://sourceforge.net/projects/secureideas/files/BASE/base-1.4.3.1/
[1.4.3 (gabi)]: https://sourceforge.net/projects/secureideas/files/BASE/base-1.4.3/
[1.4.2 (chandy)]: https://sourceforge.net/projects/secureideas/files/BASE/base-1.4.2/
[1.4.1 (lara)]: https://sourceforge.net/projects/secureideas/files/BASE/base-1.4.1/
[1.4.0 (katherine)]: https://sourceforge.net/projects/secureideas/files/BASE/base-1.4.0/
[1.3.9 (anne)]: https://sourceforge.net/projects/secureideas/files/BASE/base-1.3.9/
[1.3.8 (jodie)]: https://sourceforge.net/projects/secureideas/files/BASE/base-1.3.8/
[1.3.6 (louise)]: https://sourceforge.net/projects/secureideas/files/BASE/base-1.3.6/
[1.3.5 (marie)]: https://sourceforge.net/projects/secureideas/files/BASE/base-1.3.5/
[1.2.7 (karen)]: https://sourceforge.net/projects/secureideas/files/BASE/base-1.2.7/
[1.2.6 (christine)]: https://sourceforge.net/projects/secureideas/files/BASE/base-1.2.6/
[1.2.5 (sarah)]: https://sourceforge.net/projects/secureideas/files/BASE/base-1.2.5/
[1.2.4 (melissa)]: https://sourceforge.net/projects/secureideas/files/BASE/base-1.2.4/
[1.2.2 (cindy)]: https://sourceforge.net/projects/secureideas/files/BASE/base-1.2.2/
[1.2.1 (kris)]: https://sourceforge.net/projects/secureideas/files/BASE/base-1.2.1/
[1.2.0 (betty)]: https://sourceforge.net/projects/secureideas/files/BASE/base-1.2/
[1.1.4 (cheryl)]: https://sourceforge.net/projects/secureideas/files/BASE/base-1.1.4/
[1.1.3 (lynn)]: https://sourceforge.net/projects/secureideas/files/BASE/base-1.1.3/
[1.1.2 (zora)]: https://sourceforge.net/projects/secureideas/files/BASE/base-1.1.2/
[1.1.0 (elizabeth)]: https://sourceforge.net/projects/secureideas/files/BASE/base-1.1/
[1.0.2 (racquel)]: https://sourceforge.net/projects/secureideas/files/BASE/base-1.0.2/
[1.0.1 (michelle)]: https://sourceforge.net/projects/secureideas/files/BASE/base-1.0.1/
[1.0.0 (denise)]: https://sourceforge.net/projects/secureideas/files/BASE/base-1.0/
[0.9.9 (brenna)]: https://sourceforge.net/projects/secureideas/files/BASE/base-0.9.9-RC1/
[0.9.8 (dhara)]: https://sourceforge.net/projects/secureideas/files/BASE/base-0.9.8/
[0.9.7.1 (Francis)]: https://sourceforge.net/projects/secureideas/files/BASE/base-0.9.7.1/
[0.9.7 (Initial Release)]: https://sourceforge.net/projects/secureideas/files/BASE/base-0.9.7/

[Kevin Johnson]: https://github.com/secureideas
[Nathan Gibbs]: https://github.com/NathanGibbs3
[FalcoGer]: https://github.com/FalcoGer