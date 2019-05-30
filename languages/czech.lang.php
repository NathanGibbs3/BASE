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

//locale
DEFINE('_LOCALESTR1', 'eng_ENG.ISO8859-1'); //NEW
DEFINE('_LOCALESTR2', 'eng_ENG.utf-8'); //NEW
DEFINE('_LOCALESTR3', 'english'); //NEW
DEFINE('_STRFTIMEFORMAT','%a %B %d, %Y %H:%M:%S'); //NEW - see strftime() sintax

//common phrases
DEFINE('_CHARSET','iso-8859-2');
DEFINE('_TITLE','Basic Analysis and Security Engine (BASE) '.$BASE_installID);
DEFINE('_FRMLOGIN','Login:');
DEFINE('_FRMPWD','Heslo:');
DEFINE('_SOURCE','Zdroj');
DEFINE('_SOURCENAME','Jm�no zdoje');
DEFINE('_DEST','C�l');
DEFINE('_DESTNAME','Jm�no c�le');
DEFINE('_SORD','Zdroj n. c�l');
DEFINE('_EDIT','Upravit');
DEFINE('_DELETE','Smazat');
DEFINE('_ID','ID');
DEFINE('_NAME','Jm�no');
DEFINE('_INTERFACE','Rozhran�');
DEFINE('_FILTER','Filtr');
DEFINE('_DESC','Popis');
DEFINE('_LOGIN','Login');
DEFINE('_ROLEID','ID role');
DEFINE('_ENABLED','Enabled');
DEFINE('_SUCCESS','Successful');
DEFINE('_SENSOR','Senzor');
DEFINE('_SENSORS','Sensors'); //NEW
DEFINE('_SIGNATURE','Podpis');
DEFINE('_TIMESTAMP','�asov� zna�ka');
DEFINE('_NBSOURCEADDR','Zdrojov�&nbsp;adresa');
DEFINE('_NBDESTADDR','C�lov�&nbsp;adresa');
DEFINE('_NBLAYER4','Protokol 4. vrstvy');
DEFINE('_PRIORITY','Priorita');
DEFINE('_EVENTTYPE','typ ud�losti');
DEFINE('_JANUARY','Leden');
DEFINE('_FEBRUARY','�nor');
DEFINE('_MARCH','B�ezen');
DEFINE('_APRIL','Duben');
DEFINE('_MAY','Kv�ten');
DEFINE('_JUNE','�erven');
DEFINE('_JULY','�ervenec');
DEFINE('_AUGUST','Srpen');
DEFINE('_SEPTEMBER','Z���');
DEFINE('_OCTOBER','��jen');
DEFINE('_NOVEMBER','Listopad');
DEFINE('_DECEMBER','Prosinec');
DEFINE('_LAST','Posledn�');
DEFINE('_FIRST','First'); //NEW
DEFINE('_TOTAL','Total'); //NEW
DEFINE('_ALERT','Alarm');
DEFINE('_ADDRESS','Adresa');
DEFINE('_UNKNOWN','nezn�m�');
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
DEFINE('_AGMAINT','Spr�va skupin alarm�');
DEFINE('_USERPREF','U�ivatelsk� volby');
DEFINE('_CACHE','Ke� a stav');
DEFINE('_ADMIN','Administrace');
DEFINE('_GALERTD','Vytvo�it graf alarm�');
DEFINE('_GALERTDT','Vytvo�it graf �asu detekce alarm�');
DEFINE('_USERMAN','Spr�va u�ivatel�');
DEFINE('_LISTU','Seznam u�ivatel�');
DEFINE('_CREATEU','Vytvo�it u�ivatele');
DEFINE('_ROLEMAN','Spr�va rol�');
DEFINE('_LISTR','Seznam rol�');
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
DEFINE('_CHNGPWD','Zm�nit heslo');
DEFINE('_DISPLAYU','Zobraz u�ivatele');

//base_footer.php
DEFINE('_FOOTER',' (by <A class="largemenuitem" href="mailto:base@secureideas.net">Kevin Johnson</A> and the <A class="largemenuitem" href="http://sourceforge.net/project/memberlist.php?group_id=103348">BASE Project Team</A><BR>Built on ACID by Roman Danyliw )');

