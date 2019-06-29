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
$UI_CW_Dst = 'C√≠l';
$UI_CW_Id = 'ID';
$UI_CW_Name = 'Jm√©no';
$UI_CW_Int = 'Rozhran√≠';
$UI_CW_Filter = 'Filtr';
$UI_CW_Desc = 'Popis';
$UI_CW_SucDesc = 'Successful';
$UI_CW_Sensor = 'Senzor';
$UI_CW_Sig = 'Podpis';
$UI_CW_Role = 'role';
$UI_CW_Pw = 'Heslo';
$UI_CW_Ts = '»asov√° znaËka';
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
DEFINE('_EVENTTYPE','typ ud√°losti');
DEFINE('_JANUARY','Leden');
DEFINE('_FEBRUARY','⁄nor');
DEFINE('_MARCH','B¯ezen');
DEFINE('_APRIL','Duben');
DEFINE('_MAY','KvÏten');
DEFINE('_JUNE','»erven');
DEFINE('_JULY','»ervenec');
DEFINE('_AUGUST','Srpen');
DEFINE('_SEPTEMBER','Z√°¯√≠');
DEFINE('_OCTOBER','ÿ√≠jen');
DEFINE('_NOVEMBER','Listopad');
DEFINE('_DECEMBER','Prosinec');
DEFINE('_LAST','Posledn√≠');
DEFINE('_FIRST','First'); //NEW
DEFINE('_TOTAL','Total'); //NEW
DEFINE('_ALERT','Alarm');
DEFINE('_ADDRESS','Adresa');
DEFINE('_UNKNOWN','nezn√°m√Ω');
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
DEFINE('_HOME','Dom˘');
DEFINE('_SEARCH','Hledat');
DEFINE('_AGMAINT','Spr√°va skupin alarm˘');
DEFINE('_USERPREF','Uæivatelsk√© volby');
DEFINE('_CACHE','Keπ a stav');
DEFINE('_ADMIN','Administrace');
DEFINE('_GALERTD','Vytvo¯it graf alarm˘');
DEFINE('_GALERTDT','Vytvo¯it graf Ëasu detekce alarm˘');
DEFINE('_USERMAN','Spr√°va uæivatel˘');
DEFINE('_LISTU','Seznam uæivatel˘');
DEFINE('_CREATEU','Vytvo¯it uæivatele');
DEFINE('_ROLEMAN','Spr√°va rol√≠');
DEFINE('_LISTR','Seznam rol√≠');
DEFINE('_CREATER','Vytvo¯it roli');
DEFINE('_LISTALL','Vypsat vπe');
DEFINE('_CREATE','Vytvo¯');
DEFINE('_VIEW','Zobraz');
DEFINE('_CLEAR','VyËisti');
DEFINE('_LISTGROUPS','Seznam skupin');
DEFINE('_CREATEGROUPS','Vytvo¯ skupinu');
DEFINE('_VIEWGROUPS','Zobraz skupinu');
DEFINE('_EDITGROUPS','Edituj skupinu');
DEFINE('_DELETEGROUPS','Smaæ skupinu');
DEFINE('_CLEARGROUPS','VyËisti skupinu');
DEFINE('_CHNGPWD','ZmÏnit '.strtolower($UI_CW_Pw));
DEFINE('_DISPLAYU','Zobraz uæivatele');

//base_footer.php
DEFINE('_FOOTER',' (by <A class="largemenuitem" href="mailto:base@secureideas.net">Kevin Johnson</A> and the <A class="largemenuitem" href="http://sourceforge.net/project/memberlist.php?group_id=103348">BASE Project Team</A><BR>Built on ACID by Roman Danyliw )');

//index.php --Log in Page
DEFINE('_LOGINERROR','Uæivatel neexistuje nebo jste zadali πpatn√© '.strtolower($UI_CW_Pw).'!<br>Zkuste pros√≠m znovu.');

