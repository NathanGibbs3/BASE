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

$UI_Spacing = 1; // Inter Character Spacing.
$UI_ILC = 'da'; // ISO 639-1 Language Code.
$UI_IRC = 'DK'; // Region Code.
// Locales.
$UI_Locales = array( 'eng_ENG.ISO8859-1', 'eng_ENG.utf-8', 'english' );
// Time Format - See strftime() syntax.
$UI_Timefmt = '%a %B %d, %Y %H:%M:%S';
// UI Init.
$UI_Charset = 'iso-8859-1';
$UI_Title = 'Basic Analysis and Security Engine';
// Common Words.
$UI_CW_Edit = 'Rediger';
$UI_CW_Delete = 'Slet';
$UI_CW_Src = 'Kilde';
$UI_CW_Dst = 'Destination';
$UI_CW_Id = 'ID';
$UI_CW_Name = 'Navn';
$UI_CW_Int = 'Brugerflade';
$UI_CW_Filter = 'Filter';
$UI_CW_Desc = 'Beskrivelse';
$UI_CW_SucDesc = 'Successful';
$UI_CW_Sensor = 'Sensor';
$UI_CW_Sig = 'Signature';
$UI_CW_Role = 'Rolle';
$UI_CW_Pw = 'Password';
$UI_CW_Ts = 'Tidsmærke';
$UI_CW_Addr = 'Adresse';
$UI_CW_Layer = 'Lag';
$UI_CW_Proto = 'Protocol';
$UI_CW_Pri = 'Prioritet';
$UI_CW_Event = 'hændelses';
$UI_CW_Type = 'Type';
$UI_CW_ML1 = 'Januar';
$UI_CW_ML2 = 'Februar';
$UI_CW_ML3 = 'Marts';
$UI_CW_ML4 = 'April';
$UI_CW_ML5 = 'Maj';
$UI_CW_ML6 = 'Juni';
$UI_CW_ML7 = 'Juli';
$UI_CW_ML8 = 'August';
$UI_CW_ML9 = 'September';
$UI_CW_ML10 = 'Oktober';
$UI_CW_ML11 = 'November';
$UI_CW_ML12 = 'December';
$UI_CW_Last = 'Sidste';
$UI_CW_First = 'First';
$UI_CW_Total = 'Total';
$UI_CW_Alert = 'Alert';
// Common Phrases.
$UI_CP_SrcName = array($UI_CW_Src,$UI_CW_Name);
$UI_CP_DstName = array('Dest.',$UI_CW_Name);
$UI_CP_SrcDst = array($UI_CW_Src,'eller','Dest.');
$UI_CP_SrcAddr = array($UI_CW_Src,$UI_CW_Addr);
$UI_CP_DstAddr = array($UI_CW_Dst,$UI_CW_Addr);
$UI_CP_L4P = array($UI_CW_Layer,'4',$UI_CW_Proto);
$UI_CP_ET = array($UI_CW_Event,$UI_CW_Type);
// Authentication Data.
$UI_AD_UND = 'Login';
$UI_AD_RID = array($UI_CW_Role,$UI_CW_Id);
$UI_AD_ASD = 'Enabled';

