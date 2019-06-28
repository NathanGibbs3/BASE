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
** Czech tranlation by: Michal Mertl <mime@traveller.cz>
********************************************************************************
*/

// Inter Character Spacing.
$UI_Spacing = 1;
// Locales.
$UI_Locales = array( 'eng_ENG.ISO8859-1', 'eng_ENG.utf-8', 'english' );
// Time Format - See strftime() syntax.
$UI_Timefmt = '%a %B %d, %Y %H:%M:%S';
// UI Init.
$UI_Charset = 'iso-8859-2';
$UI_Title = 'Basic Analysis and Security Engine';
// Common Words.
$UI_CW_Edit = 'Upravit';
$UI_CW_Delete = 'Smazat';
$UI_CW_Src = 'Zdroj';
$UI_CW_Dst = 'CÃ­l';
$UI_CW_Id = 'ID';
$UI_CW_Name = 'Jméno';
$UI_CW_Int = 'RozhranÃ­';
$UI_CW_Filter = 'Filtr';
$UI_CW_Desc = 'Popis';
$UI_CW_SucDesc = 'Successful';
$UI_CW_Sensor = 'Senzor';
$UI_CW_Sig = 'Podpis';
$UI_CW_Role = 'role';
$UI_CW_Pw = 'Heslo';
$UI_CW_Ts = 'ÈasovÃ¡ znaèka';
$UI_CW_Addr = 'adresa';
// Common Phrases.
$UI_CP_SrcName = array($UI_CW_Name,$UI_CW_Src);
$UI_CP_DstName = array($UI_CW_Name,$UI_CW_Dst);
$UI_CP_SrcDst = array($UI_CW_Src,'n.',$UI_CW_Dst);
$UI_CP_SrcAddr = array($UI_CW_Src,$UI_CW_Addr);
$UI_CP_DstAddr = array($UI_CW_Dst,$UI_CW_Addr);
// Authentication Data.
$UI_AD_UND = 'Login';
$UI_AD_RID = array($UI_CW_Id,$UI_CW_Role);
$UI_AD_ASD = 'Enabled';

//common phrases
DEFINE('_NBLAYER4','Protokol 4. vrstvy');
DEFINE('_PRIORITY','Priorita');
DEFINE('_EVENTTYPE','typ udÃ¡losti');
DEFINE('_JANUARY','Leden');
DEFINE('_FEBRUARY','Únor');
DEFINE('_MARCH','Bøezen');
DEFINE('_APRIL','Duben');
DEFINE('_MAY','Kvìten');
DEFINE('_JUNE','Èerven');
DEFINE('_JULY','Èervenec');
DEFINE('_AUGUST','Srpen');
DEFINE('_SEPTEMBER','ZÃ¡øÃ­');
DEFINE('_OCTOBER','ØÃ­jen');
DEFINE('_NOVEMBER','Listopad');
DEFINE('_DECEMBER','Prosinec');
DEFINE('_LAST','PoslednÃ­');
DEFINE('_FIRST','First'); //NEW
DEFINE('_TOTAL','Total'); //NEW
DEFINE('_ALERT','Alarm');
DEFINE('_ADDRESS','Adresa');
DEFINE('_UNKNOWN','neznÃ¡mÃ½');
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
DEFINE('_ALERTGROUP','Alert Group'); //NEW
DEFINE('_ALERTTIME','Alert Time'); //NEW
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
DEFINE('_TYPE','type'); //NEW
DEFINE('_NEXT','Next'); //NEW
DEFINE('_PREVIOUS','Previous'); //NEW

//Menu items
DEFINE('_HOME','Domù');
DEFINE('_SEARCH','Hledat');
DEFINE('_AGMAINT','SprÃ¡va skupin alarmù');
DEFINE('_USERPREF','U¾ivatelské volby');
DEFINE('_CACHE','Ke¹ a stav');
DEFINE('_ADMIN','Administrace');
DEFINE('_GALERTD','Vytvoøit graf alarmù');
DEFINE('_GALERTDT','Vytvoøit graf èasu detekce alarmù');
DEFINE('_USERMAN','SprÃ¡va u¾ivatelù');
DEFINE('_LISTU','Seznam u¾ivatelù');
DEFINE('_CREATEU','Vytvoøit u¾ivatele');
DEFINE('_ROLEMAN','SprÃ¡va rolÃ­');
DEFINE('_LISTR','Seznam rolÃ­');
DEFINE('_CREATER','Vytvoøit roli');
DEFINE('_LISTALL','Vypsat v¹e');
DEFINE('_CREATE','Vytvoø');
DEFINE('_VIEW','Zobraz');
DEFINE('_CLEAR','Vyèisti');
DEFINE('_LISTGROUPS','Seznam skupin');
DEFINE('_CREATEGROUPS','Vytvoø skupinu');
DEFINE('_VIEWGROUPS','Zobraz skupinu');
DEFINE('_EDITGROUPS','Edituj skupinu');
DEFINE('_DELETEGROUPS','Sma¾ skupinu');
DEFINE('_CLEARGROUPS','Vyèisti skupinu');
DEFINE('_CHNGPWD','Zmìnit '.strtolower($UI_CW_Pw));
DEFINE('_DISPLAYU','Zobraz u¾ivatele');

//base_footer.php
DEFINE('_FOOTER',' (by <A class="largemenuitem" href="mailto:base@secureideas.net">Kevin Johnson</A> and the <A class="largemenuitem" href="http://sourceforge.net/project/memberlist.php?group_id=103348">BASE Project Team</A><BR>Built on ACID by Roman Danyliw )');