// base_main.php
DEFINE('_MOSTRECENT','Posledn√≠ch ');
DEFINE('_MOSTFREQUENT','NejËastÏjπ√≠ch ');
DEFINE('_ALERTS',' alarm˘:');
DEFINE('_ADDRESSES',' adres:');
DEFINE('_ANYPROTO','jak√Ωkoliv<br>protokol');
DEFINE('_UNI','unik√°tn√≠');
DEFINE('_LISTING','v√Ωpis');
DEFINE('_TALERTS','Dneπn√≠ alarmy: ');
DEFINE('_SOURCEIP','Source IP'); //NEW
DEFINE('_DESTIP','Destination IP'); //NEW
DEFINE('_L24ALERTS','Alarmy za posledn√≠ch 24 hodin: ');
DEFINE('_L72ALERTS','Alarmy za posledn√≠ch 72 hodin: ');
DEFINE('_UNIALERTS','unik√°tn√≠ch alarm˘');
DEFINE('_LSOURCEPORTS','Posledn√≠ zdrojov√© porty: ');
DEFINE('_LDESTPORTS','Posledn√≠ c√≠lov√© porty: ');
DEFINE('_FREGSOURCEP','NejËastÏjπ√≠ zdrojov√© porty: ');
DEFINE('_FREGDESTP','NejËastÏjπ√≠ c√≠lov√© porty: ');
DEFINE('_QUERIED','Dot√°z√°no ');
DEFINE('_DATABASE','Datab√°ze:');
DEFINE('_SCHEMAV','Verze sch√©matu:');
DEFINE('_TIMEWIN','»asov√© rozmez√≠:');
DEFINE('_NOALERTSDETECT','Æ√°dn√© alarmy dezjiπtÏny');
DEFINE('_USEALERTDB','Use Alert Database'); //NEW
DEFINE('_USEARCHIDB','Use Archive Database'); //NEW
DEFINE('_TRAFFICPROBPRO','Traffic Profile by Protocol'); //NEW

//base_auth.inc.php
DEFINE('_ADDEDSF','⁄spÏπnÏ p¯id√°n');
DEFINE('_NOPWDCHANGE','Nelze zmÏnit '.strtolower($UI_CW_Pw).': ');
DEFINE('_NOUSER','Uæivatel neexistuje!');
DEFINE('_OLDPWD','Aktu√°ln√≠ '.strtolower($UI_CW_Pw).' nen√≠ spr√°vn√©!');
DEFINE('_PWDCANT','Nelze zmÏnit '.strtolower($UI_CW_Pw).': ');
DEFINE('_PWDDONE',"$UI_CW_Pw bylo zmÏnÏno.");
DEFINE('_ROLEEXIST',"$UI_CW_Role existuje");
// TD Migration Hack
if ($UI_Spacing == 1){
	$glue = ' ';
}else{
	$glue = '';
}
DEFINE('_ROLEIDEXIST',implode($glue, $UI_AD_RID)." existuje");
DEFINE('_ROLEADDED',"$UI_CW_Role p¯id√°na ˙spÏπnÏ");

//base_roleadmin.php
DEFINE('_ROLEADMIN','Spr√°va rol√≠ BASE');
DEFINE('_FRMROLENAME',"Jm√©no $UI_CW_Role:");
DEFINE('_UPDATEROLE',"Update $UI_CW_Role"); //NEW

//base_useradmin.php
DEFINE('_USERADMIN','Spr√°va uæivatel˘ BASE');
DEFINE('_FRMFULLNAME','Cel√© jm√©no:');
DEFINE('_FRMUID','ID uæivatele:');
DEFINE('_SUBMITQUERY','Submit Query'); //NEW
DEFINE('_UPDATEUSER','Update User'); //NEW

//admin/index.php
DEFINE('_BASEADMIN','Administrace BASE');
DEFINE('_BASEADMINTEXT','Zvolte pros√≠m operaci nalevo.');