//index.php --Log in Page
DEFINE('_LOGINERROR','U�ivatel neexistuje nebo jste zadali �patn� heslo!<br>Zkuste pros�m znovu.');

// base_main.php
DEFINE('_MOSTRECENT','Posledn�ch ');
DEFINE('_MOSTFREQUENT','Nej�ast�j��ch ');
DEFINE('_ALERTS',' alarm�:');
DEFINE('_ADDRESSES',' adres:');
DEFINE('_ANYPROTO','jak�koliv<br>protokol');
DEFINE('_UNI','unik�tn�');
DEFINE('_LISTING','v�pis');
DEFINE('_TALERTS','Dne�n� alarmy: ');
DEFINE('_SOURCEIP','Source IP'); //NEW
DEFINE('_DESTIP','Destination IP'); //NEW
DEFINE('_L24ALERTS','Alarmy za posledn�ch 24 hodin: ');
DEFINE('_L72ALERTS','Alarmy za posledn�ch 72 hodin: ');
DEFINE('_UNIALERTS','unik�tn�ch alarm�');
DEFINE('_LSOURCEPORTS','Posledn� zdrojov� porty: ');
DEFINE('_LDESTPORTS','Posledn� c�lov� porty: ');
DEFINE('_FREGSOURCEP','Nej�ast�j�� zdrojov� porty: ');
DEFINE('_FREGDESTP','Nej�ast�j�� c�lov� porty: ');
DEFINE('_QUERIED','Dot�z�no ');
DEFINE('_DATABASE','Datab�ze:');
DEFINE('_SCHEMAV','Verze sch�matu:');
DEFINE('_TIMEWIN','�asov� rozmez�:');
DEFINE('_NOALERTSDETECT','��dn� alarmy dezji�t�ny');
DEFINE('_USEALERTDB','Use Alert Database'); //NEW
DEFINE('_USEARCHIDB','Use Archive Database'); //NEW
DEFINE('_TRAFFICPROBPRO','Traffic Profile by Protocol'); //NEW

//base_auth.inc.php
DEFINE('_ADDEDSF','�sp�n� p�id�n');
DEFINE('_NOPWDCHANGE','Nelze zm�nit heslo: ');
DEFINE('_NOUSER','U�ivatel neexistuje!');
DEFINE('_OLDPWD','Aktu�ln� heslo nen� spr�vn�!');
DEFINE('_PWDCANT','Nelze zm�nit heslo: ');
DEFINE('_PWDDONE','Heslo bylo zm�n�no.');
DEFINE('_ROLEEXIST','Role existuje');
DEFINE('_ROLEIDEXIST','ID role existuje');
DEFINE('_ROLEADDED','Role p�id�na �sp�n�');

//base_roleadmin.php
DEFINE('_ROLEADMIN','Spr�va rol� BASE');
DEFINE('_FRMROLEID','ID role:');
DEFINE('_FRMROLENAME','Jm�no role:');
DEFINE('_FRMROLEDESC','Popis:');
DEFINE('_UPDATEROLE','Update Role'); //NEW

//base_useradmin.php
DEFINE('_USERADMIN','Spr�va u�ivatel� BASE');
DEFINE('_FRMFULLNAME','Cel� jm�no:');
DEFINE('_FRMROLE','Role:');
DEFINE('_FRMUID','ID u�ivatele:');
DEFINE('_SUBMITQUERY','Submit Query'); //NEW
DEFINE('_UPDATEUSER','Update User'); //NEW

//admin/index.php
DEFINE('_BASEADMIN','Administrace BASE');
DEFINE('_BASEADMINTEXT','Zvolte pros�m operaci nalevo.');