//common phrases
DEFINE('_ADDRESS','Adresse');
DEFINE('_UNKNOWN','ukendt');
DEFINE('_AND','AND'); //NEW
DEFINE('_OR','OR'); //NEW
DEFINE('_IS','is'); //NEW
DEFINE('_ON','on'); //NEW
DEFINE('_IN','in'); //NEW
DEFINE('_ANY','any'); //NEW
DEFINE('_NONE','none'); //NEW
DEFINE('_HOUR','Hour'); //NEW
DEFINE('_DAY','Day'); //NEW
DEFINE('_MONTH','Month'); //NEW
DEFINE('_YEAR','Year'); //NEW
DEFINE('_ALERTGROUP',$UI_CW_Alert.' Group'); //NEW
DEFINE('_ALERTTIME',$UI_CW_Alert.' Time'); //NEW
DEFINE('_CONTAINS','contains'); //NEW
DEFINE('_DOESNTCONTAIN','does not contain'); //NEW
DEFINE('_SOURCEPORT','source port'); //NEW
DEFINE('_DESTPORT','dest port'); //NEW
DEFINE('_HAS','has'); //NEW
DEFINE('_HASNOT','has not'); //NEW
DEFINE('_PORT','Port'); //NEW
DEFINE('_FLAGS','Flags'); //NEW
DEFINE('_MISC','Misc'); //NEW
DEFINE('_BACK','Back'); //NEW
DEFINE('_DISPYEAR','{ year }'); //NEW
DEFINE('_DISPMONTH','{ month }'); //NEW
DEFINE('_DISPHOUR','{ hour }'); //NEW
DEFINE('_DISPDAY','{ day }'); //NEW
DEFINE('_DISPTIME','{ time }'); //NEW
DEFINE('_ADDADDRESS','ADD Addr'); //NEW
DEFINE('_ADDIPFIELD','ADD IP Field'); //NEW
DEFINE('_ADDTIME','ADD TIME'); //NEW
DEFINE('_ADDTCPPORT','ADD TCP Port'); //NEW
DEFINE('_ADDTCPFIELD','ADD TCP Field'); //NEW
DEFINE('_ADDUDPPORT','ADD UDP Port'); //NEW
DEFINE('_ADDUDPFIELD','ADD UDP Field'); //NEW
DEFINE('_ADDICMPFIELD','ADD ICMP Field'); //NEW
DEFINE('_ADDPAYLOAD','ADD Payload'); //NEW
DEFINE('_MOSTFREQALERTS','Most Frequent Alerts'); //NEW
DEFINE('_MOSTFREQPORTS','Most Frequent Ports'); //NEW
DEFINE('_MOSTFREQADDRS','Most Frequent IP addresses'); //NEW
DEFINE('_LASTALERTS','Last Alerts'); //NEW
DEFINE('_LASTPORTS','Last Ports'); //NEW
DEFINE('_LASTTCP','Last TCP Alerts'); //NEW
DEFINE('_LASTUDP','Last UDP Alerts'); //NEW
DEFINE('_LASTICMP','Last ICMP Alerts'); //NEW
DEFINE('_QUERYDB','Query DB'); //NEW
DEFINE('_QUERYDBP','Query+DB'); //NEW - Equals to _QUERYDB where spaces are '+'s. 
                                //Should be something like: DEFINE('_QUERYDBP',str_replace(" ", "+", _QUERYDB));
DEFINE('_SELECTED','Selected'); //NEW
DEFINE('_ALLONSCREEN','ALL on Screen'); //NEW
DEFINE('_ENTIREQUERY','Entire Query'); //NEW
DEFINE('_OPTIONS','Options'); //NEW
DEFINE('_LENGTH','length'); //NEW
DEFINE('_CODE','code'); //NEW
DEFINE('_DATA','data'); //NEW
DEFINE('_TYPE',$UI_CW_Type); //NEW
DEFINE('_NEXT','Next'); //NEW
DEFINE('_PREVIOUS','Previous'); //NEW

//Menu items
DEFINE('_HOME','Hjem');
DEFINE('_SEARCH','Søg');
DEFINE('_AGMAINT','Alarm Gruppe Vedligholdelse');
DEFINE('_USERPREF','Bruger Indstillinger');
DEFINE('_CACHE','Cache & Status');
DEFINE('_ADMIN','Administration');
DEFINE('_GALERTD','Graf Alarm Data');
DEFINE('_GALERTDT','Graf Alarm Opdagelses Tid');
DEFINE('_USERMAN','Bruger Styring');
DEFINE('_LISTU','Bruger Liste');
DEFINE('_CREATEU','Lav en ny bruger');
DEFINE('_ROLEMAN',"$UI_CW_Role Styring");
DEFINE('_LISTR',"$UI_CW_Role Liste");
DEFINE('_LOGOUT','Logout');
DEFINE('_CREATER',"Lav en $UI_CW_Role");
DEFINE('_LISTALL','Vise alle');
DEFINE('_CREATE','Lav');
DEFINE('_VIEW','Vis');
DEFINE('_CLEAR','Ryd');
DEFINE('_LISTGROUPS','Gruppe Liste');
DEFINE('_CREATEGROUPS','Lav Gruppe');
DEFINE('_VIEWGROUPS','Vis Gruppe');
DEFINE('_EDITGROUPS','Rediger Gruppe');
DEFINE('_DELETEGROUPS','Slet Gruppe');
DEFINE('_CLEARGROUPS','Ryd Gruppe');
DEFINE('_CHNGPWD','�ndre '.strtolower($UI_CW_Pw));
DEFINE('_DISPLAYU','Vis bruger');

//base_footer.php
DEFINE('_FOOTER','( by <A class="largemenuitem" href="https://github.com/secureideas">Kevin Johnson</A> and the <A class="largemenuitem" href="https://github.com/NathanGibbs3/BASE/wiki/Project-Team">BASE Project Team</A><BR>Built on ACID by Roman Danyliw )');

