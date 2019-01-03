<?php
/*******************************************************************************
** Basic Analysis and Security Engine (BASE)
** Copyright (C) 2004 BASE Project Team
** Copyright (C) 2000 Carnegie Mellon University
** 
** (see the file 'base_main.php' for license details)
**
** Project Leads: Kevin Johnson <kjohnson@secureideas.net>
** Built upon work by Roman Danyliw <rdd@cert.org>, <roman@danyliw.com>
**
** Purpose: Vanilla Config file used for the setup program
********************************************************************************
** Authors:
********************************************************************************
** Kevin Johnson <kjohnson@secureideas.net
**
********************************************************************************
*/

function returnContents()
{
    GLOBAL $language, $useauth, $adodb, $chartlib, $uri, $dbtype, $dbhost,
        $dbport, $dbname, $dbusername, $dbpasswd, $arcdbexists, $arcdbhost,
        $arcdbport, $arcdbname, $arcdbusername, $arcdbpasswd;
        
    $contents = '<?php
/*******************************************************************************
** Basic Analysis and Security Engine (BASE)
** Copyright (C) 2004 BASE Project Team
** Copyright (C) 2000 Carnegie Mellon University
**
** (see the file "base_main.php" for license details)
**
** Project Leads: Kevin Johnson <kjohnson@secureideas.net>
** Built upon work by Roman Danyliw <rdd@cert.org>, <roman@danyliw.com>
**
** Purpose: Vanilla Config file
********************************************************************************
** Authors:
********************************************************************************
** Kevin Johnson <kjohnson@secureideas.net
**
********************************************************************************
*/
    session_start();
    $BASE_VERSION = \'1.4.5 (lilias)\';
    
    /*
     Set the below to the language you would like people to use while viewing
     your install of BASE.
    */
    $BASE_Language = \''.$language.'\';
    
    /*
     Set the $Use_Auth_System variable to 1 if you would like to force users to
     authenticate to use the system.  Only turn this off if the system is not
     accessible to the public or the network at large.  i.e. a home user testing it
     out!
    */
    
    $Use_Auth_System = '.$useauth.';
    
    /*
     Set the below to 0 to remove the links from the display of alerts.
    */
    $BASE_display_sig_links = 1;

    /*
     Set the base_urlpath to the url location that is the root of your BASE install.
     This must be set for BASE to function! Do not include a trailing slash!
     But also put the preceding slash. e.g. Your URL is http://127.0.0.1/base
     set this to /base

     */
    $BASE_urlpath = \''. $uri .'\';

    /* Unique BASE ID.  The below variable, if set, will append its value to the
     * title bar of the browser.  This is for people who manage multiple installs
     * of BASE and want a simple way to differentiate them on the task bar.
     */

    $BASE_installID = \'\';

    /*
     * Create a unique cookie name for each BASE installation.
     */

    $sessionName = str_replace(\' \', \'_\', $BASE_installID . session_name());
    session_name($sessionName);


    /* Custom footer addition.  The below variable, if set, will cause
    *  base_main.php to include what ever file is specified.
    *  A sample custom footer file is in the contrib directory
    */
   $base_custom_footer = \'\';

    /* Path to the DB abstraction library 
     *  (Note: DO NOT include a trailing backslash after the directory)
     *   e.g. $foo = \'/tmp\'      [OK]
     *        $foo = \'/tmp/\'     [OK]
     *        $foo = \'c:\tmp\'    [OK]
     *        $foo = \'c:\tmp\\\'   [WRONG]
     */
    $DBlib_path = \'' . $adodb .'\';
    
    /* The type of underlying alert database
     * 
     *  MySQL       : \'mysql\'
     *  PostgresSQL : \'postgres\'
     *  MS SQL Server : \'mssql\'
     *  Oracle      : \'oci8\'
     */
    $DBtype = \''. $dbtype .'\';
    
    /* Alert DB connection parameters
     *   - $alert_dbname   : MySQL database name of Snort alert DB
     *   - $alert_host     : host on which the DB is stored
     *   - $alert_port     : port on which to access the DB
     *   - $alert_user     : login to the database with this user
     *   - $alert_password : password of the DB user
     *
     *  This information can be gleaned from the Snort database
     *  output plugin configuration.
     */
    $alert_dbname   = \''. $dbname .'\';
    $alert_host     = \''. $dbhost .'\';
    $alert_port     = \''. $dbport .'\';
    $alert_user     = \''. $dbusername .'\';
    $alert_password = \''. $dbpasswd .'\';
    
    /* Archive DB connection parameters */
    $archive_exists   = \''. $arcdbexists .'\'; # Set this to 1 if you have an archive DB
    $archive_dbname   = \''. $arcdbname .'\';
    $archive_host     = \''. $arcdbhost .'\';
    $archive_port     = \''. $arcdbport .'\';
    $archive_user     = \''. $arcdbusername .'\';
    $archive_password = \''. $arcdbpasswd .'\';
    
    /* Type of DB connection to use
     *   1  : use a persistant connection (pconnect)
     *   2  : use a normal connection (connect)
     */
    $db_connect_method = 1;
    
    /* Use referential integrity
     *   1  : use
     *   0  : ignore (not installed)
     *
     * Note: Only PostgreSQL and MS-SQL Server databases support
     *       referential integrity.  Use the associated
     *       create_acid_tbls_?_extra.sql script to add this
     *       functionality to the database.
     *
     *       Referential integrity will greatly improve the
     *       speed of record deletion, but also slow record
     *       insertion.
     */
		$use_referential_integrity = 0;

		/* SMTP Email Alert action
     *
     * Requires the Pear-Mail package to be installed like so:
     *
     * # pear install --alldeps mail
     *
     *
     * - action_email_smtp_host : Which smtp server to use
     * - action_email_smtp_localhost : What name to use for this server in the 
     *   SMTP HELO statement. You will likely need to replace this with the name
     *   of the machine running BASE when connecting to a remote mail server.
     * - action_email_smtp_auth : Whether or not to authenticate with
     *                            the smtp server
     *     0: We do NOT authenticate ourselves towards the smtp host
     *     1: We DO authenticate ourselves towards the smtp host
     *        with the following credentials:
     * - action_email_smtp_user : The user name with the smtp host
     * - action_email_smtp_pw : The password for this mail account
     * - action_email_from : email address to use in the FROM field of the mail message
     *                       MUST be the same email address as used for the SMTP account
     * - action_email_subject : subject to use for the mail message
     * - action_email_msg : additional text to include in the body of the mail message
     * - action_email_mode : specifies how the alert information should be enclosed
     *     0 : all emailed alerts should be in the body of the message
     *     1 : all emailed alerts should be enclosed in an attachment
     */
     $action_email_smtp_host = \'smtp.example.com\';
     $action_email_smtp_localhost = \'localhost\';
     $action_email_smtp_auth = 1;
     $action_email_smtp_user = \'username\';
     $action_email_smtp_pw = \'password\';
     $action_email_from = \'smtpuser@example.com\';
     $action_email_subject = \'BASE Incident Report\';
     $action_email_msg = \'\';
     $action_email_mode = 0;

		/* Variable to start the ability to handle themes... */
		$base_style = \'base_style.css\';

		/* Chart default colors - (red, green, blue)
		 *    - $chart_bg_color_default    : background color of chart
		 *    - $chart_lgrid_color_default : gridline color of chart
		 *    - $chart_bar_color_default   : bar/line color of chart
		 */
		$chart_bg_color_default     = array(255,255,255);
		$chart_lgrid_color_default  = array(205,205,205);
		$chart_bar_color_default    = array(190, 5, 5);

		/* Maximum number of rows per criteria element */
		$MAX_ROWS = 10;

		/* Number of rows to display for any query results */
		$show_rows = 48;

		/* Number of items to return during a snapshot
		 *  Last _X_ # of alerts/unique alerts/ports/IP
		 */
		$last_num_alerts = 15;
		$last_num_ualerts = 15;
		$last_num_uports = 15;
		$last_num_uaddr = 15;

		/* Number of items to return during a snapshot
		 *  Most Frequent unique alerts/IPs/ports
		 */
		$freq_num_alerts = 5;
		$freq_num_uaddr = 15;
		$freq_num_uports = 15;

		/* Number of scroll buttons to use when displaying query results */
		$max_scroll_buttons = 12;

		/* Debug mode     - how much debugging information should be shown
		 * Timing mode    - display timing information
		 * SQL trace mode - log SQL statements
		 *   0 : no extra information
		 *   1 : debugging information
		 *   2 : extended debugging information
		 *
		 * HTML no cache - whether a no-cache directive should be sent
		 *                 to the browser (should be = 1 for IE)
		 * 
		 * SQL trace file - file to log SQL traces
		 */
		$debug_mode = 0;
		$debug_time_mode = 1;
		$html_no_cache = 1;
		$sql_trace_mode = 0;
		$sql_trace_file = \'\';

		/* Auto-Screen refresh
		 * - Refresh_Stat_Page - Should certain statistics pages refresh?
		 * - refresh_all_pages - Should all the pages trigger the http refresh,
		 *                       as well?
		 *                       0: No, they should not.
		 *                       1: Yes, even these pages should refresh.
		 * - Stat_Page_Refresh_Time - refresh interval (in seconds)
		 */
		$refresh_stat_page = 1;
		$refresh_all_pages = 0;
		$stat_page_refresh_time = 180;

		/* Display First/Previous/Last timestamps for alerts or
		 * just First/Last on the Unique Alert listing.
		 *    1: yes
		 *    0: no
		 */
		$show_previous_alert = 0;

		/* Sets maximum execution time (in seconds) of any particular page. 
		 * Note: this overrides the PHP configuration file variable 
		 *       max_execution_time.  Thus script can run for a total of
		 *       ($max_script_runtime + max_execution_time) seconds 
		 */
		$max_script_runtime = 180;
    
    /* How should the IP address criteria be entered in the Search screen?
     *   1 : each octet is a separate field
     *   2 : entire address is as a single field
     */
    $ip_address_input = 2;
    
    /* Should a combo box with possible signatures be displayed on the
     * search form. (Requires Javascript)
     *   0 : disabled
     *   1 : show only non pre-processor signatures (e.g., ignore portscans)
     *   2 : show all signatures
     */
    $use_sig_list = 0;
    
    /* Resolve IP to FQDN (on certain queries?) 
     *    1 : yes
     *    0 : no 
     */
    $resolve_IP = 0;
    
    /* automatically expand the IP Criteria and Payload Criteria sections on the Search screen?)  
     *    1 : yes
     *    0 : no - you need to click on them to see them
     */
    $show_expanded_query = 0;     

    /* Should summary stats be calculated on every Query Results page
     * (Enabling this option will slow page loading time)
     */
    $show_summary_stats = 0;
    
    /* DNS cache lifetime (in minutes) */
    $dns_cache_lifetime = 20160;
    
    /* Whois information cache lifetime (in minutes) */
    $whois_cache_lifetime = 40320;
    
    /* Snort spp_portscan log file */
    $portscan_file = \'\';

    /* Show part of portscan payload in signature */
    $portscan_payload_in_signature = \'1\';

    /* Event cache Auto-update
     *
     *  Should the event cache be verified and updated on every
     *  page log?  Otherwise, the cache will have to be explicitly
     *  updated from the \'cache and status\' page.
     *
     *  Note: enabling this option could substantially slow down
     *  the page loading time when there are many uncached alerts.
     *  However, this is only a one-time penalty.
     *
     *   1 : yes
     *   0 : no
     */
    $event_cache_auto_update = 1;
    
    /* Maintain a history of the visited pages so that the \'Back\'
     * button can be used.
     *
     * Note: Enabling this option will cause the PHP-session to
     * grow substantially after many pages have been viewed causing
     * a slow down in page loading time. Periodically return to the
     * main page to clear the history.
     *
     *   1 : yes
     *   0 : no
     */
    $maintain_history = 1;
    
    /* Level of detail to display on the main page.
     *
     * Note: The presence of summary statistics will slow page loading time
     *
     *   1 : show both the links and summary statistics
     *   0 : show only the links and a count of the number of alerts
     */
    $main_page_detail = 1;
    
    /* avoid count(*) whenever possible 
     *
     * Note: On some databases (e.g., postgres) this can greatly increase
     * performance if you have a large number of events. On other databases
     * (e.g., mysql) this will have little to no effect. Enabling this
     * option will prevent the number of events in the database from being
     * shown on the main screen and will remove the percentages associated
     * with the number of events on the alert screen.
     */
    $avoid_counts = 0;
    
    /* show links to first/last/previous event on alert screen 
     *
     * Note: Enabling this can slow down loading of the alert screen on large 
     * databases
     */
    $show_first_last_links = 0;
    
    /*
     * External URLs
     */
    
    /* Whois query */
    $external_whois_link = \'http://www.dnsstuff.com/tools/whois.ch?ip=\';
   

    /* Local whois */
 		/* IP addresses of whois servers. Updated on Aug, 1st 2009.
		 *
		 * Name:    whois.arin.net
		 * Addresses:  199.212.0.43
		 *
		 * Name:    whois4.apnic.net
		 * Address:  202.12.29.13
		 * Aliases:  whois.apnic.net
		 *
		 * Name:    whois.ripe.net
		 * Address:  193.0.6.135
		 *
		 * Name:    whois.nic.ad.jp
		 * Address:  192.41.192.40
		 *
		 */

	  $arin_ip  = "199.212.0.43";
  	$apnic_ip = "202.12.29.13";
	  $ripe_ip  = "193.0.6.135";
  	$jnic_ip  = "192.41.192.40";

 
     /* DNS query */
    $external_dns_link = \'http://www.dnsstuff.com/tools/ptr.ch?ip=\';
    
    /* SamSpade \'all\' query */
    $external_all_link = \'http://www.whois.sc/\';
    
    /* TCP/UDP port database */
    $external_port_link = array(\'sans\'     => \'http://isc.sans.org/port.html?port=\',                             
                                \'tantalo\'  => \'http://ports.tantalo.net/?q=\',
                                \'sstats\'   => \'http://www.securitystats.com/tools/portsearch.php?type=port&amp;select=any&amp;Submit=Submit&amp;input=\');
    
    /* Signature references */
    $external_sig_link = array(\'bugtraq\'   => array(\'http://www.securityfocus.com/bid/\', \'\'),
                               \'snort\'     => array(\'http://www.snort.org/search/sid/\', \'\'),
                               \'cve\'       => array(\'http://cve.mitre.org/cgi-bin/cvename.cgi?name=\', \'\'),
                               \'arachnids\' => array(\'http://www.whitehats.com/info/ids\', \'\'),
                               \'mcafee\'    => array(\'http://vil.nai.com/vil/content/v_\', \'.htm\'),
                               \'icat\'      => array(\'http://icat.nist.gov/icat.cfm?cvename=CAN-\', \'\'),
                               \'nessus\'    => array(\'http://www.nessus.org/plugins/index.php?view=single&amp;id=\', \'\'),
                               \'url\'       => array(\'http://\', \'\'),
                               \'local\'     => array(\'signatures/\', \'.txt\'),
                               \'local_rules_dir\' => array(\'rules/\', \'.rules\'), 
                               \'EmThreats\'  => array(\'http://docs.emergingthreats.net/\', \'\'));
    
    
    /* Custom (user) PHP session handlers
     *
     * - use_user_session : sets whether user PHP session can be used (configured
     *                      with the session.save_handler variable in php.ini)
     *      0 : no
     *      1 : yes (assuming that \'user_session_path\' and \'user_session_function\'
     *               are configured correctly)
     * - user_session_path : file to include that implements the custom PHP session
     *                       handler
     * - user_session_function : function to invoke in the custom session
     *                           implementation that will register the session handler
     *                           functions
     */
    $use_user_session = 0;
    $user_session_path = \'\';
    $user_session_function = \'\';
    
    /**
     * This option is used to set if BASE will use colored results
     * based on the priority of alerts
     * 0 : no
     * 1 : yes
     */
    $colored_alerts = 0;

    // Red, yellow, orange, gray, white, blue
    $priority_colors = array (\'FF0000\',\'FFFF00\',\'FF9900\',\'999999\',\'FFFFFF\',\'006600\');


		/** Choose a font name for the BASE charts (graph alert graphics)
		 *
		 * The fonts in the PEAR::Image::Graph / PEAR::Image::Canvas libraries
		 * are broken.
		 *
		 * Better would be a scalable font, like DejaVuSans or Verdana.  A scalable
		 * font would allow us to get different (more appropriate) font sizes.  
		 * However, this won\'t work without minor or major modifications of 
		 * these libraries.
		 * See docs/README.graph_alert_data for details.
		 * 
		 * If you do NOT manage to tweak these libraries to get a proper font,
		 * choose "Image_Graph_Font".  However, this font is not scalable, 
		 * i.e. a headline would have the same font size as a small label. 
		 *
		 * Image_Graph_Font used to be a fail-safe font name.  But for php
		 * versions >= 5.3 even this does not seem to be true, any more.  
		 * So, as last resort, choose an empty string.
		 */
		// $graph_font_name = "Verdana";
   	$graph_font_name = "DejaVuSans";
		// $graph_font_name = "Image_Graph_Font";
		// $graph_font_name = "";


    /** IP address to country support
     *
     * 1. First method for the mapping of ip addresses to country names:
     *
     * If you have installed the perl module Geo::IPfree
     * http://search.cpan.org/CPAN/authors/id/G/GM/GMPASSOS/Geo-IPfree-0.2.tar.gz
     * then generate the country database in readable ASCII format,
     * similarly to this:
     *         cd /usr/lib/perl5/site_perl/5.8.8/Geo/
     *         perl ipct2txt.pl ./ipscountry.dat /var/www/html/ips-ascii.txt
     *
     * Set the absolute path to this database accordingly:
     */
     //$Geo_IPfree_file_ascii = "/var/www/html/ips-ascii.txt";
    
    /** 2. Second method for the mapping of ip addresses to country names:
     * 
     * If you have installed the perl module IP::Country
     * http://search.cpan.org/dist/IP-Country/
     * (requires Geography::Countries as well),
     * then uncomment and correct the absolute path to this perl executable:
     */
     //$IP2CC = "/usr/bin/ip2cc";


    /*
     The below line should not be changed!
     */
    $BASE_path = dirname(__FILE__);
    
    // _BASE_INC is a variable set to prevent direct access to certain include files....
    define( \'_BASE_INC\', 1 );
    
    // Include for languages
    include("$BASE_path/languages/$BASE_Language.lang.php");
    ?>';
    return $contents;
}
?>
