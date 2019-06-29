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
$UI_CW_Ts = '�asová zna�ka';
$UI_CW_Addr = 'adresa';
$UI_CW_Layer = 'vrstvy';
$UI_CW_Protp = 'Protokol';
// Common Phrases.
$UI_CP_SrcName = array($UI_CW_Name,$UI_CW_Src);
$UI_CP_DstName = array($UI_CW_Name,$UI_CW_Dst);
$UI_CP_SrcDst = array($UI_CW_Src,'n.',$UI_CW_Dst);
$UI_CP_SrcAddr = array($UI_CW_Src,$UI_CW_Addr);
$UI_CP_DstAddr = array($UI_CW_Dst,$UI_CW_Addr);
$UI_CP_L4P = array($UI_CW_Proto,$UI_CW_Layer,'4');
// Authentication Data.
$UI_AD_UND = 'Login';
$UI_AD_RID = array($UI_CW_Id,$UI_CW_Role);
$UI_AD_ASD = 'Enabled';

//common phrases
DEFINE('_PRIORITY','Priorita');
DEFINE('_EVENTTYPE','typ události');
DEFINE('_JANUARY','Leden');
DEFINE('_FEBRUARY','�nor');
DEFINE('_MARCH','B�ezen');
DEFINE('_APRIL','Duben');
DEFINE('_MAY','Kv�ten');
DEFINE('_JUNE','�erven');
DEFINE('_JULY','�ervenec');
DEFINE('_AUGUST','Srpen');
DEFINE('_SEPTEMBER','Zá�í');
DEFINE('_OCTOBER','�íjen');
DEFINE('_NOVEMBER','Listopad');
DEFINE('_DECEMBER','Prosinec');
DEFINE('_LAST','Poslední');
DEFINE('_FIRST','First'); //NEW
DEFINE('_TOTAL','Total'); //NEW
DEFINE('_ALERT','Alarm');
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
DEFINE('_TYPE','type'); //NEW
DEFINE('_NEXT','Next'); //NEW
DEFINE('_PREVIOUS','Previous'); //NEW

//Menu items
DEFINE('_HOME','Dom�');
DEFINE('_SEARCH','Hledat');
DEFINE('_AGMAINT','Správa skupin alarm�');
DEFINE('_USERPREF','U�ivatelské volby');
DEFINE('_CACHE','Ke� a stav');
DEFINE('_ADMIN','Administrace');
DEFINE('_GALERTD','Vytvo�it graf alarm�');
DEFINE('_GALERTDT','Vytvo�it graf �asu detekce alarm�');
DEFINE('_USERMAN','Správa u�ivatel�');
DEFINE('_LISTU','Seznam u�ivatel�');
DEFINE('_CREATEU','Vytvo�it u�ivatele');
DEFINE('_ROLEMAN','Správa rolí');
DEFINE('_LISTR','Seznam rolí');
DEFINE('_CREATER','Vytvo�it roli');
DEFINE('_LISTALL','Vypsat v�e');
DEFINE('_CREATE','Vytvo�');
DEFINE('_VIEW','Zobraz');
DEFINE('_CLEAR','Vy�isti');
DEFINE('_LISTGROUPS','Seznam skupin');
DEFINE('_CREATEGROUPS','Vytvo� skupinu');
DEFINE('_VIEWGROUPS','Zobraz skupinu');
DEFINE('_EDITGROUPS','Edituj skupinu');
DEFINE('_DELETEGROUPS','Sma� skupinu');
DEFINE('_CLEARGROUPS','Vy�isti skupinu');
DEFINE('_CHNGPWD','Zm�nit '.strtolower($UI_CW_Pw));
DEFINE('_DISPLAYU','Zobraz u�ivatele');

//base_footer.php
DEFINE('_FOOTER',' (by <A class="largemenuitem" href="mailto:base@secureideas.net">Kevin Johnson</A> and the <A class="largemenuitem" href="http://sourceforge.net/project/memberlist.php?group_id=103348">BASE Project Team</A><BR>Built on ACID by Roman Danyliw )');