//base_action.inc.php
DEFINE('_NOACTION','Nebyla specifikov√°na operace');
DEFINE('_INVALIDACT',' je neplatn√° operace');
DEFINE('_ERRNOAG','Nemohu p¯idat alarmy; nebyla specifikov√°na skupina');
DEFINE('_ERRNOEMAIL','Nemohu zaslat alarmy poπtou; nebyla specifikov√°na emailov√° adresa');
DEFINE('_ACTION','Operace');
DEFINE('_CONTEXT','kontext');
DEFINE('_ADDAGID','P¯idat do skupiny (podle ID)');
DEFINE('_ADDAG','P¯idat do novÏ vytvo¯en√© skupiny'); // not used
DEFINE('_ADDAGNAME','P¯idat do skupiny (podle jm√©na)');
DEFINE('_CREATEAG','P¯idat do novÏ vytvo¯en√© skupiny');
DEFINE('_CLEARAG','Vymazat se skupiny');
DEFINE('_DELETEALERT','Smazat');
DEFINE('_EMAILALERTSFULL','Zaslat emailem (detailn√≠)');
DEFINE('_EMAILALERTSSUMM','Zaslat emailem (shrnut√≠)');
DEFINE('_EMAILALERTSCSV','Zaslat emailem (csv)');
DEFINE('_ARCHIVEALERTSCOPY','Archivovat (vytvo¯it kopii)');
DEFINE('_ARCHIVEALERTSMOVE','Archivovat (p¯esunout)');
DEFINE('_IGNORED','Ignorov√°no');
DEFINE('_DUPALERTS',' duplicitn√≠ alarm(y)');
DEFINE('_ALERTSPARA',' alarm(y)');
DEFINE('_NOALERTSSELECT','Æ√°dn√© alarmy nebyly vybr√°ny nebo');
DEFINE('_NOTSUCCESSFUL','nebyla ˙spÏπn√°');
DEFINE('_ERRUNKAGID','Zad√°no nezn√°m√© ID skupiny (skupina pravdÏpodobnÏ neexistuje)');
DEFINE('_ERRREMOVEFAIL','Selhalo odstranÏn√≠ nov√© skupiny');
DEFINE('_GENBASE','Vytvo¯eno BASE');
DEFINE('_ERRNOEMAILEXP','Chyba p¯i exportov√°n√≠: Nemohu poslat alarmy');
DEFINE('_ERRNOEMAILPHP','Zkontrolujte konfiguraci emailu PHP.');
DEFINE('_ERRDELALERT','Chyba p¯i maz√°n√≠ alarmu');
DEFINE('_ERRARCHIVE','Chyba p¯i archivaci:');
DEFINE('_ERRMAILNORECP','Chyba p¯i zas√≠l√°n√≠ emailem: Nebyl zad√°n p¯√≠jemce');

//base_cache.inc.php
DEFINE('_ADDED','P¯id√°no ');
DEFINE('_HOSTNAMESDNS',' jmen do IP DNS vyrovn√°vac√≠ pamÏti');
DEFINE('_HOSTNAMESWHOIS',' jmen do Whois vyrovn√°vac√≠ pamÏti');
DEFINE('_ERRCACHENULL','Chyba p¯i aktualizaci vyrovn√°vac√≠ pamÏti: nalezena NULL ¯√°dka event?');
DEFINE('_ERRCACHEERROR','Chyba p¯i aktualizaci vyrovn√°vac√≠ pamÏti:');
DEFINE('_ERRCACHEUPDATE','Nemohu aktualizovat vyrovn√°vac√≠ pamÏª');
DEFINE('_ALERTSCACHE',' alarm˘ do vyrovn√°vac√≠ pamÏti');

