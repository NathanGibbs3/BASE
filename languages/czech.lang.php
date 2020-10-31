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

$UI_Spacing = 1; // Inter Character Spacing.
$UI_ILC = 'cs'; // ISO 639-1 Language Code.
$UI_IRC = 'CZ'; // Region Code.
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
$UI_CW_Dst = 'Cíl';
$UI_CW_Id = 'ID';
$UI_CW_Name = 'Jméno';
$UI_CW_Int = 'Rozhraní';
$UI_CW_Filter = 'Filtr';
$UI_CW_Desc = 'Popis';
$UI_CW_SucDesc = 'Successful';
$UI_CW_Sensor = 'Senzor';
$UI_CW_Sig = 'Podpis';
$UI_CW_Role = 'role';
$UI_CW_Pw = 'Heslo';
$UI_CW_Ts = 'Časová značka';
$UI_CW_Addr = 'adresa';
$UI_CW_Layer = 'vrstvy';
$UI_CW_Protp = 'Protokol';
$UI_CW_Pri = 'Priorita';
$UI_CW_Event = 'Událost';
$UI_CW_Type = 'Typu';
$UI_CW_ML1 = 'Leden';
$UI_CW_ML2 = 'Únor';
$UI_CW_ML3 = 'Březen';
$UI_CW_ML4 = 'Duben';
$UI_CW_ML5 = 'Květen';
$UI_CW_ML6 = 'Červen';
$UI_CW_ML7 = 'Červenec';
$UI_CW_ML8 = 'Srpen';
$UI_CW_ML9 = 'Září';
$UI_CW_ML10 = 'Říjen';
$UI_CW_ML11 = 'Listopad';
$UI_CW_ML12 = 'Prosinec';
$UI_CW_Last = 'Poslední';
$UI_CW_First = 'First';
$UI_CW_Total = 'Celkový';
$UI_CW_Alert = 'Alarm';
// Common Phrases.
$UI_CP_SrcName = array($UI_CW_Name,$UI_CW_Src);
$UI_CP_DstName = array($UI_CW_Name,$UI_CW_Dst);
$UI_CP_SrcDst = array($UI_CW_Src,'n.',$UI_CW_Dst);
$UI_CP_SrcAddr = array($UI_CW_Src,$UI_CW_Addr);
$UI_CP_DstAddr = array($UI_CW_Dst,$UI_CW_Addr);
$UI_CP_L4P = array($UI_CW_Proto,$UI_CW_Layer,'4');
$UI_CP_ET = array($UI_CW_Type,$UI_CW_Event);
// Authentication Data.
$UI_AD_UND = 'Login';
$UI_AD_RID = array($UI_CW_Id,$UI_CW_Role);
$UI_AD_ASD = 'Enabled';

//common phrases
DEFINE('_ADDRESS','Adresa');
DEFINE('_UNKNOWN','neznámý');
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
DEFINE('_TYPE',$UI_CW_Type); //NEW
DEFINE('_NEXT','Next'); //NEW
DEFINE('_PREVIOUS','Previous'); //NEW

//Menu items
DEFINE('_HOME','Domů');
DEFINE('_SEARCH','Hledat');
DEFINE('_AGMAINT','Správa skupin '.$UI_CW_Alert.'ů');
DEFINE('_USERPREF','Uživatelské volby');
DEFINE('_CACHE','Keš a stav');
DEFINE('_ADMIN','Administrace');
DEFINE('_GALERTD','Vytvořit graf '.$UI_CW_Alert.'ů');
DEFINE('_GALERTDT','Vytvořit graf času detekce '.$UI_CW_Alert.'ů');
DEFINE('_USERMAN','Správa uživatelů');
DEFINE('_LISTU','Seznam uživatelů');
DEFINE('_CREATEU','Vytvořit uživatele');
DEFINE('_ROLEMAN','Správa rolí');
DEFINE('_LISTR','Seznam rolí');
DEFINE('_CREATER','Vytvořit roli');
DEFINE('_LISTALL','Vypsat vše');
DEFINE('_CREATE','Vytvoř');
DEFINE('_VIEW','Zobraz');
DEFINE('_CLEAR','Vyčisti');
DEFINE('_LISTGROUPS','Seznam skupin');
DEFINE('_CREATEGROUPS','Vytvoř skupinu');
DEFINE('_VIEWGROUPS','Zobraz skupinu');
DEFINE('_EDITGROUPS','Edituj skupinu');
DEFINE('_DELETEGROUPS','Smaž skupinu');
DEFINE('_CLEARGROUPS','Vyčisti skupinu');
DEFINE('_CHNGPWD','Změnit '.strtolower($UI_CW_Pw));
DEFINE('_DISPLAYU','Zobraz uživatele');

