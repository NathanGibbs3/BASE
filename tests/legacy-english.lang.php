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
** Purpose: English language file
**      To translate into another language, copy this file and
**          translate each variable into your chosen language.
**          Leave any variable not translated so that the system will have
**          something to display.
********************************************************************************
** Authors:
********************************************************************************
** Kevin Johnson <kjohnson@secureideas.net>
** Joel Esler <joelesler@users.sourceforge.net>
********************************************************************************
*/

//locale
DEFINE('_LOCALESTR1', 'eng_ENG.ISO8859-1');
DEFINE('_LOCALESTR2', 'eng_ENG.utf-8');
DEFINE('_LOCALESTR3', 'english');
DEFINE('_STRFTIMEFORMAT','%a %B %d, %Y %H:%M:%S'); //see strftime() sintax

//common phrases
DEFINE('_CHARSET','iso-8859-1');
DEFINE('_TITLE','Basic Analysis and Security Engine (BASE) '.$BASE_installID);
DEFINE('_FRMLOGIN','Login:');
DEFINE('_FRMPWD','Password:');
DEFINE('_SOURCE','Source');
DEFINE('_SOURCENAME','Source Name');
DEFINE('_DEST','Destination');
DEFINE('_DESTNAME','Dest. Name');
DEFINE('_SORD','Src or Dest');
DEFINE('_EDIT','Edit');
DEFINE('_DELETE','Delete');
DEFINE('_ID','ID');
DEFINE('_NAME','Name');
DEFINE('_INTERFACE','Interface');
DEFINE('_FILTER','Filter');
DEFINE('_DESC','Description');
DEFINE('_LOGIN','Login');
DEFINE('_ROLEID','Role ID');
DEFINE('_ENABLED','Enabled');
DEFINE('_SUCCESS','Successful');
DEFINE('_SENSOR','Sensor');
DEFINE('_SENSORS','Sensors');
DEFINE('_SIGNATURE','Signature');
DEFINE('_TIMESTAMP','Timestamp');
DEFINE('_NBSOURCEADDR','Source&nbsp;Address');
DEFINE('_NBDESTADDR','Dest.&nbsp;Address');
DEFINE('_NBLAYER4','Layer&nbsp;4&nbsp;Proto');
DEFINE('_PRIORITY','Priority');
DEFINE('_EVENTTYPE','event type');
DEFINE('_JANUARY','January');
DEFINE('_FEBRUARY','February');
DEFINE('_MARCH','March');
DEFINE('_APRIL','April');
DEFINE('_MAY','May');
DEFINE('_JUNE','June');
DEFINE('_JULY','July');
DEFINE('_AUGUST','August');
DEFINE('_SEPTEMBER','September');
DEFINE('_OCTOBER','October');
DEFINE('_NOVEMBER','November');
DEFINE('_DECEMBER','December');
DEFINE('_LAST','Last');
DEFINE('_FIRST','First');
DEFINE('_TOTAL','Total');
DEFINE('_ALERT','Alert');
DEFINE('_ADDRESS','Address');
DEFINE('_UNKNOWN','unknown');
DEFINE('_AND','AND');
DEFINE('_OR','OR');
DEFINE('_IS','is');
DEFINE('_ON','on');
DEFINE('_IN','in');
DEFINE('_ANY','any');
DEFINE('_NONE','none');
DEFINE('_HOUR','Hour');
DEFINE('_DAY','Day');
DEFINE('_MONTH','Month');
DEFINE('_YEAR','Year');
DEFINE('_ALERTGROUP','Alert Group');
DEFINE('_ALERTTIME','Alert Time');
DEFINE('_CONTAINS','contains');
DEFINE('_DOESNTCONTAIN','does not contain');
DEFINE('_SOURCEPORT','source port');
DEFINE('_DESTPORT','dest port');
DEFINE('_HAS','has');
DEFINE('_HASNOT','has not');
DEFINE('_PORT','Port');
DEFINE('_FLAGS','Flags');
DEFINE('_MISC','Misc');
DEFINE('_BACK','Back');
DEFINE('_DISPYEAR','{ year }');
DEFINE('_DISPMONTH','{ month }');
DEFINE('_DISPHOUR','{ hour }');
DEFINE('_DISPDAY','{ day }');
DEFINE('_DISPTIME','{ time }');
DEFINE('_ADDADDRESS','ADD Addr');
DEFINE('_ADDIPFIELD','ADD IP Field');
DEFINE('_ADDTIME','ADD TIME');
DEFINE('_ADDTCPPORT','ADD TCP Port');
DEFINE('_ADDTCPFIELD','ADD TCP Field');
DEFINE('_ADDUDPPORT','ADD UDP Port');
DEFINE('_ADDUDPFIELD','ADD UDP Field');
DEFINE('_ADDICMPFIELD','ADD ICMP Field');
DEFINE('_ADDPAYLOAD','ADD Payload');
DEFINE('_MOSTFREQALERTS','Most Frequent Alerts');
DEFINE('_MOSTFREQPORTS','Most Frequent Ports');
DEFINE('_MOSTFREQADDRS','Most Frequent IP addresses');
DEFINE('_LASTALERTS','Last Alerts');
DEFINE('_LASTPORTS','Last Ports');
DEFINE('_LASTTCP','Last TCP Alerts');
DEFINE('_LASTUDP','Last UDP Alerts');
DEFINE('_LASTICMP','Last ICMP Alerts');
DEFINE('_QUERYDB','Query DB');
DEFINE('_QUERYDBP','Query+DB'); //Equals to _QUERYDB where spaces are '+'s. 
                                //Should be something like: DEFINE('_QUERYDBP',str_replace(" ", "+", _QUERYDB));