//index.php --Log in Page
DEFINE('_LOGINERROR','U�ivatel neexistuje nebo jste zadali �patné '.strtolower($UI_CW_Pw).'!<br>Zkuste prosím znovu.');

// base_main.php
DEFINE('_MOSTRECENT','Posledních ');
DEFINE('_MOSTFREQUENT','Nej�ast�j�ích ');
DEFINE('_ALERTS',' alarm�:');
DEFINE('_ADDRESSES',' adres:');
DEFINE('_ANYPROTO','jakýkoliv<br>protokol');
DEFINE('_UNI','unikátní');
DEFINE('_LISTING','výpis');
DEFINE('_TALERTS','Dne�ní alarmy: ');
DEFINE('_SOURCEIP','Source IP'); //NEW
DEFINE('_DESTIP','Destination IP'); //NEW
DEFINE('_L24ALERTS','Alarmy za posledních 24 hodin: ');
DEFINE('_L72ALERTS','Alarmy za posledních 72 hodin: ');
DEFINE('_UNIALERTS','unikátních alarm�');
DEFINE('_LSOURCEPORTS','Poslední zdrojové porty: ');
DEFINE('_LDESTPORTS','Poslední cílové porty: ');
DEFINE('_FREGSOURCEP','Nej�ast�j�í zdrojové porty: ');
DEFINE('_FREGDESTP','Nej�ast�j�í cílové porty: ');
DEFINE('_QUERIED','Dotázáno ');
DEFINE('_DATABASE','Databáze:');
DEFINE('_SCHEMAV','Verze schématu:');
DEFINE('_TIMEWIN','�asové rozmezí:');
DEFINE('_NOALERTSDETECT','�ádné alarmy dezji�t�ny');
DEFINE('_USEALERTDB','Use Alert Database'); //NEW
DEFINE('_USEARCHIDB','Use Archive Database'); //NEW
DEFINE('_TRAFFICPROBPRO','Traffic Profile by Protocol'); //NEW

//base_auth.inc.php
DEFINE('_ADDEDSF','�sp�n� p�idán');
DEFINE('_NOPWDCHANGE','Nelze zm�nit '.strtolower($UI_CW_Pw).': ');
DEFINE('_NOUSER','U�ivatel neexistuje!');
DEFINE('_OLDPWD','Aktuální '.strtolower($UI_CW_Pw).' není správné!');
DEFINE('_PWDCANT','Nelze zm�nit '.strtolower($UI_CW_Pw).': ');
DEFINE('_PWDDONE',"$UI_CW_Pw bylo zm�n�no.");
DEFINE('_ROLEEXIST',"$UI_CW_Role existuje");
// TD Migration Hack
if ($UI_Spacing == 1){
	$glue = ' ';
}else{
	$glue = '';
}
DEFINE('_ROLEIDEXIST',implode($glue, $UI_AD_RID)." existuje");
DEFINE('_ROLEADDED',"$UI_CW_Role p�idána �sp�n�");

//base_roleadmin.php
DEFINE('_ROLEADMIN','Správa rolí BASE');
DEFINE('_FRMROLENAME',"Jméno $UI_CW_Role:");
DEFINE('_UPDATEROLE',"Update $UI_CW_Role"); //NEW

//base_useradmin.php
DEFINE('_USERADMIN','Správa u�ivatel� BASE');
DEFINE('_FRMFULLNAME','Celé jméno:');
DEFINE('_FRMUID','ID u�ivatele:');
DEFINE('_SUBMITQUERY','Submit Query'); //NEW
DEFINE('_UPDATEUSER','Update User'); //NEW

//admin/index.php
DEFINE('_BASEADMIN','Administrace BASE');
DEFINE('_BASEADMINTEXT','Zvolte prosím operaci nalevo.');