//index.php --Log in Page
DEFINE('_LOGINERROR','Bruger eksistere ikke eller dit '.strtolower($UI_CW_Pw).' var forkert!<br>Prøv venligst igen');

// base_main.php
DEFINE('_MOSTRECENT','Seneste ');
DEFINE('_MOSTFREQUENT','Oftes ');
DEFINE('_ALERTS',' Alarmer:');
DEFINE('_ADDRESSES',' Adresser:');
DEFINE('_ANYPROTO','alle protokoler');
DEFINE('_UNI','unik');
DEFINE('_LISTING','liste');
DEFINE('_TALERTS','Alarmer idag: ');
DEFINE('_SOURCEIP','Source IP'); //NEW
DEFINE('_DESTIP','Destination IP'); //NEW
DEFINE('_L24ALERTS','De '.$UI_CW_Last.' 24 timers alarmer: ');
DEFINE('_L72ALERTS','De '.$UI_CW_Last.' 72 timers alarmer: ');
DEFINE('_UNIALERTS',' Unikke Alarmer');
DEFINE('_LSOURCEPORTS',$UI_CW_Last.' Kilde Porte: ');
DEFINE('_LDESTPORTS',$UI_CW_Last.' Destination Porte: ');
DEFINE('_FREGSOURCEP','De Meste Brugte Kilde Porte: ');
DEFINE('_FREGDESTP','De Meste Brugte Destination Porte: ');
DEFINE('_QUERIED','Sat I Kø Den');
DEFINE('_DATABASE','Database:');
DEFINE('_SCHEMAV','Schema Version:');
DEFINE('_TIMEWIN','Tids Vindue:');
DEFINE('_NOALERTSDETECT','ingen alarmer fundet');
DEFINE('_USEALERTDB','Use '.$UI_CW_Alert.' Database'); //NEW
DEFINE('_USEARCHIDB','Use Archive Database'); //NEW
DEFINE('_TRAFFICPROBPRO','Traffic Profile by Protocol'); //NEW

//base_auth.inc.php
DEFINE('_ADDEDSF','Lagt Til Vellykket');
DEFINE('_NOPWDCHANGE','Kan ikke ændre dit '.strtolower($UI_CW_Pw).': ');
DEFINE('_NOUSER','Bruger eksistere ikke!');
DEFINE('_OLDPWD','Det gamle '.strtolower($UI_CW_Pw).' tastet ind matcher ikke vores registreringer!');
DEFINE('_PWDCANT','Kan ikke ændre dit '.strtolower($UI_CW_Pw).': ');
DEFINE('_PWDDONE','Dit '.strtolower($UI_CW_Pw).' er blevet ændret!');
DEFINE('_ROLEEXIST',"$UI_CW_Role Eksistere Allerede");
// TD Migration Hack
if ($UI_Spacing == 1){
	$glue = ' ';
}else{
	$glue = '';
}
DEFINE('_ROLEIDEXIST',implode($glue, $UI_AD_RID)." Eksistere Allerede");
DEFINE('_ROLEADDED',"$UI_CW_Role Lagt Til Vellykket");

//base_roleadmin.php
DEFINE('_ROLEADMIN',"BASE $UI_CW_Role Administration");
DEFINE('_FRMROLENAME',"$UI_CW_Role Navn:");
DEFINE('_UPDATEROLE',"Update $UI_CW_Role"); //NEW

//base_useradmin.php
DEFINE('_USERADMIN','BASE Bruger Administration');
DEFINE('_FRMFULLNAME','Fuldt Navn:');
DEFINE('_FRMUID','Bruger ID:');
DEFINE('_SUBMITQUERY','Submit Query'); //NEW
DEFINE('_UPDATEUSER','Update User'); //NEW

//admin/index.php
DEFINE('_BASEADMIN','BASE Administration');
DEFINE('_BASEADMINTEXT','Vælg venligst en valgmulighed til venstre');