DEFINE('_SELECTED','Selected');
DEFINE('_ALLONSCREEN','ALL on Screen');
DEFINE('_ENTIREQUERY','Entire Query');
DEFINE('_OPTIONS','Options');
DEFINE('_LENGTH','length');
DEFINE('_CODE','code');
DEFINE('_DATA','data');
DEFINE('_TYPE','type');
DEFINE('_NEXT','Next');
DEFINE('_PREVIOUS','Previous');

//Menu items
DEFINE('_HOME','Home');
DEFINE('_SEARCH','Search');
DEFINE('_AGMAINT','Alert Group Maintenance');
DEFINE('_USERPREF','User Preferences');
DEFINE('_CACHE','Cache & Status');
DEFINE('_ADMIN','Administration');
DEFINE('_GALERTD','Graph Alert Data');
DEFINE('_GALERTDT','Graph Alert Detection Time');
DEFINE('_USERMAN','User Management');
DEFINE('_LISTU','List users');
DEFINE('_CREATEU','Create a user');
DEFINE('_ROLEMAN','Role Management');
DEFINE('_LISTR','List Roles');
DEFINE('_CREATER','Create a Role');
DEFINE('_LISTALL','List All');
DEFINE('_CREATE','Create');
DEFINE('_VIEW','View');
DEFINE('_CLEAR','Clear');
DEFINE('_LISTGROUPS','List Groups');
DEFINE('_CREATEGROUPS','Create Group');
DEFINE('_VIEWGROUPS','View Group');
DEFINE('_EDITGROUPS','Edit Group');
DEFINE('_DELETEGROUPS','Delete Group');
DEFINE('_CLEARGROUPS','Clear Group');
DEFINE('_CHNGPWD','Change password');
DEFINE('_DISPLAYU','Display user');

//base_footer.php
DEFINE('_FOOTER',' (by <A class="largemenuitem" href="mailto:base@secureideas.net">Kevin Johnson</A> and the <A class="largemenuitem" href="http://sourceforge.net/project/memberlist.php?group_id=103348">BASE Project Team</A><BR>Built on ACID by Roman Danyliw )');

//index.php --Log in Page
DEFINE('_LOGINERROR','User does not exist or your password was incorrect!<br>Please try again');

// base_main.php
DEFINE('_MOSTRECENT','Most recent ');
DEFINE('_MOSTFREQUENT','Most frequent ');
DEFINE('_ALERTS',' Alerts:');
DEFINE('_ADDRESSES',' Addresses');
DEFINE('_ANYPROTO','any protocol');
DEFINE('_UNI','unique');
DEFINE('_LISTING','listing');
DEFINE('_TALERTS','Today\'s alerts: ');
DEFINE('_SOURCEIP','Source IP');
DEFINE('_DESTIP','Destination IP');
DEFINE('_L24ALERTS','Last 24 Hours alerts: ');
DEFINE('_L72ALERTS','Last 72 Hours alerts: ');
DEFINE('_UNIALERTS',' Unique Alerts');
DEFINE('_LSOURCEPORTS','Last Source Ports: ');
DEFINE('_LDESTPORTS','Last Destination Ports: ');
DEFINE('_FREGSOURCEP','Most Frequent Source Ports: ');
DEFINE('_FREGDESTP','Most Frequent Destination Ports: ');
DEFINE('_QUERIED','Queried on');
DEFINE('_DATABASE','Database:');
DEFINE('_SCHEMAV','Schema Version:');
DEFINE('_TIMEWIN','Time Window:');
DEFINE('_NOALERTSDETECT','no alerts detected');
DEFINE('_USEALERTDB','Use Alert Database');
DEFINE('_USEARCHIDB','Use Archive Database');
DEFINE('_TRAFFICPROBPRO','Traffic Profile by Protocol');

//base_auth.inc.php
DEFINE('_ADDEDSF','Added Successfully');
DEFINE('_NOPWDCHANGE','Unable to change your password: ');
DEFINE('_NOUSER','User doesn\'t exist!');
DEFINE('_OLDPWD','Old password entered doesn\'t match our records!');
DEFINE('_PWDCANT','Unable to change your password: ');
DEFINE('_PWDDONE','Your password has been changed!');
DEFINE('_ROLEEXIST','Role Already Exists');
DEFINE('_ROLEIDEXIST','Role ID Already Exists');
DEFINE('_ROLEADDED','Role Added Successfully');