//base_action.inc.php
DEFINE('_NOACTION','Nebyla specifikována operace');
DEFINE('_INVALIDACT',' je neplatná operace');
DEFINE('_ERRNOAG','Nemohu p�idat alarmy; nebyla specifikována skupina');
DEFINE('_ERRNOEMAIL','Nemohu zaslat alarmy po�tou; nebyla specifikována emailová adresa');
DEFINE('_ACTION','Operace');
DEFINE('_CONTEXT','kontext');
DEFINE('_ADDAGID','P�idat do skupiny (podle ID)');
DEFINE('_ADDAG','P�idat do nov� vytvo�ené skupiny'); // not used
DEFINE('_ADDAGNAME','P�idat do skupiny (podle jména)');
DEFINE('_CREATEAG','P�idat do nov� vytvo�ené skupiny');
DEFINE('_CLEARAG','Vymazat se skupiny');
DEFINE('_DELETEALERT','Smazat');
DEFINE('_EMAILALERTSFULL','Zaslat emailem (detailní)');
DEFINE('_EMAILALERTSSUMM','Zaslat emailem (shrnutí)');
DEFINE('_EMAILALERTSCSV','Zaslat emailem (csv)');
DEFINE('_ARCHIVEALERTSCOPY','Archivovat (vytvo�it kopii)');
DEFINE('_ARCHIVEALERTSMOVE','Archivovat (p�esunout)');
DEFINE('_IGNORED','Ignorováno');
DEFINE('_DUPALERTS',' duplicitní alarm(y)');
DEFINE('_ALERTSPARA',' alarm(y)');
DEFINE('_NOALERTSSELECT','�ádné alarmy nebyly vybrány nebo');
DEFINE('_NOTSUCCESSFUL','nebyla �sp�ná');
DEFINE('_ERRUNKAGID','Zadáno neznámé ID skupiny (skupina pravd�podobn� neexistuje)');
DEFINE('_ERRREMOVEFAIL','Selhalo odstran�ní nové skupiny');
DEFINE('_GENBASE','Vytvo�eno BASE');
DEFINE('_ERRNOEMAILEXP','Chyba p�i exportování: Nemohu poslat alarmy');
DEFINE('_ERRNOEMAILPHP','Zkontrolujte konfiguraci emailu PHP.');
DEFINE('_ERRDELALERT','Chyba p�i mazání alarmu');
DEFINE('_ERRARCHIVE','Chyba p�i archivaci:');
DEFINE('_ERRMAILNORECP','Chyba p�i zasílání emailem: Nebyl zadán p�íjemce');

//base_cache.inc.php
DEFINE('_ADDED','P�idáno ');
DEFINE('_HOSTNAMESDNS',' jmen do IP DNS vyrovnávací pam�ti');
DEFINE('_HOSTNAMESWHOIS',' jmen do Whois vyrovnávací pam�ti');
DEFINE('_ERRCACHENULL','Chyba p�i aktualizaci vyrovnávací pam�ti: nalezena NULL �ádka event?');
DEFINE('_ERRCACHEERROR','Chyba p�i aktualizaci vyrovnávací pam�ti:');
DEFINE('_ERRCACHEUPDATE','Nemohu aktualizovat vyrovnávací pam�');
DEFINE('_ALERTSCACHE',' alarm� do vyrovnávací pam�ti');