//index.php --Log in Page
DEFINE('_LOGINERROR','U¾ivatel neexistuje nebo jste zadali ¹patné '.strtolower($UI_CW_Pw).'!<br>Zkuste prosÃ­m znovu.');

// base_main.php
DEFINE('_MOSTRECENT','PoslednÃ­ch ');
DEFINE('_MOSTFREQUENT','Nejèastìj¹Ã­ch ');
DEFINE('_ALERTS',' alarmù:');
DEFINE('_ADDRESSES',' adres:');
DEFINE('_ANYPROTO','jakÃ½koliv<br>protokol');
DEFINE('_UNI','unikÃ¡tnÃ­');
DEFINE('_LISTING','vÃ½pis');
DEFINE('_TALERTS','Dne¹nÃ­ alarmy: ');
DEFINE('_SOURCEIP','Source IP'); //NEW
DEFINE('_DESTIP','Destination IP'); //NEW
DEFINE('_L24ALERTS','Alarmy za poslednÃ­ch 24 hodin: ');
DEFINE('_L72ALERTS','Alarmy za poslednÃ­ch 72 hodin: ');
DEFINE('_UNIALERTS','unikÃ¡tnÃ­ch alarmù');
DEFINE('_LSOURCEPORTS','PoslednÃ­ zdrojové porty: ');
DEFINE('_LDESTPORTS','PoslednÃ­ cÃ­lové porty: ');
DEFINE('_FREGSOURCEP','Nejèastìj¹Ã­ zdrojové porty: ');
DEFINE('_FREGDESTP','Nejèastìj¹Ã­ cÃ­lové porty: ');
DEFINE('_QUERIED','DotÃ¡zÃ¡no ');
DEFINE('_DATABASE','DatabÃ¡ze:');
DEFINE('_SCHEMAV','Verze schématu:');
DEFINE('_TIMEWIN','Èasové rozmezÃ­:');
DEFINE('_NOALERTSDETECT','®Ã¡dné alarmy dezji¹tìny');
DEFINE('_USEALERTDB','Use Alert Database'); //NEW
DEFINE('_USEARCHIDB','Use Archive Database'); //NEW
DEFINE('_TRAFFICPROBPRO','Traffic Profile by Protocol'); //NEW

//base_auth.inc.php
DEFINE('_ADDEDSF','Úspì¹nì pøidÃ¡n');
DEFINE('_NOPWDCHANGE','Nelze zmìnit '.strtolower($UI_CW_Pw).': ');
DEFINE('_NOUSER','U¾ivatel neexistuje!');
DEFINE('_OLDPWD','AktuÃ¡lnÃ­ '.strtolower($UI_CW_Pw).' nenÃ­ sprÃ¡vné!');
DEFINE('_PWDCANT','Nelze zmìnit '.strtolower($UI_CW_Pw).': ');
DEFINE('_PWDDONE',"$UI_CW_Pw bylo zmìnìno.");
DEFINE('_ROLEEXIST',"$UI_CW_Role existuje");
// TD Migration Hack
if ($UI_Spacing == 1){
	$glue = ' ';
}else{
	$glue = '';
}
DEFINE('_ROLEIDEXIST',implode($glue, $UI_AD_RID)." existuje");
DEFINE('_ROLEADDED',"$UI_CW_Role pøidÃ¡na úspì¹nì");

//base_roleadmin.php
DEFINE('_ROLEADMIN','SprÃ¡va rolÃ­ BASE');
DEFINE('_FRMROLENAME',"Jméno $UI_CW_Role:");
DEFINE('_UPDATEROLE',"Update $UI_CW_Role"); //NEW

//base_useradmin.php
DEFINE('_USERADMIN','SprÃ¡va u¾ivatelù BASE');
DEFINE('_FRMFULLNAME','Celé jméno:');
DEFINE('_FRMUID','ID u¾ivatele:');
DEFINE('_SUBMITQUERY','Submit Query'); //NEW
DEFINE('_UPDATEUSER','Update User'); //NEW

//admin/index.php
DEFINE('_BASEADMIN','Administrace BASE');
DEFINE('_BASEADMINTEXT','Zvolte prosÃ­m operaci nalevo.');