//base_roleadmin.php
DEFINE('_ROLEADMIN','BASE Role Administration');
DEFINE('_FRMROLEID','Role ID:');
DEFINE('_FRMROLENAME','Role Name:');
DEFINE('_FRMROLEDESC','Description:');
DEFINE('_UPDATEROLE','Update Role');

//base_useradmin.php
DEFINE('_USERADMIN','BASE User Administration');
DEFINE('_FRMFULLNAME','Full Name:');
DEFINE('_FRMROLE','Role:');
DEFINE('_FRMUID','User ID:');
DEFINE('_SUBMITQUERY','Submit Query');
DEFINE('_UPDATEUSER','Update User');

//admin/index.php
DEFINE('_BASEADMIN','BASE Administration');
DEFINE('_BASEADMINTEXT','Please select an option from the left.');

//base_action.inc.php
DEFINE('_NOACTION','No action was specified on the alerts');
DEFINE('_INVALIDACT',' is an invalid action');
DEFINE('_ERRNOAG','Could not add alerts since no AG was specified');
DEFINE('_ERRNOEMAIL','Could not email alerts since no email address was specified');
DEFINE('_ACTION','ACTION');
DEFINE('_CONTEXT','context');
DEFINE('_ADDAGID','ADD to AG (by ID)');
DEFINE('_ADDAG','ADD-New-AG');
DEFINE('_ADDAGNAME','ADD to AG (by Name)');
DEFINE('_CREATEAG','Create AG (by Name)');
DEFINE('_CLEARAG','Clear from AG');
DEFINE('_DELETEALERT','Delete alert(s)');
DEFINE('_EMAILALERTSFULL','Email alert(s) (full)');
DEFINE('_EMAILALERTSSUMM','Email alert(s) (summary)');
DEFINE('_EMAILALERTSCSV','Email alert(s) (csv)');
DEFINE('_ARCHIVEALERTSCOPY','Archive alert(s) (copy)');
DEFINE('_ARCHIVEALERTSMOVE','Archive alert(s) (move)');
DEFINE('_IGNORED','Ignored ');
DEFINE('_DUPALERTS',' duplicate alert(s)');
DEFINE('_ALERTSPARA',' alert(s)');
DEFINE('_NOALERTSSELECT','No alerts were selected or the');
DEFINE('_NOTSUCCESSFUL','was not successful');
DEFINE('_ERRUNKAGID','Unknown AG ID specified (AG probably does not exist)');
DEFINE('_ERRREMOVEFAIL','Failed to remove new AG');
DEFINE('_GENBASE','Generated by BASE');
DEFINE('_ERRNOEMAILEXP','EXPORT ERROR: Could not send exported alerts to');
DEFINE('_ERRNOEMAILPHP','Check the mail configuration in PHP.');
DEFINE('_ERRDELALERT','Error Deleting Alert');
DEFINE('_ERRARCHIVE','Archive error:');
DEFINE('_ERRMAILNORECP','MAIL ERROR: No recipient Specified');

//base_cache.inc.php
DEFINE('_ADDED','Added ');
DEFINE('_HOSTNAMESDNS',' hostnames to the IP DNS cache');
DEFINE('_HOSTNAMESWHOIS',' hostnames to the Whois cache');
DEFINE('_ERRCACHENULL','Caching ERROR: NULL event row found?');
DEFINE('_ERRCACHEERROR','EVENT CACHING ERROR:');
DEFINE('_ERRCACHEUPDATE','Could not update event cache');
DEFINE('_ALERTSCACHE',' alert(s) to the Alert cache');