//base_db.inc.php
DEFINE('_ERRSQLTRACE','Nemohu otev�ít soubor pro trasování SQL');
DEFINE('_ERRSQLCONNECT','Chyba p�i p�ipojování databáze:');
DEFINE('_ERRSQLCONNECTINFO','<P>Zkontrolujte prom�nné pro p�ipojování se do databáze v souboru <I>base_conf.php</I> 
              <PRE>
               = $alert_dbname   : jméno databáze 
               = $alert_host     : hostitel
               = $alert_port     : port
               = $alert_user     : u�ivatelské jméno
               = $alert_password : '.strtolower($UI_CW_Pw).'
              </PRE>
              <P>');
DEFINE('_ERRSQLPCONNECT','Chyba p�i p�ipojování databáze:');
DEFINE('_ERRSQLDB','Databázová chyba:');
DEFINE('_DBALCHECK','Kontraoluje knihovnu pro práci s databází v');
DEFINE('_ERRSQLDBALLOAD1','<P><B>Chyba p�i na�ítání knihovny pro práci s databází: </B> od ');
DEFINE('_ERRSQLDBALLOAD2','<P>Zkontrolujte prom�nnou pro ur�ení cesty ke knihovn� pro práci s databází <CODE>$DBlib_path</CODE> v souboru <CODE>base_conf.php</CODE>
            <P>Knihovnu pro práci s databází ADODB stáhn�te z
            ');
DEFINE('_ERRSQLDBTYPE','Specifikován neplatný typ databáze');
DEFINE('_ERRSQLDBTYPEINFO1','Prom�nná <CODE>\$DBtype</CODE> v souboru <CODE>base_conf.php</CODE> byla �patn� nastavena na ');
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
DEFINE('_LOADEDIN','Na�teno za');
DEFINE('_SECONDS','vte�in');

//base_net.inc.php
DEFINE('_ERRRESOLVEADDRESS','Nemohu p�elo�it adresu');

//base_output_query.inc.php
DEFINE('_QUERYRESULTSHEADER','Záhlaví výsledk� dotazu'); //not used

//base_signature.inc.php
DEFINE('_ERRSIGNAMEUNK','neznámé SigName');
DEFINE('_ERRSIGPROIRITYUNK','neznámé SigPriority');
DEFINE('_UNCLASS','neza�azeno');

//base_state_citems.inc.php
DEFINE('_DENCODED','data zak�d�vána jako');
DEFINE('_NODENCODED','(�ádná konverze dat, p�edpokládám po�adavek ve výchozím formátu databáze)');
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
DEFINE('_PHPSESSREG','Sezení zaregistrováno');

//base_state_criteria.inc.php
DEFINE('_REMOVE','Odstranit');
DEFINE('_FROMCRIT','z kritérií');
DEFINE('_ERRCRITELEM','Neplatný elemt kritéria');

//base_state_query.inc.php
DEFINE('_VALIDCANNED','Platný základní dotaz');
DEFINE('_DISPLAYING','Zobrazuji');
DEFINE('_DISPLAYINGTOTAL','Zobrazuji alarmy %d-%d z %d celkem');
DEFINE('_NOALERTS','�ádné alarmy nenalezeny.');
DEFINE('_QUERYRESULTS','Výsledky dotazu');
DEFINE('_QUERYSTATE','Stav dotazu');
DEFINE('_DISPACTION','{ action }'); //NEW

//base_ag_common.php
DEFINE('_ERRAGNAMESEARCH','Jmenovanou skupinu nelze nalézt. Zkuste to znovu.');
DEFINE('_ERRAGNAMEEXIST','Zadaná skupina neexistuje.');
DEFINE('_ERRAGIDSEARCH','Skupinu ur�enou ID nelze nalézt. Zkuste to znovu.');
DEFINE('_ERRAGLOOKUP','Chyba p�i vyhledávání skupiny dle ID');
DEFINE('_ERRAGINSERT','Chyba p�i vkládání nové skupiny');

//base_ag_main.php
DEFINE('_AGMAINTTITLE','Správa skupin');
DEFINE('_ERRAGUPDATE','Chyba p�i aktualizaci skupiny');
DEFINE('_ERRAGPACKETLIST','Chyba p�i mazání obsahu skupiny:');
DEFINE('_ERRAGDELETE','Chyba p�i mazání skupiny');
DEFINE('_AGDELETE','smazána �sp�n�');
DEFINE('_AGDELETEINFO','informace smazána');
DEFINE('_ERRAGSEARCHINV','Zadané vyhledávací kritérium je neplatné. Zkuste to znovu.');
DEFINE('_ERRAGSEARCHNOTFOUND','�ádná skupiny s tímto kritériem nenalezena.');
DEFINE('_NOALERTGOUPS','Nejsou definovány �ádné skupiny');
DEFINE('_NUMALERTS','po�et alarm�');
DEFINE('_ACTIONS','Akce');
DEFINE('_NOTASSIGN','je�t� nep�i�azeno');
DEFINE('_SAVECHANGES','Save Changes'); //NEW
DEFINE('_CONFIRMDELETE','Confirm Delete'); //NEW
DEFINE('_CONFIRMCLEAR','Confirm Clear'); //NEW

//base_common.php
DEFINE('_PORTSCAN','Provoz skenování port�');

//base_db_common.php
DEFINE('_ERRDBINDEXCREATE','Nemohu vytvo�it index pro');
DEFINE('_DBINDEXCREATE','�sp�n� vytvo�en index pro');
DEFINE('_ERRSNORTVER','M��e se jednat o star�í verzi. Podporovány jsou pouze databáze vytvo�ené Snort 1.7-beta0 nebo nov�j�ím');
DEFINE('_ERRSNORTVER1','Základní databáze');
DEFINE('_ERRSNORTVER2','se zdá nekompletní nebo neplatná');
DEFINE('_ERRDBSTRUCT1','Verze databáze je správná, ale neobsahuje');
DEFINE('_ERRDBSTRUCT2','BASE tabulky. Pou�ijte <A HREF="base_db_setup.php">Inicializa�ní stránku</A> pro nastavení a optimalizaci databáze.');
DEFINE('_ERRPHPERROR','Chyba PHP');
DEFINE('_ERRPHPERROR1','Nekompatibilní verze');
DEFINE('_ERRVERSION','Verze');
DEFINE('_ERRPHPERROR2','PHP je p�íli� stará. Prove�te prosím aktualizaci na verzi 4.0.4 nebo pozd�j�í');
DEFINE('_ERRPHPMYSQLSUP','<B>PHP podpora není kompletní</B>: <FONT>podpora pro práci s MySQL 
               databází není sou�ástí instalace.
               Prosím p�einstalujte PHP s pot�ebnou knihovnou (<CODE>--with-mysql</CODE>)</FONT>');
DEFINE('_ERRPHPPOSTGRESSUP','<B>PHP podpora není kompletní</B>: <FONT>podpora pro práci s PostgreSQL
               databází není sou�ástí instalace.
               Prosím p�einstalujte PHP s pot�ebnou knihovnou (<CODE>--with-pgsql</CODE>)</FONT>');
DEFINE('_ERRPHPMSSQLSUP','<B>PHP podpora není kompletní</B>: <FONT>podpora pro práci s MS SQL
               databází není sou�ástí instalace.
               Prosím p�einstalujte PHP s pot�ebnou knihovnou (<CODE>--enable-mssql</CODE>)</FONT>');
DEFINE('_ERRPHPORACLESUP','<B>PHP podpora není kompletní</B>: <FONT>podpora pro práci s Oracle
               databází není sou�ástí instalace.
               Prosím p�einstalujte PHP s pot�ebnou knihovnou (<CODE>--with-oci8</CODE>)</FONT>');

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
DEFINE('_CHARTMONTH','{mês}'); //NEW
DEFINE('_GRAPHALERTS','Graph Alerts'); //NEW
DEFINE('_AXISCONTROLS','X / Y AXIS CONTROLS'); //NEW
DEFINE('_CHRTTYPEHOUR','�as (hodiny) proti po�tu alarm�');
DEFINE('_CHRTTYPEDAY','�as (dny) proti po�tu alarm�');
DEFINE('_CHRTTYPEWEEK','�as (týdny) proti po�tu alarm�');
DEFINE('_CHRTTYPEMONTH','�as (m�síce) proti po�tu alarm�');
DEFINE('_CHRTTYPEYEAR','�as (roky) proti po�tu alarm�');
DEFINE('_CHRTTYPESRCIP','Zdrojová IP adresa proti po�tu alarm�');
DEFINE('_CHRTTYPEDSTIP','Cílová IP adresa proti po�tu alarm�');
DEFINE('_CHRTTYPEDSTUDP','Cílový UDP port proti po�tu alarm�');
DEFINE('_CHRTTYPESRCUDP','Zdrojový UDP port proti po�tu alarm�');
DEFINE('_CHRTTYPEDSTPORT','Cílový TCP port proti po�tu alarm�');
DEFINE('_CHRTTYPESRCPORT','Zdrojový TCP port proti po�tu alarm�');
DEFINE('_CHRTTYPESIG','Klasifikace podpis� proti po�tu alarm�');
DEFINE('_CHRTTYPESENSOR','Senzor proti po�tu alarm�');
DEFINE('_CHRTBEGIN','Za�átek grafu:');
DEFINE('_CHRTEND','Konec grafu:');
DEFINE('_CHRTDS','Zdroj dat:');
DEFINE('_CHRTX','Osa X');
DEFINE('_CHRTY','Osa Y');
DEFINE('_CHRTMINTRESH','Minimální hodnota');
DEFINE('_CHRTROTAXISLABEL','Oto�it popisky os o 90 stup��');
DEFINE('_CHRTSHOWX','Zobraz rastr pro osu X');
DEFINE('_CHRTDISPLABELX','Zobraz popis osy X ka�dých');
DEFINE('_CHRTDATAPOINTS','vzork� dat');
DEFINE('_CHRTYLOG','Osa Y logaritmická');
DEFINE('_CHRTYGRID','Zobraz rastr pro osu Y');

//base_graph_main.php
DEFINE('_CHRTTITLE','Graf BASE');
DEFINE('_ERRCHRTNOTYPE','Nebyl ur�en typ grafu');
DEFINE('_ERRNOAGSPEC','Nebyla ur�ena skupiny. Pou�ívám v�echny alarmy.');
DEFINE('_CHRTDATAIMPORT','Za�ínám na�ítat data'); 
DEFINE('_CHRTTIMEVNUMBER','�as port proti po�tu alarm�');
DEFINE('_CHRTTIME','�as');
DEFINE('_CHRTALERTOCCUR','Výskyty alarm�');
DEFINE('_CHRTSIPNUMBER','Zdrojová IP adresa proti po�tu alarm�');
DEFINE('_CHRTSIP','Zdrojová IP adresa');
DEFINE('_CHRTDIPALERTS','Cílová IP adresa proti po�tu alarm�');
DEFINE('_CHRTDIP','Cílová IP adresa');
DEFINE('_CHRTUDPPORTNUMBER','UDP port (cíl) port proti po�tu alarm�');
DEFINE('_CHRTDUDPPORT','Cílový UDP port');
DEFINE('_CHRTSUDPPORTNUMBER','UDP port (zdroj) port proti po�tu alarm�');
DEFINE('_CHRTSUDPPORT','Zdrojový UDP port');
DEFINE('_CHRTPORTDESTNUMBER','TCP port (cíl) port proti po�tu alarm�');
DEFINE('_CHRTPORTDEST','Cílový TCP port');
DEFINE('_CHRTPORTSRCNUMBER','TCP port (zdroj) port proti po�tu alarm�');
DEFINE('_CHRTPORTSRC','Zdrojový TCP port');
DEFINE('_CHRTSIGNUMBER','Klasifikace podpis� proti po�tu alarm�');
DEFINE('_CHRTCLASS','Klasifikace');
DEFINE('_CHRTSENSORNUMBER','Senzor port proti po�tu alarm�');
DEFINE('_CHRTHANDLEPERIOD','Rozhodné období (pokud je t�eba)');
DEFINE('_CHRTDUMP','Vypisuji data ... (zobrazuji jen ka�dé');
DEFINE('_CHRTDRAW','Kreslím graf');
DEFINE('_ERRCHRTNODATAPOINTS','Pro vykreslení nejsou k dispozici �ádná data');
DEFINE('_GRAPHALERTDATA','Graph Alert Data'); //NEW

//base_maintenance.php
DEFINE('_MAINTTITLE','�dr�ba');
DEFINE('_MNTPHP','PHP popis:');
DEFINE('_MNTCLIENT','CLIENT:');
DEFINE('_MNTSERVER','SERVER:');
DEFINE('_MNTSERVERHW','SERVER HW:');
DEFINE('_MNTPHPVER','PHP VERZE:');
DEFINE('_MNTPHPAPI','PHP API:');
DEFINE('_MNTPHPLOGLVL','�rove� hlá�ení PHP:');
DEFINE('_MNTPHPMODS','Nahrané moduly:');
DEFINE('_MNTDBTYPE','Typ databáze:');
DEFINE('_MNTDBALV','Verze podp�rné databázové knihovny:');
DEFINE('_MNTDBALERTNAME','Jméno ALERT databáze:');
DEFINE('_MNTDBARCHNAME','Jméno ARCHIVE databáze:');
DEFINE('_MNTAIC','Vyrovnávací pam� alarm�:');
DEFINE('_MNTAICTE','Celkový po�et událostí:');
DEFINE('_MNTAICCE','Události ve vyrovnávací pam�ti:');
DEFINE('_MNTIPAC','Vyrovnávací pam� IP address');
DEFINE('_MNTIPACUSIP','Unikátní zdrojové IP:');
DEFINE('_MNTIPACDNSC','DNS ve vyrovnávací pam�ti:');
DEFINE('_MNTIPACWC','Whois ve vyrovnávací pam�ti:');
DEFINE('_MNTIPACUDIP','Unikátní cílové IP:');

//base_qry_alert.php
DEFINE('_QAINVPAIR','Neplatný pár (sid,cid)');
DEFINE('_QAALERTDELET','Alarm smazán');
DEFINE('_QATRIGGERSIG','Detekovaný podpis alarmu');
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
DEFINE('_QCERRDATETIME','Hodnota datum/�as');
DEFINE('_QCERRPAYLOAD','Hodnota obsahu');
DEFINE('_QCERRIP','IP adresa');
DEFINE('_QCERRIPTYPE','IP adresa typu');
DEFINE('_QCERRSPECFIELD','bylo zadáno pole protokolu, ale nebyla ur�ena hodnota.');
DEFINE('_QCERRSPECVALUE','bylo vybráno, ale nebyla ur�ena hodnota.');
DEFINE('_QCERRBOOLEAN','Více polí pro ur�ení protokolu bylo zadáno, ale nebyl mezi nimi zadán logický operátor (AND, OR).');
DEFINE('_QCERRDATEVALUE','bylo zvoleno, �e se má vyhledávat podle data/�asu, ale nebyla ur�ena hodnota.');
DEFINE('_QCERRINVHOUR','(Neplatná hodina) �ádné kritérium pro ur�ení data/�asu neodpovídá ur�enému �asu.');
DEFINE('_QCERRDATECRIT','bylo zvoleno, �e se má vyhledávat podle data/�asu, ale nebyla ur�ena hodnota.');
DEFINE('_QCERROPERSELECT','bylo vlo�eno, ale nebyl zvolen �ádný operátor.');
DEFINE('_QCERRDATEBOOL','Více kritérií datum/�as bylo zadáno bez ur�ení logického operátoru (AND, OR) mezi nimi.');
DEFINE('_QCERRPAYCRITOPER','byl ur�en obsah, který se má vyhledávat, ale nebylo zvoleno, zda má být obsa�en nebo ne.');
DEFINE('_QCERRPAYCRITVALUE','bylo ur�eno, �e se má vyhledávat podle obsahu, ale nebyla ur�ena hodnota.');
DEFINE('_QCERRPAYBOOL','Více kritérií obsahu bylo zadáno bez ur�enít logického operátoru (AND, OR) mezi nimi.');
DEFINE('_QCMETACRIT','Meta kritária');
DEFINE('_QCIPCRIT','IP kritéria');
DEFINE('_QCPAYCRIT','Obsahová kritéria');
DEFINE('_QCTCPCRIT','TCP kritéria');
DEFINE('_QCUDPCRIT','UDP kritéria');
DEFINE('_QCICMPCRIT','ICMP kritéria');
DEFINE('_QCLAYER4CRIT','Layer 4 Criteria'); //NEW
DEFINE('_QCERRINVIPCRIT','Neplatné kritérium IP adresy');
DEFINE('_QCERRCRITADDRESSTYPE','byla zvolena jako kritérium, ale nebylo ur�eno, zda se jedná o zdrojovou nebo cílovou adresu.');
DEFINE('_QCERRCRITIPADDRESSNONE','ukazujíc, �e IP adresa má být kritériem, ale nebyla ur�ena hodnota.');
DEFINE('_QCERRCRITIPADDRESSNONE1','bylo vybráno (v #');
DEFINE('_QCERRCRITIPIPBOOL','Více kritérií pro IP adresy bylo zadáno bez ur�ení logického operátoru (AND, OR) mezi nimi.');

//base_qry_form.php
DEFINE('_QFRMSORTORDER','Sm�r t�íd�ní');
DEFINE('_QFRMSORTNONE','none'); //NEW
DEFINE('_QFRMTIMEA','�as (vzestupn�)');
DEFINE('_QFRMTIMED','�as (sestupn�)');
DEFINE('_QFRMSIG','podpis');
DEFINE('_QFRMSIP','zdrojová IP adresa');
DEFINE('_QFRMDIP','cílová IP adresa');

//base_qry_sqlcalls.php
DEFINE('_QSCSUMM','Souhrné statistiky');
DEFINE('_QSCTIMEPROF','Profil v �ase');
DEFINE('_QSCOFALERTS','z alarm�');

//base_stat_alerts.php
DEFINE('_ALERTTITLE','Výpis alarm�');

//base_stat_common.php
DEFINE('_SCCATEGORIES','Kategorie:');
DEFINE('_SCSENSORTOTAL','Senzory/Celkem:');
DEFINE('_SCTOTALNUMALERTS','Celkový po�et alarm�:');
DEFINE('_SCSRCIP','Zdrojových IP adres:');
DEFINE('_SCDSTIP','Cílových IP adres:');
DEFINE('_SCUNILINKS','Unikátních IP spoj�');
DEFINE('_SCSRCPORTS','Zdrojových port�: ');
DEFINE('_SCDSTPORTS','Cílových port�: ');
DEFINE('_SCSENSORS','Senzor�');
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
DEFINE('_SIPLDESTFGDN','Cílové FQDN');
DEFINE('_SIPLDIRECTION','Sm�r');
DEFINE('_SIPLPROTO','Protokol');
DEFINE('_SIPLUNIDSTPORTS','Unikátních cílových port�');
DEFINE('_SIPLUNIEVENTS','Unikátních alarm�');
DEFINE('_SIPLTOTALEVENTS','Celkem alarm�');

//base_stat_ports.php
DEFINE('_UNIQ','Unikátní');
DEFINE('_DSTPS','cílové porty');
DEFINE('_SRCPS','zdrojové porty');
DEFINE('_OCCURRENCES','Occurrences'); //NEW

//base_stat_sensor.php
DEFINE('SPSENSORLIST','Výpis senzor�');

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
DEFINE('_UNISADD','Unikátní zdrojové IP adresy');
DEFINE('_SUASRCIP','Zdrojová IP adresa');
DEFINE('_SUAERRCRITADDUNK','chyba v kritériu: neznámý typ adresy -- p�edpokládám cílovou');
DEFINE('_UNIDADD','Unikátní cílové IP adresy');
DEFINE('_SUADSTIP','Cílová IP adresa');
DEFINE('_SUAUNIALERTS','Unikátních alarm�');
DEFINE('_SUASRCADD','Zdrojových adres');
DEFINE('_SUADSTADD','Cílových adres');

//base_user.php
DEFINE('_BASEUSERTITLE','U�ivatelské p�edvolby BASE');
DEFINE('_BASEUSERERRPWD',"$UI_CW_Pw nesmí být prázné nebo ".strtolower($UI_CW_Pw).' nesouhlasí!');
DEFINE('_BASEUSEROLDPWD','Staré '.strtolower($UI_CW_Pw).':');
DEFINE('_BASEUSERNEWPWD','Nové '.strtolower($UI_CW_Pw).':');
DEFINE('_BASEUSERNEWPWDAGAIN','Nové '.strtolower($UI_CW_Pw).' znovu:');

DEFINE('_LOGOUT','Odhlásit');
?>