//base_action.inc.php
DEFINE('_NOACTION','Ingen handling var specificeret til alarmerne');
DEFINE('_INVALIDACT',' er en ugyldig handling');
DEFINE('_ERRNOAG','Kunne ikke lægge nogen alarmer til da der ikke var specificeret nogen AG');
DEFINE('_ERRNOEMAIL','Kunne ikke emaile nogen alarmer da der ikke er nogen email adresse specificeret');
DEFINE('_ACTION','HANDLING');
DEFINE('_CONTEXT','indhold');
DEFINE('_ADDAGID','L�G til AG (mth ID)');
DEFINE('_ADDAG','L�G-Ny-AG');
DEFINE('_ADDAGNAME','L�G til AG (mth Navn)');
DEFINE('_CREATEAG','Lav AG (mht Navn)');
DEFINE('_CLEARAG','Ryd AG');
DEFINE('_DELETEALERT','Slet alarm(er)');
DEFINE('_EMAILALERTSFULL','Email alarm(er) (fuld)');
DEFINE('_EMAILALERTSSUMM','Email alarm(er) (resultat)');
DEFINE('_EMAILALERTSCSV','Email alarm(er) (csv)');
DEFINE('_ARCHIVEALERTSCOPY','Arkiv alarm(er) (kopier)');
DEFINE('_ARCHIVEALERTSMOVE','Arkiv alarm(er) (flyt)');
DEFINE('_IGNORED','Ignorede ');
DEFINE('_DUPALERTS',' duplikat alarm(er)');
DEFINE('_ALERTSPARA',' alarm(er)');
DEFINE('_NOALERTSSELECT','Ingen alarmer var valgt eller');
DEFINE('_NOTSUCCESSFUL','var ikke vellykket');
DEFINE('_ERRUNKAGID','Ukendt AG ID specificeret (AG eksistere sandsynligvis ikke)');
DEFINE('_ERRREMOVEFAIL','Kunne ikke fjerne ny AG');
DEFINE('_GENBASE','Udviklet af BASE');
DEFINE('_ERRNOEMAILEXP','EXPORT FEJL: Kunne ikke sende exporterede alarmer til');
DEFINE('_ERRNOEMAILPHP','Check mail configurationen i PHP.');
DEFINE('_ERRDELALERT','Fejl Ved Sletning Af Alarm');
DEFINE('_ERRARCHIVE','Arkiv fejl:');
DEFINE('_ERRMAILNORECP','MAIL FEJL: Ingen modtager specificeret');

//base_cache.inc.php
DEFINE('_ADDED','Lagt til ');
DEFINE('_HOSTNAMESDNS',' hostnames til IP DNS cachen');
DEFINE('_HOSTNAMESWHOIS',' hostnames til Whois cachen');
DEFINE('_ERRCACHENULL','Caching FEJL: TOM handlings linie fundet?');
DEFINE('_ERRCACHEERROR','HANDLINGS CACHING FEJL:');
DEFINE('_ERRCACHEUPDATE','Kunne ikke opdatere handlings cachen');
DEFINE('_ALERTSCACHE',' alarm(er) til Alarm cachen');