//base_footer.php
DEFINE('_FOOTER','(by <A class="largemenuitem" href="https://github.com/secureideas">Kevin Johnson</A> and the <A class="largemenuitem" href="https://github.com/NathanGibbs3/BASE/wiki/Project-Team">BASE Project Team</A><BR>Built on ACID by Roman Danyliw )');

//index.php --Log in Page
DEFINE('_LOGINERROR','Uživatel neexistuje nebo jste zadali špatné '.strtolower($UI_CW_Pw).'!<br>Zkuste prosím znovu.');

// base_main.php
DEFINE('_MOSTRECENT','Posledních ');
DEFINE('_MOSTFREQUENT','Nejčastějších ');
DEFINE('_ALERTS',' alarmů:');
DEFINE('_ADDRESSES',' adres:');
DEFINE('_ANYPROTO','jakýkoliv<br>protokol');
DEFINE('_UNI','unikátní');
DEFINE('_LISTING','výpis');
DEFINE('_TALERTS','Dnešní '.$UI_CW_Alert.'y: ');
DEFINE('_SOURCEIP','Source IP'); //NEW
DEFINE('_DESTIP','Destination IP'); //NEW
DEFINE('_L24ALERTS',$UI_CW_Alert.'y za posledních 24 hodin: ');
DEFINE('_L72ALERTS',$UI_CW_Alert.'y za posledních 72 hodin: ');
DEFINE('_UNIALERTS','unikátních '.$UI_CW_Alert.'ů');
DEFINE('_LSOURCEPORTS',$UI_CW_Last.' zdrojové porty: ');
DEFINE('_LDESTPORTS',$UI_CW_Last.' cílové porty: ');
DEFINE('_FREGSOURCEP','Nejčastější zdrojové porty: ');
DEFINE('_FREGDESTP','Nejčastější cílové porty: ');
DEFINE('_QUERIED','Dotázáno ');
DEFINE('_DATABASE','Databáze:');
DEFINE('_SCHEMAV','Verze schématu:');
DEFINE('_TIMEWIN','Časové rozmezí:');
DEFINE('_NOALERTSDETECT','Žádné '.$UI_CW_Alert.'y dezjištěny');
DEFINE('_USEALERTDB','Use Alert Database'); //NEW
DEFINE('_USEARCHIDB','Use Archive Database'); //NEW
DEFINE('_TRAFFICPROBPRO','Traffic Profile by Protocol'); //NEW

//base_auth.inc.php
DEFINE('_ADDEDSF','Úspěšně přidán');
DEFINE('_NOPWDCHANGE','Nelze změnit '.strtolower($UI_CW_Pw).': ');
DEFINE('_NOUSER','Uživatel neexistuje!');
DEFINE('_OLDPWD','Aktuální '.strtolower($UI_CW_Pw).' není správné!');
DEFINE('_PWDCANT','Nelze změnit '.strtolower($UI_CW_Pw).': ');
DEFINE('_PWDDONE',"$UI_CW_Pw bylo změněno.");
DEFINE('_ROLEEXIST',"$UI_CW_Role existuje");
// TD Migration Hack
if ($UI_Spacing == 1){
	$glue = ' ';
}else{
	$glue = '';
}
DEFINE('_ROLEIDEXIST',implode($glue, $UI_AD_RID)." existuje");
DEFINE('_ROLEADDED',"$UI_CW_Role přidána úspěšně");

//base_roleadmin.php
DEFINE('_ROLEADMIN','Správa rolí BASE');
DEFINE('_FRMROLENAME',"Jméno $UI_CW_Role:");
DEFINE('_UPDATEROLE',"Update $UI_CW_Role"); //NEW

//base_useradmin.php
DEFINE('_USERADMIN','Správa uživatelů BASE');
DEFINE('_FRMFULLNAME','Celé jméno:');
DEFINE('_FRMUID','ID uživatele:');
DEFINE('_SUBMITQUERY','Submit Query'); //NEW
DEFINE('_UPDATEUSER','Update User'); //NEW

//admin/index.php
DEFINE('_BASEADMIN','Administrace BASE');
DEFINE('_BASEADMINTEXT','Zvolte prosím operaci nalevo.');