//base_action.inc.php
DEFINE('_NOACTION','Nebyla specifikovÃ¡na operace');
DEFINE('_INVALIDACT',' je neplatnÃ¡ operace');
DEFINE('_ERRNOAG','Nemohu pøidat alarmy; nebyla specifikovÃ¡na skupina');
DEFINE('_ERRNOEMAIL','Nemohu zaslat alarmy po¹tou; nebyla specifikovÃ¡na emailovÃ¡ adresa');
DEFINE('_ACTION','Operace');
DEFINE('_CONTEXT','kontext');
DEFINE('_ADDAGID','Pøidat do skupiny (podle ID)');
DEFINE('_ADDAG','Pøidat do novì vytvoøené skupiny'); // not used
DEFINE('_ADDAGNAME','Pøidat do skupiny (podle jména)');
DEFINE('_CREATEAG','Pøidat do novì vytvoøené skupiny');
DEFINE('_CLEARAG','Vymazat se skupiny');
DEFINE('_DELETEALERT','Smazat');
DEFINE('_EMAILALERTSFULL','Zaslat emailem (detailnÃ­)');
DEFINE('_EMAILALERTSSUMM','Zaslat emailem (shrnutÃ­)');
DEFINE('_EMAILALERTSCSV','Zaslat emailem (csv)');
DEFINE('_ARCHIVEALERTSCOPY','Archivovat (vytvoøit kopii)');
DEFINE('_ARCHIVEALERTSMOVE','Archivovat (pøesunout)');
DEFINE('_IGNORED','IgnorovÃ¡no');
DEFINE('_DUPALERTS',' duplicitnÃ­ alarm(y)');
DEFINE('_ALERTSPARA',' alarm(y)');
DEFINE('_NOALERTSSELECT','®Ã¡dné alarmy nebyly vybrÃ¡ny nebo');
DEFINE('_NOTSUCCESSFUL','nebyla úspì¹nÃ¡');
DEFINE('_ERRUNKAGID','ZadÃ¡no neznÃ¡mé ID skupiny (skupina pravdìpodobnì neexistuje)');
DEFINE('_ERRREMOVEFAIL','Selhalo odstranìnÃ­ nové skupiny');
DEFINE('_GENBASE','Vytvoøeno BASE');
DEFINE('_ERRNOEMAILEXP','Chyba pøi exportovÃ¡nÃ­: Nemohu poslat alarmy');
DEFINE('_ERRNOEMAILPHP','Zkontrolujte konfiguraci emailu PHP.');
DEFINE('_ERRDELALERT','Chyba pøi mazÃ¡nÃ­ alarmu');
DEFINE('_ERRARCHIVE','Chyba pøi archivaci:');
DEFINE('_ERRMAILNORECP','Chyba pøi zasÃ­lÃ¡nÃ­ emailem: Nebyl zadÃ¡n pøÃ­jemce');

//base_cache.inc.php
DEFINE('_ADDED','PøidÃ¡no ');
DEFINE('_HOSTNAMESDNS',' jmen do IP DNS vyrovnÃ¡vacÃ­ pamìti');
DEFINE('_HOSTNAMESWHOIS',' jmen do Whois vyrovnÃ¡vacÃ­ pamìti');
DEFINE('_ERRCACHENULL','Chyba pøi aktualizaci vyrovnÃ¡vacÃ­ pamìti: nalezena NULL øÃ¡dka event?');
DEFINE('_ERRCACHEERROR','Chyba pøi aktualizaci vyrovnÃ¡vacÃ­ pamìti:');
DEFINE('_ERRCACHEUPDATE','Nemohu aktualizovat vyrovnÃ¡vacÃ­ pamì»');
DEFINE('_ALERTSCACHE',' alarmù do vyrovnÃ¡vacÃ­ pamìti');