//base_db.inc.php
DEFINE('_ERRSQLTRACE','Kunne ikke åbne SQL trace fil');
DEFINE('_ERRSQLCONNECT','Fejl ved forbindelse til DB :');
DEFINE('_ERRSQLCONNECTINFO','<P>Check DB forbindelses variablerne i <I>base_conf.php</I> 
              <PRE>
               = $alert_dbname   : MySQL database navn alarmerne er gemt 
               = $alert_host     : host hvor databasen er gemt
               = $alert_port     : port hvor databasen er gemt
               = $alert_user     : brugername ind i databasen
               = $alert_password : '.strtolower($UI_CW_Pw).' for bruger navnet
              </PRE>
              <P>');
DEFINE('_ERRSQLPCONNECT','Fejl under (p)forbindelsen til DB :');
DEFINE('_ERRSQLDB','Database FEJL:');
DEFINE('_DBALCHECK','Checker for DB abstraction lib i');
DEFINE('_ERRSQLDBALLOAD1','<P><B>Fejl ved loading af DB Abstraction biblioteket: </B> fra ');
DEFINE('_ERRSQLDBALLOAD2','<P>Check DB abstraction bibliotekets variable <CODE>$DBlib_path</CODE> i <CODE>base_conf.php</CODE>
            <P>
            Det underliggende database bibliotek brugt er ADODB, som kan downloades
            på ');
DEFINE('_ERRSQLDBTYPE','Invalid Database '.$UI_CW_Type.' Specificeret');
DEFINE('_ERRSQLDBTYPEINFO1','Variablen <CODE>\$DBtype</CODE> i <CODE>base_conf.php</CODE> var sat til den ukendte database '.$UI_CW_Type.' af ');
DEFINE('_ERRSQLDBTYPEINFO2','Kun de følgende databaser kan bruges: <PRE>
                MySQL         : \'mysql\'
                PostgreSQL    : \'postgres\'
                MS SQL Server : \'mssql\'
                Oracle        : \'oci8\'
             </PRE>');

//base_log_error.inc.php
DEFINE('_ERRBASEFATAL','BASE FATAL FEJL:');

//base_log_timing.inc.php
DEFINE('_LOADEDIN','Loadet i');
DEFINE('_SECONDS','sekunder');

//base_net.inc.php
DEFINE('_ERRRESOLVEADDRESS','Kunne ikke resolve adresse');

//base_output_query.inc.php
DEFINE('_QUERYRESULTSHEADER','Kø Resultater Uddata Hoved');

//base_signature.inc.php
DEFINE('_ERRSIGNAMEUNK','SigNavn ukendt');
DEFINE('_ERRSIGPROIRITYUNK','SigPrioritet ukendt');
DEFINE('_UNCLASS','unklassificeret');

//base_state_citems.inc.php
DEFINE('_DENCODED','data krypteret som');
DEFINE('_NODENCODED','(no data conversion, assuming criteria in DB native encoding)');
DEFINE('_SHORTJAN','Jan'); //NEW
DEFINE('_SHORTFEB','Feb'); //NEW
DEFINE('_SHORTMAR','Mar'); //NEW
DEFINE('_SHORTAPR','Apr'); //NEW
DEFINE('_SHORTMAY','May'); //NEW
DEFINE('_SHORTJUN','Jun'); //NEW
DEFINE('_SHORTJLY','Jly'); //NEW
DEFINE('_SHORTAUG','Aug'); //NEW
DEFINE('_SHORTSEP','Sep'); //NEW
DEFINE('_SHORTOCT','Oct'); //NEW
DEFINE('_SHORTNOV','Nov'); //NEW
DEFINE('_SHORTDEC','Dec'); //NEW
DEFINE('_DISPSIG','{ signature }'); //NEW
DEFINE('_DISPANYCLASS','{ any Classification }'); //NEW
DEFINE('_DISPANYPRIO','{ any Priority }'); //NEW
DEFINE('_DISPANYSENSOR','{ any Sensor }'); //NEW
DEFINE('_DISPADDRESS','{ adress }'); //NEW
DEFINE('_DISPFIELD','{ field }'); //NEW
DEFINE('_DISPPORT','{ port }'); //NEW
DEFINE('_DISPENCODING','{ encoding }'); //NEW
DEFINE('_DISPCONVERT2','{ Convert To }'); //NEW
DEFINE('_DISPANYAG','{ any '.$UI_CW_Alert.' Group }'); //NEW
DEFINE('_DISPPAYLOAD','{ payload }'); //NEW
DEFINE('_DISPFLAGS','{ flags }'); //NEW
DEFINE('_SIGEXACTLY','exactly'); //NEW
DEFINE('_SIGROUGHLY','roughly'); //NEW
DEFINE('_SIGCLASS',"$UI_CW_Sig Classification"); //NEW
DEFINE('_SIGPRIO',"$UI_CW_Sig Priority"); //NEW
DEFINE('_SHORTSOURCE','Source'); //NEW
DEFINE('_SHORTDEST','Dest'); //NEW
DEFINE('_SHORTSOURCEORDEST','Src or Dest'); //NEW
DEFINE('_NOLAYER4','no layer4'); //NEW
DEFINE('_INPUTCRTENC','Input Criteria Encoding '.$UI_CW_Type); //NEW
DEFINE('_CONVERT2WS','Convert To (when searching)'); //NEW

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
DEFINE('_DISPLAYINGTOTAL','Displaying alerts %d-%d of %d '.$UI_CW_Total);
DEFINE('_NOALERTS','No Alerts were found.');
DEFINE('_DISPACTION','{ action }'); //NEW
DEFINE('_QUERYRESULTS','Query Results');
DEFINE('_QUERYSTATE','Query State');

//base_ag_common.php
DEFINE('_ERRAGNAMESEARCH','The specified AG name search is invalid.  Try again!');
DEFINE('_ERRAGNAMEEXIST','The specified AG does not exist.');
DEFINE('_ERRAGIDSEARCH','The specified AG ID search is invalid.  Try again!');
DEFINE('_ERRAGLOOKUP','Error looking up an AG ID');
DEFINE('_ERRAGINSERT','Error Inserting new AG');

//base_ag_main.php
DEFINE('_AGMAINTTITLE',$UI_CW_Alert.' Group (AG) Maintenance');
DEFINE('_ERRAGUPDATE','Error updating the AG');
DEFINE('_ERRAGPACKETLIST','Error deleting packet list for the AG:');
DEFINE('_ERRAGDELETE','Error deleting the AG');
DEFINE('_AGDELETE','DELETED successfully');
DEFINE('_AGDELETEINFO','information deleted');
DEFINE('_ERRAGSEARCHINV','The entered search criteria is invalid.  Try again!');
DEFINE('_ERRAGSEARCHNOTFOUND','No AG found with that criteria.');
DEFINE('_NOALERTGOUPS','There are no '.$UI_CW_Alert.' Groups');
DEFINE('_NUMALERTS','# Alerts');
DEFINE('_SAVECHANGES','Save Changes'); //NEW
DEFINE('_CONFIRMDELETE','Confirm Delete'); //NEW
DEFINE('_CONFIRMCLEAR','Confirm Clear'); //NEW
DEFINE('_ACTIONS','Actions');
DEFINE('_NOTASSIGN','not assigned yet');

//base_common.php
DEFINE('_PORTSCAN','Portscan Traffic');

//base_db_common.php
DEFINE('_ERRDBINDEXCREATE','Unable to CREATE INDEX for');
DEFINE('_DBINDEXCREATE','Successfully created INDEX for');
DEFINE('_ERRSNORTVER','It might be an older version.  Only '.$UI_CW_Alert.' databases created by Snort 1.7-beta0 or later are supported');
DEFINE('_ERRSNORTVER1','The underlying database');
DEFINE('_ERRSNORTVER2','appears to be incomplete/invalid');
DEFINE('_ERRDBSTRUCT1','The database version is valid, but the BASE DB structure');
DEFINE('_ERRDBSTRUCT2','is not present. Use the <A HREF="base_db_setup.php">Setup page</A> to configure and optimize the DB.');
DEFINE('_ERRPHPERROR','PHP ERROR');
DEFINE('_ERRPHPERROR1','Incompatible version');
DEFINE('_ERRVERSION','Version');
DEFINE('_ERRPHPERROR2','of PHP is too old.  Please upgrade to version 4.0.4 or later');
DEFINE('_ERRPHPMYSQLSUP','<B>PHP build incomplete</B>: <FONT>the prerequisite MySQL support required to 
               read the '.$UI_CW_Alert.' database was not built into PHP.  
               Please recompile PHP with the necessary library (<CODE>--with-mysql</CODE>)</FONT>');
DEFINE('_ERRPHPPOSTGRESSUP','<B>PHP build incomplete</B>: <FONT>the prerequisite PostgreSQL support required to 
               read the '.$UI_CW_Alert.' database was not built into PHP.  
               Please recompile PHP with the necessary library (<CODE>--with-pgsql</CODE>)</FONT>');
DEFINE('_ERRPHPMSSQLSUP','<B>PHP build incomplete</B>: <FONT>the prerequisite MS SQL Server support required to 
                   read the '.$UI_CW_Alert.' database was not built into PHP.  
                   Please recompile PHP with the necessary library (<CODE>--enable-mssql</CODE>)</FONT>');
DEFINE('_ERRPHPORACLESUP','<B>PHP build incomplete</B>: <FONT>the prerequisite Oracle support required to 
                   read the '.$UI_CW_Alert.' database was not built into PHP.  
                   Please recompile PHP with the necessary library (<CODE>--with-oci8</CODE>)</FONT>');
DEFINE('_CHARTTYPE','Chart '.$UI_CW_Type.':'); //NEW
DEFINE('_CHARTTYPES','{ chart '.$UI_CW_Type.' }'); //NEW
DEFINE('_CHARTPERIOD','Chart Period:'); //NEW
DEFINE('_PERIODNO','no period'); //NEW
DEFINE('_PERIODWEEK','7 (a week)'); //NEW
DEFINE('_PERIODDAY','24 (whole day)'); //NEW
DEFINE('_PERIOD168','168 (24x7)'); //NEW
DEFINE('_CHARTSIZE','Size: (width x height)'); //NEW
DEFINE('_PLOTMARGINS','Plot Margins: (left x right x top x bottom)'); //NEW
DEFINE('_PLOTTYPE','Plot '.$UI_CW_Type.':'); //NEW
DEFINE('_TYPEBAR','bar'); //NEW
DEFINE('_TYPELINE','line'); //NEW
DEFINE('_TYPEPIE','pie'); //NEW
DEFINE('_CHARTHOUR','{hora}'); //NEW
DEFINE('_CHARTDAY','{dia}'); //NEW
DEFINE('_CHARTMONTH','{mês}'); //NEW
DEFINE('_GRAPHALERTS','Graph Alerts'); //NEW
DEFINE('_AXISCONTROLS','X / Y AXIS CONTROLS'); //NEW

//base_graph_form.php
DEFINE('_CHARTTITLE','Chart Title:');
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
DEFINE('_ERRCHRTNOTYPE','No chart '.$UI_CW_Type.' was specified');
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
DEFINE('_CHRTSIGNUMBER',"$UI_CW_Sig Classification vs. Number of Alerts");
DEFINE('_CHRTCLASS','Classification');
DEFINE('_CHRTSENSORNUMBER','Sensor vs. Number of Alerts');
DEFINE('_CHRTHANDLEPERIOD','Handling Period if necessary');
DEFINE('_CHRTDUMP','Dumping data ... (writing only every');
DEFINE('_GRAPHALERTDATA','Graph '.$UI_CW_Alert.' Data'); //NEW
DEFINE('_CHRTDRAW','Drawing graph');
DEFINE('_ERRCHRTNODATAPOINTS','No data points to plot');

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
DEFINE('_MNTDBTYPE','DB '.$UI_CW_Type.':');
DEFINE('_MNTDBALV','DB Abstraction Version:');
DEFINE('_MNTDBALERTNAME',$UI_CW_Alert.' DB Name:');
DEFINE('_MNTDBARCHNAME','ARCHIVE DB Name:');
DEFINE('_MNTAIC',$UI_CW_Alert.' Information Cache:');
DEFINE('_MNTAICTE',$UI_CW_Total.' '.$UI_CW_Event.'s:');
DEFINE('_MNTAICCE','Cached '.$UI_CW_Event.'s:');
DEFINE('_MNTIPAC','IP Address Cache');
DEFINE('_MNTIPACUSIP','Unique Src IP:');
DEFINE('_MNTIPACDNSC','DNS Cached:');
DEFINE('_MNTIPACWC','Whois Cached:');
DEFINE('_MNTIPACUDIP','Unique Dst IP:');

//base_qry_alert.php
DEFINE('_QAINVPAIR','Invalid (sid,cid) pair');
DEFINE('_QAALERTDELET',$UI_CW_Alert.' DELETED');
DEFINE('_QATRIGGERSIG','Triggered '.$UI_CW_Sig);
DEFINE('_QANORMALD','Normal Display'); //NEW
DEFINE('_QAPLAIND','Plain Display'); //NEW
DEFINE('_QANOPAYLOAD','Fast logging used so payload was discarded'); //NEW

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
DEFINE('_QCERRIPTYPE','An IP address of '.$UI_CW_Type);
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
DEFINE('_QCLAYER4CRIT','Layer 4 Criteria'); //NEW
DEFINE('_QCUDPCRIT','UDP Criteria');
DEFINE('_QCICMPCRIT','ICMP Criteria');
DEFINE('_QCERRINVIPCRIT','Invalid IP address criteria');
DEFINE('_QCERRCRITADDRESSTYPE','was entered for as a criteria value, but the '.$UI_CW_Type.' of address (e.g. source, destination) was not specified.');
DEFINE('_QCERRCRITIPADDRESSNONE','indicating that an IP address should be a criteria, but no address on which to match was specified.');
DEFINE('_QCERRCRITIPADDRESSNONE1','was selected (at #');
DEFINE('_QCERRCRITIPIPBOOL','Multiple IP address criteria entered without a boolean operator (e.g. AND, OR) between IP Criteria');
DEFINE('_QFRMSORTNONE','none'); //NEW

//base_qry_form.php
DEFINE('_QFRMSORTORDER','Sort order');
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
DEFINE('_ALERTTITLE',$UI_CW_Alert.' Listing');

//base_stat_common.php
DEFINE('_SCCATEGORIES','Categories:');
DEFINE('_SCSENSORTOTAL','Sensors/'.$UI_CW_Total.':');
DEFINE('_SCTOTALNUMALERTS',$UI_CW_Total .'Number of Alerts:');
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
DEFINE('_PSEVENTERR','PORTSCAN $UI_CW_Event ERROR: ');
DEFINE('_PSEVENTERRNOFILE','No file was specified in the $portscan_file variable.');
DEFINE('_PSEVENTERROPENFILE','Unable to open Portscan $UI_CW_Event file');
DEFINE('_PSDATETIME','Date/Time');
DEFINE('_PSSRCIP','Source IP');
DEFINE('_PSDSTIP','Destination IP');
DEFINE('_PSSRCPORT','Source Port');
DEFINE('_PSDSTPORT','Destination Port');
DEFINE('_PSTCPFLAGS','TCP Flags');
DEFINE('_PSTOTALOCC',$UI_CW_Total.'<BR> Occurrences');
DEFINE('_PSNUMSENSORS','Num of Sensors');
DEFINE('_PSFIRSTOCC',$UI_CW_First.'<BR> Occurrence');
DEFINE('_PSLASTOCC','Last<BR> Occurrence');
DEFINE('_PSUNIALERTS','Unique Alerts');
DEFINE('_PSPORTSCANEVE','Portscan Events');
DEFINE('_PSREGWHOIS','Registry lookup (whois) in');
DEFINE('_PSNODNS','no DNS resolution attempted');
DEFINE('_PSNUMSENSORSBR','Num of <BR>Sensors');
DEFINE('_PSOCCASSRC','Occurances <BR>as Src.');
DEFINE('_PSTOTALHOSTS',$UI_CW_Total.' Hosts Scanned'); //NEW
DEFINE('_PSDETECTAMONG','%d unique alerts detected among %d alerts on %s'); //NEW
DEFINE('_PSALLALERTSAS','all alerts with %s/%s as'); //NEW
DEFINE('_PSSHOW','show'); //NEW
DEFINE('_PSEXTERNAL','external'); //NEW
DEFINE('_PSOCCASDST','Occurances <BR>as Dest.');
DEFINE('_PSWHOISINFO','Whois Information');

//base_stat_iplink.php
DEFINE('_SIPLTITLE','IP Links');
DEFINE('_SIPLSOURCEFGDN','Source FQDN');
DEFINE('_SIPLDESTFGDN','Destination FQDN');
DEFINE('_SIPLDIRECTION','Direction');
DEFINE('_SIPLPROTO','Protocol');
DEFINE('_SIPLUNIDSTPORTS','Unique Dst Ports');
DEFINE('_SIPLUNIEVENTS','Unique '.$UI_CW_Event.'s');
DEFINE('_SIPLTOTALEVENTS',$UI_CW_Total.' '.$UI_CW_Event.'s');

//base_stat_ports.php
DEFINE('_UNIQ','Unique');
DEFINE('_OCCURRENCES','Occurrences'); //NEW
DEFINE('_DSTPS','Destination Port(s)');
DEFINE('_SRCPS','Source Port(s)');

//base_stat_sensor.php
DEFINE('SPSENSORLIST','Sensor Listing');

//base_stat_time.php
DEFINE('_BSTTITLE','Time Profile of Alerts');
DEFINE('_BSTTIMECRIT','Time Criteria');
DEFINE('_BSTERRPROFILECRIT','<FONT><B>No profiling criteria was specified!</B>  Click on "hour", "day", or "month" to choose the granularity of the aggregate statistics.</FONT>');
DEFINE('_BSTERRTIMETYPE','<FONT><B>The '.$UI_CW_Type.' of time parameter which will be passed was not specified!</B>  Choose either "on", to specify a single date, or "between" to specify an interval.</FONT>');
DEFINE('_BSTERRNOYEAR','<FONT><B>No Year parameter was specified!</B></FONT>');
DEFINE('_BSTPROFILEBY','Profile by'); //NEW
DEFINE('_TIMEON','on'); //NEW
DEFINE('_TIMEBETWEEN','between'); //NEW
DEFINE('_PROFILEALERT','Profile '.$UI_CW_Alert); //NEW
DEFINE('_BSTERRNOMONTH','<FONT><B>No Month parameter was specified!</B></FONT>');
DEFINE('_BSTERRNODAY','<FONT><B>No Day parameter was specified!</B></FONT>');

//base_stat_uaddr.php
DEFINE('_UNISADD','Unique Source Address(es)');
DEFINE('_SUASRCIP','Src IP address');
DEFINE('_SUAERRCRITADDUNK','CRITERIA ERROR: unknown address '.$UI_CW_Type.' -- assuming Dst address');
DEFINE('_UNIDADD','Unique Destination Address(es)');
DEFINE('_SUADSTIP','Dst IP address');
DEFINE('_SUAUNIALERTS','Unique&nbsp;Alerts');
DEFINE('_SUASRCADD','Src.&nbsp;Addr.');
DEFINE('_SUADSTADD','Dest.&nbsp;Addr.');

//base_user.php
DEFINE('_BASEUSERTITLE','BASE User preferences');
DEFINE('_BASEUSERERRPWD','Your '.strtolower($UI_CW_Pw).' can not be blank or the two '.strtolower($UI_CW_Pw).'s did not match!');
DEFINE('_BASEUSEROLDPWD',"Old $UI_CW_Pw".':');
DEFINE('_BASEUSERNEWPWD',"New $UI_CW_Pw".':');
DEFINE('_BASEUSERNEWPWDAGAIN',"New $UI_CW_Pw Again:");

?>