//base_action.inc.php
DEFINE('_NOACTION','Nebyla specifikována operace');
DEFINE('_INVALIDACT',' je neplatná operace');
DEFINE('_ERRNOAG','Nemohu přidat '.$UI_CW_Alert.'y; nebyla specifikována skupina');
DEFINE('_ERRNOEMAIL','Nemohu zaslat '.$UI_CW_Alert.'y poštou; nebyla specifikována emailová adresa');
DEFINE('_ACTION','Operace');
DEFINE('_CONTEXT','kontext');
DEFINE('_ADDAGID','Přidat do skupiny (podle ID)');
DEFINE('_ADDAG','Přidat do nově vytvořené skupiny'); // not used
DEFINE('_ADDAGNAME','Přidat do skupiny (podle jména)');
DEFINE('_CREATEAG','Přidat do nově vytvořené skupiny');
DEFINE('_CLEARAG','Vymazat se skupiny');
DEFINE('_DELETEALERT','Smazat');
DEFINE('_EMAILALERTSFULL','Zaslat emailem (detailní)');
DEFINE('_EMAILALERTSSUMM','Zaslat emailem (shrnutí)');
DEFINE('_EMAILALERTSCSV','Zaslat emailem (csv)');
DEFINE('_ARCHIVEALERTSCOPY','Archivovat (vytvořit kopii)');
DEFINE('_ARCHIVEALERTSMOVE','Archivovat (přesunout)');
DEFINE('_IGNORED','Ignorováno');
DEFINE('_DUPALERTS',' duplicitní '.$UI_CW_Alert.'(y)');
DEFINE('_ALERTSPARA',' '.$UI_CW_Alert.'(y)');
DEFINE('_NOALERTSSELECT','Žádné '.$UI_CW_Alert.'y nebyly vybrány nebo');
DEFINE('_NOTSUCCESSFUL','nebyla úspěšná');
DEFINE('_ERRUNKAGID','Zadáno neznámé ID skupiny (skupina pravděpodobně neexistuje)');
DEFINE('_ERRREMOVEFAIL','Selhalo odstranění nové skupiny');
DEFINE('_GENBASE','Vytvořeno BASE');
DEFINE('_ERRNOEMAILEXP','Chyba při exportování: Nemohu poslat '.$UI_CW_Alerty);
DEFINE('_ERRNOEMAILPHP','Zkontrolujte konfiguraci emailu PHP.');
DEFINE('_ERRDELALERT','Chyba při mazání '.$UI_CW_Alert.'u');
DEFINE('_ERRARCHIVE','Chyba při archivaci:');
DEFINE('_ERRMAILNORECP','Chyba při zasílání emailem: Nebyl zadán příjemce');

//base_cache.inc.php
DEFINE('_ADDED','Přidáno ');
DEFINE('_HOSTNAMESDNS',' jmen do IP DNS vyrovnávací paměti');
DEFINE('_HOSTNAMESWHOIS',' jmen do Whois vyrovnávací paměti');
DEFINE('_ERRCACHENULL','Chyba při aktualizaci vyrovnávací paměti: nalezena NULL řádka event?');
DEFINE('_ERRCACHEERROR','Chyba při aktualizaci vyrovnávací paměti:');
DEFINE('_ERRCACHEUPDATE','Nemohu aktualizovat vyrovnávací pamě�');
DEFINE('_ALERTSCACHE',' '.$UI_CW_Alert.'ů do vyrovnávací paměti');