//base_db.inc.php
DEFINE('_ERRSQLTRACE','Nemohu otevøÃ­t soubor pro trasovÃ¡nÃ­ SQL');
DEFINE('_ERRSQLCONNECT','Chyba pøi pøipojovÃ¡nÃ­ databÃ¡ze:');
DEFINE('_ERRSQLCONNECTINFO','<P>Zkontrolujte promìnné pro pøipojovÃ¡nÃ­ se do databÃ¡ze v souboru <I>base_conf.php</I> 
              <PRE>
               = $alert_dbname   : jméno databÃ¡ze 
               = $alert_host     : hostitel
               = $alert_port     : port
               = $alert_user     : u¾ivatelské jméno
               = $alert_password : '.strtolower($UI_CW_Pw).'
              </PRE>
              <P>');
DEFINE('_ERRSQLPCONNECT','Chyba pøi pøipojovÃ¡nÃ­ databÃ¡ze:');
DEFINE('_ERRSQLDB','DatabÃ¡zovÃ¡ chyba:');
DEFINE('_DBALCHECK','Kontraoluje knihovnu pro prÃ¡ci s databÃ¡zÃ­ v');
DEFINE('_ERRSQLDBALLOAD1','<P><B>Chyba pøi naèÃ­tÃ¡nÃ­ knihovny pro prÃ¡ci s databÃ¡zÃ­: </B> od ');
DEFINE('_ERRSQLDBALLOAD2','<P>Zkontrolujte promìnnou pro urèenÃ­ cesty ke knihovnì pro prÃ¡ci s databÃ¡zÃ­ <CODE>$DBlib_path</CODE> v souboru <CODE>base_conf.php</CODE>
            <P>Knihovnu pro prÃ¡ci s databÃ¡zÃ­ ADODB stÃ¡hnìte z
            ');
DEFINE('_ERRSQLDBTYPE','SpecifikovÃ¡n neplatnÃ½ typ databÃ¡ze');
DEFINE('_ERRSQLDBTYPEINFO1','PromìnnÃ¡ <CODE>\$DBtype</CODE> v souboru <CODE>base_conf.php</CODE> byla ¹patnì nastavena na ');
DEFINE('_ERRSQLDBTYPEINFO2','PodporovÃ¡ny jsou pouze nÃ¡sledujÃ­cÃ­ databÃ¡zové systémy: <PRE>
                MySQL         : \'mysql\'
                PostgreSQL    : \'postgres\'
                MS SQL Server : \'mssql\'
                MS SQL Server : \'mssql\'
                Oracle        : \'oci8\'
             </PRE>');

//base_log_error.inc.php
DEFINE('_ERRBASEFATAL','KritickÃ¡ chyba BASE:');

//base_log_timing.inc.php
DEFINE('_LOADEDIN','Naèteno za');
DEFINE('_SECONDS','vteøin');

//base_net.inc.php
DEFINE('_ERRRESOLVEADDRESS','Nemohu pøelo¾it adresu');

//base_output_query.inc.php
DEFINE('_QUERYRESULTSHEADER','ZÃ¡hlavÃ­ vÃ½sledkù dotazu'); //not used

//base_signature.inc.php
DEFINE('_ERRSIGNAMEUNK','neznÃ¡mé SigName');
DEFINE('_ERRSIGPROIRITYUNK','neznÃ¡mé SigPriority');
DEFINE('_UNCLASS','nezaøazeno');

//base_state_citems.inc.php
DEFINE('_DENCODED','data zakódóvÃ¡na jako');
DEFINE('_NODENCODED','(¾Ã¡dnÃ¡ konverze dat, pøedpoklÃ¡dÃ¡m po¾adavek ve vÃ½chozÃ­m formÃ¡tu databÃ¡ze)');
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
DEFINE('_DISPANYAG','{ any Alert Group }'); //NEW
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
DEFINE('_INPUTCRTENC','Input Criteria Encoding Type'); //NEW
DEFINE('_CONVERT2WS','Convert To (when searching)'); //NEW

//base_state_common.inc.php
DEFINE('_PHPERRORCSESSION','PHP ERROR: A custom (user) PHP session have been detected. However, BASE has not been set to explicitly use this custom handler. Set <CODE>use_user_session=1</CODE> in <CODE>base_conf.php</CODE>');
DEFINE('_PHPERRORCSESSIONCODE','PHP ERROR: A custom (user) PHP session hander has been configured, but the supplied hander code specified in <CODE>user_session_path</CODE> is invalid.');
DEFINE('_PHPERRORCSESSIONVAR','PHP ERROR: A custom (user) PHP session handler has been configured, but the implementation of this handler has not been specified in BASE.  If a custom session handler is desired, set the <CODE>user_session_path</CODE> variable in <CODE>base_conf.php</CODE>.');
DEFINE('_PHPSESSREG','SezenÃ­ zaregistrovÃ¡no');

//base_state_criteria.inc.php
DEFINE('_REMOVE','Odstranit');
DEFINE('_FROMCRIT','z kritériÃ­');
DEFINE('_ERRCRITELEM','NeplatnÃ½ elemt kritéria');

//base_state_query.inc.php
DEFINE('_VALIDCANNED','PlatnÃ½ zÃ¡kladnÃ­ dotaz');
DEFINE('_DISPLAYING','Zobrazuji');
DEFINE('_DISPLAYINGTOTAL','Zobrazuji alarmy %d-%d z %d celkem');
DEFINE('_NOALERTS','®Ã¡dné alarmy nenalezeny.');
DEFINE('_QUERYRESULTS','VÃ½sledky dotazu');
DEFINE('_QUERYSTATE','Stav dotazu');
DEFINE('_DISPACTION','{ action }'); //NEW

//base_ag_common.php
DEFINE('_ERRAGNAMESEARCH','Jmenovanou skupinu nelze nalézt. Zkuste to znovu.');
DEFINE('_ERRAGNAMEEXIST','ZadanÃ¡ skupina neexistuje.');
DEFINE('_ERRAGIDSEARCH','Skupinu urèenou ID nelze nalézt. Zkuste to znovu.');
DEFINE('_ERRAGLOOKUP','Chyba pøi vyhledÃ¡vÃ¡nÃ­ skupiny dle ID');
DEFINE('_ERRAGINSERT','Chyba pøi vklÃ¡dÃ¡nÃ­ nové skupiny');

//base_ag_main.php
DEFINE('_AGMAINTTITLE','SprÃ¡va skupin');
DEFINE('_ERRAGUPDATE','Chyba pøi aktualizaci skupiny');
DEFINE('_ERRAGPACKETLIST','Chyba pøi mazÃ¡nÃ­ obsahu skupiny:');
DEFINE('_ERRAGDELETE','Chyba pøi mazÃ¡nÃ­ skupiny');
DEFINE('_AGDELETE','smazÃ¡na úspì¹nì');
DEFINE('_AGDELETEINFO','informace smazÃ¡na');
DEFINE('_ERRAGSEARCHINV','Zadané vyhledÃ¡vacÃ­ kritérium je neplatné. Zkuste to znovu.');
DEFINE('_ERRAGSEARCHNOTFOUND','®Ã¡dnÃ¡ skupiny s tÃ­mto kritériem nenalezena.');
DEFINE('_NOALERTGOUPS','Nejsou definovÃ¡ny ¾Ã¡dné skupiny');
DEFINE('_NUMALERTS','poèet alarmù');
DEFINE('_ACTIONS','Akce');
DEFINE('_NOTASSIGN','je¹tì nepøiøazeno');
DEFINE('_SAVECHANGES','Save Changes'); //NEW
DEFINE('_CONFIRMDELETE','Confirm Delete'); //NEW
DEFINE('_CONFIRMCLEAR','Confirm Clear'); //NEW

//base_common.php
DEFINE('_PORTSCAN','Provoz skenovÃ¡nÃ­ portù');

//base_db_common.php
DEFINE('_ERRDBINDEXCREATE','Nemohu vytvoøit index pro');
DEFINE('_DBINDEXCREATE','Úspì¹nì vytvoøen index pro');
DEFINE('_ERRSNORTVER','Mù¾e se jednat o star¹Ã­ verzi. PodporovÃ¡ny jsou pouze databÃ¡ze vytvoøené Snort 1.7-beta0 nebo novìj¹Ã­m');
DEFINE('_ERRSNORTVER1','ZÃ¡kladnÃ­ databÃ¡ze');
DEFINE('_ERRSNORTVER2','se zdÃ¡ nekompletnÃ­ nebo neplatnÃ¡');
DEFINE('_ERRDBSTRUCT1','Verze databÃ¡ze je sprÃ¡vnÃ¡, ale neobsahuje');
DEFINE('_ERRDBSTRUCT2','BASE tabulky. Pou¾ijte <A HREF="base_db_setup.php">InicializaènÃ­ strÃ¡nku</A> pro nastavenÃ­ a optimalizaci databÃ¡ze.');
DEFINE('_ERRPHPERROR','Chyba PHP');
DEFINE('_ERRPHPERROR1','NekompatibilnÃ­ verze');
DEFINE('_ERRVERSION','Verze');
DEFINE('_ERRPHPERROR2','PHP je pøÃ­li¹ starÃ¡. Proveïte prosÃ­m aktualizaci na verzi 4.0.4 nebo pozdìj¹Ã­');
DEFINE('_ERRPHPMYSQLSUP','<B>PHP podpora nenÃ­ kompletnÃ­</B>: <FONT>podpora pro prÃ¡ci s MySQL 
               databÃ¡zÃ­ nenÃ­ souèÃ¡stÃ­ instalace.
               ProsÃ­m pøeinstalujte PHP s potøebnou knihovnou (<CODE>--with-mysql</CODE>)</FONT>');
DEFINE('_ERRPHPPOSTGRESSUP','<B>PHP podpora nenÃ­ kompletnÃ­</B>: <FONT>podpora pro prÃ¡ci s PostgreSQL
               databÃ¡zÃ­ nenÃ­ souèÃ¡stÃ­ instalace.
               ProsÃ­m pøeinstalujte PHP s potøebnou knihovnou (<CODE>--with-pgsql</CODE>)</FONT>');
DEFINE('_ERRPHPMSSQLSUP','<B>PHP podpora nenÃ­ kompletnÃ­</B>: <FONT>podpora pro prÃ¡ci s MS SQL
               databÃ¡zÃ­ nenÃ­ souèÃ¡stÃ­ instalace.
               ProsÃ­m pøeinstalujte PHP s potøebnou knihovnou (<CODE>--enable-mssql</CODE>)</FONT>');
DEFINE('_ERRPHPORACLESUP','<B>PHP podpora nenÃ­ kompletnÃ­</B>: <FONT>podpora pro prÃ¡ci s Oracle
               databÃ¡zÃ­ nenÃ­ souèÃ¡stÃ­ instalace.
               ProsÃ­m pøeinstalujte PHP s potøebnou knihovnou (<CODE>--with-oci8</CODE>)</FONT>');

//base_graph_form.php
DEFINE('_CHARTTITLE','Nadpis grafu:');
DEFINE('_CHARTTYPE','Chart Type:'); //NEW
DEFINE('_CHARTTYPES','{ chart type }'); //NEW
DEFINE('_CHARTPERIOD','Chart Period:'); //NEW
DEFINE('_PERIODNO','no period'); //NEW
DEFINE('_PERIODWEEK','7 (a week)'); //NEW
DEFINE('_PERIODDAY','24 (whole day)'); //NEW
DEFINE('_PERIOD168','168 (24x7)'); //NEW
DEFINE('_CHARTSIZE','Size: (width x height)'); //NEW
DEFINE('_PLOTMARGINS','Plot Margins: (left x right x top x bottom)'); //NEW
DEFINE('_PLOTTYPE','Plot type:'); //NEW
DEFINE('_TYPEBAR','bar'); //NEW
DEFINE('_TYPELINE','line'); //NEW
DEFINE('_TYPEPIE','pie'); //NEW
DEFINE('_CHARTHOUR','{hora}'); //NEW
DEFINE('_CHARTDAY','{dia}'); //NEW
DEFINE('_CHARTMONTH','{mÃªs}'); //NEW
DEFINE('_GRAPHALERTS','Graph Alerts'); //NEW
DEFINE('_AXISCONTROLS','X / Y AXIS CONTROLS'); //NEW
DEFINE('_CHRTTYPEHOUR','Èas (hodiny) proti poètu alarmù');
DEFINE('_CHRTTYPEDAY','Èas (dny) proti poètu alarmù');
DEFINE('_CHRTTYPEWEEK','Èas (tÃ½dny) proti poètu alarmù');
DEFINE('_CHRTTYPEMONTH','Èas (mìsÃ­ce) proti poètu alarmù');
DEFINE('_CHRTTYPEYEAR','Èas (roky) proti poètu alarmù');
DEFINE('_CHRTTYPESRCIP','ZdrojovÃ¡ IP adresa proti poètu alarmù');
DEFINE('_CHRTTYPEDSTIP','CÃ­lovÃ¡ IP adresa proti poètu alarmù');
DEFINE('_CHRTTYPEDSTUDP','CÃ­lovÃ½ UDP port proti poètu alarmù');
DEFINE('_CHRTTYPESRCUDP','ZdrojovÃ½ UDP port proti poètu alarmù');
DEFINE('_CHRTTYPEDSTPORT','CÃ­lovÃ½ TCP port proti poètu alarmù');
DEFINE('_CHRTTYPESRCPORT','ZdrojovÃ½ TCP port proti poètu alarmù');
DEFINE('_CHRTTYPESIG','Klasifikace podpisù proti poètu alarmù');
DEFINE('_CHRTTYPESENSOR','Senzor proti poètu alarmù');
DEFINE('_CHRTBEGIN','ZaèÃ¡tek grafu:');
DEFINE('_CHRTEND','Konec grafu:');
DEFINE('_CHRTDS','Zdroj dat:');
DEFINE('_CHRTX','Osa X');
DEFINE('_CHRTY','Osa Y');
DEFINE('_CHRTMINTRESH','MinimÃ¡lnÃ­ hodnota');
DEFINE('_CHRTROTAXISLABEL','Otoèit popisky os o 90 stupòù');
DEFINE('_CHRTSHOWX','Zobraz rastr pro osu X');
DEFINE('_CHRTDISPLABELX','Zobraz popis osy X ka¾dÃ½ch');
DEFINE('_CHRTDATAPOINTS','vzorkù dat');
DEFINE('_CHRTYLOG','Osa Y logaritmickÃ¡');
DEFINE('_CHRTYGRID','Zobraz rastr pro osu Y');

//base_graph_main.php
DEFINE('_CHRTTITLE','Graf BASE');
DEFINE('_ERRCHRTNOTYPE','Nebyl urèen typ grafu');
DEFINE('_ERRNOAGSPEC','Nebyla urèena skupiny. Pou¾Ã­vÃ¡m v¹echny alarmy.');
DEFINE('_CHRTDATAIMPORT','ZaèÃ­nÃ¡m naèÃ­tat data'); 
DEFINE('_CHRTTIMEVNUMBER','Èas port proti poètu alarmù');
DEFINE('_CHRTTIME','Èas');
DEFINE('_CHRTALERTOCCUR','VÃ½skyty alarmù');
DEFINE('_CHRTSIPNUMBER','ZdrojovÃ¡ IP adresa proti poètu alarmù');
DEFINE('_CHRTSIP','ZdrojovÃ¡ IP adresa');
DEFINE('_CHRTDIPALERTS','CÃ­lovÃ¡ IP adresa proti poètu alarmù');
DEFINE('_CHRTDIP','CÃ­lovÃ¡ IP adresa');
DEFINE('_CHRTUDPPORTNUMBER','UDP port (cÃ­l) port proti poètu alarmù');
DEFINE('_CHRTDUDPPORT','CÃ­lovÃ½ UDP port');
DEFINE('_CHRTSUDPPORTNUMBER','UDP port (zdroj) port proti poètu alarmù');
DEFINE('_CHRTSUDPPORT','ZdrojovÃ½ UDP port');
DEFINE('_CHRTPORTDESTNUMBER','TCP port (cÃ­l) port proti poètu alarmù');
DEFINE('_CHRTPORTDEST','CÃ­lovÃ½ TCP port');
DEFINE('_CHRTPORTSRCNUMBER','TCP port (zdroj) port proti poètu alarmù');
DEFINE('_CHRTPORTSRC','ZdrojovÃ½ TCP port');
DEFINE('_CHRTSIGNUMBER','Klasifikace podpisù proti poètu alarmù');
DEFINE('_CHRTCLASS','Klasifikace');
DEFINE('_CHRTSENSORNUMBER','Senzor port proti poètu alarmù');
DEFINE('_CHRTHANDLEPERIOD','Rozhodné obdobÃ­ (pokud je tøeba)');
DEFINE('_CHRTDUMP','Vypisuji data ... (zobrazuji jen ka¾dé');
DEFINE('_CHRTDRAW','KreslÃ­m graf');
DEFINE('_ERRCHRTNODATAPOINTS','Pro vykreslenÃ­ nejsou k dispozici ¾Ã¡dnÃ¡ data');
DEFINE('_GRAPHALERTDATA','Graph Alert Data'); //NEW

//base_maintenance.php
DEFINE('_MAINTTITLE','Údr¾ba');
DEFINE('_MNTPHP','PHP popis:');
DEFINE('_MNTCLIENT','CLIENT:');
DEFINE('_MNTSERVER','SERVER:');
DEFINE('_MNTSERVERHW','SERVER HW:');
DEFINE('_MNTPHPVER','PHP VERZE:');
DEFINE('_MNTPHPAPI','PHP API:');
DEFINE('_MNTPHPLOGLVL','Úroveò hlÃ¡¹enÃ­ PHP:');
DEFINE('_MNTPHPMODS','Nahrané moduly:');
DEFINE('_MNTDBTYPE','Typ databÃ¡ze:');
DEFINE('_MNTDBALV','Verze podpùrné databÃ¡zové knihovny:');
DEFINE('_MNTDBALERTNAME','Jméno ALERT databÃ¡ze:');
DEFINE('_MNTDBARCHNAME','Jméno ARCHIVE databÃ¡ze:');
DEFINE('_MNTAIC','VyrovnÃ¡vacÃ­ pamì» alarmù:');
DEFINE('_MNTAICTE','CelkovÃ½ poèet udÃ¡lostÃ­:');
DEFINE('_MNTAICCE','UdÃ¡losti ve vyrovnÃ¡vacÃ­ pamìti:');
DEFINE('_MNTIPAC','VyrovnÃ¡vacÃ­ pamì» IP address');
DEFINE('_MNTIPACUSIP','UnikÃ¡tnÃ­ zdrojové IP:');
DEFINE('_MNTIPACDNSC','DNS ve vyrovnÃ¡vacÃ­ pamìti:');
DEFINE('_MNTIPACWC','Whois ve vyrovnÃ¡vacÃ­ pamìti:');
DEFINE('_MNTIPACUDIP','UnikÃ¡tnÃ­ cÃ­lové IP:');

//base_qry_alert.php
DEFINE('_QAINVPAIR','NeplatnÃ½ pÃ¡r (sid,cid)');
DEFINE('_QAALERTDELET','Alarm smazÃ¡n');
DEFINE('_QATRIGGERSIG','DetekovanÃ½ podpis alarmu');
DEFINE('_QANORMALD','Normal Display'); //NEW
DEFINE('_QAPLAIND','Plain Display'); //NEW
DEFINE('_QANOPAYLOAD','Fast logging used so payload was discarded'); //NEW

//base_qry_common.php
DEFINE('_QCSIG','podpis');
DEFINE('_QCIPADDR','IP adresy');
DEFINE('_QCIPFIELDS','IP pole');
DEFINE('_QCTCPPORTS','TCP porty');
DEFINE('_QCTCPFLAGS','TCP vlajky');
DEFINE('_QCTCPFIELD','TCP pole');
DEFINE('_QCUDPPORTS','UDP porty');
DEFINE('_QCUDPFIELDS','UDP pole');
DEFINE('_QCICMPFIELDS','ICMP poel');
DEFINE('_QCDATA','Data');
DEFINE('_QCERRCRITWARN','VarovÃ¡nÃ­ vyhledÃ¡vacÃ­ch kritériÃ­:');
DEFINE('_QCERRVALUE','Hodnota');
DEFINE('_QCERRFIELD','Pole');
DEFINE('_QCERROPER','OperÃ¡tor');
DEFINE('_QCERRDATETIME','Hodnota datum/èas');
DEFINE('_QCERRPAYLOAD','Hodnota obsahu');
DEFINE('_QCERRIP','IP adresa');
DEFINE('_QCERRIPTYPE','IP adresa typu');
DEFINE('_QCERRSPECFIELD','bylo zadÃ¡no pole protokolu, ale nebyla urèena hodnota.');
DEFINE('_QCERRSPECVALUE','bylo vybrÃ¡no, ale nebyla urèena hodnota.');
DEFINE('_QCERRBOOLEAN','VÃ­ce polÃ­ pro urèenÃ­ protokolu bylo zadÃ¡no, ale nebyl mezi nimi zadÃ¡n logickÃ½ operÃ¡tor (AND, OR).');
DEFINE('_QCERRDATEVALUE','bylo zvoleno, ¾e se mÃ¡ vyhledÃ¡vat podle data/èasu, ale nebyla urèena hodnota.');
DEFINE('_QCERRINVHOUR','(NeplatnÃ¡ hodina) ®Ã¡dné kritérium pro urèenÃ­ data/èasu neodpovÃ­dÃ¡ urèenému èasu.');
DEFINE('_QCERRDATECRIT','bylo zvoleno, ¾e se mÃ¡ vyhledÃ¡vat podle data/èasu, ale nebyla urèena hodnota.');
DEFINE('_QCERROPERSELECT','bylo vlo¾eno, ale nebyl zvolen ¾Ã¡dnÃ½ operÃ¡tor.');
DEFINE('_QCERRDATEBOOL','VÃ­ce kritériÃ­ datum/èas bylo zadÃ¡no bez urèenÃ­ logického operÃ¡toru (AND, OR) mezi nimi.');
DEFINE('_QCERRPAYCRITOPER','byl urèen obsah, kterÃ½ se mÃ¡ vyhledÃ¡vat, ale nebylo zvoleno, zda mÃ¡ bÃ½t obsa¾en nebo ne.');
DEFINE('_QCERRPAYCRITVALUE','bylo urèeno, ¾e se mÃ¡ vyhledÃ¡vat podle obsahu, ale nebyla urèena hodnota.');
DEFINE('_QCERRPAYBOOL','VÃ­ce kritériÃ­ obsahu bylo zadÃ¡no bez urèenÃ­t logického operÃ¡toru (AND, OR) mezi nimi.');
DEFINE('_QCMETACRIT','Meta kritÃ¡ria');
DEFINE('_QCIPCRIT','IP kritéria');
DEFINE('_QCPAYCRIT','ObsahovÃ¡ kritéria');
DEFINE('_QCTCPCRIT','TCP kritéria');
DEFINE('_QCUDPCRIT','UDP kritéria');
DEFINE('_QCICMPCRIT','ICMP kritéria');
DEFINE('_QCLAYER4CRIT','Layer 4 Criteria'); //NEW
DEFINE('_QCERRINVIPCRIT','Neplatné kritérium IP adresy');
DEFINE('_QCERRCRITADDRESSTYPE','byla zvolena jako kritérium, ale nebylo urèeno, zda se jednÃ¡ o zdrojovou nebo cÃ­lovou adresu.');
DEFINE('_QCERRCRITIPADDRESSNONE','ukazujÃ­c, ¾e IP adresa mÃ¡ bÃ½t kritériem, ale nebyla urèena hodnota.');
DEFINE('_QCERRCRITIPADDRESSNONE1','bylo vybrÃ¡no (v #');
DEFINE('_QCERRCRITIPIPBOOL','VÃ­ce kritériÃ­ pro IP adresy bylo zadÃ¡no bez urèenÃ­ logického operÃ¡toru (AND, OR) mezi nimi.');

//base_qry_form.php
DEFINE('_QFRMSORTORDER','Smìr tøÃ­dìnÃ­');
DEFINE('_QFRMSORTNONE','none'); //NEW
DEFINE('_QFRMTIMEA','èas (vzestupnì)');
DEFINE('_QFRMTIMED','èas (sestupnì)');
DEFINE('_QFRMSIG','podpis');
DEFINE('_QFRMSIP','zdrojovÃ¡ IP adresa');
DEFINE('_QFRMDIP','cÃ­lovÃ¡ IP adresa');

//base_qry_sqlcalls.php
DEFINE('_QSCSUMM','Souhrné statistiky');
DEFINE('_QSCTIMEPROF','Profil v èase');
DEFINE('_QSCOFALERTS','z alarmù');

//base_stat_alerts.php
DEFINE('_ALERTTITLE','VÃ½pis alarmù');

//base_stat_common.php
DEFINE('_SCCATEGORIES','Kategorie:');
DEFINE('_SCSENSORTOTAL','Senzory/Celkem:');
DEFINE('_SCTOTALNUMALERTS','CelkovÃ½ poèet alarmù:');
DEFINE('_SCSRCIP','ZdrojovÃ½ch IP adres:');
DEFINE('_SCDSTIP','CÃ­lovÃ½ch IP adres:');
DEFINE('_SCUNILINKS','UnikÃ¡tnÃ­ch IP spojù');
DEFINE('_SCSRCPORTS','ZdrojovÃ½ch portù: ');
DEFINE('_SCDSTPORTS','CÃ­lovÃ½ch portù: ');
DEFINE('_SCSENSORS','Senzorù');
DEFINE('_SCCLASS','Klasifikace');
DEFINE('_SCUNIADDRESS','UnikÃ¡tnÃ­ch adres: ');
DEFINE('_SCSOURCE','Zdroj');
DEFINE('_SCDEST',$UI_CW_Dst);
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
DEFINE('_PSTOTALHOSTS','Total Hosts Scanned'); //NEW
DEFINE('_PSDETECTAMONG','%d unique alerts detected among %d alerts on %s'); //NEW
DEFINE('_PSALLALERTSAS','all alerts with %s/%s as'); //NEW
DEFINE('_PSSHOW','show'); //NEW
DEFINE('_PSEXTERNAL','external'); //NEW

//base_stat_iplink.php
DEFINE('_SIPLTITLE','IP spoje');
DEFINE('_SIPLSOURCEFGDN','Zdrojové FQDN');
DEFINE('_SIPLDESTFGDN','CÃ­lové FQDN');
DEFINE('_SIPLDIRECTION','Smìr');
DEFINE('_SIPLPROTO','Protokol');
DEFINE('_SIPLUNIDSTPORTS','UnikÃ¡tnÃ­ch cÃ­lovÃ½ch portù');
DEFINE('_SIPLUNIEVENTS','UnikÃ¡tnÃ­ch alarmù');
DEFINE('_SIPLTOTALEVENTS','Celkem alarmù');

//base_stat_ports.php
DEFINE('_UNIQ','UnikÃ¡tnÃ­');
DEFINE('_DSTPS','cÃ­lové porty');
DEFINE('_SRCPS','zdrojové porty');
DEFINE('_OCCURRENCES','Occurrences'); //NEW

//base_stat_sensor.php
DEFINE('SPSENSORLIST','VÃ½pis senzorù');

//base_stat_time.php
DEFINE('_BSTTITLE','Time Profile of Alerts');
DEFINE('_BSTTIMECRIT','Time Criteria');
DEFINE('_BSTERRPROFILECRIT','<FONT><B>No profiling criteria was specified!</B>  Click on "hour", "day", or "month" to choose the granularity of the aggregate statistics.</FONT>');
DEFINE('_BSTERRTIMETYPE','<FONT><B>The type of time parameter which will be passed was not specified!</B>  Choose either "on", to specify a single date, or "between" to specify an interval.</FONT>');
DEFINE('_BSTERRNOYEAR','<FONT><B>No Year parameter was specified!</B></FONT>');
DEFINE('_BSTERRNOMONTH','<FONT><B>No Month parameter was specified!</B></FONT>');
DEFINE('_BSTERRNODAY','<FONT><B>No Day parameter was specified!</B></FONT>');
DEFINE('_BSTPROFILEBY','Profile by'); //NEW
DEFINE('_TIMEON','on'); //NEW
DEFINE('_TIMEBETWEEN','between'); //NEW
DEFINE('_PROFILEALERT','Profile Alert'); //NEW

//base_stat_uaddr.php
DEFINE('_UNISADD','UnikÃ¡tnÃ­ zdrojové IP adresy');
DEFINE('_SUASRCIP','ZdrojovÃ¡ IP adresa');
DEFINE('_SUAERRCRITADDUNK','chyba v kritériu: neznÃ¡mÃ½ typ adresy -- pøedpoklÃ¡dÃ¡m cÃ­lovou');
DEFINE('_UNIDADD','UnikÃ¡tnÃ­ cÃ­lové IP adresy');
DEFINE('_SUADSTIP','CÃ­lovÃ¡ IP adresa');
DEFINE('_SUAUNIALERTS','UnikÃ¡tnÃ­ch alarmù');
DEFINE('_SUASRCADD','ZdrojovÃ½ch adres');
DEFINE('_SUADSTADD','CÃ­lovÃ½ch adres');

//base_user.php
DEFINE('_BASEUSERTITLE','U¾ivatelské pøedvolby BASE');
DEFINE('_BASEUSERERRPWD',"$UI_CW_Pw nesmÃ­ bÃ½t prÃ¡zné nebo ".strtolower($UI_CW_Pw).' nesouhlasÃ­!');
DEFINE('_BASEUSEROLDPWD','Staré '.strtolower($UI_CW_Pw).':');
DEFINE('_BASEUSERNEWPWD','Nové '.strtolower($UI_CW_Pw).':');
DEFINE('_BASEUSERNEWPWDAGAIN','Nové '.strtolower($UI_CW_Pw).' znovu:');

DEFINE('_LOGOUT','OdhlÃ¡sit');
?>