//base_action.inc.php
DEFINE('_NOACTION','Nebyla specifikov�na operace');
DEFINE('_INVALIDACT',' je neplatn� operace');
DEFINE('_ERRNOAG','Nemohu p�idat alarmy; nebyla specifikov�na skupina');
DEFINE('_ERRNOEMAIL','Nemohu zaslat alarmy po�tou; nebyla specifikov�na emailov� adresa');
DEFINE('_ACTION','Operace');
DEFINE('_CONTEXT','kontext');
DEFINE('_ADDAGID','P�idat do skupiny (podle ID)');
DEFINE('_ADDAG','P�idat do nov� vytvo�en� skupiny'); // not used
DEFINE('_ADDAGNAME','P�idat do skupiny (podle jm�na)');
DEFINE('_CREATEAG','P�idat do nov� vytvo�en� skupiny');
DEFINE('_CLEARAG','Vymazat se skupiny');
DEFINE('_DELETEALERT','Smazat');
DEFINE('_EMAILALERTSFULL','Zaslat emailem (detailn�)');
DEFINE('_EMAILALERTSSUMM','Zaslat emailem (shrnut�)');
DEFINE('_EMAILALERTSCSV','Zaslat emailem (csv)');
DEFINE('_ARCHIVEALERTSCOPY','Archivovat (vytvo�it kopii)');
DEFINE('_ARCHIVEALERTSMOVE','Archivovat (p�esunout)');
DEFINE('_IGNORED','Ignorov�no');
DEFINE('_DUPALERTS',' duplicitn� alarm(y)');
DEFINE('_ALERTSPARA',' alarm(y)');
DEFINE('_NOALERTSSELECT','��dn� alarmy nebyly vybr�ny nebo');
DEFINE('_NOTSUCCESSFUL','nebyla �sp�n�');
DEFINE('_ERRUNKAGID','Zad�no nezn�m� ID skupiny (skupina pravd�podobn� neexistuje)');
DEFINE('_ERRREMOVEFAIL','Selhalo odstran�n� nov� skupiny');
DEFINE('_GENBASE','Vytvo�eno BASE');
DEFINE('_ERRNOEMAILEXP','Chyba p�i exportov�n�: Nemohu poslat alarmy');
DEFINE('_ERRNOEMAILPHP','Zkontrolujte konfiguraci emailu PHP.');
DEFINE('_ERRDELALERT','Chyba p�i maz�n� alarmu');
DEFINE('_ERRARCHIVE','Chyba p�i archivaci:');
DEFINE('_ERRMAILNORECP','Chyba p�i zas�l�n� emailem: Nebyl zad�n p��jemce');

//base_cache.inc.php
DEFINE('_ADDED','P�id�no ');
DEFINE('_HOSTNAMESDNS',' jmen do IP DNS vyrovn�vac� pam�ti');
DEFINE('_HOSTNAMESWHOIS',' jmen do Whois vyrovn�vac� pam�ti');
DEFINE('_ERRCACHENULL','Chyba p�i aktualizaci vyrovn�vac� pam�ti: nalezena NULL ��dka event?');
DEFINE('_ERRCACHEERROR','Chyba p�i aktualizaci vyrovn�vac� pam�ti:');
DEFINE('_ERRCACHEUPDATE','Nemohu aktualizovat vyrovn�vac� pam�');
DEFINE('_ALERTSCACHE',' alarm� do vyrovn�vac� pam�ti');