//base_db.inc.php
DEFINE('_ERRSQLTRACE','Nemohu otevřít soubor pro trasování SQL');
DEFINE('_ERRSQLCONNECT','Chyba při připojování databáze:');
DEFINE('_ERRSQLCONNECTINFO','<P>Zkontrolujte proměnné pro připojování se do databáze v souboru <I>base_conf.php</I> 
              <PRE>
               = $alert_dbname   : jméno databáze 
               = $alert_host     : hostitel
               = $alert_port     : port
               = $alert_user     : uživatelské jméno
               = $alert_password : '.strtolower($UI_CW_Pw).'
              </PRE>
              <P>');
DEFINE('_ERRSQLPCONNECT','Chyba při připojování databáze:');
DEFINE('_ERRSQLDB','Databázová chyba:');
DEFINE('_DBALCHECK','Kontraoluje knihovnu pro práci s databází v');
DEFINE('_ERRSQLDBALLOAD1','<P><B>Chyba při načítání knihovny pro práci s databází: </B> od ');
DEFINE('_ERRSQLDBALLOAD2','<P>Zkontrolujte proměnnou pro určení cesty ke knihovně pro práci s databází <CODE>$DBlib_path</CODE> v souboru <CODE>base_conf.php</CODE>
            <P>Knihovnu pro práci s databází ADODB stáhněte z
            ');
DEFINE('_ERRSQLDBTYPE','Specifikován neplatný '.$UI_CW_Type.' databáze');
DEFINE('_ERRSQLDBTYPEINFO1','Proměnná <CODE>\$DBtype</CODE> v souboru <CODE>base_conf.php</CODE> byla špatně nastavena na ');
DEFINE('_ERRSQLDBTYPEINFO2','Podporovány jsou pouze následující databázové systémy: <PRE>
                MySQL         : \'mysql\'
                PostgreSQL    : \'postgres\'
                MS SQL Server : \'mssql\'
                MS SQL Server : \'mssql\'
                Oracle        : \'oci8\'
             </PRE>');

//base_log_error.inc.php
DEFINE('_ERRBASEFATAL','Kritická chyba BASE:');

//base_log_timing.inc.php
DEFINE('_LOADEDIN','Načteno za');
DEFINE('_SECONDS','vteřin');

//base_net.inc.php
DEFINE('_ERRRESOLVEADDRESS','Nemohu přeložit adresu');

//base_output_query.inc.php
DEFINE('_QUERYRESULTSHEADER','Záhlaví výsledků dotazu'); //not used

//base_signature.inc.php
DEFINE('_ERRSIGNAMEUNK','neznámé SigName');
DEFINE('_ERRSIGPROIRITYUNK','neznámé SigPriority');
DEFINE('_UNCLASS','nezařazeno');

//base_state_citems.inc.php
DEFINE('_DENCODED','data zakódóvána jako');
DEFINE('_NODENCODED','(žádná konverze dat, předpokládám požadavek ve výchozím formátu databáze)');
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
DEFINE('_INPUTCRTENC','Input Criteria Encoding '.$UI_CW_Type); //NEW
DEFINE('_CONVERT2WS','Convert To (when searching)'); //NEW

//base_state_common.inc.php
DEFINE('_PHPERRORCSESSION','PHP ERROR: A custom (user) PHP session have been detected. However, BASE has not been set to explicitly use this custom handler. Set <CODE>use_user_session=1</CODE> in <CODE>base_conf.php</CODE>');
DEFINE('_PHPERRORCSESSIONCODE','PHP ERROR: A custom (user) PHP session hander has been configured, but the supplied hander code specified in <CODE>user_session_path</CODE> is invalid.');
DEFINE('_PHPERRORCSESSIONVAR','PHP ERROR: A custom (user) PHP session handler has been configured, but the implementation of this handler has not been specified in BASE.  If a custom session handler is desired, set the <CODE>user_session_path</CODE> variable in <CODE>base_conf.php</CODE>.');
DEFINE('_PHPSESSREG','Sezení zaregistrováno');

//base_state_criteria.inc.php
DEFINE('_REMOVE','Odstranit');
DEFINE('_FROMCRIT','z kritérií');
DEFINE('_ERRCRITELEM','Neplatný elemt kritéria');

//base_state_query.inc.php
DEFINE('_VALIDCANNED','Platný základní dotaz');
DEFINE('_DISPLAYING','Zobrazuji');
DEFINE('_DISPLAYINGTOTAL','Zobrazuji '.$UI_CW_Alert.'y %d-%d z %d '.$UI_CW_Total);
DEFINE('_NOALERTS','Žádné '.$UI_CW_Alert.'y nenalezeny.');
DEFINE('_QUERYRESULTS','Výsledky dotazu');
DEFINE('_QUERYSTATE','Stav dotazu');
DEFINE('_DISPACTION','{ action }'); //NEW

//base_ag_common.php
DEFINE('_ERRAGNAMESEARCH','Jmenovanou skupinu nelze nalézt. Zkuste to znovu.');
DEFINE('_ERRAGNAMEEXIST','Zadaná skupina neexistuje.');
DEFINE('_ERRAGIDSEARCH','Skupinu určenou ID nelze nalézt. Zkuste to znovu.');
DEFINE('_ERRAGLOOKUP','Chyba při vyhledávání skupiny dle ID');
DEFINE('_ERRAGINSERT','Chyba při vkládání nové skupiny');

//base_ag_main.php
DEFINE('_AGMAINTTITLE','Správa skupin');
DEFINE('_ERRAGUPDATE','Chyba při aktualizaci skupiny');
DEFINE('_ERRAGPACKETLIST','Chyba při mazání obsahu skupiny:');
DEFINE('_ERRAGDELETE','Chyba při mazání skupiny');
DEFINE('_AGDELETE','smazána úspěšně');
DEFINE('_AGDELETEINFO','informace smazána');
DEFINE('_ERRAGSEARCHINV','Zadané vyhledávací kritérium je neplatné. Zkuste to znovu.');
DEFINE('_ERRAGSEARCHNOTFOUND','Žádná skupiny s tímto kritériem nenalezena.');
DEFINE('_NOALERTGOUPS','Nejsou definovány žádné skupiny');
DEFINE('_NUMALERTS','počet '.$UI_CW_Alert.'ů');
DEFINE('_ACTIONS','Akce');
DEFINE('_NOTASSIGN','ještě nepřiřazeno');
DEFINE('_SAVECHANGES','Save Changes'); //NEW
DEFINE('_CONFIRMDELETE','Confirm Delete'); //NEW
DEFINE('_CONFIRMCLEAR','Confirm Clear'); //NEW

//base_common.php
DEFINE('_PORTSCAN','Provoz skenování portů');

//base_db_common.php
DEFINE('_ERRDBINDEXCREATE','Nemohu vytvořit index pro');
DEFINE('_DBINDEXCREATE','Úspěšně vytvořen index pro');
DEFINE('_ERRSNORTVER','Může se jednat o starší verzi. Podporovány jsou pouze databáze vytvořené Snort 1.7-beta0 nebo novějším');
DEFINE('_ERRSNORTVER1','Základní databáze');
DEFINE('_ERRSNORTVER2','se zdá nekompletní nebo neplatná');
DEFINE('_ERRDBSTRUCT1','Verze databáze je správná, ale neobsahuje');
DEFINE('_ERRDBSTRUCT2','BASE tabulky. Použijte <A HREF="base_db_setup.php">Inicializační stránku</A> pro nastavení a optimalizaci databáze.');
DEFINE('_ERRPHPERROR','Chyba PHP');
DEFINE('_ERRPHPERROR1','Nekompatibilní verze');
DEFINE('_ERRVERSION','Verze');
DEFINE('_ERRPHPERROR2','PHP je příliš stará. Proveďte prosím aktualizaci na verzi 4.0.4 nebo pozdější');
DEFINE('_ERRPHPMYSQLSUP','<B>PHP podpora není kompletní</B>: <FONT>podpora pro práci s MySQL 
               databází není součástí instalace.
               Prosím přeinstalujte PHP s potřebnou knihovnou (<CODE>--with-mysql</CODE>)</FONT>');
DEFINE('_ERRPHPPOSTGRESSUP','<B>PHP podpora není kompletní</B>: <FONT>podpora pro práci s PostgreSQL
               databází není součástí instalace.
               Prosím přeinstalujte PHP s potřebnou knihovnou (<CODE>--with-pgsql</CODE>)</FONT>');
DEFINE('_ERRPHPMSSQLSUP','<B>PHP podpora není kompletní</B>: <FONT>podpora pro práci s MS SQL
               databází není součástí instalace.
               Prosím přeinstalujte PHP s potřebnou knihovnou (<CODE>--enable-mssql</CODE>)</FONT>');
DEFINE('_ERRPHPORACLESUP','<B>PHP podpora není kompletní</B>: <FONT>podpora pro práci s Oracle
               databází není součástí instalace.
               Prosím přeinstalujte PHP s potřebnou knihovnou (<CODE>--with-oci8</CODE>)</FONT>');

//base_graph_form.php
DEFINE('_CHARTTITLE','Nadpis grafu:');
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
DEFINE('_CHRTTYPEHOUR','Čas (hodiny) proti počtu '.$UI_CW_Alert.'ů');
DEFINE('_CHRTTYPEDAY','Čas (dny) proti počtu '.$UI_CW_Alert.'ů');
DEFINE('_CHRTTYPEWEEK','Čas (týdny) proti počtu '.$UI_CW_Alert.'ů');
DEFINE('_CHRTTYPEMONTH','Čas (měsíce) proti počtu '.$UI_CW_Alert.'ů');
DEFINE('_CHRTTYPEYEAR','Čas (roky) proti počtu '.$UI_CW_Alert.'ů');
DEFINE('_CHRTTYPESRCIP','Zdrojová IP adresa proti počtu '.$UI_CW_Alert.'ů');
DEFINE('_CHRTTYPEDSTIP','Cílová IP adresa proti počtu '.$UI_CW_Alert.'ů');
DEFINE('_CHRTTYPEDSTUDP','Cílový UDP port proti počtu '.$UI_CW_Alert.'ů');
DEFINE('_CHRTTYPESRCUDP','Zdrojový UDP port proti počtu '.$UI_CW_Alert.'ů');
DEFINE('_CHRTTYPEDSTPORT','Cílový TCP port proti počtu '.$UI_CW_Alert.'ů');
DEFINE('_CHRTTYPESRCPORT','Zdrojový TCP port proti počtu '.$UI_CW_Alert.'ů');
DEFINE('_CHRTTYPESIG','Klasifikace podpisů proti počtu '.$UI_CW_Alert.'ů');
DEFINE('_CHRTTYPESENSOR','Senzor proti počtu '.$UI_CW_Alert.'ů');
DEFINE('_CHRTBEGIN','Začátek grafu:');
DEFINE('_CHRTEND','Konec grafu:');
DEFINE('_CHRTDS','Zdroj dat:');
DEFINE('_CHRTX','Osa X');
DEFINE('_CHRTY','Osa Y');
DEFINE('_CHRTMINTRESH','Minimální hodnota');
DEFINE('_CHRTROTAXISLABEL','Otočit popisky os o 90 stup�ů');
DEFINE('_CHRTSHOWX','Zobraz rastr pro osu X');
DEFINE('_CHRTDISPLABELX','Zobraz popis osy X každých');
DEFINE('_CHRTDATAPOINTS','vzorků dat');
DEFINE('_CHRTYLOG','Osa Y logaritmická');
DEFINE('_CHRTYGRID','Zobraz rastr pro osu Y');

//base_graph_main.php
DEFINE('_CHRTTITLE','Graf BASE');
DEFINE('_ERRCHRTNOTYPE','Nebyl určen '.$UI_CW_Type.' grafu');
DEFINE('_ERRNOAGSPEC','Nebyla určena skupiny. Používám všechny '.$UI_CW_Alert.'y.');
DEFINE('_CHRTDATAIMPORT','Začínám načítat data');
DEFINE('_CHRTTIMEVNUMBER','Čas port proti počtu '.$UI_CW_Alert.'ů');
DEFINE('_CHRTTIME','Čas');
DEFINE('_CHRTALERTOCCUR','Výskyty '.$UI_CW_Alert.'ů');
DEFINE('_CHRTSIPNUMBER','Zdrojová IP adresa proti počtu '.$UI_CW_Alert.'ů');
DEFINE('_CHRTSIP','Zdrojová IP adresa');
DEFINE('_CHRTDIPALERTS','Cílová IP adresa proti počtu '.$UI_CW_Alert.'ů');
DEFINE('_CHRTDIP','Cílová IP adresa');
DEFINE('_CHRTUDPPORTNUMBER','UDP port (cíl) port proti počtu '.$UI_CW_Alert.'ů');
DEFINE('_CHRTDUDPPORT','Cílový UDP port');
DEFINE('_CHRTSUDPPORTNUMBER','UDP port (zdroj) port proti počtu '.$UI_CW_Alert.'ů');
DEFINE('_CHRTSUDPPORT','Zdrojový UDP port');
DEFINE('_CHRTPORTDESTNUMBER','TCP port (cíl) port proti počtu '.$UI_CW_Alert.'ů');
DEFINE('_CHRTPORTDEST','Cílový TCP port');
DEFINE('_CHRTPORTSRCNUMBER','TCP port (zdroj) port proti počtu '.$UI_CW_Alert.'ů');
DEFINE('_CHRTPORTSRC','Zdrojový TCP port');
DEFINE('_CHRTSIGNUMBER','Klasifikace podpisů proti počtu '.$UI_CW_Alert.'ů');
DEFINE('_CHRTCLASS','Klasifikace');
DEFINE('_CHRTSENSORNUMBER','Senzor port proti počtu '.$UI_CW_Alert.'ů');
DEFINE('_CHRTHANDLEPERIOD','Rozhodné období (pokud je třeba)');
DEFINE('_CHRTDUMP','Vypisuji data ... (zobrazuji jen každé');
DEFINE('_CHRTDRAW','Kreslím graf');
DEFINE('_ERRCHRTNODATAPOINTS','Pro vykreslení nejsou k dispozici žádná data');
DEFINE('_GRAPHALERTDATA','Graph Alert Data'); //NEW

//base_maintenance.php
DEFINE('_MAINTTITLE','Údržba');
DEFINE('_MNTPHP','PHP popis:');
DEFINE('_MNTCLIENT','CLIENT:');
DEFINE('_MNTSERVER','SERVER:');
DEFINE('_MNTSERVERHW','SERVER HW:');
DEFINE('_MNTPHPVER','PHP VERZE:');
DEFINE('_MNTPHPAPI','PHP API:');
DEFINE('_MNTPHPLOGLVL','Úrove� hlášení PHP:');
DEFINE('_MNTPHPMODS','Nahrané moduly:');
DEFINE('_MNTDBTYPE',$UI_CW_Type.' databáze:');
DEFINE('_MNTDBALV','Verze podpůrné databázové knihovny:');
DEFINE('_MNTDBALERTNAME','Jméno ALERT databáze:');
DEFINE('_MNTDBARCHNAME','Jméno ARCHIVE databáze:');
DEFINE('_MNTAIC','Vyrovnávací pamě� '.$UI_CW_Alert.'ů:');
DEFINE('_MNTAICTE',$UI_CW_Total.' počet '.$UI_CW_Event.'í:');
DEFINE('_MNTAICCE',$UI_CW_Event.'i ve vyrovnávací paměti:');
DEFINE('_MNTIPAC','Vyrovnávací pamě� IP address');
DEFINE('_MNTIPACUSIP','Unikátní zdrojové IP:');
DEFINE('_MNTIPACDNSC','DNS ve vyrovnávací paměti:');
DEFINE('_MNTIPACWC','Whois ve vyrovnávací paměti:');
DEFINE('_MNTIPACUDIP','Unikátní cílové IP:');

//base_qry_alert.php
DEFINE('_QAINVPAIR','Neplatný pár (sid,cid)');
DEFINE('_QAALERTDELET',$UI_CW_Alert.' smazán');
DEFINE('_QATRIGGERSIG','Detekovaný podpis '.$UI_CW_Alert.'u');
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
DEFINE('_QCERRCRITWARN','Varování vyhledávacích kritérií:');
DEFINE('_QCERRVALUE','Hodnota');
DEFINE('_QCERRFIELD','Pole');
DEFINE('_QCERROPER','Operátor');
DEFINE('_QCERRDATETIME','Hodnota datum/čas');
DEFINE('_QCERRPAYLOAD','Hodnota obsahu');
DEFINE('_QCERRIP','IP adresa');
DEFINE('_QCERRIPTYPE','IP adresa '.$UI_CW_Type);
DEFINE('_QCERRSPECFIELD','bylo zadáno pole protokolu, ale nebyla určena hodnota.');
DEFINE('_QCERRSPECVALUE','bylo vybráno, ale nebyla určena hodnota.');
DEFINE('_QCERRBOOLEAN','Více polí pro určení protokolu bylo zadáno, ale nebyl mezi nimi zadán logický operátor (AND, OR).');
DEFINE('_QCERRDATEVALUE','bylo zvoleno, že se má vyhledávat podle data/času, ale nebyla určena hodnota.');
DEFINE('_QCERRINVHOUR','(Neplatná hodina) Žádné kritérium pro určení data/času neodpovídá určenému času.');
DEFINE('_QCERRDATECRIT','bylo zvoleno, že se má vyhledávat podle data/času, ale nebyla určena hodnota.');
DEFINE('_QCERROPERSELECT','bylo vloženo, ale nebyl zvolen žádný operátor.');
DEFINE('_QCERRDATEBOOL','Více kritérií datum/čas bylo zadáno bez určení logického operátoru (AND, OR) mezi nimi.');
DEFINE('_QCERRPAYCRITOPER','byl určen obsah, který se má vyhledávat, ale nebylo zvoleno, zda má být obsažen nebo ne.');
DEFINE('_QCERRPAYCRITVALUE','bylo určeno, že se má vyhledávat podle obsahu, ale nebyla určena hodnota.');
DEFINE('_QCERRPAYBOOL','Více kritérií obsahu bylo zadáno bez určenít logického operátoru (AND, OR) mezi nimi.');
DEFINE('_QCMETACRIT','Meta kritária');
DEFINE('_QCIPCRIT','IP kritéria');
DEFINE('_QCPAYCRIT','Obsahová kritéria');
DEFINE('_QCTCPCRIT','TCP kritéria');
DEFINE('_QCUDPCRIT','UDP kritéria');
DEFINE('_QCICMPCRIT','ICMP kritéria');
DEFINE('_QCLAYER4CRIT','Layer 4 Criteria'); //NEW
DEFINE('_QCERRINVIPCRIT','Neplatné kritérium IP adresy');
DEFINE('_QCERRCRITADDRESSTYPE','byla zvolena jako kritérium, ale nebylo určeno, zda se jedná o zdrojovou nebo cílovou adresu.');
DEFINE('_QCERRCRITIPADDRESSNONE','ukazujíc, že IP adresa má být kritériem, ale nebyla určena hodnota.');
DEFINE('_QCERRCRITIPADDRESSNONE1','bylo vybráno (v #');
DEFINE('_QCERRCRITIPIPBOOL','Více kritérií pro IP adresy bylo zadáno bez určení logického operátoru (AND, OR) mezi nimi.');

//base_qry_form.php
DEFINE('_QFRMSORTORDER','Směr třídění');
DEFINE('_QFRMSORTNONE','none'); //NEW
DEFINE('_QFRMTIMEA','čas (vzestupně)');
DEFINE('_QFRMTIMED','čas (sestupně)');
DEFINE('_QFRMSIG','podpis');
DEFINE('_QFRMSIP','zdrojová IP adresa');
DEFINE('_QFRMDIP','cílová IP adresa');

//base_qry_sqlcalls.php
DEFINE('_QSCSUMM','Souhrné statistiky');
DEFINE('_QSCTIMEPROF','Profil v čase');
DEFINE('_QSCOFALERTS','z '.$UI_CW_Alert.'ů');

//base_stat_alerts.php
DEFINE('_ALERTTITLE','Výpis '.$UI_CW_Alert.'ů');

//base_stat_common.php
DEFINE('_SCCATEGORIES','Kategorie:');
DEFINE('_SCSENSORTOTAL','Senzory/'.$UI_CW_Total.':');
DEFINE('_SCTOTALNUMALERTS',$UI_CW_Total.' počet '.$UI_CW_Alert.'ů:');
DEFINE('_SCSRCIP','Zdrojových IP adres:');
DEFINE('_SCDSTIP','Cílových IP adres:');
DEFINE('_SCUNILINKS','Unikátních IP spojů');
DEFINE('_SCSRCPORTS','Zdrojových portů: ');
DEFINE('_SCDSTPORTS','Cílových portů: ');
DEFINE('_SCSENSORS','Senzorů');
DEFINE('_SCCLASS','Klasifikace');
DEFINE('_SCUNIADDRESS','Unikátních adres: ');
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
DEFINE('_PSOCCASDST','Occurances <BR>as Dest.');
DEFINE('_PSWHOISINFO','Whois Information');
DEFINE('_PSTOTALHOSTS',$UI_CW_Total.' Hosts Scanned'); //NEW
DEFINE('_PSDETECTAMONG','%d unique alerts detected among %d alerts on %s'); //NEW
DEFINE('_PSALLALERTSAS','all alerts with %s/%s as'); //NEW
DEFINE('_PSSHOW','show'); //NEW
DEFINE('_PSEXTERNAL','external'); //NEW

//base_stat_iplink.php
DEFINE('_SIPLTITLE','IP spoje');
DEFINE('_SIPLSOURCEFGDN','Zdrojové FQDN');
DEFINE('_SIPLDESTFGDN','Cílové FQDN');
DEFINE('_SIPLDIRECTION','Směr');
DEFINE('_SIPLPROTO','Protokol');
DEFINE('_SIPLUNIDSTPORTS','Unikátních cílových portů');
DEFINE('_SIPLUNIEVENTS','Unikátních '.$UI_CW_Alert.'ů');
DEFINE('_SIPLTOTALEVENTS',$UI_CW_Total.' '.$UI_CW_Alert.'ů');

//base_stat_ports.php
DEFINE('_UNIQ','Unikátní');
DEFINE('_DSTPS','cílové porty');
DEFINE('_SRCPS','zdrojové porty');
DEFINE('_OCCURRENCES','Occurrences'); //NEW

//base_stat_sensor.php
DEFINE('SPSENSORLIST','Výpis senzorů');

//base_stat_time.php
DEFINE('_BSTTITLE','Time Profile of Alerts');
DEFINE('_BSTTIMECRIT','Time Criteria');
DEFINE('_BSTERRPROFILECRIT','<FONT><B>No profiling criteria was specified!</B>  Click on "hour", "day", or "month" to choose the granularity of the aggregate statistics.</FONT>');
DEFINE('_BSTERRTIMETYPE','<FONT><B>The '.$UI_CW_Type.' of time parameter which will be passed was not specified!</B>  Choose either "on", to specify a single date, or "between" to specify an interval.</FONT>');
DEFINE('_BSTERRNOYEAR','<FONT><B>No Year parameter was specified!</B></FONT>');
DEFINE('_BSTERRNOMONTH','<FONT><B>No Month parameter was specified!</B></FONT>');
DEFINE('_BSTERRNODAY','<FONT><B>No Day parameter was specified!</B></FONT>');
DEFINE('_BSTPROFILEBY','Profile by'); //NEW
DEFINE('_TIMEON','on'); //NEW
DEFINE('_TIMEBETWEEN','between'); //NEW
DEFINE('_PROFILEALERT','Profile Alert'); //NEW

//base_stat_uaddr.php
DEFINE('_UNISADD','Unikátní zdrojové IP adresy');
DEFINE('_SUASRCIP','Zdrojová IP adresa');
DEFINE('_SUAERRCRITADDUNK','chyba v kritériu: neznámý '.$UI_CW_Type.' adresy -- předpokládám cílovou');
DEFINE('_UNIDADD','Unikátní cílové IP adresy');
DEFINE('_SUADSTIP','Cílová IP adresa');
DEFINE('_SUAUNIALERTS','Unikátních '.$UI_CW_Alert.'ů');
DEFINE('_SUASRCADD','Zdrojových adres');
DEFINE('_SUADSTADD','Cílových adres');

//base_user.php
DEFINE('_BASEUSERTITLE','Uživatelské předvolby BASE');
DEFINE('_BASEUSERERRPWD',"$UI_CW_Pw nesmí být prázné nebo ".strtolower($UI_CW_Pw).' nesouhlasí!');
DEFINE('_BASEUSEROLDPWD','Staré '.strtolower($UI_CW_Pw).':');
DEFINE('_BASEUSERNEWPWD','Nové '.strtolower($UI_CW_Pw).':');
DEFINE('_BASEUSERNEWPWDAGAIN','Nové '.strtolower($UI_CW_Pw).' znovu:');

DEFINE('_LOGOUT','Odhlásit');
?>