//base_db.inc.php
DEFINE('_ERRSQLTRACE','Unable to open SQL trace file');
DEFINE('_ERRSQLCONNECT','Error connecting to DB :');
DEFINE('_ERRSQLCONNECTINFO','<P>Check the DB connection variables in <I>base_conf.php</I> 
              <PRE>
               = $alert_dbname   : MySQL database name where the alerts are stored 
               = $alert_host     : host where the database is stored
               = $alert_port     : port where the database is stored
               = $alert_user     : username into the database
               = $alert_password : password for the username
              </PRE>
              <P>');
DEFINE('_ERRSQLPCONNECT','Error (p)connecting to DB :');
DEFINE('_ERRSQLDB','Database ERROR:');
DEFINE('_DBALCHECK','Checking for DB abstraction lib in');
DEFINE('_ERRSQLDBALLOAD1','<P><B>Error loading the DB Abstraction library: </B> from ');
DEFINE('_ERRSQLDBALLOAD2','<P>Check the DB abstraction library variable <CODE>$DBlib_path</CODE> in <CODE>base_conf.php</CODE>
            <P>
            The underlying database library currently used is ADODB, that can be downloaded
            at <A HREF="http://adodb.sourceforge.net/">http://adodb.sourceforge.net/</A>');
DEFINE('_ERRSQLDBTYPE','Invalid Database Type Specified');
DEFINE('_ERRSQLDBTYPEINFO1','The variable <CODE>\$DBtype</CODE> in <CODE>base_conf.php</CODE> was set to the unrecognized database type of ');
DEFINE('_ERRSQLDBTYPEINFO2','Only the following databases are supported: <PRE>
                MySQL         : \'mysql\'
                PostgreSQL    : \'postgres\'
                MS SQL Server : \'mssql\'
                Oracle        : \'oci8\'
             </PRE>');

//base_log_error.inc.php
DEFINE('_ERRBASEFATAL','BASE FATAL ERROR:');

//base_log_timing.inc.php
DEFINE('_LOADEDIN','Loaded in');
DEFINE('_SECONDS','seconds');

//base_net.inc.php
DEFINE('_ERRRESOLVEADDRESS','Unable to resolve address');

//base_output_query.inc.php
DEFINE('_QUERYRESULTSHEADER','Query Results Output Header');

//base_signature.inc.php
DEFINE('_ERRSIGNAMEUNK','SigName unknown');
DEFINE('_ERRSIGPROIRITYUNK','SigPriority unknown');
DEFINE('_UNCLASS','unclassified');

//base_state_citems.inc.php
DEFINE('_DENCODED','data encoded as');
DEFINE('_NODENCODED','(no data conversion, assuming criteria in DB native encoding)');
DEFINE('_SHORTJAN','Jan');
DEFINE('_SHORTFEB','Feb');
DEFINE('_SHORTMAR','Mar');
DEFINE('_SHORTAPR','Apr');
DEFINE('_SHORTMAY','May');
DEFINE('_SHORTJUN','Jun');
DEFINE('_SHORTJLY','Jly');
DEFINE('_SHORTAUG','Aug');
DEFINE('_SHORTSEP','Sep');
DEFINE('_SHORTOCT','Oct');
DEFINE('_SHORTNOV','Nov');
DEFINE('_SHORTDEC','Dec');
DEFINE('_DISPSIG','{ signature }');
DEFINE('_DISPANYCLASS','{ any Classification }');
DEFINE('_DISPANYPRIO','{ any Priority }');
DEFINE('_DISPANYSENSOR','{ any Sensor }');
DEFINE('_DISPADDRESS','{ address }');
DEFINE('_DISPFIELD','{ field }');
DEFINE('_DISPPORT','{ port }');
DEFINE('_DISPENCODING','{ encoding }');
DEFINE('_DISPCONVERT2','{ Convert To }');
DEFINE('_DISPANYAG','{ any Alert Group }');
DEFINE('_DISPPAYLOAD','{ payload }');
DEFINE('_DISPFLAGS','{ flags }');
DEFINE('_SIGEXACTLY','exactly');
DEFINE('_SIGROUGHLY','roughly');
DEFINE('_SIGCLASS','Signature Classification');
DEFINE('_SIGPRIO','Signature Priority');
DEFINE('_SHORTSOURCE','Source');
DEFINE('_SHORTDEST','Dest');
DEFINE('_SHORTSOURCEORDEST','Src or Dest');
DEFINE('_NOLAYER4','no layer4');
DEFINE('_INPUTCRTENC','Input Criteria Encoding Type');
DEFINE('_CONVERT2WS','Convert To (when searching)');

//base_state_common.inc.php
DEFINE('_PHPERRORCSESSION','PHP ERROR: A custom (user) PHP session have been detected. However, BASE has not been set to explicitly use this custom handler.  Set <CODE>use_user_session=1</CODE> in <CODE>base_conf.php</CODE>');
DEFINE('_PHPERRORCSESSIONCODE','PHP ERROR: A custom (user) PHP session hander has been configured, but the supplied hander code specified in <CODE>user_session_path</CODE> is invalid.');
DEFINE('_PHPERRORCSESSIONVAR','PHP ERROR: A custom (user) PHP session handler has been configured, but the implementation of this handler has not been specified in BASE.  If a custom session handler is desired, set the <CODE>user_session_path</CODE> variable in <CODE>base_conf.php</CODE>.');
DEFINE('_PHPSESSREG','Session Registered');

//base_state_criteria.inc.php
DEFINE('_REMOVE','Removing');
DEFINE('_FROMCRIT','from criteria');
DEFINE('_ERRCRITELEM','Invalid criteria element');

//base_state_query.inc.php
DEFINE('_VALIDCANNED','Valid Canned Query List');
DEFINE('_DISPLAYING','Displaying');
DEFINE('_DISPLAYINGTOTAL','Displaying alerts %d-%d of %d total');
DEFINE('_NOALERTS','No Alerts were found.');
DEFINE('_QUERYRESULTS','Query Results');
DEFINE('_QUERYSTATE','Query State');
DEFINE('_DISPACTION','{ action }');

//base_ag_common.php
DEFINE('_ERRAGNAMESEARCH','The specified AG name search is invalid.  Try again!');
DEFINE('_ERRAGNAMEEXIST','The specified AG does not exist.');
DEFINE('_ERRAGIDSEARCH','The specified AG ID search is invalid.  Try again!');
DEFINE('_ERRAGLOOKUP','Error looking up an AG ID');
DEFINE('_ERRAGINSERT','Error Inserting new AG');

//base_ag_main.php
DEFINE('_AGMAINTTITLE','Alert Group (AG) Maintenance');
DEFINE('_ERRAGUPDATE','Error updating the AG');
DEFINE('_ERRAGPACKETLIST','Error deleting packet list for the AG:');
DEFINE('_ERRAGDELETE','Error deleting the AG');
DEFINE('_AGDELETE','DELETED successfully');
DEFINE('_AGDELETEINFO','information deleted');
DEFINE('_ERRAGSEARCHINV','The entered search criteria is invalid.  Try again!');
DEFINE('_ERRAGSEARCHNOTFOUND','No AG found with that criteria.');
DEFINE('_NOALERTGOUPS','There are no Alert Groups');
DEFINE('_NUMALERTS','# Alerts');
DEFINE('_ACTIONS','Actions');
DEFINE('_NOTASSIGN','not assigned yet');
DEFINE('_SAVECHANGES','Save Changes');
DEFINE('_CONFIRMDELETE','Confirm Delete');
DEFINE('_CONFIRMCLEAR','Confirm Clear');

//base_common.php
DEFINE('_PORTSCAN','Portscan Traffic');

//base_db_common.php
DEFINE('_ERRDBINDEXCREATE','Unable to CREATE INDEX for');
DEFINE('_DBINDEXCREATE','Successfully created INDEX for');
DEFINE('_ERRSNORTVER','It might be an older version.  Only alert databases created by Snort 1.7-beta0 or later are supported');
DEFINE('_ERRSNORTVER1','The underlying database');
DEFINE('_ERRSNORTVER2','appears to be incomplete/invalid');
DEFINE('_ERRDBSTRUCT1','The database version is valid, but the BASE DB structure');
DEFINE('_ERRDBSTRUCT2','is not present. Use the <A HREF="base_db_setup.php">Setup page</A> to configure and optimize the DB.');
DEFINE('_ERRPHPERROR','PHP ERROR');
DEFINE('_ERRPHPERROR1','Incompatible version');
DEFINE('_ERRVERSION','Version');
DEFINE('_ERRPHPERROR2','of PHP is too old.  Please upgrade to version 4.0.4 or later');
DEFINE('_ERRPHPMYSQLSUP','<B>PHP build incomplete</B>: <FONT>the prerequisite MySQL support required to 
               read the alert database was not built into PHP.  
               Please recompile PHP with the necessary library (<CODE>--with-mysql</CODE>)</FONT>');
DEFINE('_ERRPHPPOSTGRESSUP','<B>PHP build incomplete</B>: <FONT>the prerequisite PostgreSQL support required to 
               read the alert database was not built into PHP.  
               Please recompile PHP with the necessary library (<CODE>--with-pgsql</CODE>)</FONT>');
DEFINE('_ERRPHPMSSQLSUP','<B>PHP build incomplete</B>: <FONT>the prerequisite MS SQL Server support required to 
                   read the alert database was not built into PHP.  
                   Please recompile PHP with the necessary library (<CODE>--enable-mssql</CODE>)</FONT>');
DEFINE('_ERRPHPORACLESUP','<B>PHP build incomplete</B>: <FONT>the prerequisite Oracle support required to 
                   read the alert database was not built into PHP.  
                   Please recompile PHP with the necessary library (<CODE>--with-oci8</CODE>)</FONT>');

//base_graph_form.php
DEFINE('_CHARTTITLE','Chart Title:');
DEFINE('_CHARTTYPE','Chart Type:');
DEFINE('_CHARTTYPES','{ chart type }');
DEFINE('_CHARTPERIOD','Chart Period:');
DEFINE('_PERIODNO','no period');
DEFINE('_PERIODWEEK','7 (a week)');
DEFINE('_PERIODDAY','24 (whole day)');
DEFINE('_PERIOD168','168 (24x7)');
DEFINE('_CHARTSIZE','Size: (width x height)');
DEFINE('_PLOTMARGINS','Plot Margins: (left x right x top x bottom)');
DEFINE('_PLOTTYPE','Plot type:');
DEFINE('_TYPEBAR','bar');
DEFINE('_TYPELINE','line');
DEFINE('_TYPEPIE','pie');
DEFINE('_CHARTHOUR','{hour}');
DEFINE('_CHARTDAY','{day}');
DEFINE('_CHARTMONTH','{month}');
DEFINE('_GRAPHALERTS','Graph Alerts');
DEFINE('_AXISCONTROLS','X / Y AXIS CONTROLS');
DEFINE('_CHRTTYPEHOUR','Time (hour) vs. Number of Alerts');
DEFINE('_CHRTTYPEDAY','Time (day) vs. Number of Alerts');
DEFINE('_CHRTTYPEWEEK','Time (week) vs. Number of Alerts');
DEFINE('_CHRTTYPEMONTH','Time (month) vs. Number of Alerts');
DEFINE('_CHRTTYPEYEAR','Time (year) vs. Number of Alerts');
DEFINE('_CHRTTYPESRCIP','Src. IP address vs. Number of Alerts');
DEFINE('_CHRTTYPEDSTIP','Dst. IP address vs. Number of Alerts');
DEFINE('_CHRTTYPEDSTUDP','Dst. UDP Port vs. Number of Alerts');
DEFINE('_CHRTTYPESRCUDP','Src. UDP Port vs. Number of Alerts');
DEFINE('_CHRTTYPEDSTPORT','Dst. TCP Port vs. Number of Alerts');
DEFINE('_CHRTTYPESRCPORT','Src. TCP Port vs. Number of Alerts');
DEFINE('_CHRTTYPESIG','Sig. Classification vs. Number of Alerts');
DEFINE('_CHRTTYPESENSOR','Sensor vs. Number of Alerts');
DEFINE('_CHRTBEGIN','Chart Begin:');
DEFINE('_CHRTEND','Chart End:');
DEFINE('_CHRTDS','Data Source:');
DEFINE('_CHRTX','X Axis');
DEFINE('_CHRTY','Y Axis');
DEFINE('_CHRTMINTRESH','Minimum Threshold Value');
DEFINE('_CHRTROTAXISLABEL','Rotate Axis Labels (90 degrees)');
DEFINE('_CHRTSHOWX','Show X-axis grid-lines');
DEFINE('_CHRTDISPLABELX','Display X-axis label every');
DEFINE('_CHRTDATAPOINTS','data points');
DEFINE('_CHRTYLOG','Y-axis logarithmic');
DEFINE('_CHRTYGRID','Show Y-axis grid-lines');

//base_graph_main.php
DEFINE('_CHRTTITLE','BASE Chart');
DEFINE('_ERRCHRTNOTYPE','No chart type was specified');
DEFINE('_ERRNOAGSPEC','No AG was specified.  Using all alerts.');
DEFINE('_CHRTDATAIMPORT','Starting data import');
DEFINE('_CHRTTIMEVNUMBER','Time vs. Number of Alerts');
DEFINE('_CHRTTIME','Time');
DEFINE('_CHRTALERTOCCUR','Alert Occurrences');
DEFINE('_CHRTSIPNUMBER','Source IP vs. Number of Alerts');
DEFINE('_CHRTSIP','Source IP Address');
DEFINE('_CHRTDIPALERTS','Destination IP vs. Number of Alerts');
DEFINE('_CHRTDIP','Destination IP Address');
DEFINE('_CHRTUDPPORTNUMBER','UDP Port (Destination) vs. Number of Alerts');
DEFINE('_CHRTDUDPPORT','Dst. UDP Port');
DEFINE('_CHRTSUDPPORTNUMBER','UDP Port (Source) vs. Number of Alerts');
DEFINE('_CHRTSUDPPORT','Src. UDP Port');
DEFINE('_CHRTPORTDESTNUMBER','TCP Port (Destination) vs. Number of Alerts');
DEFINE('_CHRTPORTDEST','Dst. TCP Port');
DEFINE('_CHRTPORTSRCNUMBER','TCP Port (Source) vs. Number of Alerts');
DEFINE('_CHRTPORTSRC','Src. TCP Port');
DEFINE('_CHRTSIGNUMBER','Signature Classification vs. Number of Alerts');
DEFINE('_CHRTCLASS','Classification');
DEFINE('_CHRTSENSORNUMBER','Sensor vs. Number of Alerts');
DEFINE('_CHRTHANDLEPERIOD','Handling Period if necessary');
DEFINE('_CHRTDUMP','Dumping data ... (writing only every');
DEFINE('_CHRTDRAW','Drawing graph');
DEFINE('_ERRCHRTNODATAPOINTS','No data points to plot');
DEFINE('_GRAPHALERTDATA','Graph Alert Data');

//base_maintenance.php
DEFINE('_MAINTTITLE','Maintenance');
DEFINE('_MNTPHP','PHP Build:');
DEFINE('_MNTCLIENT','CLIENT:');
DEFINE('_MNTSERVER','SERVER:');
DEFINE('_MNTSERVERHW','SERVER HW:');
DEFINE('_MNTPHPVER','PHP VERSION:');
DEFINE('_MNTPHPAPI','PHP API:');
DEFINE('_MNTPHPLOGLVL','PHP Logging level:');
DEFINE('_MNTPHPMODS','Loaded Modules:');
DEFINE('_MNTDBTYPE','DB Type:');
DEFINE('_MNTDBALV','DB Abstraction Version:');
DEFINE('_MNTDBALERTNAME','ALERT DB Name:');
DEFINE('_MNTDBARCHNAME','ARCHIVE DB Name:');
DEFINE('_MNTAIC','Alert Information Cache:');
DEFINE('_MNTAICTE','Total Events:');
DEFINE('_MNTAICCE','Cached Events:');
DEFINE('_MNTIPAC','IP Address Cache');
DEFINE('_MNTIPACUSIP','Unique Src IP:');
DEFINE('_MNTIPACDNSC','DNS Cached:');
DEFINE('_MNTIPACWC','Whois Cached:');
DEFINE('_MNTIPACUDIP','Unique Dst IP:');

//base_qry_alert.php
DEFINE('_QAINVPAIR','Invalid (sid,cid) pair');
DEFINE('_QAALERTDELET','Alert DELETED');
DEFINE('_QATRIGGERSIG','Triggered Signature');
DEFINE('_QANORMALD','Normal Display');
DEFINE('_QAPLAIND','Plain Display');
DEFINE('_QANOPAYLOAD','Fast logging used so payload was discarded');

//base_qry_common.php
DEFINE('_QCSIG','signature');
DEFINE('_QCIPADDR','IP addresses');
DEFINE('_QCIPFIELDS','IP fields');
DEFINE('_QCTCPPORTS','TCP ports');
DEFINE('_QCTCPFLAGS','TCP flags');
DEFINE('_QCTCPFIELD','TCP fields');
DEFINE('_QCUDPPORTS','UDP ports');
DEFINE('_QCUDPFIELDS','UDP fields');
DEFINE('_QCICMPFIELDS','ICMP fields');
DEFINE('_QCDATA','Data');
DEFINE('_QCERRCRITWARN','Criteria warning:');
DEFINE('_QCERRVALUE','A value of');
DEFINE('_QCERRFIELD','A field of');
DEFINE('_QCERROPER','An operator of');
DEFINE('_QCERRDATETIME','A date/time value of');
DEFINE('_QCERRPAYLOAD','A payload value of');
DEFINE('_QCERRIP','An IP address of');
DEFINE('_QCERRIPTYPE','An IP address of type');
DEFINE('_QCERRSPECFIELD',' was entered for a protocol field, but the particular field was not specified.');
DEFINE('_QCERRSPECVALUE','was selected indicating that it should be a criteria, but no value was specified on which to match.');
DEFINE('_QCERRBOOLEAN','Multiple protocol field criteria entered without a boolean operator (e.g. AND, OR) between them.');
DEFINE('_QCERRDATEVALUE','was selected indicating that some date/time criteria should be matched, but no value was specified.');
DEFINE('_QCERRINVHOUR','(Invalid Hour) No date criteria were entered with the specified time.');
DEFINE('_QCERRDATECRIT','was selected indicating that some date/time criteria should be matched, but no value was specified.');
DEFINE('_QCERROPERSELECT','was entered but no operator was selected.');
DEFINE('_QCERRDATEBOOL','Multiple Date/Time criteria entered without a boolean operator (e.g. AND, OR) between them.');
DEFINE('_QCERRPAYCRITOPER','was entered for a payload criteria field, but an operator (e.g. has, has not) was not specified.');
DEFINE('_QCERRPAYCRITVALUE','was selected indicating that payload should be a criteria, but no value on which to match was specified.');
DEFINE('_QCERRPAYBOOL','Multiple Data payload criteria entered without a boolean operator (e.g. AND, OR) between them.');
DEFINE('_QCMETACRIT','Meta Criteria');
DEFINE('_QCIPCRIT','IP Criteria');
DEFINE('_QCPAYCRIT','Payload Criteria');
DEFINE('_QCTCPCRIT','TCP Criteria');
DEFINE('_QCUDPCRIT','UDP Criteria');
DEFINE('_QCICMPCRIT','ICMP Criteria');
DEFINE('_QCLAYER4CRIT','Layer 4 Criteria');
DEFINE('_QCERRINVIPCRIT','Invalid IP address criteria');
DEFINE('_QCERRCRITADDRESSTYPE','was entered for as a criteria value, but the type of address (e.g. source, destination) was not specified.');
DEFINE('_QCERRCRITIPADDRESSNONE','indicating that an IP address should be a criteria, but no address on which to match was specified.');
DEFINE('_QCERRCRITIPADDRESSNONE1','was selected (at #');
DEFINE('_QCERRCRITIPIPBOOL','Multiple IP address criteria entered without a boolean operator (e.g. AND, OR) between IP Criteria');

//base_qry_form.php
DEFINE('_QFRMSORTORDER','Sort order');
DEFINE('_QFRMSORTNONE','none');
DEFINE('_QFRMTIMEA','timestamp (ascend)');
DEFINE('_QFRMTIMED','timestamp (descend)');
DEFINE('_QFRMSIG','signature');
DEFINE('_QFRMSIP','source IP');
DEFINE('_QFRMDIP','dest. IP');

//base_qry_sqlcalls.php
DEFINE('_QSCSUMM','Summary Statistics');
DEFINE('_QSCTIMEPROF','Time profile');
DEFINE('_QSCOFALERTS','of alerts');

//base_stat_alerts.php
DEFINE('_ALERTTITLE','Alert Listing');

//base_stat_common.php
DEFINE('_SCCATEGORIES','Categories:');
DEFINE('_SCSENSORTOTAL','Sensors/Total:');
DEFINE('_SCTOTALNUMALERTS','Total Number of Alerts:');
DEFINE('_SCSRCIP','Src IP addrs:');
DEFINE('_SCDSTIP','Dest. IP addrs:');
DEFINE('_SCUNILINKS','Unique IP links');
DEFINE('_SCSRCPORTS','Source Ports: ');
DEFINE('_SCDSTPORTS','Dest Ports: ');
DEFINE('_SCSENSORS','Sensors');
DEFINE('_SCCLASS','classifications');
DEFINE('_SCUNIADDRESS','Unique addresses: ');
DEFINE('_SCSOURCE','Source');
DEFINE('_SCDEST','Destination');
DEFINE('_SCPORT','Port');

//base_stat_ipaddr.php
DEFINE('_PSEVENTERR','PORTSCAN EVENT ERROR: ');
DEFINE('_PSEVENTERRNOFILE','No file was specified in the $portscan_file variable.');
DEFINE('_PSEVENTERROPENFILE','Unable to open Portscan event file');
DEFINE('_PSDATETIME','Date/Time');
DEFINE('_PSSRCIP','Source IP');
DEFINE('_PSDSTIP','Destination IP');
DEFINE('_PSSRCPORT','Source Port');
DEFINE('_PSDSTPORT','Destination Port');
DEFINE('_PSTCPFLAGS','TCP Flags');
DEFINE('_PSTOTALOCC','Total<BR> Occurrences');
DEFINE('_PSNUMSENSORS','Num of Sensors');
DEFINE('_PSFIRSTOCC','First<BR> Occurrence');
DEFINE('_PSLASTOCC','Last<BR> Occurrence');
DEFINE('_PSUNIALERTS','Unique Alerts');
DEFINE('_PSPORTSCANEVE','Portscan Events');
DEFINE('_PSREGWHOIS','Registry lookup (whois) in');
DEFINE('_PSNODNS','no DNS resolution attempted');
DEFINE('_PSNUMSENSORSBR','Num of <BR>Sensors');
DEFINE('_PSOCCASSRC','Occurances <BR>as Src.');
DEFINE('_PSOCCASDST','Occurances <BR>as Dest.');
DEFINE('_PSWHOISINFO','Whois Information');
DEFINE('_PSTOTALHOSTS','Total Hosts Scanned');
DEFINE('_PSDETECTAMONG','%d unique alerts detected among %d alerts on %s');
DEFINE('_PSALLALERTSAS','all alerts with %s/%s as');
DEFINE('_PSSHOW','show');
DEFINE('_PSEXTERNAL','external');

//base_stat_iplink.php
DEFINE('_SIPLTITLE','IP Links');
DEFINE('_SIPLSOURCEFGDN','Source FQDN');
DEFINE('_SIPLDESTFGDN','Destination FQDN');
DEFINE('_SIPLDIRECTION','Direction');
DEFINE('_SIPLPROTO','Protocol');
DEFINE('_SIPLUNIDSTPORTS','Unique Dst Ports');
DEFINE('_SIPLUNIEVENTS','Unique Events');
DEFINE('_SIPLTOTALEVENTS','Total Events');

//base_stat_ports.php
DEFINE('_UNIQ','Unique');
DEFINE('_DSTPS','Destination Port(s)');
DEFINE('_SRCPS','Source Port(s)');
DEFINE('_OCCURRENCES','Occurrences');

//base_stat_sensor.php
DEFINE('SPSENSORLIST','Sensor Listing');

//base_stat_time.php
DEFINE('_BSTTITLE','Time Profile of Alerts');
DEFINE('_BSTTIMECRIT','Time Criteria');
DEFINE('_BSTERRPROFILECRIT','<FONT><B>No profiling criteria was specified!</B>  Click on "hour", "day", or "month" to choose the granularity of the aggregate statistics.</FONT>');
DEFINE('_BSTERRTIMETYPE','<FONT><B>The type of time parameter which will be passed was not specified!</B>  Choose either "on", to specify a single date, or "between" to specify an interval.</FONT>');
DEFINE('_BSTERRNOYEAR','<FONT><B>No Year parameter was specified!</B></FONT>');
DEFINE('_BSTERRNOMONTH','<FONT><B>No Month parameter was specified!</B></FONT>');
DEFINE('_BSTERRNODAY','<FONT><B>No Day parameter was specified!</B></FONT>');
DEFINE('_BSTPROFILEBY','Profile by');
DEFINE('_TIMEON','on');
DEFINE('_TIMEBETWEEN','between');
DEFINE('_PROFILEALERT','Profile Alert');

//base_stat_uaddr.php
DEFINE('_UNISADD','Unique Source Address(es)');
DEFINE('_SUASRCIP','Src IP address');
DEFINE('_SUAERRCRITADDUNK','CRITERIA ERROR: unknown address type -- assuming Dst address');
DEFINE('_UNIDADD','Unique Destination Address(es)');
DEFINE('_SUADSTIP','Dst IP address');
DEFINE('_SUAUNIALERTS','Unique&nbsp;Alerts');
DEFINE('_SUASRCADD','Src.&nbsp;Addr.');
DEFINE('_SUADSTADD','Dest.&nbsp;Addr.');

//base_user.php
DEFINE('_BASEUSERTITLE','BASE User preferences');
DEFINE('_BASEUSERERRPWD','Your password can not be blank or the two passwords did not match!');
DEFINE('_BASEUSEROLDPWD','Old Password:');
DEFINE('_BASEUSERNEWPWD','New Password:');
DEFINE('_BASEUSERNEWPWDAGAIN','New Password Again:');

DEFINE('_LOGOUT','Logout');

?>