//base_db.inc.php
DEFINE('_ERRSQLTRACE','Nemohu otev¯√≠t soubor pro trasov√°n√≠ SQL');
DEFINE('_ERRSQLCONNECT','Chyba p¯i p¯ipojov√°n√≠ datab√°ze:');
DEFINE('_ERRSQLCONNECTINFO','<P>Zkontrolujte promÏnn√© pro p¯ipojov√°n√≠ se do datab√°ze v souboru <I>base_conf.php</I> 
              <PRE>
               = $alert_dbname   : jm√©no datab√°ze 
               = $alert_host     : hostitel
               = $alert_port     : port
               = $alert_user     : uæivatelsk√© jm√©no
               = $alert_password : '.strtolower($UI_CW_Pw).'
              </PRE>
              <P>');
DEFINE('_ERRSQLPCONNECT','Chyba p¯i p¯ipojov√°n√≠ datab√°ze:');
DEFINE('_ERRSQLDB','Datab√°zov√° chyba:');
DEFINE('_DBALCHECK','Kontraoluje knihovnu pro pr√°ci s datab√°z√≠ v');
DEFINE('_ERRSQLDBALLOAD1','<P><B>Chyba p¯i naË√≠t√°n√≠ knihovny pro pr√°ci s datab√°z√≠: </B> od ');
DEFINE('_ERRSQLDBALLOAD2','<P>Zkontrolujte promÏnnou pro urËen√≠ cesty ke knihovnÏ pro pr√°ci s datab√°z√≠ <CODE>$DBlib_path</CODE> v souboru <CODE>base_conf.php</CODE>
            <P>Knihovnu pro pr√°ci s datab√°z√≠ ADODB st√°hnÏte z
            ');
DEFINE('_ERRSQLDBTYPE','Specifikov√°n neplatn√Ω typ datab√°ze');
DEFINE('_ERRSQLDBTYPEINFO1','PromÏnn√° <CODE>\$DBtype</CODE> v souboru <CODE>base_conf.php</CODE> byla πpatnÏ nastavena na ');
DEFINE('_ERRSQLDBTYPEINFO2','Podporov√°ny jsou pouze n√°sleduj√≠c√≠ datab√°zov√© syst√©my: <PRE>
                MySQL         : \'mysql\'
                PostgreSQL    : \'postgres\'
                MS SQL Server : \'mssql\'
                MS SQL Server : \'mssql\'
                Oracle        : \'oci8\'
             </PRE>');

//base_log_error.inc.php
DEFINE('_ERRBASEFATAL','Kritick√° chyba BASE:');

//base_log_timing.inc.php
DEFINE('_LOADEDIN','NaËteno za');
DEFINE('_SECONDS','vte¯in');

//base_net.inc.php
DEFINE('_ERRRESOLVEADDRESS','Nemohu p¯eloæit adresu');

//base_output_query.inc.php
DEFINE('_QUERYRESULTSHEADER','Z√°hlav√≠ v√Ωsledk˘ dotazu'); //not used

//base_signature.inc.php
DEFINE('_ERRSIGNAMEUNK','nezn√°m√© SigName');
DEFINE('_ERRSIGPROIRITYUNK','nezn√°m√© SigPriority');
DEFINE('_UNCLASS','neza¯azeno');

//base_state_citems.inc.php
DEFINE('_DENCODED','data zakÛdÛv√°na jako');
DEFINE('_NODENCODED','(æ√°dn√° konverze dat, p¯edpokl√°d√°m poæadavek ve v√Ωchoz√≠m form√°tu datab√°ze)');
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
DEFINE('_PHPSESSREG','Sezen√≠ zaregistrov√°no');

//base_state_criteria.inc.php
DEFINE('_REMOVE','Odstranit');
DEFINE('_FROMCRIT','z krit√©ri√≠');
DEFINE('_ERRCRITELEM','Neplatn√Ω elemt krit√©ria');

//base_state_query.inc.php
DEFINE('_VALIDCANNED','Platn√Ω z√°kladn√≠ dotaz');
DEFINE('_DISPLAYING','Zobrazuji');
DEFINE('_DISPLAYINGTOTAL','Zobrazuji alarmy %d-%d z %d celkem');
DEFINE('_NOALERTS','Æ√°dn√© alarmy nenalezeny.');
DEFINE('_QUERYRESULTS','V√Ωsledky dotazu');
DEFINE('_QUERYSTATE','Stav dotazu');
DEFINE('_DISPACTION','{ action }'); //NEW

//base_ag_common.php
DEFINE('_ERRAGNAMESEARCH','Jmenovanou skupinu nelze nal√©zt. Zkuste to znovu.');
DEFINE('_ERRAGNAMEEXIST','Zadan√° skupina neexistuje.');
DEFINE('_ERRAGIDSEARCH','Skupinu urËenou ID nelze nal√©zt. Zkuste to znovu.');
DEFINE('_ERRAGLOOKUP','Chyba p¯i vyhled√°v√°n√≠ skupiny dle ID');
DEFINE('_ERRAGINSERT','Chyba p¯i vkl√°d√°n√≠ nov√© skupiny');

//base_ag_main.php
DEFINE('_AGMAINTTITLE','Spr√°va skupin');
DEFINE('_ERRAGUPDATE','Chyba p¯i aktualizaci skupiny');
DEFINE('_ERRAGPACKETLIST','Chyba p¯i maz√°n√≠ obsahu skupiny:');
DEFINE('_ERRAGDELETE','Chyba p¯i maz√°n√≠ skupiny');
DEFINE('_AGDELETE','smaz√°na ˙spÏπnÏ');
DEFINE('_AGDELETEINFO','informace smaz√°na');
DEFINE('_ERRAGSEARCHINV','Zadan√© vyhled√°vac√≠ krit√©rium je neplatn√©. Zkuste to znovu.');
DEFINE('_ERRAGSEARCHNOTFOUND','Æ√°dn√° skupiny s t√≠mto krit√©riem nenalezena.');
DEFINE('_NOALERTGOUPS','Nejsou definov√°ny æ√°dn√© skupiny');
DEFINE('_NUMALERTS','poËet alarm˘');
DEFINE('_ACTIONS','Akce');
DEFINE('_NOTASSIGN','jeπtÏ nep¯i¯azeno');
DEFINE('_SAVECHANGES','Save Changes'); //NEW
DEFINE('_CONFIRMDELETE','Confirm Delete'); //NEW
DEFINE('_CONFIRMCLEAR','Confirm Clear'); //NEW

//base_common.php
DEFINE('_PORTSCAN','Provoz skenov√°n√≠ port˘');

//base_db_common.php
DEFINE('_ERRDBINDEXCREATE','Nemohu vytvo¯it index pro');
DEFINE('_DBINDEXCREATE','⁄spÏπnÏ vytvo¯en index pro');
DEFINE('_ERRSNORTVER','M˘æe se jednat o starπ√≠ verzi. Podporov√°ny jsou pouze datab√°ze vytvo¯en√© Snort 1.7-beta0 nebo novÏjπ√≠m');
DEFINE('_ERRSNORTVER1','Z√°kladn√≠ datab√°ze');
DEFINE('_ERRSNORTVER2','se zd√° nekompletn√≠ nebo neplatn√°');
DEFINE('_ERRDBSTRUCT1','Verze datab√°ze je spr√°vn√°, ale neobsahuje');
DEFINE('_ERRDBSTRUCT2','BASE tabulky. Pouæijte <A HREF="base_db_setup.php">InicializaËn√≠ str√°nku</A> pro nastaven√≠ a optimalizaci datab√°ze.');
DEFINE('_ERRPHPERROR','Chyba PHP');
DEFINE('_ERRPHPERROR1','Nekompatibiln√≠ verze');
DEFINE('_ERRVERSION','Verze');
DEFINE('_ERRPHPERROR2','PHP je p¯√≠liπ star√°. ProveÔte pros√≠m aktualizaci na verzi 4.0.4 nebo pozdÏjπ√≠');
DEFINE('_ERRPHPMYSQLSUP','<B>PHP podpora nen√≠ kompletn√≠</B>: <FONT>podpora pro pr√°ci s MySQL 
               datab√°z√≠ nen√≠ souË√°st√≠ instalace.
               Pros√≠m p¯einstalujte PHP s pot¯ebnou knihovnou (<CODE>--with-mysql</CODE>)</FONT>');
DEFINE('_ERRPHPPOSTGRESSUP','<B>PHP podpora nen√≠ kompletn√≠</B>: <FONT>podpora pro pr√°ci s PostgreSQL
               datab√°z√≠ nen√≠ souË√°st√≠ instalace.
               Pros√≠m p¯einstalujte PHP s pot¯ebnou knihovnou (<CODE>--with-pgsql</CODE>)</FONT>');
DEFINE('_ERRPHPMSSQLSUP','<B>PHP podpora nen√≠ kompletn√≠</B>: <FONT>podpora pro pr√°ci s MS SQL
               datab√°z√≠ nen√≠ souË√°st√≠ instalace.
               Pros√≠m p¯einstalujte PHP s pot¯ebnou knihovnou (<CODE>--enable-mssql</CODE>)</FONT>');
DEFINE('_ERRPHPORACLESUP','<B>PHP podpora nen√≠ kompletn√≠</B>: <FONT>podpora pro pr√°ci s Oracle
               datab√°z√≠ nen√≠ souË√°st√≠ instalace.
               Pros√≠m p¯einstalujte PHP s pot¯ebnou knihovnou (<CODE>--with-oci8</CODE>)</FONT>');

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
DEFINE('_CHARTMONTH','{m√™s}'); //NEW
DEFINE('_GRAPHALERTS','Graph Alerts'); //NEW
DEFINE('_AXISCONTROLS','X / Y AXIS CONTROLS'); //NEW
DEFINE('_CHRTTYPEHOUR','»as (hodiny) proti poËtu alarm˘');
DEFINE('_CHRTTYPEDAY','»as (dny) proti poËtu alarm˘');
DEFINE('_CHRTTYPEWEEK','»as (t√Ωdny) proti poËtu alarm˘');
DEFINE('_CHRTTYPEMONTH','»as (mÏs√≠ce) proti poËtu alarm˘');
DEFINE('_CHRTTYPEYEAR','»as (roky) proti poËtu alarm˘');
DEFINE('_CHRTTYPESRCIP','Zdrojov√° IP adresa proti poËtu alarm˘');
DEFINE('_CHRTTYPEDSTIP','C√≠lov√° IP adresa proti poËtu alarm˘');
DEFINE('_CHRTTYPEDSTUDP','C√≠lov√Ω UDP port proti poËtu alarm˘');
DEFINE('_CHRTTYPESRCUDP','Zdrojov√Ω UDP port proti poËtu alarm˘');
DEFINE('_CHRTTYPEDSTPORT','C√≠lov√Ω TCP port proti poËtu alarm˘');
DEFINE('_CHRTTYPESRCPORT','Zdrojov√Ω TCP port proti poËtu alarm˘');
DEFINE('_CHRTTYPESIG','Klasifikace podpis˘ proti poËtu alarm˘');
DEFINE('_CHRTTYPESENSOR','Senzor proti poËtu alarm˘');
DEFINE('_CHRTBEGIN','ZaË√°tek grafu:');
DEFINE('_CHRTEND','Konec grafu:');
DEFINE('_CHRTDS','Zdroj dat:');
DEFINE('_CHRTX','Osa X');
DEFINE('_CHRTY','Osa Y');
DEFINE('_CHRTMINTRESH','Minim√°ln√≠ hodnota');
DEFINE('_CHRTROTAXISLABEL','OtoËit popisky os o 90 stupÚ˘');
DEFINE('_CHRTSHOWX','Zobraz rastr pro osu X');
DEFINE('_CHRTDISPLABELX','Zobraz popis osy X kaæd√Ωch');
DEFINE('_CHRTDATAPOINTS','vzork˘ dat');
DEFINE('_CHRTYLOG','Osa Y logaritmick√°');
DEFINE('_CHRTYGRID','Zobraz rastr pro osu Y');

//base_graph_main.php
DEFINE('_CHRTTITLE','Graf BASE');
DEFINE('_ERRCHRTNOTYPE','Nebyl urËen typ grafu');
DEFINE('_ERRNOAGSPEC','Nebyla urËena skupiny. Pouæ√≠v√°m vπechny alarmy.');
DEFINE('_CHRTDATAIMPORT','ZaË√≠n√°m naË√≠tat data'); 
DEFINE('_CHRTTIMEVNUMBER','»as port proti poËtu alarm˘');
DEFINE('_CHRTTIME','»as');
DEFINE('_CHRTALERTOCCUR','V√Ωskyty alarm˘');
DEFINE('_CHRTSIPNUMBER','Zdrojov√° IP adresa proti poËtu alarm˘');
DEFINE('_CHRTSIP','Zdrojov√° IP adresa');
DEFINE('_CHRTDIPALERTS','C√≠lov√° IP adresa proti poËtu alarm˘');
DEFINE('_CHRTDIP','C√≠lov√° IP adresa');
DEFINE('_CHRTUDPPORTNUMBER','UDP port (c√≠l) port proti poËtu alarm˘');
DEFINE('_CHRTDUDPPORT','C√≠lov√Ω UDP port');
DEFINE('_CHRTSUDPPORTNUMBER','UDP port (zdroj) port proti poËtu alarm˘');
DEFINE('_CHRTSUDPPORT','Zdrojov√Ω UDP port');
DEFINE('_CHRTPORTDESTNUMBER','TCP port (c√≠l) port proti poËtu alarm˘');
DEFINE('_CHRTPORTDEST','C√≠lov√Ω TCP port');
DEFINE('_CHRTPORTSRCNUMBER','TCP port (zdroj) port proti poËtu alarm˘');
DEFINE('_CHRTPORTSRC','Zdrojov√Ω TCP port');
DEFINE('_CHRTSIGNUMBER','Klasifikace podpis˘ proti poËtu alarm˘');
DEFINE('_CHRTCLASS','Klasifikace');
DEFINE('_CHRTSENSORNUMBER','Senzor port proti poËtu alarm˘');
DEFINE('_CHRTHANDLEPERIOD','Rozhodn√© obdob√≠ (pokud je t¯eba)');
DEFINE('_CHRTDUMP','Vypisuji data ... (zobrazuji jen kaæd√©');
DEFINE('_CHRTDRAW','Kresl√≠m graf');
DEFINE('_ERRCHRTNODATAPOINTS','Pro vykreslen√≠ nejsou k dispozici æ√°dn√° data');
DEFINE('_GRAPHALERTDATA','Graph Alert Data'); //NEW

//base_maintenance.php
DEFINE('_MAINTTITLE','⁄dræba');
DEFINE('_MNTPHP','PHP popis:');
DEFINE('_MNTCLIENT','CLIENT:');
DEFINE('_MNTSERVER','SERVER:');
DEFINE('_MNTSERVERHW','SERVER HW:');
DEFINE('_MNTPHPVER','PHP VERZE:');
DEFINE('_MNTPHPAPI','PHP API:');
DEFINE('_MNTPHPLOGLVL','⁄roveÚ hl√°πen√≠ PHP:');
DEFINE('_MNTPHPMODS','Nahran√© moduly:');
DEFINE('_MNTDBTYPE','Typ datab√°ze:');
DEFINE('_MNTDBALV','Verze podp˘rn√© datab√°zov√© knihovny:');
DEFINE('_MNTDBALERTNAME','Jm√©no ALERT datab√°ze:');
DEFINE('_MNTDBARCHNAME','Jm√©no ARCHIVE datab√°ze:');
DEFINE('_MNTAIC','Vyrovn√°vac√≠ pamÏª alarm˘:');
DEFINE('_MNTAICTE','Celkov√Ω poËet ud√°lost√≠:');
DEFINE('_MNTAICCE','Ud√°losti ve vyrovn√°vac√≠ pamÏti:');
DEFINE('_MNTIPAC','Vyrovn√°vac√≠ pamÏª IP address');
DEFINE('_MNTIPACUSIP','Unik√°tn√≠ zdrojov√© IP:');
DEFINE('_MNTIPACDNSC','DNS ve vyrovn√°vac√≠ pamÏti:');
DEFINE('_MNTIPACWC','Whois ve vyrovn√°vac√≠ pamÏti:');
DEFINE('_MNTIPACUDIP','Unik√°tn√≠ c√≠lov√© IP:');

//base_qry_alert.php
DEFINE('_QAINVPAIR','Neplatn√Ω p√°r (sid,cid)');
DEFINE('_QAALERTDELET','Alarm smaz√°n');
DEFINE('_QATRIGGERSIG','Detekovan√Ω podpis alarmu');
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
DEFINE('_QCERRCRITWARN','Varov√°n√≠ vyhled√°vac√≠ch krit√©ri√≠:');
DEFINE('_QCERRVALUE','Hodnota');
DEFINE('_QCERRFIELD','Pole');
DEFINE('_QCERROPER','Oper√°tor');
DEFINE('_QCERRDATETIME','Hodnota datum/Ëas');
DEFINE('_QCERRPAYLOAD','Hodnota obsahu');
DEFINE('_QCERRIP','IP adresa');
DEFINE('_QCERRIPTYPE','IP adresa typu');
DEFINE('_QCERRSPECFIELD','bylo zad√°no pole protokolu, ale nebyla urËena hodnota.');
DEFINE('_QCERRSPECVALUE','bylo vybr√°no, ale nebyla urËena hodnota.');
DEFINE('_QCERRBOOLEAN','V√≠ce pol√≠ pro urËen√≠ protokolu bylo zad√°no, ale nebyl mezi nimi zad√°n logick√Ω oper√°tor (AND, OR).');
DEFINE('_QCERRDATEVALUE','bylo zvoleno, æe se m√° vyhled√°vat podle data/Ëasu, ale nebyla urËena hodnota.');
DEFINE('_QCERRINVHOUR','(Neplatn√° hodina) Æ√°dn√© krit√©rium pro urËen√≠ data/Ëasu neodpov√≠d√° urËen√©mu Ëasu.');
DEFINE('_QCERRDATECRIT','bylo zvoleno, æe se m√° vyhled√°vat podle data/Ëasu, ale nebyla urËena hodnota.');
DEFINE('_QCERROPERSELECT','bylo vloæeno, ale nebyl zvolen æ√°dn√Ω oper√°tor.');
DEFINE('_QCERRDATEBOOL','V√≠ce krit√©ri√≠ datum/Ëas bylo zad√°no bez urËen√≠ logick√©ho oper√°toru (AND, OR) mezi nimi.');
DEFINE('_QCERRPAYCRITOPER','byl urËen obsah, kter√Ω se m√° vyhled√°vat, ale nebylo zvoleno, zda m√° b√Ωt obsaæen nebo ne.');
DEFINE('_QCERRPAYCRITVALUE','bylo urËeno, æe se m√° vyhled√°vat podle obsahu, ale nebyla urËena hodnota.');
DEFINE('_QCERRPAYBOOL','V√≠ce krit√©ri√≠ obsahu bylo zad√°no bez urËen√≠t logick√©ho oper√°toru (AND, OR) mezi nimi.');
DEFINE('_QCMETACRIT','Meta krit√°ria');
DEFINE('_QCIPCRIT','IP krit√©ria');
DEFINE('_QCPAYCRIT','Obsahov√° krit√©ria');
DEFINE('_QCTCPCRIT','TCP krit√©ria');
DEFINE('_QCUDPCRIT','UDP krit√©ria');
DEFINE('_QCICMPCRIT','ICMP krit√©ria');
DEFINE('_QCLAYER4CRIT','Layer 4 Criteria'); //NEW
DEFINE('_QCERRINVIPCRIT','Neplatn√© krit√©rium IP adresy');
DEFINE('_QCERRCRITADDRESSTYPE','byla zvolena jako krit√©rium, ale nebylo urËeno, zda se jedn√° o zdrojovou nebo c√≠lovou adresu.');
DEFINE('_QCERRCRITIPADDRESSNONE','ukazuj√≠c, æe IP adresa m√° b√Ωt krit√©riem, ale nebyla urËena hodnota.');
DEFINE('_QCERRCRITIPADDRESSNONE1','bylo vybr√°no (v #');
DEFINE('_QCERRCRITIPIPBOOL','V√≠ce krit√©ri√≠ pro IP adresy bylo zad√°no bez urËen√≠ logick√©ho oper√°toru (AND, OR) mezi nimi.');

//base_qry_form.php
DEFINE('_QFRMSORTORDER','SmÏr t¯√≠dÏn√≠');
DEFINE('_QFRMSORTNONE','none'); //NEW
DEFINE('_QFRMTIMEA','Ëas (vzestupnÏ)');
DEFINE('_QFRMTIMED','Ëas (sestupnÏ)');
DEFINE('_QFRMSIG','podpis');
DEFINE('_QFRMSIP','zdrojov√° IP adresa');
DEFINE('_QFRMDIP','c√≠lov√° IP adresa');

//base_qry_sqlcalls.php
DEFINE('_QSCSUMM','Souhrn√© statistiky');
DEFINE('_QSCTIMEPROF','Profil v Ëase');
DEFINE('_QSCOFALERTS','z alarm˘');

//base_stat_alerts.php
DEFINE('_ALERTTITLE','V√Ωpis alarm˘');

//base_stat_common.php
DEFINE('_SCCATEGORIES','Kategorie:');
DEFINE('_SCSENSORTOTAL','Senzory/Celkem:');
DEFINE('_SCTOTALNUMALERTS','Celkov√Ω poËet alarm˘:');
DEFINE('_SCSRCIP','Zdrojov√Ωch IP adres:');
DEFINE('_SCDSTIP','C√≠lov√Ωch IP adres:');
DEFINE('_SCUNILINKS','Unik√°tn√≠ch IP spoj˘');
DEFINE('_SCSRCPORTS','Zdrojov√Ωch port˘: ');
DEFINE('_SCDSTPORTS','C√≠lov√Ωch port˘: ');
DEFINE('_SCSENSORS','Senzor˘');
DEFINE('_SCCLASS','Klasifikace');
DEFINE('_SCUNIADDRESS','Unik√°tn√≠ch adres: ');
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
DEFINE('_SIPLSOURCEFGDN','Zdrojov√© FQDN');
DEFINE('_SIPLDESTFGDN','C√≠lov√© FQDN');
DEFINE('_SIPLDIRECTION','SmÏr');
DEFINE('_SIPLPROTO','Protokol');
DEFINE('_SIPLUNIDSTPORTS','Unik√°tn√≠ch c√≠lov√Ωch port˘');
DEFINE('_SIPLUNIEVENTS','Unik√°tn√≠ch alarm˘');
DEFINE('_SIPLTOTALEVENTS','Celkem alarm˘');

//base_stat_ports.php
DEFINE('_UNIQ','Unik√°tn√≠');
DEFINE('_DSTPS','c√≠lov√© porty');
DEFINE('_SRCPS','zdrojov√© porty');
DEFINE('_OCCURRENCES','Occurrences'); //NEW

//base_stat_sensor.php
DEFINE('SPSENSORLIST','V√Ωpis senzor˘');

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
DEFINE('_UNISADD','Unik√°tn√≠ zdrojov√© IP adresy');
DEFINE('_SUASRCIP','Zdrojov√° IP adresa');
DEFINE('_SUAERRCRITADDUNK','chyba v krit√©riu: nezn√°m√Ω typ adresy -- p¯edpokl√°d√°m c√≠lovou');
DEFINE('_UNIDADD','Unik√°tn√≠ c√≠lov√© IP adresy');
DEFINE('_SUADSTIP','C√≠lov√° IP adresa');
DEFINE('_SUAUNIALERTS','Unik√°tn√≠ch alarm˘');
DEFINE('_SUASRCADD','Zdrojov√Ωch adres');
DEFINE('_SUADSTADD','C√≠lov√Ωch adres');

//base_user.php
DEFINE('_BASEUSERTITLE','Uæivatelsk√© p¯edvolby BASE');
DEFINE('_BASEUSERERRPWD',"$UI_CW_Pw nesm√≠ b√Ωt pr√°zn√© nebo ".strtolower($UI_CW_Pw).' nesouhlas√≠!');
DEFINE('_BASEUSEROLDPWD','Star√© '.strtolower($UI_CW_Pw).':');
DEFINE('_BASEUSERNEWPWD','Nov√© '.strtolower($UI_CW_Pw).':');
DEFINE('_BASEUSERNEWPWDAGAIN','Nov√© '.strtolower($UI_CW_Pw).' znovu:');

DEFINE('_LOGOUT','Odhl√°sit');
?>