//base_db.inc.php
DEFINE('_ERRSQLTRACE','Nemohu otev��t soubor pro trasov�n� SQL');
DEFINE('_ERRSQLCONNECT','Chyba p�i p�ipojov�n� datab�ze:');
DEFINE('_ERRSQLCONNECTINFO','<P>Zkontrolujte prom�nn� pro p�ipojov�n� se do datab�ze v souboru <I>base_conf.php</I> 
              <PRE>
               = $alert_dbname   : jm�no datab�ze 
               = $alert_host     : hostitel
               = $alert_port     : port
               = $alert_user     : u�ivatelsk� jm�no
               = $alert_password : heslo
              </PRE>
              <P>');
DEFINE('_ERRSQLPCONNECT','Chyba p�i p�ipojov�n� datab�ze:');
DEFINE('_ERRSQLDB','Datab�zov� chyba:');
DEFINE('_DBALCHECK','Kontraoluje knihovnu pro pr�ci s datab�z� v');
DEFINE('_ERRSQLDBALLOAD1','<P><B>Chyba p�i na��t�n� knihovny pro pr�ci s datab�z�: </B> od ');
DEFINE('_ERRSQLDBALLOAD2','<P>Zkontrolujte prom�nnou pro ur�en� cesty ke knihovn� pro pr�ci s datab�z� <CODE>$DBlib_path</CODE> v souboru <CODE>base_conf.php</CODE>
            <P>Knihovnu pro pr�ci s datab�z� ADODB st�hn�te z
            ');
DEFINE('_ERRSQLDBTYPE','Specifikov�n neplatn� typ datab�ze');
DEFINE('_ERRSQLDBTYPEINFO1','Prom�nn� <CODE>\$DBtype</CODE> v souboru <CODE>base_conf.php</CODE> byla �patn� nastavena na ');
DEFINE('_ERRSQLDBTYPEINFO2','Podporov�ny jsou pouze n�sleduj�c� datab�zov� syst�my: <PRE>
                MySQL         : \'mysql\'
                PostgreSQL    : \'postgres\'
                MS SQL Server : \'mssql\'
                MS SQL Server : \'mssql\'
                Oracle        : \'oci8\'
             </PRE>');

//base_log_error.inc.php
DEFINE('_ERRBASEFATAL','Kritick� chyba BASE:');

//base_log_timing.inc.php
DEFINE('_LOADEDIN','Na�teno za');
DEFINE('_SECONDS','vte�in');

//base_net.inc.php
DEFINE('_ERRRESOLVEADDRESS','Nemohu p�elo�it adresu');

//base_output_query.inc.php
DEFINE('_QUERYRESULTSHEADER','Z�hlav� v�sledk� dotazu'); //not used

//base_signature.inc.php
DEFINE('_ERRSIGNAMEUNK','nezn�m� SigName');
DEFINE('_ERRSIGPROIRITYUNK','nezn�m� SigPriority');
DEFINE('_UNCLASS','neza�azeno');

//base_state_citems.inc.php
DEFINE('_DENCODED','data zak�d�v�na jako');
DEFINE('_NODENCODED','(��dn� konverze dat, p�edpokl�d�m po�adavek ve v�choz�m form�tu datab�ze)');
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
DEFINE('_SIGCLASS','Signature Classification'); //NEW
DEFINE('_SIGPRIO','Signature Priority'); //NEW
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
DEFINE('_PHPSESSREG','Sezen� zaregistrov�no');

//base_state_criteria.inc.php
DEFINE('_REMOVE','Odstranit');
DEFINE('_FROMCRIT','z krit�ri�');
DEFINE('_ERRCRITELEM','Neplatn� elemt krit�ria');

//base_state_query.inc.php
DEFINE('_VALIDCANNED','Platn� z�kladn� dotaz');
DEFINE('_DISPLAYING','Zobrazuji');
DEFINE('_DISPLAYINGTOTAL','Zobrazuji alarmy %d-%d z %d celkem');
DEFINE('_NOALERTS','��dn� alarmy nenalezeny.');
DEFINE('_QUERYRESULTS','V�sledky dotazu');
DEFINE('_QUERYSTATE','Stav dotazu');
DEFINE('_DISPACTION','{ action }'); //NEW

//base_ag_common.php
DEFINE('_ERRAGNAMESEARCH','Jmenovanou skupinu nelze nal�zt. Zkuste to znovu.');
DEFINE('_ERRAGNAMEEXIST','Zadan� skupina neexistuje.');
DEFINE('_ERRAGIDSEARCH','Skupinu ur�enou ID nelze nal�zt. Zkuste to znovu.');
DEFINE('_ERRAGLOOKUP','Chyba p�i vyhled�v�n� skupiny dle ID');
DEFINE('_ERRAGINSERT','Chyba p�i vkl�d�n� nov� skupiny');

//base_ag_main.php
DEFINE('_AGMAINTTITLE','Spr�va skupin');
DEFINE('_ERRAGUPDATE','Chyba p�i aktualizaci skupiny');
DEFINE('_ERRAGPACKETLIST','Chyba p�i maz�n� obsahu skupiny:');
DEFINE('_ERRAGDELETE','Chyba p�i maz�n� skupiny');
DEFINE('_AGDELETE','smaz�na �sp�n�');
DEFINE('_AGDELETEINFO','informace smaz�na');
DEFINE('_ERRAGSEARCHINV','Zadan� vyhled�vac� krit�rium je neplatn�. Zkuste to znovu.');
DEFINE('_ERRAGSEARCHNOTFOUND','��dn� skupiny s t�mto krit�riem nenalezena.');
DEFINE('_NOALERTGOUPS','Nejsou definov�ny ��dn� skupiny');
DEFINE('_NUMALERTS','po�et alarm�');
DEFINE('_ACTIONS','Akce');
DEFINE('_NOTASSIGN','je�t� nep�i�azeno');
DEFINE('_SAVECHANGES','Save Changes'); //NEW
DEFINE('_CONFIRMDELETE','Confirm Delete'); //NEW
DEFINE('_CONFIRMCLEAR','Confirm Clear'); //NEW

//base_common.php
DEFINE('_PORTSCAN','Provoz skenov�n� port�');

//base_db_common.php
DEFINE('_ERRDBINDEXCREATE','Nemohu vytvo�it index pro');
DEFINE('_DBINDEXCREATE','�sp�n� vytvo�en index pro');
DEFINE('_ERRSNORTVER','M��e se jednat o star�� verzi. Podporov�ny jsou pouze datab�ze vytvo�en� Snort 1.7-beta0 nebo nov�j��m');
DEFINE('_ERRSNORTVER1','Z�kladn� datab�ze');
DEFINE('_ERRSNORTVER2','se zd� nekompletn� nebo neplatn�');
DEFINE('_ERRDBSTRUCT1','Verze datab�ze je spr�vn�, ale neobsahuje');
DEFINE('_ERRDBSTRUCT2','BASE tabulky. Pou�ijte <A HREF="base_db_setup.php">Inicializa�n� str�nku</A> pro nastaven� a optimalizaci datab�ze.');
DEFINE('_ERRPHPERROR','Chyba PHP');
DEFINE('_ERRPHPERROR1','Nekompatibiln� verze');
DEFINE('_ERRVERSION','Verze');
DEFINE('_ERRPHPERROR2','PHP je p��li� star�. Prove�te pros�m aktualizaci na verzi 4.0.4 nebo pozd�j��');
DEFINE('_ERRPHPMYSQLSUP','<B>PHP podpora nen� kompletn�</B>: <FONT>podpora pro pr�ci s MySQL 
               datab�z� nen� sou��st� instalace.
               Pros�m p�einstalujte PHP s pot�ebnou knihovnou (<CODE>--with-mysql</CODE>)</FONT>');
DEFINE('_ERRPHPPOSTGRESSUP','<B>PHP podpora nen� kompletn�</B>: <FONT>podpora pro pr�ci s PostgreSQL
               datab�z� nen� sou��st� instalace.
               Pros�m p�einstalujte PHP s pot�ebnou knihovnou (<CODE>--with-pgsql</CODE>)</FONT>');
DEFINE('_ERRPHPMSSQLSUP','<B>PHP podpora nen� kompletn�</B>: <FONT>podpora pro pr�ci s MS SQL
               datab�z� nen� sou��st� instalace.
               Pros�m p�einstalujte PHP s pot�ebnou knihovnou (<CODE>--enable-mssql</CODE>)</FONT>');
DEFINE('_ERRPHPORACLESUP','<B>PHP podpora nen� kompletn�</B>: <FONT>podpora pro pr�ci s Oracle
               datab�z� nen� sou��st� instalace.
               Pros�m p�einstalujte PHP s pot�ebnou knihovnou (<CODE>--with-oci8</CODE>)</FONT>');

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
DEFINE('_CHRTTYPEWEEK','�as (t�dny) proti po�tu alarm�');
DEFINE('_CHRTTYPEMONTH','�as (m�s�ce) proti po�tu alarm�');
DEFINE('_CHRTTYPEYEAR','�as (roky) proti po�tu alarm�');
DEFINE('_CHRTTYPESRCIP','Zdrojov� IP adresa proti po�tu alarm�');
DEFINE('_CHRTTYPEDSTIP','C�lov� IP adresa proti po�tu alarm�');
DEFINE('_CHRTTYPEDSTUDP','C�lov� UDP port proti po�tu alarm�');
DEFINE('_CHRTTYPESRCUDP','Zdrojov� UDP port proti po�tu alarm�');
DEFINE('_CHRTTYPEDSTPORT','C�lov� TCP port proti po�tu alarm�');
DEFINE('_CHRTTYPESRCPORT','Zdrojov� TCP port proti po�tu alarm�');
DEFINE('_CHRTTYPESIG','Klasifikace podpis� proti po�tu alarm�');
DEFINE('_CHRTTYPESENSOR','Senzor proti po�tu alarm�');
DEFINE('_CHRTBEGIN','Za��tek grafu:');
DEFINE('_CHRTEND','Konec grafu:');
DEFINE('_CHRTDS','Zdroj dat:');
DEFINE('_CHRTX','Osa X');
DEFINE('_CHRTY','Osa Y');
DEFINE('_CHRTMINTRESH','Minim�ln� hodnota');
DEFINE('_CHRTROTAXISLABEL','Oto�it popisky os o 90 stup��');
DEFINE('_CHRTSHOWX','Zobraz rastr pro osu X');
DEFINE('_CHRTDISPLABELX','Zobraz popis osy X ka�d�ch');
DEFINE('_CHRTDATAPOINTS','vzork� dat');
DEFINE('_CHRTYLOG','Osa Y logaritmick�');
DEFINE('_CHRTYGRID','Zobraz rastr pro osu Y');

//base_graph_main.php
DEFINE('_CHRTTITLE','Graf BASE');
DEFINE('_ERRCHRTNOTYPE','Nebyl ur�en typ grafu');
DEFINE('_ERRNOAGSPEC','Nebyla ur�ena skupiny. Pou��v�m v�echny alarmy.');
DEFINE('_CHRTDATAIMPORT','Za��n�m na��tat data'); 
DEFINE('_CHRTTIMEVNUMBER','�as port proti po�tu alarm�');
DEFINE('_CHRTTIME','�as');
DEFINE('_CHRTALERTOCCUR','V�skyty alarm�');
DEFINE('_CHRTSIPNUMBER','Zdrojov� IP adresa proti po�tu alarm�');
DEFINE('_CHRTSIP','Zdrojov� IP adresa');
DEFINE('_CHRTDIPALERTS','C�lov� IP adresa proti po�tu alarm�');
DEFINE('_CHRTDIP','C�lov� IP adresa');
DEFINE('_CHRTUDPPORTNUMBER','UDP port (c�l) port proti po�tu alarm�');
DEFINE('_CHRTDUDPPORT','C�lov� UDP port');
DEFINE('_CHRTSUDPPORTNUMBER','UDP port (zdroj) port proti po�tu alarm�');
DEFINE('_CHRTSUDPPORT','Zdrojov� UDP port');
DEFINE('_CHRTPORTDESTNUMBER','TCP port (c�l) port proti po�tu alarm�');
DEFINE('_CHRTPORTDEST','C�lov� TCP port');
DEFINE('_CHRTPORTSRCNUMBER','TCP port (zdroj) port proti po�tu alarm�');
DEFINE('_CHRTPORTSRC','Zdrojov� TCP port');
DEFINE('_CHRTSIGNUMBER','Klasifikace podpis� proti po�tu alarm�');
DEFINE('_CHRTCLASS','Klasifikace');
DEFINE('_CHRTSENSORNUMBER','Senzor port proti po�tu alarm�');
DEFINE('_CHRTHANDLEPERIOD','Rozhodn� obdob� (pokud je t�eba)');
DEFINE('_CHRTDUMP','Vypisuji data ... (zobrazuji jen ka�d�');
DEFINE('_CHRTDRAW','Kresl�m graf');
DEFINE('_ERRCHRTNODATAPOINTS','Pro vykreslen� nejsou k dispozici ��dn� data');
DEFINE('_GRAPHALERTDATA','Graph Alert Data'); //NEW

//base_maintenance.php
DEFINE('_MAINTTITLE','�dr�ba');
DEFINE('_MNTPHP','PHP popis:');
DEFINE('_MNTCLIENT','CLIENT:');
DEFINE('_MNTSERVER','SERVER:');
DEFINE('_MNTSERVERHW','SERVER HW:');
DEFINE('_MNTPHPVER','PHP VERZE:');
DEFINE('_MNTPHPAPI','PHP API:');
DEFINE('_MNTPHPLOGLVL','�rove� hl�en� PHP:');
DEFINE('_MNTPHPMODS','Nahran� moduly:');
DEFINE('_MNTDBTYPE','Typ datab�ze:');
DEFINE('_MNTDBALV','Verze podp�rn� datab�zov� knihovny:');
DEFINE('_MNTDBALERTNAME','Jm�no ALERT datab�ze:');
DEFINE('_MNTDBARCHNAME','Jm�no ARCHIVE datab�ze:');
DEFINE('_MNTAIC','Vyrovn�vac� pam� alarm�:');
DEFINE('_MNTAICTE','Celkov� po�et ud�lost�:');
DEFINE('_MNTAICCE','Ud�losti ve vyrovn�vac� pam�ti:');
DEFINE('_MNTIPAC','Vyrovn�vac� pam� IP address');
DEFINE('_MNTIPACUSIP','Unik�tn� zdrojov� IP:');
DEFINE('_MNTIPACDNSC','DNS ve vyrovn�vac� pam�ti:');
DEFINE('_MNTIPACWC','Whois ve vyrovn�vac� pam�ti:');
DEFINE('_MNTIPACUDIP','Unik�tn� c�lov� IP:');

//base_qry_alert.php
DEFINE('_QAINVPAIR','Neplatn� p�r (sid,cid)');
DEFINE('_QAALERTDELET','Alarm smaz�n');
DEFINE('_QATRIGGERSIG','Detekovan� podpis alarmu');
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
DEFINE('_QCERRCRITWARN','Varov�n� vyhled�vac�ch krit�ri�:');
DEFINE('_QCERRVALUE','Hodnota');
DEFINE('_QCERRFIELD','Pole');
DEFINE('_QCERROPER','Oper�tor');
DEFINE('_QCERRDATETIME','Hodnota datum/�as');
DEFINE('_QCERRPAYLOAD','Hodnota obsahu');
DEFINE('_QCERRIP','IP adresa');
DEFINE('_QCERRIPTYPE','IP adresa typu');
DEFINE('_QCERRSPECFIELD','bylo zad�no pole protokolu, ale nebyla ur�ena hodnota.');
DEFINE('_QCERRSPECVALUE','bylo vybr�no, ale nebyla ur�ena hodnota.');
DEFINE('_QCERRBOOLEAN','V�ce pol� pro ur�en� protokolu bylo zad�no, ale nebyl mezi nimi zad�n logick� oper�tor (AND, OR).');
DEFINE('_QCERRDATEVALUE','bylo zvoleno, �e se m� vyhled�vat podle data/�asu, ale nebyla ur�ena hodnota.');
DEFINE('_QCERRINVHOUR','(Neplatn� hodina) ��dn� krit�rium pro ur�en� data/�asu neodpov�d� ur�en�mu �asu.');
DEFINE('_QCERRDATECRIT','bylo zvoleno, �e se m� vyhled�vat podle data/�asu, ale nebyla ur�ena hodnota.');
DEFINE('_QCERROPERSELECT','bylo vlo�eno, ale nebyl zvolen ��dn� oper�tor.');
DEFINE('_QCERRDATEBOOL','V�ce krit�ri� datum/�as bylo zad�no bez ur�en� logick�ho oper�toru (AND, OR) mezi nimi.');
DEFINE('_QCERRPAYCRITOPER','byl ur�en obsah, kter� se m� vyhled�vat, ale nebylo zvoleno, zda m� b�t obsa�en nebo ne.');
DEFINE('_QCERRPAYCRITVALUE','bylo ur�eno, �e se m� vyhled�vat podle obsahu, ale nebyla ur�ena hodnota.');
DEFINE('_QCERRPAYBOOL','V�ce krit�ri� obsahu bylo zad�no bez ur�en�t logick�ho oper�toru (AND, OR) mezi nimi.');
DEFINE('_QCMETACRIT','Meta krit�ria');
DEFINE('_QCIPCRIT','IP krit�ria');
DEFINE('_QCPAYCRIT','Obsahov� krit�ria');
DEFINE('_QCTCPCRIT','TCP krit�ria');
DEFINE('_QCUDPCRIT','UDP krit�ria');
DEFINE('_QCICMPCRIT','ICMP krit�ria');
DEFINE('_QCLAYER4CRIT','Layer 4 Criteria'); //NEW
DEFINE('_QCERRINVIPCRIT','Neplatn� krit�rium IP adresy');
DEFINE('_QCERRCRITADDRESSTYPE','byla zvolena jako krit�rium, ale nebylo ur�eno, zda se jedn� o zdrojovou nebo c�lovou adresu.');
DEFINE('_QCERRCRITIPADDRESSNONE','ukazuj�c, �e IP adresa m� b�t krit�riem, ale nebyla ur�ena hodnota.');
DEFINE('_QCERRCRITIPADDRESSNONE1','bylo vybr�no (v #');
DEFINE('_QCERRCRITIPIPBOOL','V�ce krit�ri� pro IP adresy bylo zad�no bez ur�en� logick�ho oper�toru (AND, OR) mezi nimi.');

//base_qry_form.php
DEFINE('_QFRMSORTORDER','Sm�r t��d�n�');
DEFINE('_QFRMSORTNONE','none'); //NEW
DEFINE('_QFRMTIMEA','�as (vzestupn�)');
DEFINE('_QFRMTIMED','�as (sestupn�)');
DEFINE('_QFRMSIG','podpis');
DEFINE('_QFRMSIP','zdrojov� IP adresa');
DEFINE('_QFRMDIP','c�lov� IP adresa');

//base_qry_sqlcalls.php
DEFINE('_QSCSUMM','Souhrn� statistiky');
DEFINE('_QSCTIMEPROF','Profil v �ase');
DEFINE('_QSCOFALERTS','z alarm�');

//base_stat_alerts.php
DEFINE('_ALERTTITLE','V�pis alarm�');

//base_stat_common.php
DEFINE('_SCCATEGORIES','Kategorie:');
DEFINE('_SCSENSORTOTAL','Senzory/Celkem:');
DEFINE('_SCTOTALNUMALERTS','Celkov� po�et alarm�:');
DEFINE('_SCSRCIP','Zdrojov�ch IP adres:');
DEFINE('_SCDSTIP','C�lov�ch IP adres:');
DEFINE('_SCUNILINKS','Unik�tn�ch IP spoj�');
DEFINE('_SCSRCPORTS','Zdrojov�ch port�: ');
DEFINE('_SCDSTPORTS','C�lov�ch port�: ');
DEFINE('_SCSENSORS','Senzor�');
DEFINE('_SCCLASS','Klasifikace');
DEFINE('_SCUNIADDRESS','Unik�tn�ch adres: ');
DEFINE('_SCSOURCE','Zdroj');
DEFINE('_SCDEST','C�l');
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
DEFINE('_SIPLSOURCEFGDN','Zdrojov� FQDN');
DEFINE('_SIPLDESTFGDN','C�lov� FQDN');
DEFINE('_SIPLDIRECTION','Sm�r');
DEFINE('_SIPLPROTO','Protokol');
DEFINE('_SIPLUNIDSTPORTS','Unik�tn�ch c�lov�ch port�');
DEFINE('_SIPLUNIEVENTS','Unik�tn�ch alarm�');
DEFINE('_SIPLTOTALEVENTS','Celkem alarm�');

//base_stat_ports.php
DEFINE('_UNIQ','Unik�tn�');
DEFINE('_DSTPS','c�lov� porty');
DEFINE('_SRCPS','zdrojov� porty');
DEFINE('_OCCURRENCES','Occurrences'); //NEW

//base_stat_sensor.php
DEFINE('SPSENSORLIST','V�pis senzor�');

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
DEFINE('_UNISADD','Unik�tn� zdrojov� IP adresy');
DEFINE('_SUASRCIP','Zdrojov� IP adresa');
DEFINE('_SUAERRCRITADDUNK','chyba v krit�riu: nezn�m� typ adresy -- p�edpokl�d�m c�lovou');
DEFINE('_UNIDADD','Unik�tn� c�lov� IP adresy');
DEFINE('_SUADSTIP','C�lov� IP adresa');
DEFINE('_SUAUNIALERTS','Unik�tn�ch alarm�');
DEFINE('_SUASRCADD','Zdrojov�ch adres');
DEFINE('_SUADSTADD','C�lov�ch adres');

//base_user.php
DEFINE('_BASEUSERTITLE','U�ivatelsk� p�edvolby BASE');
DEFINE('_BASEUSERERRPWD','Heslo nesm� b�t pr�zn� nebo heslo nesouhlas�!');
DEFINE('_BASEUSEROLDPWD','Star� heslo:');
DEFINE('_BASEUSERNEWPWD','Nov� heslo:');
DEFINE('_BASEUSERNEWPWDAGAIN','Nov� heslo znovu:');

DEFINE('_LOGOUT','Odhl�sit');
?>
